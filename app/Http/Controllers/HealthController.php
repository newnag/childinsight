<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use App\Models\DevelopmentRecord;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Student::query();

        if ($user->center_id) {
            $query->where('center_id', $user->center_id);
        }

        $students = $query->with('center')
            ->orderBy('center_id')
            ->orderBy('first_name')
            ->paginate(20);

        return view('health.index', compact('students'));
    }

    public function create(Request $request)
    {
        $studentId = $request->query('student_id');
        $student = Student::findOrFail($studentId);
        
        return view('health.create', compact('student'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'recorded_at' => 'required|date',
            'weight' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'physical_desc' => 'nullable|string',
            'emotional_desc' => 'nullable|string',
            'behavior_desc' => 'nullable|string',
            'learning_desc' => 'nullable|string',
        ]);

        // Calculate BMI
        $weight = $request->input('weight');
        $height = $request->input('height'); // in cm
        
        if ($height > 0) {
            $heightM = $height / 100;
            $bmi = $weight / ($heightM * $heightM);
        } else {
            $bmi = 0;
        }

        // Determine Nutrition Status (Simplified logic, usually depends on age/gender charts)
        $nutritionStatus = 'normal';
        if ($bmi < 18.5) $nutritionStatus = 'thin';
        elseif ($bmi > 23) $nutritionStatus = 'obese';

        HealthRecord::create([
            'student_id' => $request->input('student_id'),
            'recorded_at' => $request->input('recorded_at'),
            'weight' => $weight,
            'height' => $height,
            'bmi' => round($bmi, 2),
            'nutrition_status' => $nutritionStatus,
            'illness' => $request->input('illness'),
            'health_constraints' => $request->input('health_constraints'),
        ]);

        // Create Development Record if any field is present
        if ($request->filled(['physical_desc', 'emotional_desc', 'behavior_desc', 'learning_desc'])) {
            DevelopmentRecord::create([
                'student_id' => $request->input('student_id'),
                'recorded_at' => $request->input('recorded_at'),
                'physical_desc' => $request->input('physical_desc'),
                'emotional_desc' => $request->input('emotional_desc'),
                'behavior_desc' => $request->input('behavior_desc'),
                'learning_desc' => $request->input('learning_desc'),
            ]);
        }

        return redirect()->route('health.show', $request->input('student_id'))
            ->with('success', 'Health and development record added successfully.');
    }

    public function show($id)
    {
        $student = Student::with(['center'])->findOrFail($id);
        $records = HealthRecord::where('student_id', $id)->orderBy('recorded_at', 'desc')->get();
        $developmentRecords = DevelopmentRecord::where('student_id', $id)->orderBy('recorded_at', 'desc')->get();

        // Prepare data for charts
        $chartData = [
            'dates' => $records->pluck('recorded_at')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M Y'))->reverse()->values(),
            'weights' => $records->pluck('weight')->reverse()->values(),
            'heights' => $records->pluck('height')->reverse()->values(),
        ];

        return view('health.show', compact('student', 'records', 'developmentRecords', 'chartData'));
    }
}
