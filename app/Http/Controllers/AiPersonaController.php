<?php

namespace App\Http\Controllers;

use App\Models\AiPersona;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AiPersonaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of AI personas.
     */
    public function index(Request $request): JsonResponse
    {
        $personas = AiPersona::query()
            ->when($request->type, function ($query, $type) {
                return $query->where('persona_type', $type);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->active_only, function ($query) {
                return $query->where('is_active', true);
            })
            ->orderBy('name')
            ->paginate(20);

        return response()->json($personas);
    }

    /**
     * Store a newly created AI persona.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ai_personas,name',
            'description' => 'required|string|max:1000',
            'persona_type' => 'required|in:technical,behavioral,case_study,general,industry_specific',
            'personality_traits' => 'required|array',
            'personality_traits.*' => 'string|max:100',
            'interview_style' => 'required|in:friendly,challenging,formal,casual,analytical',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced,expert',
            'system_prompt' => 'required|string|max:2000',
            'sample_questions' => 'nullable|array',
            'sample_questions.*' => 'string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $persona = AiPersona::create([
            'name' => $request->name,
            'description' => $request->description,
            'persona_type' => $request->persona_type,
            'personality_traits' => $request->personality_traits,
            'interview_style' => $request->interview_style,
            'difficulty_level' => $request->difficulty_level,
            'system_prompt' => $request->system_prompt,
            'sample_questions' => $request->sample_questions ?? [],
            'is_active' => $request->is_active ?? true,
            'usage_count' => 0,
        ]);

        return response()->json([
            'message' => 'AI persona created successfully',
            'persona' => $persona
        ], 201);
    }

    /**
     * Display the specified AI persona.
     */
    public function show(AiPersona $aiPersona): JsonResponse
    {
        return response()->json($aiPersona);
    }

    /**
     * Update the specified AI persona.
     */
    public function update(Request $request, AiPersona $aiPersona): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:ai_personas,name,' . $aiPersona->id,
            'description' => 'sometimes|required|string|max:1000',
            'persona_type' => 'sometimes|required|in:technical,behavioral,case_study,general,industry_specific',
            'personality_traits' => 'sometimes|required|array',
            'personality_traits.*' => 'string|max:100',
            'interview_style' => 'sometimes|required|in:friendly,challenging,formal,casual,analytical',
            'difficulty_level' => 'sometimes|required|in:beginner,intermediate,advanced,expert',
            'system_prompt' => 'sometimes|required|string|max:2000',
            'sample_questions' => 'nullable|array',
            'sample_questions.*' => 'string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $aiPersona->update($request->validated());

        return response()->json([
            'message' => 'AI persona updated successfully',
            'persona' => $aiPersona
        ]);
    }

    /**
     * Remove the specified AI persona.
     */
    public function destroy(AiPersona $aiPersona): JsonResponse
    {
        // Check if persona is being used in any active sessions
        $activeSessionsCount = $aiPersona->interviewSessions()
            ->where('status', 'in_progress')
            ->count();

        if ($activeSessionsCount > 0) {
            return response()->json([
                'message' => 'Cannot delete persona that is being used in active interview sessions',
                'active_sessions' => $activeSessionsCount
            ], 409);
        }

        $aiPersona->delete();

        return response()->json([
            'message' => 'AI persona deleted successfully'
        ]);
    }

    /**
     * Toggle the active status of an AI persona.
     */
    public function toggleActive(AiPersona $aiPersona): JsonResponse
    {
        $aiPersona->update([
            'is_active' => !$aiPersona->is_active
        ]);

        return response()->json([
            'message' => 'AI persona status updated successfully',
            'persona' => $aiPersona,
            'is_active' => $aiPersona->is_active
        ]);
    }

    /**
     * Get AI persona statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_personas' => AiPersona::count(),
            'active_personas' => AiPersona::where('is_active', true)->count(),
            'by_type' => AiPersona::selectRaw('persona_type, COUNT(*) as count')
                ->groupBy('persona_type')
                ->pluck('count', 'persona_type'),
            'by_difficulty' => AiPersona::selectRaw('difficulty_level, COUNT(*) as count')
                ->groupBy('difficulty_level')
                ->pluck('count', 'difficulty_level'),
            'most_used' => AiPersona::orderBy('usage_count', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'usage_count', 'persona_type']),
        ];

        return response()->json($stats);
    }

    /**
     * Get recommended personas based on user preferences or job requirements.
     */
    public function recommendations(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:100',
            'experience_level' => 'nullable|in:entry,mid,senior,executive',
            'interview_type' => 'nullable|in:technical,behavioral,case_study,general',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = AiPersona::where('is_active', true);

        // Filter by interview type if specified
        if ($request->interview_type) {
            $query->where('persona_type', $request->interview_type);
        }

        // Adjust difficulty based on experience level
        if ($request->experience_level) {
            $difficultyMap = [
                'entry' => ['beginner', 'intermediate'],
                'mid' => ['intermediate', 'advanced'],
                'senior' => ['advanced', 'expert'],
                'executive' => ['expert']
            ];
            
            $difficulties = $difficultyMap[$request->experience_level] ?? ['intermediate'];
            $query->whereIn('difficulty_level', $difficulties);
        }

        $recommendations = $query->orderBy('usage_count', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'recommendations' => $recommendations,
            'criteria' => $request->only(['job_title', 'industry', 'experience_level', 'interview_type']),
            'message' => 'Personas recommended based on your criteria'
        ]);
    }

    /**
     * Test an AI persona with a sample question.
     */
    public function test(Request $request, AiPersona $aiPersona): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'test_question' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Use provided question or pick a random sample question
        $question = $request->test_question;
        if (!$question && !empty($aiPersona->sample_questions)) {
            $question = $aiPersona->sample_questions[array_rand($aiPersona->sample_questions)];
        }
        
        if (!$question) {
            $question = "Tell me about yourself and your background.";
        }

        return response()->json([
            'persona' => $aiPersona->only(['name', 'description', 'interview_style', 'personality_traits']),
            'test_question' => $question,
            'system_prompt' => $aiPersona->system_prompt,
            'message' => 'This is how this persona would conduct an interview'
        ]);
    }

    /**
     * Clone an existing AI persona.
     */
    public function clone(Request $request, AiPersona $aiPersona): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ai_personas,name',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $clonedPersona = $aiPersona->replicate();
        $clonedPersona->name = $request->name;
        $clonedPersona->description = $request->description ?? $aiPersona->description . ' (Copy)';
        $clonedPersona->usage_count = 0;
        $clonedPersona->save();

        return response()->json([
            'message' => 'AI persona cloned successfully',
            'original' => $aiPersona->only(['id', 'name']),
            'clone' => $clonedPersona
        ], 201);
    }
}