<?php

namespace App\Http\Controllers;

use App\Models\MasteryTopic;
use App\Models\InterviewSession;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MasteryTopicController extends Controller
{
    use AuthorizesRequests;
{
    /**
     * Display a listing of mastery topics for the user.
     */
    public function index(Request $request): JsonResponse
    {
        $masteryTopics = $request->user()->masteryTopics()
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('topic_name', 'like', "%{$search}%");
            })
            ->orderBy('mastery_level', 'desc')
            ->orderBy('topic_name')
            ->paginate(20);

        return response()->json($masteryTopics);
    }

    /**
     * Store a newly created mastery topic.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'topic_name' => 'required|string|max:255',
            'category' => 'required|in:technical,behavioral,industry,communication,problem_solving',
            'description' => 'nullable|string|max:1000',
            'target_level' => 'nullable|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if topic already exists for this user
        $existingTopic = $request->user()->masteryTopics()
            ->where('topic_name', $request->topic_name)
            ->where('category', $request->category)
            ->first();

        if ($existingTopic) {
            return response()->json([
                'message' => 'This mastery topic already exists',
                'mastery_topic' => $existingTopic
            ], 409);
        }

        $masteryTopic = MasteryTopic::create([
            'user_id' => $request->user()->id,
            'topic_name' => $request->topic_name,
            'category' => $request->category,
            'description' => $request->description,
            'mastery_level' => 1.0, // Start at beginner level
            'target_level' => $request->target_level ?? 8,
            'practice_count' => 0,
            'last_practiced_at' => null,
        ]);

        return response()->json([
            'message' => 'Mastery topic created successfully',
            'mastery_topic' => $masteryTopic
        ], 201);
    }

    /**
     * Display the specified mastery topic.
     */
    public function show(MasteryTopic $masteryTopic): JsonResponse
    {
        $this->authorize('view', $masteryTopic);

        // Load related data
        $masteryTopic->load(['user']);
        
        // Get recent practice sessions for this topic
        $recentSessions = InterviewSession::where('user_id', $masteryTopic->user_id)
            ->whereJsonContains('topics_covered', $masteryTopic->topic_name)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'created_at', 'overall_score', 'feedback_summary']);

        $masteryTopic->recent_sessions = $recentSessions;

        return response()->json($masteryTopic);
    }

    /**
     * Update the specified mastery topic.
     */
    public function update(Request $request, MasteryTopic $masteryTopic): JsonResponse
    {
        $this->authorize('update', $masteryTopic);

        $validator = Validator::make($request->all(), [
            'topic_name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|in:technical,behavioral,industry,communication,problem_solving',
            'description' => 'nullable|string|max:1000',
            'target_level' => 'nullable|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $masteryTopic->update($request->validated());

        return response()->json([
            'message' => 'Mastery topic updated successfully',
            'mastery_topic' => $masteryTopic
        ]);
    }

    /**
     * Remove the specified mastery topic.
     */
    public function destroy(MasteryTopic $masteryTopic): JsonResponse
    {
        $this->authorize('delete', $masteryTopic);

        $masteryTopic->delete();

        return response()->json([
            'message' => 'Mastery topic deleted successfully'
        ]);
    }

    /**
     * Get mastery statistics for the user.
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $stats = [
            'total_topics' => $user->masteryTopics()->count(),
            'by_category' => $user->masteryTopics()
                ->selectRaw('category, COUNT(*) as count, AVG(mastery_level) as avg_level')
                ->groupBy('category')
                ->get()
                ->keyBy('category'),
            'overall_average' => $user->masteryTopics()->avg('mastery_level'),
            'mastered_topics' => $user->masteryTopics()->where('mastery_level', '>=', 8)->count(),
            'needs_practice' => $user->masteryTopics()
                ->where('mastery_level', '<', 6)
                ->orWhere('last_practiced_at', '<', now()->subWeeks(2))
                ->count(),
            'recent_improvements' => $user->masteryTopics()
                ->where('updated_at', '>=', now()->subWeek())
                ->where('mastery_level', '>', 1)
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get mastery progress over time.
     */
    public function progress(Request $request, MasteryTopic $masteryTopic): JsonResponse
    {
        $this->authorize('view', $masteryTopic);

        // Get practice sessions that covered this topic
        $sessions = InterviewSession::where('user_id', $masteryTopic->user_id)
            ->whereJsonContains('topics_covered', $masteryTopic->topic_name)
            ->orderBy('created_at')
            ->get(['created_at', 'overall_score', 'feedback_summary']);

        $progress = $sessions->map(function ($session) {
            return [
                'date' => $session->created_at->format('Y-m-d'),
                'score' => $session->overall_score,
                'feedback' => $session->feedback_summary,
            ];
        });

        return response()->json([
            'topic' => $masteryTopic->topic_name,
            'current_level' => $masteryTopic->mastery_level,
            'target_level' => $masteryTopic->target_level,
            'progress_data' => $progress,
            'total_sessions' => $sessions->count(),
            'average_score' => $sessions->avg('overall_score'),
        ]);
    }

    /**
     * Get recommended topics to practice.
     */
    public function recommendations(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Get topics that need practice (low mastery or haven't been practiced recently)
        $needsPractice = $user->masteryTopics()
            ->where(function ($query) {
                $query->where('mastery_level', '<', 6)
                      ->orWhere('last_practiced_at', '<', now()->subWeeks(2))
                      ->orWhereNull('last_practiced_at');
            })
            ->orderBy('mastery_level')
            ->orderBy('last_practiced_at', 'asc')
            ->limit(5)
            ->get();

        // Get topics close to target level (for final push)
        $nearTarget = $user->masteryTopics()
            ->whereRaw('mastery_level >= target_level - 1')
            ->where('mastery_level', '<', 'target_level')
            ->orderBy('mastery_level', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'needs_practice' => $needsPractice,
            'near_target' => $nearTarget,
            'recommendations' => [
                'Focus on topics with mastery level below 6',
                'Practice topics you haven\'t worked on in 2+ weeks',
                'Push topics close to your target level over the finish line',
            ]
        ]);
    }

    /**
     * Bulk update mastery levels (typically called after practice sessions).
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'updates' => 'required|array',
            'updates.*.topic_id' => 'required|exists:mastery_topics,id',
            'updates.*.mastery_level' => 'required|numeric|min:0|max:10',
            'updates.*.practice_count_increment' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updatedTopics = [];
        
        foreach ($request->updates as $update) {
            $masteryTopic = MasteryTopic::find($update['topic_id']);
            
            // Ensure user owns this topic
            if ($masteryTopic && $masteryTopic->user_id === $request->user()->id) {
                $masteryTopic->update([
                    'mastery_level' => $update['mastery_level'],
                    'practice_count' => $masteryTopic->practice_count + ($update['practice_count_increment'] ?? 1),
                    'last_practiced_at' => now(),
                ]);
                
                $updatedTopics[] = $masteryTopic;
            }
        }

        return response()->json([
            'message' => 'Mastery topics updated successfully',
            'updated_topics' => $updatedTopics,
            'count' => count($updatedTopics)
        ]);
    }
}