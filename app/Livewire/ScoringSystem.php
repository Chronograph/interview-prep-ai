<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class ScoringSystem extends Component
{
    // Core Properties
    public $interviewId;

    public $currentScore = 0;

    public $maxScore = 100;

    public $scoreBreakdown = [];

    public $overallGrade = 'N/A';

    public $gradeColor = 'gray';

    // Scoring Categories
    public $categories = [
        'technical_knowledge' => [
            'name' => 'Technical Knowledge',
            'weight' => 30,
            'score' => 0,
            'max_score' => 30,
            'icon' => 'academic-cap',
            'color' => 'blue',
        ],
        'communication' => [
            'name' => 'Communication Skills',
            'weight' => 25,
            'score' => 0,
            'max_score' => 25,
            'icon' => 'chat-bubble-left-right',
            'color' => 'green',
        ],
        'problem_solving' => [
            'name' => 'Problem Solving',
            'weight' => 20,
            'score' => 0,
            'max_score' => 20,
            'icon' => 'puzzle-piece',
            'color' => 'purple',
        ],
        'presentation' => [
            'name' => 'Presentation & Delivery',
            'weight' => 15,
            'score' => 0,
            'max_score' => 15,
            'icon' => 'presentation-chart-line',
            'color' => 'orange',
        ],
        'professionalism' => [
            'name' => 'Professionalism',
            'weight' => 10,
            'score' => 0,
            'max_score' => 10,
            'icon' => 'user-tie',
            'color' => 'indigo',
        ],
    ];

    // Analytics Data
    public $analytics = [
        'total_questions' => 0,
        'questions_answered' => 0,
        'average_response_time' => 0,
        'total_speaking_time' => 0,
        'filler_words_count' => 0,
        'confidence_level' => 0,
        'eye_contact_percentage' => 0,
        'speaking_pace' => 0,
    ];

    // Detailed Feedback
    public $detailedFeedback = [];

    public $strengths = [];

    public $improvements = [];

    public $recommendations = [];

    // UI State
    public $showDetailedView = false;

    public $selectedCategory = null;

    public $isCalculating = false;

    public $showExportModal = false;

    // Historical Data
    public $scoreHistory = [];

    public $improvementTrend = 0;

    public function mount($interviewId = null)
    {
        $this->interviewId = $interviewId;
        $this->initializeScoring();

        if ($this->interviewId) {
            $this->loadInterviewData();
        }
    }

    #[On('interview-response-submitted')]
    public function handleResponseSubmitted($responseData)
    {
        $this->updateScoreFromResponse($responseData);
        $this->calculateOverallScore();
        $this->generateFeedback();
    }

    #[On('real-time-feedback-updated')]
    public function handleRealTimeFeedback($feedbackData)
    {
        $this->updatePresentationScore($feedbackData);
        $this->updateAnalytics($feedbackData);
        $this->calculateOverallScore();
    }

    #[On('interview-completed')]
    public function handleInterviewCompleted($interviewData)
    {
        $this->finalizeScoring($interviewData);
        $this->generateDetailedReport();
        $this->saveScoreHistory();
    }

    public function initializeScoring()
    {
        $this->resetScores();
        $this->resetAnalytics();
        $this->isCalculating = false;
    }

    public function resetScores()
    {
        foreach ($this->categories as $key => $category) {
            $this->categories[$key]['score'] = 0;
        }
        $this->currentScore = 0;
        $this->overallGrade = 'N/A';
        $this->gradeColor = 'gray';
    }

    public function resetAnalytics()
    {
        $this->analytics = [
            'total_questions' => 0,
            'questions_answered' => 0,
            'average_response_time' => 0,
            'total_speaking_time' => 0,
            'filler_words_count' => 0,
            'confidence_level' => 0,
            'eye_contact_percentage' => 0,
            'speaking_pace' => 0,
        ];
    }

    public function updateScoreFromResponse($responseData)
    {
        try {
            $this->isCalculating = true;

            // Simulate scoring logic - in real implementation, this would use AI/ML
            $technicalScore = $this->calculateTechnicalScore($responseData);
            $communicationScore = $this->calculateCommunicationScore($responseData);
            $problemSolvingScore = $this->calculateProblemSolvingScore($responseData);

            $this->categories['technical_knowledge']['score'] += $technicalScore;
            $this->categories['communication']['score'] += $communicationScore;
            $this->categories['problem_solving']['score'] += $problemSolvingScore;

            $this->analytics['questions_answered']++;

        } catch (\Exception $e) {
            Log::error('Error updating score from response: '.$e->getMessage());
        } finally {
            $this->isCalculating = false;
        }
    }

    public function updatePresentationScore($feedbackData)
    {
        try {
            // Update presentation score based on real-time feedback
            $presentationScore = 0;

            // Eye contact scoring
            if (isset($feedbackData['eye_contact'])) {
                $eyeContactScore = match ($feedbackData['eye_contact']) {
                    'excellent' => 5,
                    'good' => 4,
                    'fair' => 3,
                    'poor' => 1,
                    default => 2
                };
                $presentationScore += $eyeContactScore;
                $this->analytics['eye_contact_percentage'] = $eyeContactScore * 20;
            }

            // Speaking pace scoring
            if (isset($feedbackData['speaking_pace'])) {
                $paceScore = ($feedbackData['speaking_pace'] >= 120 && $feedbackData['speaking_pace'] <= 160) ? 5 : 3;
                $presentationScore += $paceScore;
                $this->analytics['speaking_pace'] = $feedbackData['speaking_pace'];
            }

            // Filler words penalty
            if (isset($feedbackData['filler_words'])) {
                $fillerPenalty = min($feedbackData['filler_words'] * 0.5, 3);
                $presentationScore = max(0, $presentationScore - $fillerPenalty);
                $this->analytics['filler_words_count'] += $feedbackData['filler_words'];
            }

            $this->categories['presentation']['score'] = min(
                $this->categories['presentation']['max_score'],
                $presentationScore
            );

        } catch (\Exception $e) {
            Log::error('Error updating presentation score: '.$e->getMessage());
        }
    }

    public function updateAnalytics($feedbackData)
    {
        if (isset($feedbackData['response_time'])) {
            $this->analytics['average_response_time'] =
                ($this->analytics['average_response_time'] + $feedbackData['response_time']) / 2;
        }

        if (isset($feedbackData['speaking_time'])) {
            $this->analytics['total_speaking_time'] += $feedbackData['speaking_time'];
        }

        if (isset($feedbackData['confidence_level'])) {
            $this->analytics['confidence_level'] = $feedbackData['confidence_level'];
        }
    }

    public function calculateOverallScore()
    {
        $totalScore = 0;

        foreach ($this->categories as $category) {
            $totalScore += $category['score'];
        }

        $this->currentScore = min(100, round($totalScore));
        $this->updateGrade();
        $this->updateScoreBreakdown();
    }

    public function updateGrade()
    {
        $grade = $this->calculateGrade($this->currentScore);
        $this->overallGrade = $grade['letter'];
        $this->gradeColor = $grade['color'];
    }

    public function calculateGrade($score)
    {
        if ($score >= 90) {
            return ['letter' => 'A+', 'color' => 'green'];
        }
        if ($score >= 85) {
            return ['letter' => 'A', 'color' => 'green'];
        }
        if ($score >= 80) {
            return ['letter' => 'B+', 'color' => 'blue'];
        }
        if ($score >= 75) {
            return ['letter' => 'B', 'color' => 'blue'];
        }
        if ($score >= 70) {
            return ['letter' => 'C+', 'color' => 'yellow'];
        }
        if ($score >= 65) {
            return ['letter' => 'C', 'color' => 'yellow'];
        }
        if ($score >= 60) {
            return ['letter' => 'D', 'color' => 'orange'];
        }

        return ['letter' => 'F', 'color' => 'red'];
    }

    public function updateScoreBreakdown()
    {
        $this->scoreBreakdown = [];

        foreach ($this->categories as $key => $category) {
            $percentage = $category['max_score'] > 0
                ? round(($category['score'] / $category['max_score']) * 100)
                : 0;

            $this->scoreBreakdown[$key] = [
                'name' => $category['name'],
                'score' => $category['score'],
                'max_score' => $category['max_score'],
                'percentage' => $percentage,
                'color' => $category['color'],
                'icon' => $category['icon'],
            ];
        }
    }

    public function generateFeedback()
    {
        $this->strengths = $this->identifyStrengths();
        $this->improvements = $this->identifyImprovements();
        $this->recommendations = $this->generateRecommendations();
    }

    public function identifyStrengths()
    {
        $strengths = [];

        foreach ($this->categories as $key => $category) {
            $percentage = $category['max_score'] > 0
                ? ($category['score'] / $category['max_score']) * 100
                : 0;

            if ($percentage >= 80) {
                $strengths[] = [
                    'category' => $category['name'],
                    'score' => $percentage,
                    'message' => $this->getStrengthMessage($key, $percentage),
                ];
            }
        }

        return $strengths;
    }

    public function identifyImprovements()
    {
        $improvements = [];

        foreach ($this->categories as $key => $category) {
            $percentage = $category['max_score'] > 0
                ? ($category['score'] / $category['max_score']) * 100
                : 0;

            if ($percentage < 70) {
                $improvements[] = [
                    'category' => $category['name'],
                    'score' => $percentage,
                    'message' => $this->getImprovementMessage($key, $percentage),
                ];
            }
        }

        return $improvements;
    }

    public function generateRecommendations()
    {
        $recommendations = [];

        // Based on analytics and scores
        if ($this->analytics['filler_words_count'] > 5) {
            $recommendations[] = [
                'type' => 'speaking',
                'priority' => 'high',
                'message' => 'Practice reducing filler words like "um", "uh", and "like" to sound more confident.',
            ];
        }

        if ($this->analytics['eye_contact_percentage'] < 60) {
            $recommendations[] = [
                'type' => 'presentation',
                'priority' => 'medium',
                'message' => 'Improve eye contact by looking directly at the camera more frequently.',
            ];
        }

        if ($this->analytics['speaking_pace'] > 180) {
            $recommendations[] = [
                'type' => 'delivery',
                'priority' => 'medium',
                'message' => 'Slow down your speaking pace to ensure clarity and comprehension.',
            ];
        }

        return $recommendations;
    }

    public function toggleDetailedView()
    {
        $this->showDetailedView = ! $this->showDetailedView;
    }

    public function selectCategory($category)
    {
        $this->selectedCategory = $this->selectedCategory === $category ? null : $category;
    }

    public function exportReport()
    {
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
    }

    public function downloadPDF()
    {
        // Implementation for PDF export
        $this->dispatch('download-pdf-report', [
            'score' => $this->currentScore,
            'breakdown' => $this->scoreBreakdown,
            'analytics' => $this->analytics,
            'feedback' => [
                'strengths' => $this->strengths,
                'improvements' => $this->improvements,
                'recommendations' => $this->recommendations,
            ],
        ]);

        $this->closeExportModal();
    }

    public function shareResults()
    {
        // Implementation for sharing results
        $this->dispatch('share-results', [
            'score' => $this->currentScore,
            'grade' => $this->overallGrade,
        ]);

        $this->closeExportModal();
    }

    // Helper methods for scoring simulation
    private function calculateTechnicalScore($responseData)
    {
        // Simulate technical knowledge scoring
        $keywords = ['algorithm', 'database', 'framework', 'optimization', 'security'];
        $response = strtolower($responseData['content'] ?? '');
        $keywordCount = 0;

        foreach ($keywords as $keyword) {
            if (strpos($response, $keyword) !== false) {
                $keywordCount++;
            }
        }

        return min(6, $keywordCount * 1.2);
    }

    private function calculateCommunicationScore($responseData)
    {
        // Simulate communication scoring based on response length and structure
        $content = $responseData['content'] ?? '';
        $wordCount = str_word_count($content);

        if ($wordCount < 20) {
            return 2;
        }
        if ($wordCount < 50) {
            return 3;
        }
        if ($wordCount < 100) {
            return 4;
        }
        if ($wordCount < 200) {
            return 5;
        }

        return 6;
    }

    private function calculateProblemSolvingScore($responseData)
    {
        // Simulate problem-solving scoring
        $problemSolvingWords = ['solution', 'approach', 'method', 'strategy', 'analyze'];
        $response = strtolower($responseData['content'] ?? '');
        $score = 0;

        foreach ($problemSolvingWords as $word) {
            if (strpos($response, $word) !== false) {
                $score += 1;
            }
        }

        return min(4, $score);
    }

    private function getStrengthMessage($category, $percentage)
    {
        $messages = [
            'technical_knowledge' => 'Excellent technical understanding and knowledge demonstration.',
            'communication' => 'Clear and effective communication throughout the interview.',
            'problem_solving' => 'Strong analytical and problem-solving approach.',
            'presentation' => 'Professional presentation and confident delivery.',
            'professionalism' => 'Maintained high level of professionalism throughout.',
        ];

        return $messages[$category] ?? 'Strong performance in this area.';
    }

    private function getImprovementMessage($category, $percentage)
    {
        $messages = [
            'technical_knowledge' => 'Focus on strengthening technical concepts and industry knowledge.',
            'communication' => 'Work on clarity and structure in your responses.',
            'problem_solving' => 'Practice breaking down complex problems systematically.',
            'presentation' => 'Improve confidence and delivery through practice.',
            'professionalism' => 'Focus on maintaining professional demeanor and etiquette.',
        ];

        return $messages[$category] ?? 'This area needs improvement.';
    }

    private function loadInterviewData()
    {
        // Load existing interview data if available
        // This would typically fetch from database
    }

    private function finalizeScoring($interviewData)
    {
        // Final scoring calculations
        $this->analytics['total_questions'] = count($interviewData['responses'] ?? []);
        $this->calculateOverallScore();
        $this->generateFeedback();
    }

    private function generateDetailedReport()
    {
        // Generate comprehensive report
        $this->detailedFeedback = [
            'overall_performance' => $this->generateOverallPerformanceText(),
            'category_analysis' => $this->generateCategoryAnalysis(),
            'improvement_plan' => $this->generateImprovementPlan(),
        ];
    }

    private function generateOverallPerformanceText()
    {
        $grade = $this->overallGrade;
        $score = $this->currentScore;

        if ($score >= 85) {
            return 'Outstanding performance! You demonstrated excellent skills across all categories.';
        } elseif ($score >= 75) {
            return 'Good performance with strong capabilities. Some areas for minor improvement.';
        } elseif ($score >= 65) {
            return 'Satisfactory performance. Focus on key improvement areas for better results.';
        } else {
            return 'Performance needs improvement. Consider additional practice and preparation.';
        }
    }

    private function generateCategoryAnalysis()
    {
        $analysis = [];

        foreach ($this->scoreBreakdown as $key => $category) {
            $analysis[$key] = [
                'performance' => $category['percentage'] >= 80 ? 'excellent' :
                               ($category['percentage'] >= 70 ? 'good' :
                               ($category['percentage'] >= 60 ? 'fair' : 'needs_improvement')),
                'details' => $this->getCategoryDetails($key, $category['percentage']),
            ];
        }

        return $analysis;
    }

    private function generateImprovementPlan()
    {
        $plan = [];

        foreach ($this->improvements as $improvement) {
            $plan[] = [
                'area' => $improvement['category'],
                'current_score' => $improvement['score'],
                'target_score' => min(100, $improvement['score'] + 20),
                'action_items' => $this->getActionItems($improvement['category']),
            ];
        }

        return $plan;
    }

    private function getCategoryDetails($category, $percentage)
    {
        // Return detailed analysis for each category
        return "Scored {$percentage}% in this category.";
    }

    private function getActionItems($category)
    {
        $actionItems = [
            'Technical Knowledge' => [
                'Review fundamental concepts',
                'Practice coding problems',
                'Study industry best practices',
            ],
            'Communication Skills' => [
                'Practice explaining complex topics simply',
                'Work on active listening',
                'Improve non-verbal communication',
            ],
            'Problem Solving' => [
                'Practice systematic problem breakdown',
                'Learn different problem-solving frameworks',
                'Work on time management during problem solving',
            ],
            'Presentation & Delivery' => [
                'Practice speaking clearly and confidently',
                'Work on eye contact and posture',
                'Reduce filler words',
            ],
            'Professionalism' => [
                'Research company culture',
                'Practice professional etiquette',
                'Prepare thoughtful questions',
            ],
        ];

        return $actionItems[$category] ?? ['Focus on improvement in this area'];
    }

    private function saveScoreHistory()
    {
        // Save score to history for trend analysis
        $this->scoreHistory[] = [
            'date' => now()->toDateString(),
            'score' => $this->currentScore,
            'grade' => $this->overallGrade,
        ];

        // Calculate improvement trend
        if (count($this->scoreHistory) > 1) {
            $previousScore = $this->scoreHistory[count($this->scoreHistory) - 2]['score'];
            $this->improvementTrend = $this->currentScore - $previousScore;
        }
    }

    public function render()
    {
        return view('livewire.scoring-system');
    }
}
