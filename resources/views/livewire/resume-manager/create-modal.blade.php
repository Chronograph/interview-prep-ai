<x-modal-card title="Create Resume" name="createResumeModal" blur="md" max-width="5xl">
    <div x-data="{
        tab: 'basic',
        experiences: [],
        educations: [],
        projects: [],
        addExperience() {
            this.experiences.push({ company: '', position: '', start_date: '', end_date: '', description: '', current: false });
        },
        removeExperience(index) {
            this.experiences.splice(index, 1);
        },
        addEducation() {
            this.educations.push({ school: '', degree: '', field: '', start_date: '', end_date: '', gpa: '' });
        },
        removeEducation(index) {
            this.educations.splice(index, 1);
        },
        addProject() {
            this.projects.push({ name: '', description: '', tech_stack: '', url: '' });
        },
        removeProject(index) {
            this.projects.splice(index, 1);
        }
    }" x-init="
        if (experiences.length === 0) addExperience();
        if (educations.length === 0) addEducation();
        if (projects.length === 0) addProject();
    " class="space-y-4">

        <!-- Enhanced Tab Navigation -->
        <div class="border-b border-gray-200 bg-gray-50 -mx-6 -mt-2 px-6">
            <nav class="-mb-px flex space-x-4 overflow-x-auto">
                <button @click="tab = 'basic'" :class="tab === 'basic' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="flex items-center gap-2 whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Basic Info
                </button>
                <button @click="tab = 'experience'" :class="tab === 'experience' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="flex items-center gap-2 whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Experience
                </button>
                <button @click="tab = 'education'" :class="tab === 'education' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="flex items-center gap-2 whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                    Education
                </button>
                <button @click="tab = 'projects'" :class="tab === 'projects' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="flex items-center gap-2 whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Projects
                </button>
                <button @click="tab = 'additional'" :class="tab === 'additional' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="flex items-center gap-2 whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Additional
                </button>
            </nav>
        </div>

        <!-- Basic Info Tab -->
        <div x-show="tab === 'basic'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Resume Title" wire:model="form.title" placeholder="Senior Software Engineer Resume" corner-hint="Required" />
                <x-input label="Full Name" wire:model="form.full_name" placeholder="John Doe" />
                <x-input label="Professional Headline" wire:model="form.headline" placeholder="Senior Software Engineer | Full Stack Developer" hint="One-line tagline" class="md:col-span-2" />
                <x-input label="Email" wire:model="form.email" type="email" placeholder="[email protected]" />
                <x-input label="Phone" wire:model="form.phone" placeholder="+1 (555) 123-4567" />
                <x-input label="Location" wire:model="form.location" placeholder="San Francisco, CA" />
                <x-input label="LinkedIn URL" wire:model="form.linkedin_url" placeholder="https://linkedin.com/in/johndoe" />
                <x-input label="Portfolio URL" wire:model="form.portfolio_url" placeholder="https://johndoe.com" />
                <x-input label="GitHub URL" wire:model="form.github_url" placeholder="https://github.com/johndoe" />
            </div>

            <x-textarea label="Professional Summary" wire:model="form.summary" placeholder="Brief professional summary highlighting your key strengths..." rows="4" hint="2-3 sentences about your experience" />
            <x-textarea label="Career Objective" wire:model="form.objective" placeholder="What you're looking for in your next role..." rows="3" />
            <x-textarea label="Skills (comma-separated)" wire:model="form.skills" placeholder="JavaScript, Python, React, Node.js, AWS, Docker" rows="3" hint="Separate skills with commas" />

            <x-input label="Upload Resume File" type="file" wire:model="form.file" accept=".pdf,.doc,.docx,.txt" hint="PDF, DOC, DOCX - AI will parse content" />
        </div>

        <!-- Experience Tab with Repeater -->
        <div x-show="tab === 'experience'" class="space-y-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Work Experience
                </h3>
                <x-button xs primary icon="plus" @click="addExperience()" label="Add Position" />
            </div>

            <template x-for="(exp, index) in experiences" :key="index">
                <div class="p-4 bg-gray-50 rounded-lg border-2 border-gray-200 hover:border-blue-300 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Position <span x-text="index + 1"></span>
                        </span>
                        <x-button xs flat negative icon="trash" @click="removeExperience(index)" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                            <input type="text" x-model="exp.company" placeholder="TechCorp Inc." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                            <input type="text" x-model="exp.position" placeholder="Senior Software Engineer" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="month" x-model="exp.start_date" placeholder="2020-01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="month" x-model="exp.end_date" placeholder="2023-12" x-bind:disabled="exp.current" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100" />
                        </div>
                        <div class="md:col-span-2 flex items-center">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" x-model="exp.current" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                <span class="text-sm text-gray-700">I currently work here</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea x-model="exp.description" placeholder="Key responsibilities and achievements..." rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Education Tab with Repeater -->
        <div x-show="tab === 'education'" class="space-y-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    </svg>
                    Education
                </h3>
                <x-button xs primary icon="plus" @click="addEducation()" label="Add Education" />
            </div>

            <template x-for="(edu, index) in educations" :key="index">
                <div class="p-4 bg-gray-50 rounded-lg border-2 border-gray-200 hover:border-blue-300 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                            </svg>
                            Education <span x-text="index + 1"></span>
                        </span>
                        <x-button xs flat negative icon="trash" @click="removeEducation(index)" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">School/University</label>
                            <input type="text" x-model="edu.school" placeholder="Stanford University" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Degree</label>
                            <input type="text" x-model="edu.degree" placeholder="Master of Science" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field of Study</label>
                            <input type="text" x-model="edu.field" placeholder="Computer Science" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">GPA (Optional)</label>
                            <input type="text" x-model="edu.gpa" placeholder="3.8" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="month" x-model="edu.start_date" placeholder="2018-09" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="month" x-model="edu.end_date" placeholder="2020-05" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Projects Tab with Repeater -->
        <div x-show="tab === 'projects'" class="space-y-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Projects & Portfolio
                </h3>
                <x-button xs primary icon="plus" @click="addProject()" label="Add Project" />
            </div>

            <template x-for="(project, index) in projects" :key="index">
                <div class="p-4 bg-gray-50 rounded-lg border-2 border-gray-200 hover:border-blue-300 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            Project <span x-text="index + 1"></span>
                        </span>
                        <x-button xs flat negative icon="trash" @click="removeProject(index)" />
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                            <input type="text" x-model="project.name" placeholder="E-commerce Platform" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project URL (Optional)</label>
                            <input type="url" x-model="project.url" placeholder="https://github.com/..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea x-model="project.description" placeholder="Built a scalable e-commerce platform..." rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tech Stack</label>
                            <input type="text" x-model="project.tech_stack" placeholder="React, Node.js, PostgreSQL, AWS" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Additional Tab -->
        <div x-show="tab === 'additional'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-textarea label="Certifications (one per line)" wire:model="form.certifications" placeholder="AWS Certified Solutions Architect&#10;Google Cloud Professional" rows="4" />
                <x-textarea label="Languages (comma-separated)" wire:model="form.languages" placeholder="English (Native), Spanish (Fluent)" rows="4" />
                <x-textarea label="Awards & Honors (one per line)" wire:model="form.awards" placeholder="Employee of the Year 2023&#10;Best Innovation Award" rows="4" />
                <x-textarea label="Publications (one per line)" wire:model="form.publications" placeholder="Published paper on ML&#10;Tech blog with 10K+ readers" rows="4" />
                <x-textarea label="Volunteer Work (one per line)" wire:model="form.volunteer_work" placeholder="Code mentor at bootcamp&#10;Open source contributor" rows="4" />
                <x-textarea label="Interests (comma-separated)" wire:model="form.interests" placeholder="Photography, Hiking, Chess" rows="4" />
            </div>
        </div>
    </div>

    <x-slot name="footer" class="flex justify-between items-center gap-x-4">
        <div class="text-sm text-gray-600">
            <span class="font-medium">Tip:</span> Fill all sections for 100% optimization
        </div>
        <div class="flex gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button primary icon="check" label="Create Resume" wire:click="createResume" spinner />
        </div>
    </x-slot>
</x-modal-card>
