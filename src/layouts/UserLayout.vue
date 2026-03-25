<script setup>
import { RouterView, RouterLink, useRoute } from 'vue-router'
import { ref, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const route = useRoute()

const isMobile = ref(false)

// Check for mobile on mount and resize
if (typeof window !== 'undefined') {
  isMobile.value = window.innerWidth < 768
  window.addEventListener('resize', () => {
    isMobile.value = window.innerWidth < 768
  })
}

const pageTitle = computed(() => {
  switch (route.name) {
    case 'home': return 'E-Ticket'
    case 'search': return 'Search Results'
    case 'booking': return 'My Bookings'
    case 'user-settings': return 'Settings'
    case 'about': return 'About Us'
    case 'contact': return 'Contact'
    default: return 'E-Ticket'
  }
})
</script>

<template>
  <div class="user-layout flex flex-col min-h-screen bg-gray-50 font-sans pb-16 md:pb-0">
    <!-- Navbar (Desktop) -->
    <header class="hidden md:block bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center">
            <RouterLink to="/" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
              E-Ticket
            </RouterLink>
          </div>

          <!-- Desktop Navigation -->
          <nav class="flex space-x-8">
            <RouterLink to="/" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Home</RouterLink>
            <RouterLink to="/about" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">About</RouterLink>
            <RouterLink to="/contact" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Contact</RouterLink>
            <RouterLink to="/settings" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Settings</RouterLink>
          </nav>

          <!-- Action Buttons -->
          <div class="flex items-center space-x-4">
            <template v-if="!auth.isAuthenticated">
              <RouterLink to="/login" class="text-gray-700 hover:text-indigo-600 font-medium text-sm">Sign In</RouterLink>
              <RouterLink to="/register" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors shadow-md hover:shadow-lg">
                Sign Up
              </RouterLink>
            </template>
            <template v-else>
              <RouterLink v-if="auth.isAdmin" to="/admin/dashboard" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
              </RouterLink>
              <RouterLink v-else-if="auth.isOperator" to="/operator/dashboard" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
              </RouterLink>
              <span class="text-sm text-gray-600 font-medium">Hi, {{ auth.user?.name?.split(' ')[0] }}</span>
              <button @click="auth.logout()" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-full text-sm font-medium transition-colors border border-red-200">
                Logout
              </button>
            </template>
          </div>
        </div>
      </div>
    </header>

    <!-- Mobile Header (App Tool Bar) -->
    <header class="md:hidden bg-white/90 backdrop-blur-xl sticky top-0 z-50 border-b border-gray-100 h-14 flex items-center px-4 justify-between">
      <div class="flex items-center gap-3">
        <button v-if="route.path !== '/'" @click="$router.back()" class="p-2 -ml-2 text-gray-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <h1 class="text-lg font-bold text-gray-900">{{ pageTitle }}</h1>
      </div>
      <div class="flex items-center gap-1">
        <button class="p-2 text-gray-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        </button>
        <template v-if="auth.isAuthenticated">
          <RouterLink to="/settings" class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
            {{ auth.user?.name?.[0]?.toUpperCase() }}
          </RouterLink>
        </template>
        <template v-else>
          <RouterLink to="/login" class="text-sm font-medium text-indigo-600">Login</RouterLink>
        </template>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
      <RouterView />
    </main>

    <!-- Bottom Navigation (Mobile Only) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-t border-gray-100 flex justify-around items-center h-16 z-50 px-2 pb-safe">
      <RouterLink to="/" class="flex flex-col items-center justify-center w-full h-full text-gray-400" active-class="text-indigo-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span class="text-[10px] mt-1 font-medium">Home</span>
      </RouterLink>
      
      <RouterLink to="/search" class="flex flex-col items-center justify-center w-full h-full text-gray-400" active-class="text-indigo-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <span class="text-[10px] mt-1 font-medium">Search</span>
      </RouterLink>

      <RouterLink to="/booking" class="flex flex-col items-center justify-center w-full h-full text-gray-400" active-class="text-indigo-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
        <span class="text-[10px] mt-1 font-medium">Bookings</span>
      </RouterLink>

      <RouterLink to="/settings" class="flex flex-col items-center justify-center w-full h-full text-gray-400" active-class="text-indigo-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        <span class="text-[10px] mt-1 font-medium">Account</span>
      </RouterLink>
    </nav>

    <!-- Footer (Desktop Only) -->
    <footer class="hidden md:block bg-gray-900 text-white pt-12 pb-8">
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
            <a href="#" class="text-gray-400 hover:text-white"><span class="sr-only">Facebook</span>FB</a>
            <a href="#" class="text-gray-400 hover:text-white"><span class="sr-only">Twitter</span>TW</a>
            <a href="#" class="text-gray-400 hover:text-white"><span class="sr-only">Instagram</span>IG</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.pb-safe {
  padding-bottom: env(safe-area-inset-bottom);
}

/* Smooth transition for active nav items */
.router-link-active svg {
  transform: translateY(-2px);
  transition: transform 0.2s ease-out;
}
</style>
