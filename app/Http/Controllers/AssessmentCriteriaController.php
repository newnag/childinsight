<?php

namespace App\Http\Controllers;

use App\Models\AssessmentCriteria;
use Illuminate\Http\Request;

class AssessmentCriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Ideally, add middleware to check if user is admin
    }

    public function index()
    {
        $criterias = AssessmentCriteria::orderBy('category')->orderBy('id')->get();
        return view('assessment_criterias.index', compact('criterias'));
    }

    public function create()
    {
        return view('assessment_criterias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'max_score' => 'required|integer|min:1',
        ]);

        AssessmentCriteria::create($request->all());

        return redirect()->route('assessment_criterias.index')
            ->with('success', 'Criteria created successfully.');
    }

    public function edit(AssessmentCriteria $assessmentCriteria)
    {
        return view('assessment_criterias.edit', compact('assessmentCriteria'));
    }

    public function update(Request $request, AssessmentCriteria $assessmentCriteria)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'max_score' => 'required|integer|min:1',
        ]);

        $assessmentCriteria->update($request->all());

        return redirect()->route('assessment_criterias.index')
            ->with('success', 'Criteria updated successfully.');
    }

    public function destroy(AssessmentCriteria $assessmentCriteria)
    {
        $assessmentCriteria->delete();

        return redirect()->route('assessment_criterias.index')
            ->with('success', 'Criteria deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'criterias' => 'present|array',
            'criterias.*.category' => 'required|string|max:255',
            'criterias.*.topic' => 'required|string|max:255',
            'criterias.*.max_score' => 'required|integer|min:1',
        ]);

        $submittedIds = collect($request->input('criterias', []))
            ->pluck('id')
            ->filter()
            ->toArray();

        // Delete criteria not present in the request
        AssessmentCriteria::whereNotIn('id', $submittedIds)->delete();

        foreach ($request->input('criterias', []) as $data) {
            if (isset($data['id']) && $data['id']) {
                AssessmentCriteria::where('id', $data['id'])->update([
                    'category' => $data['category'],
                    'topic' => $data['topic'],
                    'max_score' => $data['max_score'],
                ]);
            } else {
                AssessmentCriteria::create([
                    'category' => $data['category'],
                    'topic' => $data['topic'],
                    'max_score' => $data['max_score'],
                ]);
            }
        }

        return redirect()->route('assessment_criterias.index')
            ->with('success', 'Assessment criteria updated successfully.');
    }
}
