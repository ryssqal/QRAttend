<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Event;
use App\Models\PengurusMajlis;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPengurusMajlis = PengurusMajlis::count();
        $totalEvents = Event::count();
        $totalGuests = User::count();
        $latestPengurusMajlis = PengurusMajlis::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalPengurusMajlis', 'totalEvents', 'totalGuests', 'latestPengurusMajlis'));
    }
}

