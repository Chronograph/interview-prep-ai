<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    jobPosting: {
        type: Object,
        default: null
    },
    resume: {
        type: Object,
        default: null
    },
    currentQuestion: {
        type: Object,
        default: null
    },
    isRecording: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['start-recording', 'stop-recording', 'submit-response', 'next-question', 'complete-interview'])

// Component state
const response = ref('')
const isSubmitting = ref(false)
const showCompleteModal = ref(false)
const recordingTime = ref(0)
const recordingInterval = ref(null)
const mediaRecorder = ref(null)
const recordedChunks = ref([])
const stream = ref(null)

// Computed properties
const formattedTime = computed(() => {
    const minutes = Math.floor(recordingTime.value / 60)
    const seconds = recordingTime.value % 60
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
})

const canSubmitResponse = computed(() => {
    return response.value.trim().length > 0 && !isSubmitting.value
})

// Recording functions
const startRecording = async () => {
    try {
        stream.value = await navigator.mediaDevices.getUserMedia({ 
            video: true, 
            audio: true 
        })
        
        mediaRecorder.value = new MediaRecorder(stream.value)
        recordedChunks.value = []
        
        mediaRecorder.value.ondataavailable = (event) => {
            if (event.data.size > 0) {
                recordedChunks.value.push(event.data)
            }
        }
        
        mediaRecorder.value.start()
        recordingTime.value = 0
        
        recordingInterval.value = setInterval(() => {
            recordingTime.value++
        }, 1000)
        
        emit('start-recording')
    } catch (error) {
        console.error('Error starting recording:', error)
        alert('Unable to access camera/microphone. Please check permissions.')
    }
}

const stopRecording = () => {
    if (mediaRecorder.value && mediaRecorder.value.state === 'recording') {
        mediaRecorder.value.stop()
        
        if (stream.value) {
            stream.value.getTracks().forEach(track => track.stop())
        }
        
        if (recordingInterval.value) {
            clearInterval(recordingInterval.value)
        }
        
        emit('stop-recording', recordedChunks.value)
    }
}

// Response submission
const submitResponse = async () => {
    if (!canSubmitResponse.value) return
    
    isSubmitting.value = true
    
    try {
        await router.post(`/api/interviews/${props.interview.id}/responses`, {
            question_id: props.currentQuestion.id,
            response: response.value,
            recording_data: recordedChunks.value.length > 0 ? recordedChunks.value : null
        })
        
        response.value = ''
        emit('submit-response')
    } catch (error) {
        console.error('Error submitting response:', error)
        alert('Failed to submit response. Please try again.')
    } finally {
        isSubmitting.value = false
    }
}

// Navigation functions
const nextQuestion = () => {
    emit('next-question')
}

const completeInterview = () => {
    showCompleteModal.value = true
}

const confirmComplete = async () => {
    try {
        await router.post(`/api/interviews/${props.interview.id}/complete`)
        emit('complete-interview')
    } catch (error) {
        console.error('Error completing interview:', error)
        alert('Failed to complete interview. Please try again.')
    }
}

// Cleanup on unmount
onUnmounted(() => {
    if (recordingInterval.value) {
        clearInterval(recordingInterval.value)
    }
    if (stream.value) {
        stream.value.getTracks().forEach(track => track.stop())
    }
})
</script>

<template>
    <div class="max-w-4xl mx-auto p-6">
        <!-- Interview Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ jobPosting?.title || 'Interview Session' }}</h1>
                    <p class="text-gray-600 mt-1">{{ jobPosting?.company || 'General Interview' }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="font-semibold text-blue-600 capitalize">{{ interview.status }}</div>
                </div>
            </div>
        </div>

        <!-- Current Question -->
        <div v-if="currentQuestion" class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Current Question</h2>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    Question {{ currentQuestion.order || 1 }}
                </span>
            </div>
            
            <div class="prose max-w-none mb-6">
                <p class="text-gray-800 text-lg leading-relaxed">{{ currentQuestion.question }}</p>
            </div>

            <!-- Recording Controls -->
            <div class="flex items-center gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <div v-if="isRecording" class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                    <div v-else class="w-3 h-3 bg-gray-400 rounded-full"></div>
                    <span class="text-sm font-medium text-gray-700">
                        {{ isRecording ? 'Recording' : 'Not Recording' }}
                    </span>
                </div>
                
                <div v-if="isRecording" class="text-sm font-mono text-gray-600">
                    {{ formattedTime }}
                </div>
                
                <div class="flex gap-2 ml-auto">
                    <SecondaryButton 
                        v-if="!isRecording" 
                        @click="startRecording"
                        class="text-sm"
                    >
                        Start Recording
                    </SecondaryButton>
                    <SecondaryButton 
                        v-else 
                        @click="stopRecording"
                        class="text-sm bg-red-100 text-red-700 hover:bg-red-200"
                    >
                        Stop Recording
                    </SecondaryButton>
                </div>
            </div>

            <!-- Response Input -->
            <div class="mb-6">
                <label for="response" class="block text-sm font-medium text-gray-700 mb-2">
                    Your Response
                </label>
                <textarea
                    id="response"
                    v-model="response"
                    rows="6"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Type your response here..."
                ></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <SecondaryButton @click="completeInterview">
                    Complete Interview
                </SecondaryButton>
                
                <div class="flex gap-3">
                    <PrimaryButton 
                        @click="submitResponse"
                        :disabled="!canSubmitResponse"
                        :class="{ 'opacity-50 cursor-not-allowed': !canSubmitResponse }"
                    >
                        {{ isSubmitting ? 'Submitting...' : 'Submit Response' }}
                    </PrimaryButton>
                    
                    <SecondaryButton @click="nextQuestion">
                        Next Question
                    </SecondaryButton>
                </div>
            </div>
        </div>

        <!-- No Questions State -->
        <div v-else class="bg-white rounded-lg shadow-sm border p-8 text-center">
            <div class="text-gray-500 mb-4">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Questions Available</h3>
            <p class="text-gray-600">This interview doesn't have any questions yet.</p>
        </div>

        <!-- Complete Interview Modal -->
        <Modal :show="showCompleteModal" @close="showCompleteModal = false">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Complete Interview</h3>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to complete this interview? You won't be able to add more responses after this.
                </p>
                <div class="flex justify-end gap-3">
                    <SecondaryButton @click="showCompleteModal = false">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton @click="confirmComplete">
                        Complete Interview
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}
</style>