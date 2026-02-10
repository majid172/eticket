<script setup>
const teamMembers = [
  {
    id: 1,
    name: 'Sarah Johnson',
    role: 'CEO & Founder',
    image: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
    bio: 'Visionary leader with 15+ years in the travel industry.'
  },
  {
    id: 2,
    name: 'David Chen',
    role: 'Chief Technology Officer',
    image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
    bio: 'Tech enthusiast passionate about building seamless booking experiences.'
  },
  {
    id: 3,
    name: 'Emily Davis',
    role: 'Head of Customer Success',
    image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
    bio: 'Dedicated to ensuring every journey is smooth and enjoyable.'
  },
  {
    id: 4,
    name: 'Michael Wilson',
    role: 'Operations Manager',
    image: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
    bio: 'Expert in logistics and fleet management optimization.'
  }
]
</script>

<template>
  <div class="about-view bg-white">
    <!-- Hero Section -->
    <section class="relative h-[400px] flex items-center justify-center bg-gray-900 overflow-hidden">
      <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="About Us Hero" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-900"></div>
      </div>
      <div class="relative z-10 text-center px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">About E-Ticket</h1>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto">Connecting people and places through safe, reliable, and comfortable bus travel.</p>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-16 md:py-24">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center gap-12">
          <div class="md:w-1/2">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Our Mission" class="rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-300">
          </div>
          <div class="md:w-1/2">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
            <p class="text-gray-600 text-lg mb-6 leading-relaxed">
              At E-Ticket, our mission is to revolutionize the intercity bus travel experience. We believe that travel should be more than just getting from point A to point B; it should be an enjoyable, hassle-free part of your journey.
            </p>
            <p class="text-gray-600 text-lg leading-relaxed">
              We are committed to providing a platform that offers seamless booking, transparent pricing, and access to the best operators in the industry. Whether you're traveling for business or leisure, we're here to make your trip comfortable and memorable.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-indigo-600 py-16">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
          <div>
            <div class="text-4xl font-bold mb-2">1M+</div>
            <div class="text-indigo-200">Tickets Sold</div>
          </div>
          <div>
            <div class="text-4xl font-bold mb-2">500+</div>
            <div class="text-indigo-200">Destinations</div>
          </div>
          <div>
            <div class="text-4xl font-bold mb-2">50+</div>
            <div class="text-indigo-200">Bus Partners</div>
          </div>
          <div>
            <div class="text-4xl font-bold mb-2">4.8/5</div>
            <div class="text-indigo-200">Customer Rating</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 md:py-24 bg-gray-50">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
          <p class="text-gray-600 max-w-2xl mx-auto">The passionate individuals working behind the scenes to make your travel experience exceptional.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="member in teamMembers" :key="member.id" class="bg-white rounded-xl shadow-lg overflow-hidden hover:-translate-y-2 transition-transform duration-300">
            <img :src="member.image" :alt="member.name" class="w-full h-64 object-cover">
            <div class="p-6">
              <h3 class="text-xl font-bold text-gray-900 mb-1">{{ member.name }}</h3>
              <p class="text-indigo-600 font-medium mb-3">{{ member.role }}</p>
              <p class="text-gray-500 text-sm">{{ member.bio }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gray-900 text-center">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-8">Ready to Start Your Journey?</h2>
        <router-link to="/" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-colors shadow-lg hover:shadow-xl transform hover:scale-105">
          Book Your Ticket Now
        </router-link>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Scoped styles specific to About page if needed */
</style>
