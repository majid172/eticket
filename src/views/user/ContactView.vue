<script setup>
import { ref } from 'vue'

const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const isLoading = ref(false)
const showSuccess = ref(false)

const handleSubmit = () => {
  isLoading.value = true
  // Simulate API call
  setTimeout(() => {
    isLoading.value = false
    showSuccess.value = true
    form.value = { name: '', email: '', subject: '', message: '' }
    
    setTimeout(() => {
      showSuccess.value = false
    }, 5000)
  }, 1500)
}

const contactInfo = [
  {
    title: 'Customer Support',
    details: '+1 (555) 123-4567',
    subDetails: 'Available 24/7 for urgent queries',
    icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>`
  },
  {
    title: 'Email Address',
    details: 'support@eticket.com',
    subDetails: 'We respond within 24 hours',
    icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>`
  },
  {
    title: 'Headquarters',
    details: '123 Business Avenue',
    subDetails: 'New York, NY 10001, USA',
    icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>`
  }
]
</script>

<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Hero Section (Matched with About Page) -->
    <section class="relative h-[400px] flex items-center justify-center bg-gray-900 overflow-hidden">
      <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Contact Us Hero" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-900"></div>
      </div>
      <div class="relative z-10 text-center px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Get in Touch</h1>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto">We'd love to hear from you. Our team is always here to chat.</p>
      </div>
    </section>

    <!-- Content Section -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-20 pb-20">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Contact Info Cards -->
        <div class="lg:col-span-1 space-y-6">
            <div v-for="(item, index) in contactInfo" :key="index" class="bg-white rounded-2xl shadow-lg p-6 flex items-start space-x-4 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex-shrink-0 p-3 bg-indigo-50 rounded-xl text-indigo-600" v-html="item.icon"></div>
                <div>
                  <h3 class="text-lg font-bold text-gray-900">{{ item.title }}</h3>
                  <p class="text-indigo-600 font-medium mt-1">{{ item.details }}</p>
                  <p class="text-sm text-gray-500 mt-1">{{ item.subDetails }}</p>
                </div>
            </div>

            <!-- Social Media Card -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-lg p-8 text-white text-center">
                <h3 class="text-xl font-bold mb-4">Connect With Us</h3>
                <p class="text-indigo-100 mb-6 text-sm">Follow us on social media for updates and special offers.</p>
                <div class="flex justify-center space-x-4">
                     <a href="#" class="p-2 bg-white/20 hover:bg-white/30 rounded-full transition-colors backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="p-2 bg-white/20 hover:bg-white/30 rounded-full transition-colors backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="#" class="p-2 bg-white/20 hover:bg-white/30 rounded-full transition-colors backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.269.156 5.023 1.938 5.179 5.179.06 1.266.072 1.646.072 4.85s-.012 3.584-.072 4.85c-.156 3.269-1.938 5.023-5.179 5.179-1.266.06 1.646-.072-4.85.072s-3.584-.012-4.85-.072c-3.269-.156-5.023-1.938-5.179-5.179-.06-1.266-.072-1.646-.072-4.85s.012-3.584.072-4.85c.156-3.269 1.938-5.023 5.179-5.179 1.266-.06 1.646-.072 4.85-.072zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Form Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 h-full">
                 <div v-if="showSuccess" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                   <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                   Message sent successfully! We'll get back to you shortly.
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                
                <form @submit.prevent="handleSubmit" class="space-y-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                      <input 
                        v-model="form.name"
                        type="text" 
                        required
                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all hover:bg-white"
                        placeholder="John Doe"
                      >
                    </div>
                    
                    <!-- Email -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                      <input 
                        v-model="form.email"
                        type="email" 
                        required
                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all hover:bg-white"
                        placeholder="john@example.com"
                      >
                    </div>
                  </div>

                  <!-- Subject -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input 
                      v-model="form.subject"
                      type="text" 
                      required
                      class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all hover:bg-white"
                      placeholder="How can we help?"
                    >
                  </div>

                  <!-- Message -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea 
                      v-model="form.message"
                      required
                      rows="6"
                      class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none hover:bg-white"
                      placeholder="Tell us about your inquiry..."
                    ></textarea>
                  </div>

                  <div class="pt-2">
                    <button 
                      type="submit" 
                      :disabled="isLoading"
                      class="w-full md:w-auto inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transform transition hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none"
                    >
                      <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span>{{ isLoading ? 'Sending Message...' : 'Send Information' }}</span>
                    </button>
                  </div>
                </form>
            </div>
        </div>

      </div>

      <!-- Map Section -->
      <div class="mt-12 rounded-2xl overflow-hidden shadow-xl border border-gray-100">
       <iframe
    width="100%"
    height="400"
    class="w-full h-[400px] md:h-[500px]"
    style="border:0;"
    loading="lazy"
    allowfullscreen
    referrerpolicy="no-referrer-when-downgrade"
    src="https://www.google.com/maps?q=23.7701885,90.4060633&hl=en&z=15&output=embed">
</iframe>

      </div>
    </div>
  </div>
</template>
