<?php

namespace App\Livewire;

use App\Models\Interview;
use App\Services\AIFeedbackService;
use App\Services\AIService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RealTimeFeedback extends Component
{
    public $interviewId;

    public $currentQuestion;

    public $userResponse = '';

    public $isRecording = false;

    public $recordingTime = 0;

    // Real-time feedback metrics
    public $realTimeFeedback = [
        'speaking_pace' => 'normal',
        'filler_words' => 0,
        'eye_contact' => 'good',
        'audio_quality' => 'excellent',
        'volume_level' => 'optimal',
        'clarity_score' => 85,
        'confidence_level' => 'moderate',
    ];

    public $currentScore = 0;

    public $feedbackHistory = [];

    public $isAnalyzing = false;

    public $analysisInterval = 5; // seconds

    public $suggestions = [];

    // Feedback thresholds
    public $thresholds = [
        'filler_words_warning' => 3,
        'pace_too_fast' => 180, // words per minute
        'pace_too_slow' => 120,
        'volume_too_low' => 30,
        'volume_too_high' => 90,
        'clarity_warning' => 70,
    ];

    protected $aiService;

    protected $feedbackService;

    public function mount($interviewId = null, $currentQuestion = null)
    {
        $this->interviewId = $interviewId;
        $this->currentQuestion = $currentQuestion;
        $this->aiService = app(AIService::class);
        $this->feedbackService = app(AIFeedbackService::class);
        $this->initializeFeedback();
    }

    public function initializeFeedback()
    {
        $this->realTimeFeedback = [
            'speaking_pace' => 'normal',
            'filler_words' => 0,
            'eye_contact' => 'good',
            'audio_quality' => 'excellent',
            'volume_level' => 'optimal',
            'clarity_score' => 85,
            'confidence_level' => 'moderate',
        ];

        $this->currentScore = 0;
        $this->suggestions = [];
        $this->feedbackHistory = [];
    }

    #[On('recording-started')]
    public function startAnalysis()
    {
        $this->isRecording = true;
        $this->isAnalyzing = true;
        $this->initializeFeedback();

        // Start periodic analysis
        $this->dispatch('start-feedback-analysis');
    }

    #[On('recording-stopped')]
    public function stopAnalysis()
    {
        $this->isRecording = false;
        $this->isAnalyzing = false;

        // Generate final feedback summary
        $this->generateFinalFeedback();

        $this->dispatch('stop-feedback-analysis');
    }

    #[On('audio-data-received')]
    public function processAudioData($audioData)
    {
        if (! $this->isAnalyzing) {
            return;
        }

        try {
            // Analyze audio data for real-time feedback using AI service
            $analysis = $this->analyzeAudioData($audioData);

            $this->updateFeedbackMetrics($analysis);
            $this->generateSuggestions();
            $this->calculateCurrentScore();

        } catch (\Exception $e) {
            Log::error('Real-time feedback analysis error: '.$e->getMessage());
            // Fallback to simulation if AI service fails
            $analysis = $this->simulateAudioAnalysis($audioData);
            $this->updateFeedbackMetrics($analysis);
        }
    }

    #[On('video-data-received')]
    public function processVideoData($videoData)
    {
        if (! $this->isAnalyzing) {
            return;
        }

        try {
            // Analyze video data for eye contact and confidence using AI service
            $analysis = $this->analyzeVideoData($videoData);

            $this->realTimeFeedback['eye_contact'] = $analysis['eye_contact'] ?? 'good';
            $this->realTimeFeedback['confidence_level'] = $analysis['confidence_level'] ?? 'moderate';

        } catch (\Exception $e) {
            Log::error('Video analysis error: '.$e->getMessage());
            // Fallback to simulation if AI service fails
            $analysis = $this->simulateVideoAnalysis($videoData);
            $this->realTimeFeedback['eye_contact'] = $analysis['eye_contact'] ?? 'good';
            $this->realTimeFeedback['confidence_level'] = $analysis['confidence_level'] ?? 'moderate';
        }
    }

    #[On('text-input-received')]
    public function processTextInput($text)
    {
        $this->userResponse = $text;

        if (! empty($text) && $this->isAnalyzing) {
            try {
                // Analyze text for filler words and clarity using AI service
                $analysis = $this->analyzeTextData($text);

                $this->realTimeFeedback['filler_words'] = $analysis['filler_words'] ?? 0;
                $this->realTimeFeedback['clarity_score'] = $analysis['clarity_score'] ?? 85;

                $this->generateSuggestions();
            } catch (\Exception $e) {
                Log::error('Text analysis error: '.$e->getMessage());
                // Fallback to simulation if AI service fails
                $analysis = $this->simulateTextAnalysis($text);
                $this->realTimeFeedback['filler_words'] = $analysis['filler_words'] ?? 0;
                $this->realTimeFeedback['clarity_score'] = $analysis['clarity_score'] ?? 85;
            }
        }
    }

    protected function simulateAudioAnalysis($audioData)
    {
        // Simulate audio analysis - replace with actual implementation
        return [
            'speaking_pace' => collect(['normal', 'slightly_fast', 'slightly_slow'])->random(),
            'audio_quality' => collect(['excellent', 'good', 'fair'])->random(),
            'volume_level' => collect(['optimal', 'too_low', 'too_high'])->random(),
        ];
    }

    protected function simulateVideoAnalysis($videoData)
    {
        // Simulate video analysis - replace with actual implementation
        return [
            'eye_contact' => collect(['excellent', 'good', 'fair'])->random(),
            'confidence_level' => collect(['high', 'moderate', 'low'])->random(),
        ];
    }

    protected function simulateTextAnalysis($text)
    {
        // Simulate text analysis - replace with actual implementation
        $fillerWords = ['um', 'uh', 'like', 'you know', 'so', 'actually'];
        $fillerCount = 0;

        foreach ($fillerWords as $filler) {
            $fillerCount += substr_count(strtolower($text), $filler);
        }

        $wordCount = str_word_count($text);
        $clarityScore = max(50, 100 - ($fillerCount * 5) - (max(0, 200 - $wordCount) * 0.1));

        return [
            'filler_words' => $fillerCount,
            'clarity_score' => round($clarityScore),
        ];
    }

    protected function analyzeAudioData($audioData)
    {
        // Use AI service to analyze audio data
        $prompt = "Analyze this audio data for interview feedback. Provide analysis in JSON format:

        Audio Data: {$audioData}

        Return JSON with:
        {
            \"speaking_pace\": \"normal|slightly_fast|slightly_slow|too_fast|too_slow\",
            \"audio_quality\": \"excellent|good|fair|poor\",
            \"volume_level\": \"optimal|too_low|too_high\",
            \"confidence_score\": 0-100,
            \"clarity_indicators\": [\"clear_articulation\", \"good_pace\", \"appropriate_volume\"]
        }";

        try {
            $response = $this->aiService->generateResponse($prompt, 'You are an expert speech analyst. Analyze audio characteristics for interview performance feedback.');
            $analysis = json_decode($response, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $analysis;
            }
        } catch (\Exception $e) {
            Log::warning('AI audio analysis failed, using fallback', ['error' => $e->getMessage()]);
        }

        // Fallback to simulation
        return $this->simulateAudioAnalysis($audioData);
    }

    protected function analyzeVideoData($videoData)
    {
        // Use AI service to analyze video data
        $prompt = "Analyze this video data for interview feedback. Provide analysis in JSON format:

        Video Data: {$videoData}

        Return JSON with:
        {
            \"eye_contact\": \"excellent|good|fair|poor\",
            \"confidence_level\": \"high|moderate|low\",
            \"body_language\": \"confident|neutral|nervous\",
            \"engagement_score\": 0-100,
            \"visual_indicators\": [\"good_posture\", \"eye_contact\", \"natural_gestures\"]
        }";

        try {
            $response = $this->aiService->generateResponse($prompt, 'You are an expert body language analyst. Analyze visual cues for interview performance feedback.');
            $analysis = json_decode($response, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $analysis;
            }
        } catch (\Exception $e) {
            Log::warning('AI video analysis failed, using fallback', ['error' => $e->getMessage()]);
        }

        // Fallback to simulation
        return $this->simulateVideoAnalysis($videoData);
    }

    protected function analyzeTextData($text)
    {
        // Use AI service to analyze text data
        $prompt = "Analyze this interview response text for communication quality. Provide analysis in JSON format:

        Text: {$text}

        Return JSON with:
        {
            \"filler_words\": number,
            \"clarity_score\": 0-100,
            \"structure_score\": 0-100,
            \"relevance_score\": 0-100,
            \"communication_quality\": \"excellent|good|fair|poor\",
            \"improvement_areas\": [\"area1\", \"area2\"],
            \"strengths\": [\"strength1\", \"strength2\"]
        }";

        try {
            $response = $this->aiService->generateResponse($prompt, 'You are an expert communication analyst. Analyze text quality for interview performance feedback.');
            $analysis = json_decode($response, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $analysis;
            }
        } catch (\Exception $e) {
            Log::warning('AI text analysis failed, using fallback', ['error' => $e->getMessage()]);
        }

        // Fallback to simulation
        return $this->simulateTextAnalysis($text);
    }

    public function updateFeedbackMetrics($analysis)
    {
        if (isset($analysis['speaking_pace'])) {
            $this->realTimeFeedback['speaking_pace'] = $analysis['speaking_pace'];
        }

        if (isset($analysis['audio_quality'])) {
            $this->realTimeFeedback['audio_quality'] = $analysis['audio_quality'];
        }

        if (isset($analysis['volume_level'])) {
            $this->realTimeFeedback['volume_level'] = $analysis['volume_level'];
        }

        // Store feedback snapshot
        $this->feedbackHistory[] = [
            'timestamp' => now(),
            'metrics' => $this->realTimeFeedback,
            'score' => $this->currentScore,
        ];

        // Keep only last 20 entries to prevent memory issues
        if (count($this->feedbackHistory) > 20) {
            array_shift($this->feedbackHistory);
        }
    }

    public function generateSuggestions()
    {
        $suggestions = [];

        // Speaking pace suggestions
        if ($this->realTimeFeedback['speaking_pace'] === 'too_fast') {
            $suggestions[] = [
                'type' => 'pace',
                'message' => 'Try to slow down your speaking pace for better clarity',
                'priority' => 'high',
                'icon' => 'clock',
            ];
        } elseif ($this->realTimeFeedback['speaking_pace'] === 'too_slow') {
            $suggestions[] = [
                'type' => 'pace',
                'message' => 'You can speak a bit faster to maintain engagement',
                'priority' => 'medium',
                'icon' => 'clock',
            ];
        }

        // Filler words suggestions
        if ($this->realTimeFeedback['filler_words'] >= $this->thresholds['filler_words_warning']) {
            $suggestions[] = [
                'type' => 'filler_words',
                'message' => 'Try to reduce filler words like "um", "uh", "like"',
                'priority' => 'high',
                'icon' => 'microphone',
            ];
        }

        // Audio quality suggestions
        if ($this->realTimeFeedback['audio_quality'] === 'poor') {
            $suggestions[] = [
                'type' => 'audio',
                'message' => 'Check your microphone or move closer to improve audio quality',
                'priority' => 'high',
                'icon' => 'volume-up',
            ];
        }

        // Volume level suggestions
        if ($this->realTimeFeedback['volume_level'] === 'too_low') {
            $suggestions[] = [
                'type' => 'volume',
                'message' => 'Speak a bit louder for better clarity',
                'priority' => 'medium',
                'icon' => 'volume-up',
            ];
        } elseif ($this->realTimeFeedback['volume_level'] === 'too_high') {
            $suggestions[] = [
                'type' => 'volume',
                'message' => 'Lower your voice slightly to avoid distortion',
                'priority' => 'medium',
                'icon' => 'volume-down',
            ];
        }

        // Eye contact suggestions
        if ($this->realTimeFeedback['eye_contact'] === 'poor') {
            $suggestions[] = [
                'type' => 'eye_contact',
                'message' => 'Try to look at the camera more often to maintain eye contact',
                'priority' => 'medium',
                'icon' => 'eye',
            ];
        }

        // Clarity suggestions
        if ($this->realTimeFeedback['clarity_score'] < $this->thresholds['clarity_warning']) {
            $suggestions[] = [
                'type' => 'clarity',
                'message' => 'Speak more clearly and articulate your words',
                'priority' => 'high',
                'icon' => 'message-circle',
            ];
        }

        $this->suggestions = $suggestions;
    }

    public function calculateCurrentScore()
    {
        $scores = [];

        // Speaking pace score (0-100)
        switch ($this->realTimeFeedback['speaking_pace']) {
            case 'normal':
                $scores['pace'] = 100;
                break;
            case 'slightly_fast':
            case 'slightly_slow':
                $scores['pace'] = 80;
                break;
            case 'too_fast':
            case 'too_slow':
                $scores['pace'] = 60;
                break;
            default:
                $scores['pace'] = 70;
        }

        // Filler words score (0-100)
        $fillerCount = $this->realTimeFeedback['filler_words'];
        $scores['filler'] = max(0, 100 - ($fillerCount * 10));

        // Audio quality score (0-100)
        switch ($this->realTimeFeedback['audio_quality']) {
            case 'excellent':
                $scores['audio'] = 100;
                break;
            case 'good':
                $scores['audio'] = 85;
                break;
            case 'fair':
                $scores['audio'] = 70;
                break;
            case 'poor':
                $scores['audio'] = 50;
                break;
            default:
                $scores['audio'] = 75;
        }

        // Eye contact score (0-100)
        switch ($this->realTimeFeedback['eye_contact']) {
            case 'excellent':
                $scores['eye_contact'] = 100;
                break;
            case 'good':
                $scores['eye_contact'] = 85;
                break;
            case 'fair':
                $scores['eye_contact'] = 70;
                break;
            case 'poor':
                $scores['eye_contact'] = 50;
                break;
            default:
                $scores['eye_contact'] = 75;
        }

        // Clarity score (already 0-100)
        $scores['clarity'] = $this->realTimeFeedback['clarity_score'];

        // Calculate weighted average
        $weights = [
            'pace' => 0.2,
            'filler' => 0.2,
            'audio' => 0.2,
            'eye_contact' => 0.2,
            'clarity' => 0.2,
        ];

        $weightedSum = 0;
        foreach ($scores as $metric => $score) {
            $weightedSum += $score * $weights[$metric];
        }

        $this->currentScore = round($weightedSum);
    }

    public function generateFinalFeedback()
    {
        if (empty($this->feedbackHistory)) {
            return;
        }

        // Calculate averages from feedback history
        $totalEntries = count($this->feedbackHistory);
        $averages = [
            'speaking_pace' => 'normal',
            'filler_words' => 0,
            'audio_quality' => 'good',
            'eye_contact' => 'good',
            'clarity_score' => 85,
        ];

        $fillerWordsSum = 0;
        $claritySum = 0;

        foreach ($this->feedbackHistory as $entry) {
            $fillerWordsSum += $entry['metrics']['filler_words'] ?? 0;
            $claritySum += $entry['metrics']['clarity_score'] ?? 85;
        }

        $averages['filler_words'] = round($fillerWordsSum / $totalEntries);
        $averages['clarity_score'] = round($claritySum / $totalEntries);

        // Cache final feedback for the interview
        Cache::put("interview_feedback_{$this->interviewId}", [
            'averages' => $averages,
            'final_score' => $this->currentScore,
            'suggestions' => $this->suggestions,
            'analysis_duration' => $this->recordingTime,
        ], now()->addHours(24));

        $this->dispatch('final-feedback-generated', $averages);
    }

    public function clearFeedback()
    {
        $this->initializeFeedback();
        $this->dispatch('feedback-cleared');
    }

    public function pauseAnalysis()
    {
        $this->isAnalyzing = false;
        $this->dispatch('feedback-analysis-paused');
    }

    public function resumeAnalysis()
    {
        if ($this->isRecording) {
            $this->isAnalyzing = true;
            $this->dispatch('feedback-analysis-resumed');
        }
    }

    public function getPaceIndicatorProperty()
    {
        switch ($this->realTimeFeedback['speaking_pace']) {
            case 'too_slow':
                return ['color' => 'blue', 'text' => 'Too Slow', 'icon' => 'chevron-down'];
            case 'slightly_slow':
                return ['color' => 'yellow', 'text' => 'Slightly Slow', 'icon' => 'minus'];
            case 'normal':
                return ['color' => 'green', 'text' => 'Perfect Pace', 'icon' => 'check'];
            case 'slightly_fast':
                return ['color' => 'yellow', 'text' => 'Slightly Fast', 'icon' => 'plus'];
            case 'too_fast':
                return ['color' => 'red', 'text' => 'Too Fast', 'icon' => 'chevron-up'];
            default:
                return ['color' => 'gray', 'text' => 'Analyzing...', 'icon' => 'clock'];
        }
    }

    public function getAudioQualityIndicatorProperty()
    {
        switch ($this->realTimeFeedback['audio_quality']) {
            case 'excellent':
                return ['color' => 'green', 'text' => 'Excellent', 'bars' => 4];
            case 'good':
                return ['color' => 'green', 'text' => 'Good', 'bars' => 3];
            case 'fair':
                return ['color' => 'yellow', 'text' => 'Fair', 'bars' => 2];
            case 'poor':
                return ['color' => 'red', 'text' => 'Poor', 'bars' => 1];
            default:
                return ['color' => 'gray', 'text' => 'Detecting...', 'bars' => 0];
        }
    }

    public function getScoreGradeProperty()
    {
        if ($this->currentScore >= 90) {
            return ['grade' => 'A', 'color' => 'green'];
        }
        if ($this->currentScore >= 80) {
            return ['grade' => 'B', 'color' => 'blue'];
        }
        if ($this->currentScore >= 70) {
            return ['grade' => 'C', 'color' => 'yellow'];
        }
        if ($this->currentScore >= 60) {
            return ['grade' => 'D', 'color' => 'orange'];
        }

        return ['grade' => 'F', 'color' => 'red'];
    }

    #[On('recording-time-update')]
    public function updateRecordingTime($time)
    {
        $this->recordingTime = $time;
    }

    public function render()
    {
        return view('livewire.real-time-feedback', [
            'paceIndicator' => $this->getPaceIndicatorProperty(),
            'audioQualityIndicator' => $this->getAudioQualityIndicatorProperty(),
            'scoreGrade' => $this->getScoreGradeProperty(),
        ]);
    }
}
