<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClientController extends Controller
{
    public function home()
    {
        return view('client.home');
    }

    public function myqr()
    {
        $user = Auth::user();
        $activeEvent = Event::where('user_id', $user->id)
                           ->where('is_active', true)
                           ->first();

        $completedEvents = Event::where('user_id', $user->id)
                               ->where('is_active', false)
                               ->orderBy('event_date', 'desc')
                               ->get();

        return view('client.myqr', compact('activeEvent', 'completedEvents'));
    }

    public function contact()
    {
        return view('client.contact');
    }

    public function createEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required',
            'max_attendees' => 'required|integer|min:1',
        ]);

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'max_attendees' => $request->max_attendees,
            'user_id' => Auth::id(),
        ]);

        // Generate QR Code
        $qrCodeData = route('attendance.scan', ['event_id' => $event->id]);
        $qrCodePath = 'qr-codes/' . $event->id . '.png';
        Storage::disk('public')->put($qrCodePath, QrCode::format('png')->size(300)->generate($qrCodeData));

        $event->update(['qr_code_path' => $qrCodePath]);

        return redirect()->route('client.myqr')->with('success', 'Event created successfully!');
    }

    public function deactivateEvent($eventId)
    {
        $event = Event::where('id', $eventId)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();

        $event->update(['is_active' => false]);

        return redirect()->route('client.myqr')->with('success', 'Event deactivated successfully!');
    }

    public function downloadQr($eventId)
    {
        $event = Event::where('id', $eventId)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if (!$event->qr_code_path || !Storage::disk('public')->exists($event->qr_code_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($event->qr_code_path);
    }
}
