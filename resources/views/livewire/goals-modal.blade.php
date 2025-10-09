<x-modal-card
    title="Set Your Goals"
    description="Define your weekly objectives to stay on track with your job search"
    name="goals-modal"
    blur="md"
    max-width="2xl"
    wire:model="show"
>
    <!-- Goals Sections -->
    <div class="space-y-8">
        <!-- Job Applications per Week -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <x-icon name="briefcase" class="w-6 h-6 text-purple-600" />
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Job Applications per Week</h4>
                    <p class="text-gray-600 text-sm">How many jobs do you want to apply to each week?</p>
                </div>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    {{ $jobApplicationsPerWeek }}
                </div>
            </div>
            <div class="pl-16">
                <div class="flex items-center gap-4 mb-2">
                    <span class="text-sm text-gray-500">1</span>
                    <div class="flex-1 relative">
                        <input
                            type="range"
                            wire:model.live="jobApplicationsPerWeek"
                            min="1"
                            max="20"
                            class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-purple"
                            style="background: linear-gradient(to right, #8b5cf6 0%, #8b5cf6 {{ ($jobApplicationsPerWeek - 1) / 19 * 100 }}%, #e5e7eb {{ ($jobApplicationsPerWeek - 1) / 19 * 100 }}%, #e5e7eb 100%)"
                        />
                    </div>
                    <span class="text-sm text-gray-500">20</span>
                </div>
            </div>
        </div>

        <!-- Practice Interviews per Week -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <x-icon name="video-camera" class="w-6 h-6 text-orange-600" />
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Practice Interviews per Week</h4>
                    <p class="text-gray-600 text-sm">How many practice interviews do you want to do each week?</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-sm text-gray-600">Avg. Score 7.5/10</span>
                        <span class="text-sm text-green-600 font-medium flex items-center gap-1">
                            <x-icon name="check-circle" class="w-4 h-4" />
                            Interview Ready
                        </span>
                    </div>
                </div>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    {{ $practiceInterviewsPerWeek }}
                </div>
            </div>
            <div class="pl-16">
                <div class="flex items-center gap-4 mb-2">
                    <span class="text-sm text-gray-500">1</span>
                    <div class="flex-1 relative">
                        <input
                            type="range"
                            wire:model.live="practiceInterviewsPerWeek"
                            min="1"
                            max="10"
                            class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-purple"
                            style="background: linear-gradient(to right, #8b5cf6 0%, #8b5cf6 {{ ($practiceInterviewsPerWeek - 1) / 9 * 100 }}%, #e5e7eb {{ ($practiceInterviewsPerWeek - 1) / 9 * 100 }}%, #e5e7eb 100%)"
                        />
                    </div>
                    <span class="text-sm text-gray-500">10</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>No change</span>
                    <span class="flex items-center gap-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">3</span>
                        Companies
                    </span>
                </div>
            </div>
        </div>

        <!-- Score Improvement Target -->
        <div class="pb-6">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <x-icon name="chart-bar" class="w-6 h-6 text-green-600" />
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Score Improvement Target</h4>
                    <p class="text-gray-600 text-sm">How much do you want to improve your practice scores?</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        +{{ $scoreImprovementTarget }}%
                    </div>
                    <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All →</button>
                </div>
            </div>
            <div class="pl-16">
                <div class="flex items-center gap-4 mb-2">
                    <span class="text-sm text-gray-500">5%</span>
                    <div class="flex-1 relative">
                        <input
                            type="range"
                            wire:model.live="scoreImprovementTarget"
                            min="5"
                            max="50"
                            class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-purple"
                            style="background: linear-gradient(to right, #8b5cf6 0%, #8b5cf6 {{ ($scoreImprovementTarget - 5) / 45 * 100 }}%, #e5e7eb {{ ($scoreImprovementTarget - 5) / 45 * 100 }}%, #e5e7eb 100%)"
                        />
                    </div>
                    <span class="text-sm text-gray-500">50%</span>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <div class="flex justify-end gap-3">
            <x-button flat wire:click="close">Cancel</x-button>
            <x-button primary wire:click="saveGoals">Set Goals</x-button>
        </div>
    </x-slot>
</x-modal-card>
