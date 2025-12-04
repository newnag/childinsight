<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin' && !$user->center_id) {
             $students = Student::with('center')->paginate(20);
        } else {
             $students = Student::where('center_id', $user->center_id)->paginate(20);
        }
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $centers = Center::all();
        return view('students.create', compact('centers'));
    }

    public function store(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'dob' => 'required|date',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:255',
        ];

        if (!Auth::user()->center_id) {
            $rules['center_id'] = 'required|exists:centers,id';
        }

        $request->validate($rules);

        $data = $request->all();
        if (Auth::user()->center_id) {
            $data['center_id'] = Auth::user()->center_id;
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        // Check permission
        if (Auth::user()->center_id && Auth::user()->center_id != $student->center_id) {
            abort(403);
        }
        $centers = Center::all();
        return view('students.edit', compact('student', 'centers'));
    }

    public function update(Request $request, Student $student)
    {
        // Check permission
        if (Auth::user()->center_id && Auth::user()->center_id != $student->center_id) {
            abort(403);
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'dob' => 'required|date',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:255',
        ];

        if (!Auth::user()->center_id) {
            $rules['center_id'] = 'required|exists:centers,id';
        }

        $request->validate($rules);

        $data = $request->all();
        // Prevent changing center_id if user is restricted to a center
        if (Auth::user()->center_id) {
            unset($data['center_id']);
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        // Check permission
        if (Auth::user()->center_id && Auth::user()->center_id != $student->center_id) {
            abort(403);
        }
        
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
