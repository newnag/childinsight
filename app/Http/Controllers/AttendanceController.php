<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Student::query();

        if ($user->center_id) {
            $query->where('center_id', $user->center_id);
        }

        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $students = $query->with(['attendances' => function($query) use ($date) {
                $query->where('date', $date);
            }, 'center'])
            ->orderBy('center_id')
            ->orderBy('first_name')
            ->paginate(20)
            ->appends(['date' => $date]);

        return view('attendance.index', compact('students', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,sick,leave',
        ]);

        $date = $request->input('date');
        $attendances = $request->input('attendance');

        foreach ($attendances as $studentId => $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'remark' => $data['remark'] ?? null,
                    'check_in_time' => ($data['status'] == 'present') ? Carbon::now()->format('H:i:s') : null,
                ]
            );
        }

        return redirect()->route('attendance.index', ['date' => $date])->with('success', 'Attendance saved successfully.');
    }

    public function report(Request $request)
    {
        $user = Auth::user();
        
        $query = Student::query();

        if ($user->center_id) {
            $query->where('center_id', $user->center_id);
        }

        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $students = $query->with(['attendances' => function($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }, 'center'])
            ->orderBy('center_id')
            ->orderBy('first_name')
            ->get();

        // Calculate summary stats
        $summary = [
            'present' => 0,
            'absent' => 0,
            'sick' => 0,
            'leave' => 0,
            'total_days' => $startOfMonth->diffInDays($endOfMonth) + 1, // Just for reference
        ];

        foreach ($students as $student) {
            $student->stats = [
                'present' => $student->attendances->where('status', 'present')->count(),
                'absent' => $student->attendances->where('status', 'absent')->count(),
                'sick' => $student->attendances->where('status', 'sick')->count(),
                'leave' => $student->attendances->where('status', 'leave')->count(),
            ];
            
            $summary['present'] += $student->stats['present'];
            $summary['absent'] += $student->stats['absent'];
            $summary['sick'] += $student->stats['sick'];
            $summary['leave'] += $student->stats['leave'];
        }

        return view('attendance.report', compact('students', 'month', 'summary', 'startOfMonth', 'endOfMonth'));
    }

    public function reportPdf(Request $request)
    {
        $user = Auth::user();
        
        $query = Student::query();

        if ($user->center_id) {
            $query->where('center_id', $user->center_id);
        }

        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $students = $query->with(['attendances' => function($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }, 'center'])
            ->orderBy('center_id')
            ->orderBy('first_name')
            ->get();

        foreach ($students as $student) {
            $student->stats = [
                'present' => $student->attendances->where('status', 'present')->count(),
                'absent' => $student->attendances->where('status', 'absent')->count(),
                'sick' => $student->attendances->where('status', 'sick')->count(),
                'leave' => $student->attendances->where('status', 'leave')->count(),
            ];
        }

        return view('attendance.report_pdf', compact('students', 'month', 'startOfMonth', 'endOfMonth'));
    }
}
