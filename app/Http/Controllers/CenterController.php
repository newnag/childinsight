<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CenterController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $centers = Center::all();
        return view('centers.index', compact('centers'));
    }

    public function create()
    {
        return view('centers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:centers',
            'district' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Center::create($request->all());

        return redirect()->route('centers.index')->with('success', 'Center created successfully.');
    }

    public function edit(Center $center)
    {
        return view('centers.edit', compact('center'));
    }

    public function update(Request $request, Center $center)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:centers,code,' . $center->id,
            'district' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $center->update($request->all());

        return redirect()->route('centers.index')->with('success', 'Center updated successfully.');
    }

    public function destroy(Center $center)
    {
        // Check if center has related data before deleting if necessary, or rely on foreign key constraints
        // For now, simple delete
        $center->delete();

        return redirect()->route('centers.index')->with('success', 'Center deleted successfully.');
    }
}
