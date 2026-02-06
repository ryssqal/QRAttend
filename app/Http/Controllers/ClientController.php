<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\QrCode as AttendanceQr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClientController extends Controller
{
    public function home()
    {
        $activeEvents = Event::where('is_active', true)->get();
        return view('client.home', compact('activeEvents'));
    }

    /**
     * =========================
     * MY QR PAGE (DISPLAY ONLY)
     * =========================
     */
    public function myqr()
{
    $user = Auth::user();

    /**
     * =========================
     * ACTIVE QR (BELUM DIGUNAKAN)
     * =========================
     * - Event masih aktif
     * - QR milik user
     * - QR belum digunakan
     */
    $activeEvents = Event::where('is_active', true)
        ->whereHas('attendanceQrs', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->where('is_used', false);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    /**
     * =========================
     * USED / INACTIVE QR
     * =========================
     * - QR sudah digunakan
     * ATAU
     * - Event tidak aktif
     */
    $usedEvents = Event::where(function ($query) use ($user) {
            $query->whereHas('attendanceQrs', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('is_used', true);
            })
            ->orWhere('is_active', false);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    /**
     * =========================
     * GENERATE QR SVG
     * =========================
     */
    $qrCodes = [];

    foreach ($activeEvents as $event) {
        $attendance = AttendanceQr::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('is_used', false)
            ->first();

        if ($attendance) {
            $payload = [
                'attendance_token' => $attendance->qr_token,
                'event_id' => $attendance->event_id,
                'user_id' => $attendance->user_id,
            ];

            $qrCodes[$event->id] = QrCode::format('svg')
                ->size(250)
                ->generate(json_encode($payload));
        }
    }

    return view('client.myqr', compact('activeEvents', 'usedEvents', 'qrCodes'));
}


    public function contact()
    {
        return view('client.contact');
    }

    /**
     * =========================
     * VERIFY EVENT PASSWORD
     * =========================
     */
    public function verifyEventPassword(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'password' => 'required|string',
        ]);

        $user = Auth::user();
        $eventId = $request->event_id;
        $password = $request->password;

        // Event mesti aktif
        $event = Event::where('id', $eventId)
            ->where('is_active', true)
            ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or inactive.'
            ], 404);
        }

        // Check if password is required
        if (!$event->password_hash) {
            return response()->json([
                'success' => false,
                'message' => 'This event does not require a password.'
            ], 400);
        }

        // Verify password
        if (!Hash::check($password, $event->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata laluan anda salah'
            ], 401);
        }

        // Password correct, proceed with joining
        $result = $this->joinEvent($event, $user);

        // If successful, redirect to My QR page
        if ($result->getData()->success) {
            return response()->json([
                'success' => true,
                'redirect' => route('client.myqr')
            ]);
        }

        return $result;
    }

    /**
     * =========================
     * USER JOIN EVENT
     * (QR GENERATED HERE)
     * =========================
     */
    public function registerForEvent(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
        ]);

        $user = Auth::user();
        $eventId = $request->event_id;

        // Event mesti aktif
        $event = Event::where('id', $eventId)
            ->where('is_active', true)
            ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or inactive.'
            ], 404);
        }

        // This method should only be called for public events (no password)
        // Password-protected events should use verifyEventPassword instead
        if ($event->password_hash) {
            return response()->json([
                'success' => false,
                'message' => 'This event requires a password.'
            ], 403);
        }

        // No password required, proceed with joining
        return $this->joinEvent($event, $user);
    }

    /**
     * =========================
     * JOIN EVENT HELPER METHOD
     * =========================
     */
    private function joinEvent(Event $event, $user)
    {
        // Elak duplicate join
        $existingParticipant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingParticipant) {
            return response()->json([
                'success' => false,
                'message' => 'You have already joined this event.'
            ], 409);
        }

        try {
            // Simpan penyertaan
            EventParticipant::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'registered_at' => now(),
            ]);

            // Jana QR kehadiran (SEKALI SAHAJA)
            AttendanceQr::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'qr_token' => (string) Str::uuid(),
                'qr_data' => [],
                'is_used' => false,
                'generated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully joined the event!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to join event.'
            ], 500);
        }
    }

    /**
     * =========================
     * DOWNLOAD QR CODE AS PNG
     * =========================
     */
    public function downloadQr($eventId)
    {
        $user = Auth::user();

        // Find the event and ensure user has access
        $event = Event::where('id', $eventId)
            ->where(function($q) use ($user) {
                $q->where('pengurus_id', $user->id)
                  ->orWhereHas('participants', function ($query) use ($user) {
                      $query->where('user_id', $user->id);
                  });
            })
            ->firstOrFail();

        // Get QR code data for this user and event
        $attendance = AttendanceQr::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Generate QR code payload
        $payload = [
            'attendance_token' => $attendance->qr_token,
            'event_id' => $attendance->event_id,
            'user_id' => $attendance->user_id,
        ];

        // Generate SVG QR code
        $svgContent = QrCode::format('svg')
            ->size(500) // Larger size for better PNG quality
            ->generate(json_encode($payload));

        // Convert SVG to PNG using GD library
        $pngData = $this->convertSvgToPng($svgContent, 500, 500);

        // Generate filename
        $filename = 'QR_' . Str::slug($event->title) . '_' . $user->name . '.png';

        // Return PNG file for download
        return response($pngData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Convert SVG to PNG using GD library
     */
    private function convertSvgToPng($svgContent, $width = 500, $height = 500)
    {
        // Create a temporary file for the SVG
        $tempSvg = tempnam(sys_get_temp_dir(), 'qr_svg_') . '.svg';
        file_put_contents($tempSvg, $svgContent);

        // Create PNG image
        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);

        // Fill background with white
        imagefill($image, 0, 0, $white);

        // Simple SVG parsing to extract QR code pattern
        // This is a basic implementation - for production, consider using a proper SVG library
        $svgLines = explode("\n", $svgContent);

        foreach ($svgLines as $line) {
            // Look for rectangle elements in SVG
            if (preg_match('/<rect[^>]*x="([^"]*)"[^>]*y="([^"]*)"[^>]*width="([^"]*)"[^>]*height="([^"]*)"[^>]*fill="([^"]*)"[^>]*\/?>/', $line, $matches)) {
                $x = (float) $matches[1];
                $y = (float) $matches[2];
                $rectWidth = (float) $matches[3];
                $rectHeight = (float) $matches[4];
                $fill = $matches[5];

                // Convert coordinates (SVG viewBox is typically 0 0 37 37 for QR codes)
                $scaleX = $width / 37;
                $scaleY = $height / 37;

                $pixelX = (int) ($x * $scaleX);
                $pixelY = (int) ($y * $scaleY);
                $pixelWidth = max(1, (int) ($rectWidth * $scaleX));
                $pixelHeight = max(1, (int) ($rectHeight * $scaleY));

                // Draw black rectangles for QR code modules
                if ($fill === 'black' || $fill === '#000000' || $fill === '#000') {
                    imagefilledrectangle($image, $pixelX, $pixelY, $pixelX + $pixelWidth - 1, $pixelY + $pixelHeight - 1, $black);
                }
            }
        }

        // Capture PNG output
        ob_start();
        imagepng($image);
        $pngData = ob_get_clean();

        // Clean up
        imagedestroy($image);
        unlink($tempSvg);

        return $pngData;
    }

    /**
     * =========================
     * DEACTIVATE QR CODE
     * =========================
     */
    public function deactivateEvent($eventId)
    {
        $user = Auth::user();

        // Check if eventId is provided
        if (!$eventId) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event ID is missing.',
                    'received_eventId' => $eventId
                ], 400);
            }
            return redirect()->back()->with('error', 'Event ID is missing.');
        }

        // Find event that user has access to (either as organizer or participant)
        $event = Event::where('id', $eventId)
            ->where(function($q) use ($user) {
                $q->where('pengurus_id', $user->id) // User is the organizer
                  ->orWhereHas('participants', function ($query) use ($user) { // User is a participant
                      $query->where('user_id', $user->id);
                  });
            })
            ->first();

        // If event not found, return error
        if (!$event) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found or you do not have permission to deactivate this QR code.',
                    'eventId' => $eventId,
                    'userId' => $user->id
                ], 404);
            }
            return redirect()->back()->with('error', 'Event not found or you do not have permission.');
        }

        // Check if QR is already inactive
        if (!$event->is_qr_active) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This QR code is already deactivated.'
                ], 400);
            }
            return redirect()->back()->with('error', 'This QR code is already deactivated.');
        }

        // Mark the QR as inactive (this will move it to "Used/Inactive" section)
        $event->update(['is_qr_active' => false]);

        // Mark all QR codes for this event as inactive (prevent further scanning)
        AttendanceQr::where('event_id', $eventId)
            ->update(['is_used' => true]);

        // Check if request is AJAX
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'QR Code for event "' . $event->title . '" has been deactivated.',
                'event' => $event
            ]);
        }

        return redirect()->route('client.myqr')
            ->with('success', 'QR Code for event "' . $event->title . '" has been deactivated.');
    }
}
