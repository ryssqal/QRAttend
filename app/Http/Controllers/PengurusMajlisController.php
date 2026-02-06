<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengurusMajlisController extends Controller
{
    public function home()
    {
        // Get dashboard statistics
        $activeEvents = Event::where('is_active', true)->count();
        $registeredGuests = Attendance::distinct('user_id')->count();
        $confirmedAttendance = Attendance::where('status', 'confirmed')->count();

        // Get recent events (limit to 6 for dashboard display)
        $events = Event::orderBy('created_at', 'desc')->take(6)->get();

        return view('pengurusMajlis.home', compact('activeEvents', 'registeredGuests', 'confirmedAttendance', 'events'));
    }

    public function index()
    {
        // Get all events for the current pengurusMajlis
        $events = Event::where('pengurus_id', Auth::guard('pengurusMajlis')->id())
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('pengurusMajlis.eventlis', compact('events'));
    }

    public function create()
    {
        return view('pengurusMajlis.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:1000',
            'date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'pax' => 'required|integer|min:1|max:10000',
            'password' => 'nullable|string|min:4|max:20',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $mediaPath = null;
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('events', 'public');
        }

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'pax' => $request->pax,
            'password_hash' => $request->password ? Hash::make($request->password) : null,
            'media_path' => $mediaPath,
            'pengurus_id' => Auth::guard('pengurusMajlis')->id(),
            'is_active' => true,
        ]);

        return redirect()->route('pengurusMajlis.home')->with('success', 'Event created successfully!');
    }

    public function edit($eventId)
    {
        $event = Event::where('id', $eventId)
                     ->where('pengurus_id', Auth::guard('pengurusMajlis')->id())
                     ->firstOrFail();

        return view('pengurusMajlis.edit', compact('event'));
    }

    public function update(Request $request, $eventId)
    {
        $event = Event::where('id', $eventId)
                     ->where('pengurus_id', Auth::guard('pengurusMajlis')->id())
                     ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:1000',
            'date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'pax' => 'required|integer|min:1|max:10000',
            'password' => 'nullable|string|min:4|max:20',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $mediaPath = $event->media_path; // Keep existing media by default
        if ($request->hasFile('media')) {
            // Delete old media if exists
            if ($event->media_path && Storage::disk('public')->exists($event->media_path)) {
                Storage::disk('public')->delete($event->media_path);
            }
            // Store new media
            $mediaPath = $request->file('media')->store('events', 'public');
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'pax' => $request->pax,
            'password_hash' => $request->password ? Hash::make($request->password) : null,
            'media_path' => $mediaPath,
        ]);

        return redirect()->route('pengurusMajlis.events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy($eventId)
    {
        $event = Event::where('id', $eventId)
                     ->where('pengurus_id', Auth::guard('pengurusMajlis')->id())
                     ->firstOrFail();

        // Delete associated media file if exists
        if ($event->media_path && Storage::disk('public')->exists($event->media_path)) {
            Storage::disk('public')->delete($event->media_path);
        }

        // Delete the event (this will cascade delete related records due to foreign keys)
        $event->delete();

        // Check if request is AJAX
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Event deleted successfully!']);
        }

        return redirect()->route('pengurusMajlis.home')->with('success', 'Event deleted successfully!');
    }
}
