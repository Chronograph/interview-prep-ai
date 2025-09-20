<script setup>
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import Modal from '@/Components/Modal.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
    resumes: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['resume-created', 'resume-updated', 'resume-deleted', 'primary-changed'])

// Component state
const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingResume = ref(null)
const isUploading = ref(false)
const uploadProgress = ref(0)

// Form for creating/editing resumes
const form = useForm({
    title: '',
    summary: '',
    skills: '',
    experience: '',
    education: '',
    certifications: '',
    file: null
})

// File upload handling
const fileInput = ref(null)
const selectedFile = ref(null)

const handleFileSelect = (event) => {
    const file = event.target.files[0]
    if (file) {
        selectedFile.value = file
        form.file = file
        
        // Auto-parse if it's a supported file type
        if (file.type === 'application/pdf' || file.type.includes('text') || file.type.includes('document')) {
            parseResume()
        }
    }
}

const parseResume = async () => {
    if (!selectedFile.value) return
    
    isUploading.value = true
    uploadProgress.value = 0
    
    try {
        const formData = new FormData()
        formData.append('file', selectedFile.value)
        
        const response = await fetch('/api/resumes/parse', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        
        if (response.ok) {
            const data = await response.json()
            
            // Auto-fill form with parsed data
            form.title = data.title || selectedFile.value.name.replace(/\.[^/.]+$/, '')
            form.summary = data.summary || ''
            form.skills = Array.isArray(data.skills) ? data.skills.join(', ') : (data.skills || '')
            form.experience = data.experience || ''
            form.education = data.education || ''
            form.certifications = Array.isArray(data.certifications) ? data.certifications.join(', ') : (data.certifications || '')
        }
    } catch (error) {
        console.error('Error parsing resume:', error)
        alert('Failed to parse resume. Please fill in the details manually.')
    } finally {
        isUploading.value = false
        uploadProgress.value = 0
    }
}

// CRUD operations
const createResume = () => {
    form.post('/api/resumes', {
        onSuccess: () => {
            showCreateModal.value = false
            form.reset()
            selectedFile.value = null
            emit('resume-created')
        },
        onError: (errors) => {
            console.error('Validation errors:', errors)
        }
    })
}

const editResume = (resume) => {
    editingResume.value = resume
    form.title = resume.title
    form.summary = resume.summary || ''
    form.skills = resume.skills || ''
    form.experience = resume.experience || ''
    form.education = resume.education || ''
    form.certifications = resume.certifications || ''
    showEditModal.value = true
}

const updateResume = () => {
    form.put(`/api/resumes/${editingResume.value.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            form.reset()
            editingResume.value = null
            emit('resume-updated')
        }
    })
}

const deleteResume = (resume) => {
    if (confirm('Are you sure you want to delete this resume?')) {
        router.delete(`/api/resumes/${resume.id}`, {
            onSuccess: () => {
                emit('resume-deleted')
            }
        })
    }
}

const setPrimaryResume = (resume) => {
    router.post(`/api/resumes/${resume.id}/set-primary`, {}, {
        onSuccess: () => {
            emit('primary-changed')
        }
    })
}

const downloadFile = (resume) => {
    window.open(`/api/resumes/${resume.id}/download`, '_blank')
}

// Modal controls
const openCreateModal = () => {
    form.reset()
    selectedFile.value = null
    showCreateModal.value = true
}

const closeCreateModal = () => {
    showCreateModal.value = false
    form.reset()
    selectedFile.value = null
}

const closeEditModal = () => {
    showEditModal.value = false
    form.reset()
    editingResume.value = null
}

// Computed properties
const primaryResume = computed(() => {
    return props.resumes.find(resume => resume.is_primary)
})
</script>

<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Resumes</h2>
            <PrimaryButton @click="openCreateModal">
                Upload Resume
            </PrimaryButton>
        </div>

        <!-- Primary Resume Highlight -->
        <div v-if="primaryResume" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                <span class="text-blue-800 font-semibold">Primary Resume</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900">{{ primaryResume.title }}</h3>
            <p v-if="primaryResume.summary" class="text-gray-700 mt-1">{{ primaryResume.summary }}</p>
        </div>

        <!-- Resumes List -->
        <div v-if="resumes.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div 
                v-for="resume in resumes" 
                :key="resume.id"
                class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow"
                :class="{ 'ring-2 ring-blue-500': resume.is_primary }"
            >
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ resume.title }}</h3>
                            <span v-if="resume.is_primary" class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                Primary
                            </span>
                        </div>
                        <p class="text-gray-500 text-sm">
                            Updated {{ new Date(resume.updated_at).toLocaleDateString() }}
                        </p>
                    </div>
                    <div class="flex gap-1 ml-4">
                        <button 
                            v-if="!resume.is_primary"
                            @click="setPrimaryResume(resume)"
                            class="p-1 text-gray-400 hover:text-yellow-600 transition-colors"
                            title="Set as Primary"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </button>
                        <button 
                            @click="editResume(resume)"
                            class="p-1 text-gray-400 hover:text-blue-600 transition-colors"
                            title="Edit"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button 
                            @click="deleteResume(resume)"
                            class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                            title="Delete"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div v-if="resume.summary" class="mb-4">
                    <p class="text-gray-700 text-sm line-clamp-3">{{ resume.summary }}</p>
                </div>
                
                <div v-if="resume.skills" class="mb-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Key Skills</h4>
                    <div class="flex flex-wrap gap-1">
                        <span 
                            v-for="skill in resume.skills.split(',').slice(0, 5)" 
                            :key="skill.trim()"
                            class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"
                        >
                            {{ skill.trim() }}
                        </span>
                        <span 
                            v-if="resume.skills.split(',').length > 5"
                            class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded"
                        >
                            +{{ resume.skills.split(',').length - 5 }} more
                        </span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">
                        Created {{ new Date(resume.created_at).toLocaleDateString() }}
                    </span>
                    <button 
                        v-if="resume.file_path"
                        @click="downloadFile(resume)"
                        class="text-blue-600 hover:text-blue-800 transition-colors"
                    >
                        Download
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Resumes</h3>
            <p class="text-gray-600 mb-4">Upload your first resume to get started with interview preparation.</p>
            <PrimaryButton @click="openCreateModal">
                Upload Resume
            </PrimaryButton>
        </div>

        <!-- Create Modal -->
        <Modal :show="showCreateModal" @close="closeCreateModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Resume</h3>
                
                <!-- File Upload -->
                <div class="mb-6">
                    <InputLabel for="file" value="Resume File" />
                    <input 
                        ref="fileInput"
                        type="file" 
                        @change="handleFileSelect"
                        accept=".pdf,.doc,.docx,.txt"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        required
                    />
                    <p class="mt-1 text-sm text-gray-500">PDF, DOC, DOCX, or TXT files. AI will auto-parse the content.</p>
                    
                    <div v-if="isUploading" class="mt-2">
                        <div class="bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="{ width: uploadProgress + '%' }"></div>
                        </div>
                        <p class="text-sm text-blue-600 mt-1">Parsing resume...</p>
                    </div>
                </div>
                
                <form @submit.prevent="createResume" class="space-y-4">
                    <div>
                        <InputLabel for="title" value="Resume Title" />
                        <TextInput 
                            id="title" 
                            v-model="form.title" 
                            type="text" 
                            class="mt-1 block w-full" 
                            placeholder="e.g., Senior Software Engineer Resume"
                            required 
                        />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>
                    
                    <div>
                        <InputLabel for="summary" value="Professional Summary" />
                        <textarea 
                            id="summary" 
                            v-model="form.summary" 
                            rows="3" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="Brief professional summary..."
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.summary" />
                    </div>
                    
                    <div>
                        <InputLabel for="skills" value="Skills" />
                        <textarea 
                            id="skills" 
                            v-model="form.skills" 
                            rows="2" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="JavaScript, Python, React, Node.js, etc."
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.skills" />
                    </div>
                    
                    <div>
                        <InputLabel for="experience" value="Work Experience" />
                        <textarea 
                            id="experience" 
                            v-model="form.experience" 
                            rows="4" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="Previous work experience..."
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.experience" />
                    </div>
                    
                    <div>
                        <InputLabel for="education" value="Education" />
                        <textarea 
                            id="education" 
                            v-model="form.education" 
                            rows="2" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="Educational background..."
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.education" />
                    </div>
                    
                    <div>
                        <InputLabel for="certifications" value="Certifications" />
                        <textarea 
                            id="certifications" 
                            v-model="form.certifications" 
                            rows="2" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="Professional certifications..."
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.certifications" />
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <SecondaryButton @click="closeCreateModal">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Uploading...' : 'Upload Resume' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit Modal -->
        <Modal :show="showEditModal" @close="closeEditModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Resume</h3>
                
                <form @submit.prevent="updateResume" class="space-y-4">
                    <div>
                        <InputLabel for="edit_title" value="Resume Title" />
                        <TextInput 
                            id="edit_title" 
                            v-model="form.title" 
                            type="text" 
                            class="mt-1 block w-full" 
                            required 
                        />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>
                    
                    <div>
                        <InputLabel for="edit_summary" value="Professional Summary" />
                        <textarea 
                            id="edit_summary" 
                            v-model="form.summary" 
                            rows="3" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.summary" />
                    </div>
                    
                    <div>
                        <InputLabel for="edit_skills" value="Skills" />
                        <textarea 
                            id="edit_skills" 
                            v-model="form.skills" 
                            rows="2" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.skills" />
                    </div>
                    
                    <div>
                        <InputLabel for="edit_experience" value="Work Experience" />
                        <textarea 
                            id="edit_experience" 
                            v-model="form.experience" 
                            rows="4" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.experience" />
                    </div>
                    
                    <div>
                        <InputLabel for="edit_education" value="Education" />
                        <textarea 
                            id="edit_education" 
                            v-model="form.education" 
                            rows="2" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.education" />
                    </div>
                    
                    <div>
                        <InputLabel for="edit_certifications" value="Certifications" />
                        <textarea 
                            id="edit_certifications" 
                            v-model="form.certifications" 
                            rows="2" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.certifications" />
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <SecondaryButton @click="closeEditModal">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Updating...' : 'Update Resume' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>