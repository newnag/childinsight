<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $centerId = $user->center_id;

        if (!$centerId && $user->role === 'admin') {
            $requests = MaintenanceRequest::with(['user', 'center'])->orderBy('created_at', 'desc')->get();
        } else {
            if (!$centerId) {
                return redirect()->route('home')->with('error', 'No center assigned.');
            }
            $requests = MaintenanceRequest::where('center_id', $centerId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('maintenance.index', compact('requests'));
    }

    public function create()
    {
        return view('maintenance.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $centerId = $user->center_id;

        if (!$centerId && $user->role === 'admin') {
             $center = \App\Models\Center::first();
             $centerId = $center ? $center->id : null;
        }

        if (!$centerId) {
            return back()->with('error', 'No center assigned to create a request.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('maintenance_photos', 'public');
        }

        MaintenanceRequest::create([
            'center_id' => $centerId,
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'priority' => $request->input('priority'),
            'photo_path' => $photoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('maintenance.index')->with('success', 'Maintenance request submitted.');
    }

    public function update(Request $request, MaintenanceRequest $maintenance)
    {
        // Allow updating status
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $maintenance->update([
            'status' => $request->input('status'),
        ]);

        return back()->with('success', 'Status updated.');
    }
}
