<div class="space-y-6">
    <!-- Practice Session Options -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Generic Role-Specific Interviews -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow cursor-pointer relative">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Practice Generic Role-Specific Interviews</h4>
                    <p class="text-gray-600 text-sm">Practice common interview questions for Product Manager and Product Design Manager roles</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Intermediate
                </span>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    Start Session
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </button>
                <x-icon name="play" class="w-5 h-5 text-gray-400" />
            </div>
        </div>

        <!-- Refine Elevator Pitch -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow cursor-pointer relative">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Refine Elevator Pitch</h4>
                    <p class="text-gray-600 text-sm">Perfect your 30-60 second personal introduction and value proposition</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Beginner
                </span>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    Start Session
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </button>
                <x-icon name="chat-bubble-left-ellipsis" class="w-5 h-5 text-gray-400" />
            </div>
        </div>

        <!-- Add a New Job Interview -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow cursor-pointer relative">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Add a New Job Interview</h4>
                    <p class="text-gray-600 text-sm">Schedule practice for a specific company and role you're interviewing for</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    Advanced
                </span>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    Start Session
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </button>
                <x-icon name="plus" class="w-5 h-5 text-gray-400" />
            </div>
        </div>

        <!-- Level Up Skills -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow cursor-pointer relative">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Level Up Skills</h4>
                    <p class="text-gray-600 text-sm">Focus on specific competencies based on your performance analytics</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Intermediate
                </span>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    Start Session
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </button>
                <x-icon name="chart-bar-square" class="w-5 h-5 text-gray-400" />
            </div>
        </div>
    </div>

    <!-- Practice Stats -->
    <div class="bg-gray-50 rounded-xl p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Your Practice Stats</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-600">Sessions completed</p>
                <p class="text-2xl font-bold text-gray-900">24</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Avg score</p>
                <p class="text-2xl font-bold text-gray-900">7.2</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Improvement</p>
                <p class="text-2xl font-bold text-gray-900">18%</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Overall Rating</p>
                <p class="text-2xl font-bold text-gray-900">8.1/10</p>
                <p class="text-sm text-green-600 font-medium">Ready for interviews</p>
            </div>
        </div>
    </div>

    <!-- Pro Tip -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <x-icon name="light-bulb" class="w-5 h-5 text-yellow-500 mt-0.5" />
            <div>
                <p class="text-sm text-blue-800">
                    <strong>Pro tip:</strong> Start with generic role-specific interviews if you're new, or jump into company-specific practice if you have an upcoming interview.
                </p>
            </div>
        </div>
    </div>
</div>
