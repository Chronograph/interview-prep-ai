<?php

namespace App\Http\Controllers;

use App\Models\CompanyBrief;
use App\Models\JobPosting;
use App\Services\CompanyResearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyBriefController extends Controller
{
    protected CompanyResearchService $researchService;

    public function __construct(CompanyResearchService $researchService)
    {
        $this->researchService = $researchService;
    }

    /**
     * Display a listing of company briefs
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $briefs = $user->companyBriefs()
            ->when($request->search, function ($query, $search) {
                return $query->where('company_name', 'like', "%{$search}%")
                           ->orWhere('industry', 'like', "%{$search}%");
            })
            ->when($request->industry, function ($query, $industry) {
                return $query->where('industry', $industry);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        $industries = $user->companyBriefs()
            ->distinct()
            ->pluck('industry')
            ->filter()
            ->sort()
            ->values();

        return view('company-briefs.index', compact('briefs', 'industries'));
    }

    /**
     * Show the form for creating a new company brief
     */
    public function create()
    {
        return view('company-briefs.create');
    }

    /**
     * Generate a new company brief
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'additional_context' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $brief = $this->researchService->researchCompany(
                Auth::user(),
                $request->company_name,
                $request->job_posting_id,
                $request->additional_context
            );

            return response()->json([
                'success' => true,
                'brief' => $brief,
                'redirect' => route('company-briefs.show', $brief)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate company brief: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified company brief
     */
    public function show(CompanyBrief $brief)
    {
        $this->authorize('view', $brief);

        $brief->load('jobPosting');

        // Check if brief is stale and needs updating
        $isStale = $brief->isStale();

        return view('company-briefs.show', compact('brief', 'isStale'));
    }

    /**
     * Show the form for editing the specified company brief
     */
    public function edit(CompanyBrief $brief)
    {
        $this->authorize('update', $brief);

        return view('company-briefs.edit', compact('brief'));
    }

    /**
     * Update the specified company brief
     */
    public function update(Request $request, CompanyBrief $brief)
    {
        $this->authorize('update', $brief);

        $validator = Validator::make($request->all(), [
            'talking_points' => 'nullable|array',
            'talking_points.*' => 'string|max:500',
            'potential_questions' => 'nullable|array',
            'potential_questions.*' => 'string|max:500',
            'reasons_to_work_here' => 'nullable|array',
            'reasons_to_work_here.*' => 'string|max:500',
            'notes' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $brief->update([
                'talking_points' => $request->talking_points ?? [],
                'potential_questions' => $request->potential_questions ?? [],
                'reasons_to_work_here' => $request->reasons_to_work_here ?? [],
                'notes' => $request->notes,
                'updated_at' => now(),
            ]);

            return redirect()->route('company-briefs.show', $brief)
                ->with('success', 'Company brief updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update company brief: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Refresh the company brief with new AI-generated content
     */
    public function refresh(CompanyBrief $brief): JsonResponse
    {
        $this->authorize('update', $brief);

        try {
            $updatedBrief = $this->researchService->refreshCompanyBrief($brief);

            return response()->json([
                'success' => true,
                'brief' => $updatedBrief,
                'message' => 'Company brief refreshed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh company brief: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified company brief
     */
    public function destroy(CompanyBrief $brief)
    {
        $this->authorize('delete', $brief);

        try {
            $brief->delete();

            return redirect()->route('company-briefs.index')
                ->with('success', 'Company brief deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete company brief: ' . $e->getMessage());
        }
    }

    /**
     * Get company brief data for API
     */
    public function api(CompanyBrief $brief): JsonResponse
    {
        $this->authorize('view', $brief);

        return response()->json([
            'id' => $brief->id,
            'company_name' => $brief->company_name,
            'industry' => $brief->industry,
            'company_size' => $brief->company_size,
            'talking_points' => $brief->talking_points,
            'potential_questions' => $brief->potential_questions,
            'reasons_to_work_here' => $brief->reasons_to_work_here,
            'key_competitors' => $brief->key_competitors,
            'recent_news' => $brief->recent_news,
            'company_culture' => $brief->company_culture,
            'notes' => $brief->notes,
            'is_stale' => $brief->isStale(),
            'created_at' => $brief->created_at,
            'updated_at' => $brief->updated_at,
        ]);
    }

    /**
     * Search for existing company briefs
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $briefs = Auth::user()->companyBriefs()
            ->where('company_name', 'like', "%{$query}%")
            ->select('id', 'company_name', 'industry', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($briefs);
    }

    /**
     * Get company brief suggestions based on job postings
     */
    public function suggestions(): JsonResponse
    {
        $user = Auth::user();
        
        // Get companies from job postings that don't have briefs yet
        $companiesWithoutBriefs = $user->jobPostings()
            ->whereNotIn('company', function ($query) use ($user) {
                $query->select('company_name')
                      ->from('company_briefs')
                      ->where('user_id', $user->id);
            })
            ->distinct()
            ->pluck('company')
            ->take(5);

        return response()->json($companiesWithoutBriefs);
    }
}