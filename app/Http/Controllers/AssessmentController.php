<?php

namespace App\Http\Controllers;

use App\Models\AssessmentCriteria;
use App\Models\CenterAssessment;
use App\Models\AssessmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Manager View: KPI Dashboard
        if ($user->role === 'manager') {
            $centerId = $user->center_id;
            if (!$centerId) {
                return redirect()->route('home')->with('error', 'No center assigned.');
            }

            // 1. Score History (Line Chart)
            $assessments = CenterAssessment::where('center_id', $centerId)
                ->orderBy('assessment_date', 'asc')
                ->get();
            
            $historyLabels = $assessments->pluck('assessment_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M Y'));
            $historyScores = $assessments->pluck('total_score');

            // 2. Average Score by Category (Radar/Bar Chart)
            $categoryStats = DB::table('assessment_items')
                ->join('center_assessments', 'assessment_items.assessment_id', '=', 'center_assessments.id')
                ->join('assessment_criterias', 'assessment_items.criteria_id', '=', 'assessment_criterias.id')
                ->where('center_assessments.center_id', $centerId)
                ->select('assessment_criterias.category', DB::raw('AVG(assessment_items.score) as avg_score'))
                ->groupBy('assessment_criterias.category')
                ->get();
            
            $categoryLabels = $categoryStats->pluck('category');
            $categoryScores = $categoryStats->pluck('avg_score')->map(fn($s) => round($s, 2));

            // 3. Detailed Scores by Category and Criteria
            $criteriaStats = DB::table('assessment_items')
                ->join('center_assessments', 'assessment_items.assessment_id', '=', 'center_assessments.id')
                ->join('assessment_criterias', 'assessment_items.criteria_id', '=', 'assessment_criterias.id')
                ->where('center_assessments.center_id', $centerId)
                ->select(
                    'assessment_criterias.category',
                    'assessment_criterias.topic as criteria_name',
                    DB::raw('AVG(assessment_items.score) as avg_score')
                )
                ->groupBy('assessment_criterias.category', 'assessment_criterias.topic')
                ->get()
                ->groupBy('category');

            return view('assessments.manager_kpi', compact('historyLabels', 'historyScores', 'categoryLabels', 'categoryScores', 'criteriaStats'));
        }

        // Admin/Inspector/Others View
        $centerId = $user->center_id;

        if (!$centerId && ($user->role === 'admin' || $user->role === 'inspector')) {
            // Admin and Inspector see all assessments
            $assessments = CenterAssessment::with('center')
                ->orderBy('assessment_date', 'desc')
                ->get();
        } else {
            if (!$centerId) {
                return redirect()->route('home')->with('error', 'No center assigned.');
            }

            $assessments = CenterAssessment::where('center_id', $centerId)
                ->orderBy('assessment_date', 'desc')
                ->get();
        }

        return view('assessments.index', compact('assessments'));
    }

    public function create()
    {
        if (Auth::user()->role === 'inspector' || Auth::user()->role === 'manager') {
            abort(403, 'You are not allowed to create assessments.');
        }
        $criterias = AssessmentCriteria::all()->groupBy('category');
        return view('assessments.create', compact('criterias'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'inspector' || Auth::user()->role === 'manager') {
            abort(403, 'You are not allowed to create assessments.');
        }

        $request->validate([
            'assessment_date' => 'required|date',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:0',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            $totalScore = array_sum($request->input('scores'));

            $centerId = $user->center_id;
            if (!$centerId && $user->role === 'admin') {
                $center = \App\Models\Center::first();
                $centerId = $center ? $center->id : null;
            }

            $assessment = CenterAssessment::create([
                'center_id' => $centerId,
                'assessor_id' => $user->id,
                'assessment_date' => $request->input('assessment_date'),
                'total_score' => $totalScore,
                'status' => 'submitted',
            ]);

            foreach ($request->input('scores') as $criteriaId => $score) {
                // Handle file upload if exists
                $photos = [];
                if ($request->hasFile("evidence.$criteriaId")) {
                    foreach ($request->file("evidence.$criteriaId") as $file) {
                        $path = $file->store('evidence', 'public');
                        $photos[] = $path;
                    }
                }

                AssessmentItem::create([
                    'assessment_id' => $assessment->id,
                    'criteria_id' => $criteriaId,
                    'score' => $score,
                    'evidence_photos' => !empty($photos) ? json_encode($photos) : null,
                    'comment' => $request->input("comments.$criteriaId"),
                ]);
            }
        });

        return redirect()->route('assessments.index')->with('success', 'Assessment submitted successfully.');
    }

    public function show($id)
    {
        $assessment = CenterAssessment::with(['items.criteria', 'assessor'])->findOrFail($id);
        $groupedItems = $assessment->items->groupBy(function($item) {
            return $item->criteria->category;
        });

        return view('assessments.show', compact('assessment', 'groupedItems'));
    }

    public function exportPdf()
    {
        $user = Auth::user();
        
        // Allow manager (own center) or admin/inspector (if they could access this view, but currently this is for manager dashboard)
        // The request specifically says "KPI score page of manager", so let's restrict to manager or admin acting as manager context?
        // For now, let's assume it's for the logged-in manager.
        
        $centerId = $user->center_id;
        
        if (!$centerId && $user->role === 'admin') {
             // If admin wants to export, maybe they need to select a center? 
             // But the current manager_kpi view is only for role='manager'.
             // So let's stick to that logic.
             return redirect()->back()->with('error', 'Export is only available for Managers.');
        }

        if ($user->role !== 'manager') {
             // Although the prompt says "manager", let's be safe.
             // If an inspector is viewing a specific center, they might want this too.
             // But the "KPI Dashboard" logic in index() is strictly `if ($user->role === 'manager')`.
             // So I will enforce that.
             abort(403, 'Unauthorized');
        }

        $center = \App\Models\Center::findOrFail($centerId);

        // Detailed Scores by Category and Criteria
        // Reusing the logic from index()
        $criteriaStats = DB::table('assessment_items')
            ->join('center_assessments', 'assessment_items.assessment_id', '=', 'center_assessments.id')
            ->join('assessment_criterias', 'assessment_items.criteria_id', '=', 'assessment_criterias.id')
            ->where('center_assessments.center_id', $centerId)
            ->select(
                'assessment_criterias.category',
                'assessment_criterias.topic as criteria_name',
                DB::raw('AVG(assessment_items.score) as avg_score')
            )
            ->groupBy('assessment_criterias.category', 'assessment_criterias.topic')
            ->get()
            ->groupBy('category');

        return view('assessments.pdf', compact('criteriaStats', 'center'));
    }
}
