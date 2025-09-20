<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Interview;

use App\Models\InterviewQuestion;
use Illuminate\Support\Facades\Auth;

class InterviewInterface extends Component
{
    public $interview;
    public $jobPosting;
    public $resume;
    public $currentQuestion;
    public $response = '';
    public $isSubmitting = false;
    public $showCompleteModal = false;
    public $recordingTime = 0;
    public $isRecording = false;
    public $recordedChunks = [];

    public function mount($interview = null)
    {
        if ($interview) {
            $this->interview = $interview;
            $this->jobPosting = $interview->jobPosting;
            $this->resume = $interview->resume;
            $this->loadCurrentQuestion();
        }
    }

    public function loadCurrentQuestion()
    {
        if ($this->interview) {
            // Get the next unanswered question or the first question
            $this->currentQuestion = $this->interview->questions()
                ->whereDoesntHave('responses')
                ->orderBy('order')
                ->first();
                
            if (!$this->currentQuestion) {
                // If no unanswered questions, get the last question
                $this->currentQuestion = $this->interview->questions()
                    ->orderBy('order', 'desc')
                    ->first();
            }
        }
    }

    public function getFormattedTimeProperty()
    {
        $minutes = floor($this->recordingTime / 60);
        $seconds = $this->recordingTime % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getCanSubmitResponseProperty()
    {
        return !empty(trim($this->response)) && !$this->isSubmitting;
    }

    public function startRecording()
    {
        $this->isRecording = true;
        $this->recordingTime = 0;
        $this->recordedChunks = [];
        
        // Emit event to frontend to start actual recording
        $this->dispatch('start-recording');
    }

    public function stopRecording($chunks = [])
    {
        $this->isRecording = false;
        $this->recordedChunks = $chunks;
        
        // Emit event to frontend to stop recording
        $this->dispatch('stop-recording');
    }

    public function submitResponse()
    {
        $this->validate([
            'response' => 'required|string|min:10'
        ]);

        $this->isSubmitting = true;

        try {
            // Update the current question with the user's response
            $this->currentQuestion->update([
                'user_response' => $this->response,
                'response_time_seconds' => $this->recordingTime,
                'answered_at' => now()
            ]);

            // Clear response
            $this->response = '';
            $this->recordingTime = 0;
            $this->recordedChunks = [];

            $this->dispatch('response-submitted');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit response. Please try again.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function nextQuestion()
    {
        $this->loadCurrentQuestion();
    }

    public function completeInterview()
    {
        $this->showCompleteModal = true;
    }

    public function confirmComplete()
    {
        try {
            $this->interview->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            $this->dispatch('interview-completed');
            $this->dispatch('close-interview');
            
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Failed to complete interview. Please try again.']);
        }
    }

    public function closeModal()
    {
        $this->showCompleteModal = false;
    }

    public function closeInterview()
    {
        $this->dispatch('close-interview');
    }

    #[On('recording-time-update')]
    public function updateRecordingTime($time)
    {
        $this->recordingTime = $time;
    }

    public function render()
    {
        return view('livewire.interview-interface');
    }
}
