<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { RouterLink, RouterView, useRoute } from 'vue-router'

const route = useRoute()
const isSidebarOpen = ref(true)
const isMobile = ref(false)

const menuItems = [
  { name: 'Dashboard', path: '/admin/dashboard', icon: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' },
  { name: 'Buses', path: '/admin/buses', icon: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4' }, 
  { name: 'Routes', path: '/admin/routes', icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z' },
  { name: 'Bookings', path: '/admin/bookings', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
  { name: 'Users', path: '/admin/users', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
]

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}

const checkMobile = () => {
    isMobile.value = window.innerWidth < 768
    if (isMobile.value) {
        isSidebarOpen.value = false // Auto-close on mobile init
    } else {
        isSidebarOpen.value = true // Auto-open on desktop init
    }
}

onMounted(() => {
    checkMobile()
    window.addEventListener('resize', checkMobile)
})

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile)
})
</script>

<template>
  <div class="h-screen bg-[#F3F4F6] flex font-sans overflow-hidden">
    
    <!-- Mobile Backdrop -->
    <div 
      v-if="isSidebarOpen && isMobile" 
      class="fixed inset-0 bg-slate-900/10 backdrop-blur-sm z-30 transition-opacity"
      @click="toggleSidebar"
    ></div>

    <!-- Sidebar -->
    <aside 
      class="bg-white text-slate-700 transition-all duration-300 ease-[cubic-bezier(0.25,0.8,0.25,1)] flex flex-col z-40 fixed md:relative h-full border-r border-[#E5E7EB]"
      :class="[
        isSidebarOpen ? 'w-[280px] translate-x-0' : '-translate-x-full md:translate-x-0 md:w-[88px]'
      ]"
    >
      <!-- Brand / Logo -->
      <div class="h-24 flex items-center justify-center">
        <div class="flex items-center gap-3 overflow-hidden px-6 transition-all duration-300" :class="isSidebarOpen ? 'w-full' : 'w-full justify-center'">
           <div class="w-10 h-10 rounded-lg bg-slate-900 flex items-center justify-center text-white shrink-0 shadow-md">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
           </div>
           <div v-show="isSidebarOpen" class="flex flex-col opacity-100 transition-opacity duration-300">
               <span class="text-xl font-bold tracking-tight text-slate-900 leading-none">Jatri<span class="text-indigo-600">Admin</span></span>
           </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-grow py-8 px-4 space-y-1.5 overflow-y-auto custom-scrollbar">
        <RouterLink 
          v-for="item in menuItems" 
          :key="item.path" 
          :to="item.path"
          class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative"
          :class="route.path.startsWith(item.path) ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
        >
          <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" :class="route.path.startsWith(item.path) ? 'text-white' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
          </svg>
          
          <span 
            class="ml-3.5 font-medium text-[15px] whitespace-nowrap overflow-hidden transition-all duration-300 origin-left"
            :class="isSidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 hidden md:block'"
          >
            {{ item.name }}
          </span>

          <!-- Tooltip for Collapsed State -->
          <div 
            v-if="!isSidebarOpen" 
            class="absolute left-[70px] px-3 py-1.5 bg-slate-800 text-white text-xs font-medium rounded-md shadow-xl opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 pointer-events-none transition-all duration-200 z-50 whitespace-nowrap"
          >
              {{ item.name }}
          </div>
        </RouterLink>
      </nav>

      <!-- Profile Section -->
      <div class="p-6 mt-auto">
         <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 hover:border-slate-200 transition-colors group cursor-pointer">
             <div class="flex items-center gap-3" :class="isSidebarOpen ? 'justify-start' : 'justify-center'">
                 <div class="relative shrink-0">
                     <img src="https://ui-avatars.com/api/?name=Admin+User&background=0f172a&color=fff&size=40" class="w-10 h-10 rounded-full shadow-sm">
                     <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-slate-50 rounded-full"></span>
                 </div>
                 <div v-show="isSidebarOpen" class="flex flex-col overflow-hidden min-w-0">
                     <p class="text-sm font-bold text-slate-800 truncate group-hover:text-indigo-600 transition-colors">Admin User</p>
                     <p class="text-xs text-slate-500 truncate">Super Admin</p>
                 </div>
             </div>
         </div>
      </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-grow flex flex-col h-full overflow-hidden w-full relative">
      
      <!-- Top Header -->
      <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 z-20 sticky top-0">
        <div class="flex items-center gap-4">
            <button @click="toggleSidebar" class="p-2 -ml-2 rounded-lg text-slate-400 hover:bg-slate-50 hover:text-indigo-600 focus:outline-none transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
            </button>
            <!-- <h2 class="text-2xl font-bold text-slate-800 hidden sm:block tracking-tight">
                {{ menuItems.find(i => route.path.startsWith(i.path))?.name || 'Dashboard' }} 
            </h2> -->
        </div>
        
        <div class="flex items-center space-x-6">
             <!-- Search -->
             <div class="hidden lg:flex items-center bg-slate-50 px-4 py-2 rounded-full border border-slate-200 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-100 transition-all w-64">
                 <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                 <input type="text" placeholder="Search..." class="bg-transparent border-none focus:ring-0 text-sm w-full ml-2 text-slate-700 placeholder-slate-400">
             </div>

             <!-- Notifications -->
             <button class="relative p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                 <span class="absolute top-1 right-1.5 w-2 h-2 bg-rose-500 border-2 border-white rounded-full"></span>
             </button>
             
             <!-- Settings -->
             <button class="text-slate-400 hover:text-slate-700 transition-colors">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
             </button>
        </div>
      </header>

      <!-- Page Content Scroll Area -->
      <main class="flex-grow overflow-y-auto p-8 scroll-smooth">
         <div class="max-w-[1920px] mx-auto">
            <RouterView />
         </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #cbd5e1;
}
</style>
