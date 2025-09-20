<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
          <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
            Master Your
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
              Interview Skills
            </span>
          </h1>
          <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
            Prepare for your dream job with AI-powered interview practice, personalized feedback, and comprehensive analytics.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              :href="route('register')"
              class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl"
            >
              Start Practicing Free
            </Link>
            <Link
              :href="route('login')"
              class="bg-white text-gray-700 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-lg border border-gray-200"
            >
              Sign In
            </Link>
          </div>
        </div>
      </div>
      
      <!-- Floating Elements -->
      <div class="absolute top-20 left-10 w-20 h-20 bg-blue-200 rounded-full opacity-20 animate-pulse"></div>
      <div class="absolute top-40 right-20 w-16 h-16 bg-purple-200 rounded-full opacity-20 animate-pulse delay-1000"></div>
      <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-indigo-200 rounded-full opacity-20 animate-pulse delay-2000"></div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Interview Prep AI?</h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Our AI-powered platform provides everything you need to ace your next interview.
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="feature in features" :key="feature.title" class="bg-gradient-to-br from-gray-50 to-gray-100 p-8 rounded-xl hover:shadow-lg transition-all duration-200">
            <div class="text-4xl mb-4">{{ feature.icon }}</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ feature.title }}</h3>
            <p class="text-gray-600">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-gradient-to-r from-blue-50 to-indigo-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Get started in minutes with our simple 3-step process.
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
              1
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Upload Your Resume</h3>
            <p class="text-gray-600">Upload your resume and job postings to get personalized interview questions.</p>
          </div>
          
          <div class="text-center">
            <div class="bg-purple-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
              2
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Practice Interviews</h3>
            <p class="text-gray-600">Practice with AI-generated questions tailored to your experience and target role.</p>
          </div>
          
          <div class="text-center">
            <div class="bg-indigo-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
              3
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Get Feedback</h3>
            <p class="text-gray-600">Receive detailed feedback and analytics to improve your interview performance.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Users Say</h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Join thousands of professionals who have improved their interview skills with our platform.
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="testimonial in testimonials" :key="testimonial.name" class="bg-gradient-to-br from-gray-50 to-gray-100 p-8 rounded-xl">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                {{ testimonial.name.charAt(0) }}
              </div>
              <div class="ml-4">
                <h4 class="font-semibold text-gray-900">{{ testimonial.name }}</h4>
                <p class="text-sm text-gray-600">{{ testimonial.role }} at {{ testimonial.company }}</p>
              </div>
            </div>
            <p class="text-gray-700 italic">"{{ testimonial.quote }}"</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
          <div>
            <div class="text-4xl font-bold mb-2">10,000+</div>
            <div class="text-blue-100">Interviews Practiced</div>
          </div>
          <div>
            <div class="text-4xl font-bold mb-2">95%</div>
            <div class="text-blue-100">Success Rate</div>
          </div>
          <div>
            <div class="text-4xl font-bold mb-2">500+</div>
            <div class="text-blue-100">Companies Covered</div>
          </div>
          <div>
            <div class="text-4xl font-bold mb-2">24/7</div>
            <div class="text-blue-100">AI Support</div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-900 text-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-6">Ready to Ace Your Next Interview?</h2>
        <p class="text-xl text-gray-300 mb-8">
          Join thousands of professionals who have successfully landed their dream jobs with our AI-powered interview preparation.
        </p>
        <Link
          :href="route('register')"
          class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-10 py-4 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl inline-block"
        >
          Get Started Today - It's Free!
        </Link>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 class="text-white font-semibold mb-4">Interview Prep AI</h3>
            <p class="text-sm">Empowering professionals to succeed in their career journey through AI-powered interview preparation.</p>
          </div>
          <div>
            <h4 class="text-white font-semibold mb-4">Features</h4>
            <ul class="space-y-2 text-sm">
              <li>AI Interview Questions</li>
              <li>Resume Analysis</li>
              <li>Performance Analytics</li>
              <li>Video Recording</li>
            </ul>
          </div>
          <div>
            <h4 class="text-white font-semibold mb-4">Resources</h4>
            <ul class="space-y-2 text-sm">
              <li>Interview Tips</li>
              <li>Career Guides</li>
              <li>Success Stories</li>
              <li>Blog</li>
            </ul>
          </div>
          <div>
            <h4 class="text-white font-semibold mb-4">Support</h4>
            <ul class="space-y-2 text-sm">
              <li>Help Center</li>
              <li>Contact Us</li>
              <li>Privacy Policy</li>
              <li>Terms of Service</li>
            </ul>
          </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm">
          <p>&copy; 2024 Interview Prep AI. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const features = ref([
  {
    icon: '🤖',
    title: 'AI-Powered Questions',
    description: 'Get personalized interview questions based on your resume and job postings using advanced AI technology.'
  },
  {
    icon: '📊',
    title: 'Performance Analytics',
    description: 'Track your progress with detailed analytics and insights to identify areas for improvement.'
  },
  {
    icon: '🎥',
    title: 'Video Recording',
    description: 'Practice with video recording to review your body language and communication skills.'
  },
  {
    icon: '📝',
    title: 'Resume Analysis',
    description: 'Upload your resume and get AI-powered feedback and optimization suggestions.'
  },
  {
    icon: '💼',
    title: 'Job Matching',
    description: 'Match your skills with job requirements and get tailored interview preparation.'
  },
  {
    icon: '🚀',
    title: 'Career Growth',
    description: 'Build confidence and improve your interview skills with personalized feedback and coaching.'
  }
]);

const testimonials = ref([
  {
    name: 'Sarah Johnson',
    role: 'Software Engineer',
    company: 'Tech Corp',
    quote: 'Interview Prep AI helped me land my dream job! The AI-generated questions were spot-on and the feedback was invaluable.'
  },
  {
    name: 'Michael Chen',
    role: 'Product Manager',
    company: 'StartupXYZ',
    quote: 'The resume analysis feature gave me insights I never considered. My interview performance improved dramatically.'
  },
  {
    name: 'Emily Rodriguez',
    role: 'Data Scientist',
    company: 'DataFlow Inc',
    quote: 'The personalized practice sessions and analytics helped me identify my weak points and improve them systematically.'
  }
]);
</script>