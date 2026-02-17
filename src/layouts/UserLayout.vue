<script setup>
import { RouterView, RouterLink } from 'vue-router'
import { ref } from 'vue'

const isMenuOpen = ref(false)
</script>

<template>
  <div class="user-layout flex flex-col min-h-screen bg-gray-50 font-sans">
    <!-- Navbar -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center">
            <RouterLink to="/" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
              E-Ticket
            </RouterLink>
          </div>

          <!-- Desktop Navigation -->
          <nav class="hidden md:flex space-x-8">
            <RouterLink to="/" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Home</RouterLink>
           
            <RouterLink to="/about" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">About</RouterLink>
            <RouterLink to="/contact" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Contact</RouterLink>
            <RouterLink to="/settings" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Settings</RouterLink>
          </nav>

          <!-- Action Buttons -->
          <div class="hidden md:flex items-center space-x-4">
            <button class="text-gray-700 hover:text-indigo-600 font-medium text-sm">Sign In</button>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors shadow-md hover:shadow-lg">
              Sign Up
            </button>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center">
            <button @click="isMenuOpen = !isMenuOpen" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-if="isMenuOpen" class="md:hidden bg-white border-b border-gray-100">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <RouterLink to="/" class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium">Home</RouterLink>
          <RouterLink to="/events" class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium">Events</RouterLink>
          <RouterLink to="/about" class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium">About</RouterLink>
          <RouterLink to="/contact" class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium">Contact</RouterLink>
          <RouterLink to="/settings" class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium">Settings</RouterLink>
          <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col space-y-2">
             <button class="w-full text-left text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium">Sign In</button>
             <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-base font-medium">Sign Up</button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
      <RouterView />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-8">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          <div class="col-span-1 md:col-span-1">
             <h3 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400 mb-4">E-Ticket</h3>
             <p class="text-gray-400 text-sm">Your premier destination for booking tickets to the hottest concerts, sports, and theater events.</p>
          </div>
          <div>
            <h4 class="text-lg font-semibold mb-4 text-gray-200">Quick Links</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><RouterLink to="/" class="hover:text-white transition-colors">Home</RouterLink></li>
              <li><RouterLink to="/events" class="hover:text-white transition-colors">Events</RouterLink></li>
              <li><RouterLink to="/about" class="hover:text-white transition-colors">About Us</RouterLink></li>
              <li><RouterLink to="/contact" class="hover:text-white transition-colors">Contact</RouterLink></li>
            </ul>
          </div>
          <div>
            <h4 class="text-lg font-semibold mb-4 text-gray-200">Support</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
              <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-lg font-semibold mb-4 text-gray-200">Newsletter</h4>
            <p class="text-gray-400 text-sm mb-4">Subscribe to get the latest updates and exclusive offers.</p>
            <form class="flex flex-col space-y-2">
              <input type="email" placeholder="Your email address" class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
              <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Subscribe</button>
            </form>
          </div>
        </div>
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
          <p class="text-gray-500 text-sm">&copy; 2026 E-Ticket System. All rights reserved.</p>
          <div class="flex space-x-4 mt-4 md:mt-0">
            <!-- Social Icons (Placeholders) -->
            <a href="#" class="text-gray-400 hover:text-white"><span class="sr-only">Facebook</span>FB</a>
            <a href="#" class="text-gray-400 hover:text-white"><span class="sr-only">Twitter</span>TW</a>
            <a href="#" class="text-gray-400 hover:text-white"><span class="sr-only">Instagram</span>IG</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>
