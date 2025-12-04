<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DailyLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Student::query();

        if ($user->center_id) {
            $query->where('center_id', $user->center_id);
        }

        $date = $request->input('date', date('Y-m-d'));

        $students = $query->with('center')
            ->orderBy('center_id')
            ->orderBy('first_name')
            ->paginate(20)
            ->appends(['date' => $date]);

        // Fetch logs for this date keyed by student_id
        $logs = DailyLog::where('date', $date)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('daily_logs.index', compact('students', 'logs', 'date'));
    }

    public function create(Request $request)
    {
        $studentId = $request->query('student_id');
        $date = $request->query('date', date('Y-m-d'));
        
        $student = Student::findOrFail($studentId);
        $log = DailyLog::where('student_id', $studentId)->where('date', $date)->first();

        return view('daily_logs.create', compact('student', 'log', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'milk_requisition' => 'boolean',
            'milk_amount' => 'nullable|string|max:255',
            'food_consumed' => 'nullable|string',
            'food_quantity' => 'nullable|in:low,medium,high',
            'nutrient_quality' => 'nullable|string',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except(['photos', '_token']);
        $data['milk_requisition'] = $request->has('milk_requisition');

        // Handle Photos
        $photos = [];
        // If updating, keep existing photos? 
        // For simplicity, let's append or replace. 
        // Let's check if record exists
        $log = DailyLog::where('student_id', $request->student_id)
                       ->where('date', $request->date)
                       ->first();

        if ($log && $log->activity_photos) {
            $photos = $log->activity_photos;
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('daily_logs', 'public');
                $photos[] = $path;
            }
        }
        $data['activity_photos'] = $photos;

        if ($log) {
            $log->update($data);
        } else {
            DailyLog::create($data);
        }

        return redirect()->route('daily_logs.index', ['date' => $request->date])
            ->with('success', 'Daily log saved successfully.');
    }
}
