<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Attendance;
use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function show($event_id)
    {
        $event = Event::findOrFail($event_id);

        // Kawalan akses: Hanya pengurus majlis yang memiliki event ini boleh masuk
        if ($event->pengurus_id !== Auth::guard('pengurusMajlis')->id()) {
            return redirect('/pengurusMajlis/home')->with('error', 'Unauthorized access');
        }

        $participants = EventParticipant::where('event_id', $event_id)
            ->with(['user'])
            ->get();

        // Ambil data attendance dan key kan dengan user_id
        $attendances = Attendance::where('event_id', $event_id)->get()->keyBy('user_id');

        $totalParticipants = $participants->count();
        $totalPresent = $attendances->where('status', 'Present')->count();
        $totalMissing = $totalParticipants - $totalPresent;

        return view('pengurusMajlis.attendance', compact(
            'event', 'participants', 'attendances',
            'totalParticipants', 'totalPresent', 'totalMissing'
        ));
    }

    public function record(Request $request)
{
    try {
        $eventId = $request->input('event_id');
        $qrRawData = $request->input('qr_data');

        // 1. Dekod JSON daripada QR Code
        $decoded = json_decode($qrRawData, true);

        // 2. Ekstrak token (pastikan nama key 'attendance_token' betul dalam JSON anda)
        $qrToken = (is_array($decoded) && isset($decoded['attendance_token']))
                    ? $decoded['attendance_token']
                    : $qrRawData;

        // 3. Cari token dalam pangkalan data
        $qr = QrCode::where('qr_token', $qrToken)
            ->where('event_id', $eventId)
            ->first();

        if (!$qr) {
            return response()->json(['success' => false, 'error' => 'QR Code tidak sah untuk majlis ini.'], 404);
        }

        // Check if QR is used or event is inactive
        if ($qr->is_used) {
            return response()->json(['success' => false, 'error' => 'QR Code ini telah digunakan.'], 409);
        }

        // Check if the associated event is still active
        $event = Event::find($eventId);
        if (!$event || !$event->is_qr_active) {
            return response()->json(['success' => false, 'error' => 'This QR has expired/been used (QR telah tamat tempoh/dipakai).'], 403);
        }

        // 4. Pastikan kita ada user_id sebelum cuba simpan
        if (!$qr->user_id) {
            return response()->json(['success' => false, 'error' => 'Data peserta tidak dijumpai dalam rekod QR.'], 422);
        }

        // 5. Rekod Kehadiran (Gunakan nama column 'user_id' mengikut struktur jadual anda)
        Attendance::updateOrCreate(
            [
                'event_id' => $eventId,
                'user_id'  => $qr->user_id, // Nilai ini sangat penting
            ],
            [
                'status' => 'Present',
            ]
        );

        // 6. Kemaskini status QR
        $qr->update([
            'is_used' => true,
            'used_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kehadiran berjaya direkodkan!',
            'participant_id' => $qr->user_id
        ]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => 'Ralat: ' . $e->getMessage()], 500);
    }
}
}
