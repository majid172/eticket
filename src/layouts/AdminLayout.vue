<script setup>
import { ref } from 'vue'
import { RouterLink, RouterView, useRoute } from 'vue-router'

const route = useRoute()
const isSidebarOpen = ref(true)

const menuItems = [
  { name: 'Dashboard', path: '/admin/dashboard', icon: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' },
  { name: 'Buses', path: '/admin/buses', icon: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4' }, // Placeholder icon
  { name: 'Routes', path: '/admin/routes', icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z' },
  { name: 'Bookings', path: '/admin/bookings', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
  { name: 'Users', path: '/admin/users', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
]

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}
</script>

<template>
  <div class="h-screen bg-gray-100 flex font-sans overflow-hidden">
    <!-- Sidebar -->
    <!-- Mobile: Fixed overlay, Desktop: Relative -->
    <div 
      v-if="isSidebarOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
      @click="toggleSidebar"
    ></div>

    <aside 
      class="bg-indigo-900 text-white transition-all duration-300 ease-in-out flex flex-col shadow-xl z-30 fixed md:relative h-full"
      :class="[
        isSidebarOpen ? 'w-64 translate-x-0' : 'w-64 -translate-x-full md:translate-x-0 md:w-20'
      ]"
    >
      <!-- Logo -->
      <div class="h-16 flex items-center justify-center border-b border-indigo-800">
        <div v-if="isSidebarOpen" class="text-2xl font-bold tracking-wider flex items-center">
             <span class="text-indigo-400 mr-1">Jatri</span>Admin
        </div>
        <div v-else class="text-xl font-bold">JA</div>
      </div>

      <!-- Navigation -->
      <nav class="flex-grow py-6 space-y-2 px-3">
        <RouterLink 
          v-for="item in menuItems" 
          :key="item.path" 
          :to="item.path"
          class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="route.path.startsWith(item.path) ? 'bg-indigo-700 text-white shadow-lg' : 'text-indigo-100 hover:bg-indigo-800 hover:text-white'"
        >
          <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
          </svg>
          <span 
            class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300"
            :class="isSidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
          >
            {{ item.name }}
          </span>
        </RouterLink>
      </nav>

      <!-- User Profile Summary (Bottom) -->
      <div class="p-4 border-t border-indigo-800">
         <div class="flex items-center">
             <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold border-2 border-indigo-400">
                 A
             </div>
             <div class="ml-3 overflow-hidden" :class="isSidebarOpen ? 'block' : 'hidden'">
                 <p class="text-sm font-bold text-white">Admin User</p>
                 <p class="text-xs text-indigo-300">admin@jatri.com</p>
             </div>
         </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col h-full overflow-hidden w-full">
      <!-- Header -->
      <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10">
        <button @click="toggleSidebar" class="text-gray-500 hover:text-indigo-600 focus:outline-none transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        
        <div class="flex items-center space-x-4">
             <button class="text-gray-400 hover:text-indigo-600 relative">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                 <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
             </button>
             <button class="text-gray-500 hover:text-red-600 font-medium text-sm flex items-center">
                 <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                 Logout
             </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-grow p-6 overflow-y-auto">
         <RouterView />
      </main>
    </div>
  </div>
</template>
