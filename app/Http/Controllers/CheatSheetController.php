<?php

namespace App\Http\Controllers;

use App\Models\CheatSheet;
use App\Models\JobPosting;
use App\Services\CheatSheetService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CheatSheetController extends Controller
{
    protected CheatSheetService $cheatSheetService;

    public function __construct(CheatSheetService $cheatSheetService)
    {
        $this->cheatSheetService = $cheatSheetService;
    }

    /**
     * Display a listing of cheat sheets
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $cheatSheets = $user->cheatSheets()
            ->when($request->category, function ($query, $category) {
                return $query->byCategory($category);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('topic', 'like', "%{$search}%")
                           ->orWhere('talking_points', 'like', "%{$search}%");
            })
            ->when($request->filter === 'most_practiced', function ($query) {
                return $query->mostPracticed();
            })
            ->when($request->filter === 'needs_practice', function ($query) {
                return $query->needsPractice();
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        $categories = CheatSheet::getCategories();
        $userCategories = $user->cheatSheets()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('cheat-sheets.index', compact('cheatSheets', 'categories', 'userCategories'));
    }

    /**
     * Show the form for creating a new cheat sheet
     */
    public function create()
    {
        $user = Auth::user();
        
        $jobPostings = $user->jobPostings()
            ->select('id', 'title', 'company')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = CheatSheet::getCategories();

        return view('cheat-sheets.create', compact('jobPostings', 'categories'));
    }

    /**
     * Generate a new cheat sheet
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'type' => 'required|in:general,personalized,job_specific',
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'context' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $cheatSheet = match ($request->type) {
                'general' => $this->cheatSheetService->generateGeneralCheatSheet(
                    Auth::user(),
                    $request->topic,
                    $request->category,
                    $request->context
                ),
                'personalized' => $this->cheatSheetService->generatePersonalizedCheatSheet(
                    Auth::user(),
                    $request->topic,
                    $request->category,
                    $request->context
                ),
                'job_specific' => $this->cheatSheetService->generateJobSpecificCheatSheet(
                    Auth::user(),
                    JobPosting::findOrFail($request->job_posting_id),
                    $request->topic,
                    $request->category,
                    $request->context
                ),
            };

            return response()->json([
                'success' => true,
                'cheat_sheet' => $cheatSheet,
                'redirect' => route('cheat-sheets.show', $cheatSheet)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate cheat sheet: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified cheat sheet
     */
    public function show(CheatSheet $cheatSheet)
    {
        $this->authorize('view', $cheatSheet);

        $cheatSheet->load('jobPosting');
        $cheatSheet->incrementUsage();

        return view('cheat-sheets.show', compact('cheatSheet'));
    }

    /**
     * Show the form for editing the specified cheat sheet
     */
    public function edit(CheatSheet $cheatSheet)
    {
        $this->authorize('update', $cheatSheet);

        $categories = CheatSheet::getCategories();

        return view('cheat-sheets.edit', compact('cheatSheet', 'categories'));
    }

    /**
     * Update the specified cheat sheet
     */
    public function update(Request $request, CheatSheet $cheatSheet)
    {
        $this->authorize('update', $cheatSheet);

        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'talking_points' => 'required|array|min:1',
            'talking_points.*' => 'string|max:500',
            'example_responses' => 'nullable|array',
            'example_responses.*' => 'string|max:1000',
            'tips' => 'nullable|array',
            'tips.*' => 'string|max:300',
            'notes' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $cheatSheet->update([
                'topic' => $request->topic,
                'category' => $request->category,
                'talking_points' => $request->talking_points,
                'example_responses' => $request->example_responses ?? [],
                'tips' => $request->tips ?? [],
                'notes' => $request->notes,
                'updated_at' => now(),
            ]);

            return redirect()->route('cheat-sheets.show', $cheatSheet)
                ->with('success', 'Cheat sheet updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update cheat sheet: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update cheat sheet score based on practice performance
     */
    public function updateScore(Request $request, CheatSheet $cheatSheet): JsonResponse
    {
        $this->authorize('update', $cheatSheet);

        $validator = Validator::make($request->all(), [
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $cheatSheet->updateScore($request->score, $request->feedback);

            return response()->json([
                'success' => true,
                'message' => 'Score updated successfully.',
                'new_average' => $cheatSheet->fresh()->average_score
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update score: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh the cheat sheet with new AI-generated content
     */
    public function refresh(CheatSheet $cheatSheet): JsonResponse
    {
        $this->authorize('update', $cheatSheet);

        try {
            $updatedCheatSheet = $this->cheatSheetService->refreshCheatSheet($cheatSheet);

            return response()->json([
                'success' => true,
                'cheat_sheet' => $updatedCheatSheet,
                'message' => 'Cheat sheet refreshed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh cheat sheet: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified cheat sheet
     */
    public function destroy(CheatSheet $cheatSheet)
    {
        $this->authorize('delete', $cheatSheet);

        try {
            $cheatSheet->delete();

            return redirect()->route('cheat-sheets.index')
                ->with('success', 'Cheat sheet deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete cheat sheet: ' . $e->getMessage());
        }
    }

    /**
     * Get cheat sheet recommendations
     */
    public function recommendations(): JsonResponse
    {
        try {
            $recommendations = $this->cheatSheetService->getRecommendations(Auth::user());

            return response()->json([
                'success' => true,
                'recommendations' => $recommendations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get recommendations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cheat sheet data for API
     */
    public function api(CheatSheet $cheatSheet): JsonResponse
    {
        $this->authorize('view', $cheatSheet);

        return response()->json([
            'id' => $cheatSheet->id,
            'topic' => $cheatSheet->topic,
            'category' => $cheatSheet->category,
            'talking_points' => $cheatSheet->talking_points,
            'example_responses' => $cheatSheet->example_responses,
            'tips' => $cheatSheet->tips,
            'notes' => $cheatSheet->notes,
            'usage_count' => $cheatSheet->usage_count,
            'average_score' => $cheatSheet->average_score,
            'last_practiced_at' => $cheatSheet->last_practiced_at,
            'created_at' => $cheatSheet->created_at,
            'updated_at' => $cheatSheet->updated_at,
        ]);
    }

    /**
     * Search for cheat sheets
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $cheatSheets = Auth::user()->cheatSheets()
            ->where('topic', 'like', "%{$query}%")
            ->orWhere('talking_points', 'like', "%{$query}%")
            ->select('id', 'topic', 'category', 'average_score', 'usage_count')
            ->orderBy('usage_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json($cheatSheets);
    }

    /**
     * Get cheat sheets by category
     */
    public function byCategory(string $category): JsonResponse
    {
        $cheatSheets = Auth::user()->cheatSheets()
            ->byCategory($category)
            ->select('id', 'topic', 'average_score', 'usage_count', 'last_practiced_at')
            ->orderBy('average_score', 'asc')
            ->get();

        return response()->json($cheatSheets);
    }
}