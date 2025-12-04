<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\CenterAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $data = [];
        $centerId = $user->center_id;

        // If admin or inspector has no center, show global stats
        if (!$centerId && ($user->role === 'admin' || $user->role === 'inspector')) {
            // Global Stats
            $data['total_students'] = Student::count();
            
            // Global Attendance Rate
            $today = Carbon::today();
            $totalStudents = Student::count();
            $presentCount = Attendance::whereDate('date', $today)->where('status', 'present')->count();
            $attendanceRecordsCount = Attendance::whereDate('date', $today)->count(); // Approximation based on records
            
            // Better approximation: Count distinct students who have attendance record today
            // But for simplicity, let's use total students if attendance is taken generally
            // Or just use the records count as denominator
            $data['attendance_rate'] = $attendanceRecordsCount > 0 ? round(($presentCount / $attendanceRecordsCount) * 100) : 0;

            // Average Assessment Score
            $latestAssessments = CenterAssessment::select('center_id', DB::raw('MAX(assessment_date) as last_date'))
                ->groupBy('center_id')
                ->get();
            
            $totalScore = 0;
            $count = 0;
            foreach ($latestAssessments as $la) {
                $score = CenterAssessment::where('center_id', $la->center_id)
                    ->where('assessment_date', $la->last_date)
                    ->value('total_score');
                if ($score) {
                    $totalScore += $score;
                    $count++;
                }
            }
            $data['latest_score'] = $count > 0 ? round($totalScore / $count, 1) . ' (Avg)' : '-';

            // Global Pending Maintenance
            $data['pending_maintenance'] = \App\Models\MaintenanceRequest::where('status', 'pending')->count();

            // Global Chart Data
            $dates = [];
            $rates = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dates[] = $date->format('d M');
                
                $total = Attendance::whereDate('date', $date)->count();
                $present = Attendance::whereDate('date', $date)->where('status', 'present')->count();
                
                $rates[] = $total > 0 ? round(($present / $total) * 100) : 0;
            }
            $data['chart_dates'] = $dates;
            $data['chart_rates'] = $rates;

        } elseif ($centerId) {
            $data['total_students'] = Student::where('center_id', $centerId)->count();
            
            // Today's Attendance Rate
            $today = Carbon::today();
            $totalStudents = Student::where('center_id', $centerId)->count();
            
            $presentCount = Attendance::whereDate('date', $today)
                ->where('status', 'present')
                ->whereHas('student', function($q) use ($centerId) {
                    $q->where('center_id', $centerId);
                })->count();

            // If attendance hasn't been taken today, rate is 0. If taken, calculate based on total students.
            // Or strictly based on attendance records created.
            $attendanceRecordsCount = Attendance::whereDate('date', $today)
                 ->whereHas('student', function($q) use ($centerId) {
                    $q->where('center_id', $centerId);
                })->count();

            $data['attendance_rate'] = $attendanceRecordsCount > 0 ? round(($presentCount / $attendanceRecordsCount) * 100) : 0;

            // Latest Assessment Score
            $latestAssessment = CenterAssessment::where('center_id', $centerId)
                ->orderBy('assessment_date', 'desc')
                ->first();
            
            $data['latest_score'] = $latestAssessment ? $latestAssessment->total_score : 'N/A';

            // Maintenance Requests
            $data['pending_maintenance'] = \App\Models\MaintenanceRequest::where('center_id', $centerId)
                ->where('status', 'pending')
                ->count();

            // Chart Data: Attendance (Last 7 Days)
            $dates = [];
            $rates = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dates[] = $date->format('d M');
                
                $total = Attendance::whereDate('date', $date)
                    ->whereHas('student', function($q) use ($centerId) { $q->where('center_id', $centerId); })
                    ->count();
                $present = Attendance::whereDate('date', $date)
                    ->where('status', 'present')
                    ->whereHas('student', function($q) use ($centerId) { $q->where('center_id', $centerId); })
                    ->count();
                
                $rates[] = $total > 0 ? round(($present / $total) * 100) : 0;
            }
            $data['chart_dates'] = $dates;
            $data['chart_rates'] = $rates;

        } else {
            // Fallback if no center and not admin/inspector (shouldn't happen with middleware)
            $data['total_students'] = 0;
            $data['attendance_rate'] = 0;
            $data['latest_score'] = '-';
            $data['pending_maintenance'] = 0;
            $data['chart_dates'] = [];
            $data['chart_rates'] = [];
        }

        // Ranking (Top 5 Centers by Latest Assessment Score)
        // This is a bit complex query, simplified here:
        // Get all centers, for each get latest assessment, sort by score
        $centers = \App\Models\Center::all();
        $ranking = $centers->map(function($center) {
            $assessment = CenterAssessment::where('center_id', $center->id)
                ->orderBy('assessment_date', 'desc')
                ->first();
            return [
                'name' => $center->name,
                'score' => $assessment ? $assessment->total_score : 0,
                'date' => $assessment ? $assessment->assessment_date : '-',
            ];
        })->sortByDesc('score')->take(5);

        return view('home', compact('data', 'ranking'));
    }
}
