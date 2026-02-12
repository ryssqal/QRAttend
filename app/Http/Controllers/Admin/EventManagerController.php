<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EventManagerController extends Controller
{
    // Senarai Pengurus Majlis
    public function index()
    {
        $managers = EventManager::all();
        return view('admin.event_managers.index', compact('managers'));
    }

    // Papar borang tambah pengurus
    public function create()
    {
        return view('admin.event_managers.create'); // underscore folder
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:event_managers',
            'password' => 'required|min:6',
        ]);

        EventManager::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.event-managers.index')
            ->with('success', 'Pengurus Majlis berjaya ditambah');
    }
}

