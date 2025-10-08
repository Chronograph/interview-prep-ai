<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Video Interview Practice') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Practice your interview skills with AI-powered feedback.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Main Content Layout -->
            <div class="flex gap-6 h-[70vh]">
                <!-- Left Column - Interview Progress and Question -->
                <div class="w-1/2 space-y-6">
                    <!-- Interview Progress Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Interview Progress</h3>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="session-timer">0m elapsed</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                            <div id="progress-bar" class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>

                        <div class="flex justify-between text-sm text-gray-600 mb-4">
                            <span id="progress-text">0 of 5 completed</span>
                            <span>0%</span>
                        </div>

                        <!-- Question Navigation -->
                        <div class="flex gap-2">
                            <button class="w-8 h-8 border-2 border-blue-500 bg-blue-50 rounded-full text-sm font-medium text-blue-600" data-question="1" id="q-btn-1">1</button>
                            <button class="w-8 h-8 border border-gray-300 rounded-full text-sm font-medium text-gray-500 hover:border-gray-400" data-question="2" id="q-btn-2">2</button>
                            <button class="w-8 h-8 border border-gray-300 rounded-full text-sm font-medium text-gray-500 hover:border-gray-400" data-question="3" id="q-btn-3">3</button>
                            <button class="w-8 h-8 border border-gray-300 rounded-full text-sm font-medium text-gray-500 hover:border-gray-400" data-question="4" id="q-btn-4">4</button>
                            <button class="w-8 h-8 border border-gray-300 rounded-full text-sm font-medium text-gray-500 hover:border-gray-400" data-question="5" id="q-btn-5">5</button>
                        </div>
                    </div>

                    <!-- Current Question Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex-grow">
                        <h3 class="text-lg font-bold text-gray-800 mb-4" id="question-count-text">Question 1 of 5</h3>

                        <div class="flex gap-2 mb-4">
                            <span class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs font-medium text-gray-600">General</span>
                            <span class="px-2 py-1 bg-green-100 border border-green-300 rounded text-xs font-medium text-green-600">Easy</span>
                        </div>

                        <p id="current-question" class="text-gray-800 text-lg leading-relaxed mb-4">
                            Tell me about yourself and why you're interested in this position.
                        </p>

                        <div class="text-sm text-gray-600 mb-4" id="time-limit">Recommended time: 2 minutes</div>

                        <!-- Previous Button -->
                        <button class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-800" id="prev-btn">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Previous
                        </button>
                    </div>
                </div>

                <!-- Right Column - Video Player/Recorder -->
                <div class="w-1/2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 h-full flex flex-col">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Video Player/Recorder</h3>

                        <!-- Video Area -->
                        <div class="flex-grow bg-black rounded-xl mb-4 flex items-center justify-center relative">
                            <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white text-sm font-mono px-2 py-1 rounded" id="recording-timer">0:00 / 2:00</div>
                            <div id="video-placeholder" class="text-white text-4xl">
                                <!-- Video preview would go here -->
                            </div>
                        </div>

                        <!-- Recording Progress -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                            <div id="recording-progress" class="bg-red-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>

                        <!-- Start Recording Button -->
                        <button
                            id="start-recording-btn"
                            class="m-auto flex items-center justify-center gap-3 bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-6 rounded-xl transition-colors duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 002 2v8a2 2 0 002-2z"></path>
                            </svg>
                            Start Recording
                        </button>

                        <!-- Response Input (Hidden by default) -->
                        <div id="response-section" class="mt-4 hidden">
                            <label for="user-response" class="block text-sm font-medium text-gray-700 mb-2">Your Response</label>
                            <textarea
                                id="user-response"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Type your response here..."
                            ></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Controls -->
            <div class="mt-6 flex justify-between items-center">
                <a
                    href="{{ route('practice.mock-interviews') }}"
                    class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 font-medium"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    ← Back to Practice
                </a>

                <div class="flex gap-3">
                    <button
                        id="submit-response-btn"
                        class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-xl transition-colors"
                        hidden
                    >
                        Submit Response
                    </button>

                    <button
                        id="end-session-btn"
                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-xl transition-colors"
                    >
                        End Session
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Data -->
    <script id="session-data" type="application/json">
        {
            "id": {{ $session->id ?? 1 }},
            "type": "{{ $session->session_type ?? 'behavioral' }}",
            "focusArea": "{{ $session->focus_area ?? 'general' }}",
            "difficulty": "{{ $session->difficulty ?? 'medium' }}"
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get session data from JSON script tag
            var sessionDataElement = document.getElementById('session-data');
            var sessionData = JSON.parse(sessionDataElement.textContent);
            var sessionId = sessionData.id;

            var currentQuestionNumber = 0;
            var totalQuestions = 5;
            var sessionStartTime = new Date();
            var timerInterval;
            var isRecording = false;
            var recordingStartTime = 0;
            var recordingTimer = null;

            // Start session timer
            function startTimer() {
                timerInterval = setInterval(function() {
                    var elapsed = Math.floor((new Date() - sessionStartTime) / 1000);
                    var minutes = Math.floor(elapsed / 60);
                    document.getElementById('session-timer').textContent = minutes + 'm elapsed';
                }, 1000);
            }

            // Update progress
            function updateProgress() {
                var progress = (currentQuestionNumber / totalQuestions) * 100;
                document.getElementById('progress-bar').style.width = progress + '%';
                document.getElementById('progress-text').textContent = currentQuestionNumber + ' of ' + totalQuestions + ' completed';
                document.getElementById('question-count-text').textContent = 'Question ' + (currentQuestionNumber + 1) + ' of ' + totalQuestions;

                // Update question navigation
                for (var i = 1; i <= totalQuestions; i++) {
                    var btn = document.getElementById('q-btn-' + i);
                    if (i <= currentQuestionNumber + 1) {
                        btn.className = 'w-8 h-8 border-2 border-blue-500 bg-blue-50 rounded-full text-sm font-medium text-blue-600';
                    } else {
                        btn.className = 'w-8 h-8 border border-gray-300 rounded-full text-sm font-medium text-gray-500 hover:border-gray-400';
                    }
                }
            }

            // Recording functions
            function startRecording() {
                isRecording = true;
                recordingStartTime = Date.now();
                document.getElementById('start-recording-btn').textContent = 'Stop Recording';
                document.getElementById('start-recording-btn').className = 'm-auto flex items-center justify-center gap-3 bg-red-800 hover:bg-red-900 text-white font-medium py-3 px-6 rounded-xl transition-colors duration-200';

                // Start recording timer
                recordingTimer = setInterval(function() {
                    var elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
                    var minutes = Math.floor(elapsed / 60);
                    var seconds = elapsed % 60;
                    var timeStr = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                    document.getElementById('recording-timer').textContent = timeStr + ' / 2:00';

                    // Update progress bar based on 2-minute limit
                    var progress = Math.min((elapsed / 120) * 100, 100);
                    document.getElementById('recording-progress').style.width = progress + '%';
                }, 100);
            }

            function stopRecording() {
                isRecording = false;
                clearInterval(recordingTimer);
                document.getElementById('start-recording-btn').textContent = 'Start Recording';
                document.getElementById('start-recording-btn').className = 'm-auto flex items-center justify-center gap-3 bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-6 rounded-xl transition-colors duration-200';

                // Show response section
                document.getElementById('response-section').classList.remove('hidden');
                document.getElementById('submit-response-btn').hidden = false;
            }

            // Get next question
            function getNextQuestion() {
                fetch('/interview-sessions/' + sessionId + '/next-question', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        document.getElementById('current-question').textContent = data.question;
                        document.getElementById('question-count-text').textContent = 'Question ' + (data.question_number || 1) + ' of ' + (data.total_questions || totalQuestions);

                        currentQuestionNumber = (data.question_number || 1) - 1;
                        totalQuestions = data.total_questions || totalQuestions;
                        updateProgress();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to get next question'));
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    alert('Failed to get next question. Please try again.');
                });
            }

            // Submit response
            function submitResponse() {
                var response = document.getElementById('user-response').value.trim();

                if (!response) {
                    alert('Please provide a response before submitting.');
                    return;
                }

                fetch('/interview-sessions/' + sessionId + '/submit-answer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ answer: response })
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        currentQuestionNumber++;
                        updateProgress();

                        // Clear response and reset section
                        document.getElementById('user-response').value = '';
                        document.getElementById('response-section').classList.add('hidden');
                        document.getElementById('submit-response-btn').hidden = true;

                        // Handle different states based on question completion
                        if (currentQuestionNumber < totalQuestions) {
                            // Get next question automatically
                            getNextQuestion();
                        } else {
                            // Session completed
                            document.getElementById('current-question').textContent =
                                'Congratulations! You have completed all questions in this mock interview session.';
                        }
                    } else {
                        alert('Error: ' + (data.message || 'Failed to submit response'));
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    alert('Failed to submit response. Please try again.');
                });
            }

            // End session
            function endSession() {
                if (!confirm('Are you sure you want to end this interview session?')) {
                    return;
                }

                fetch('/interview-sessions/' + sessionId + '/end', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        clearInterval(timerInterval);
                        window.location.href = '/interview-sessions/' + sessionId;
                    } else {
                        alert('Error: ' + (data.message || 'Failed to end session'));
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    alert('Failed to end session. Please try again.');
                });
            }

            // Question navigation buttons
            for (var i = 1; i <= totalQuestions; i++) {
                (function(qNum) {
                    document.getElementById('q-btn-' + qNum).addEventListener('click', function() {
                        if (qNum <= currentQuestionNumber + 1) {
                            // Navigate to question
                            currentQuestionNumber = qNum - 1;
                            updateProgress();
                            getNextQuestion();
                        }
                    });
                })(i);
            }

            // Initialize
            startTimer();
            updateProgress();

            // Event listeners
            document.getElementById('start-recording-btn').addEventListener('click', function() {
                if (isRecording) {
                    stopRecording();
                } else {
                    startRecording();
                }
            });
            document.getElementById('submit-response-btn').addEventListener('click', submitResponse);
            document.getElementById('end-session-btn').addEventListener('click', endSession);

            // Allow Ctrl+Enter to submit response
            document.getElementById('user-response').addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.ctrlKey) {
                    submitResponse();
                }
            });
        });
    </script>
</x-app-layout>
