<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { RouterLink, RouterView, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const isSidebarOpen = ref(true)
const isMobile = ref(false)

const menuItems = [
  {
    name: 'Dashboard',
    path: '/operator/dashboard',
    icon: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
  },
  {
    name: 'My Buses',
    path: '/operator/buses',
    icon: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
  },
  {
    name: 'Company',
    path: '/operator/company',
    icon: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
  },
  {
    name: 'Schedules',
    path: '/operator/schedules',
    icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
  },
  {
    name: 'Bookings',
    path: '/operator/bookings',
    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
  },
  {
    name: 'Settings',
    path: '/operator/settings',
    icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
  },
]

const userName = computed(() => auth.user?.name || 'Operator')
const userEmail = computed(() => auth.user?.email || '')
const avatarUrl = computed(() =>
  `https://ui-avatars.com/api/?name=${encodeURIComponent(userName.value)}&background=065f46&color=fff&size=40`
)

const toggleSidebar = () => { isSidebarOpen.value = !isSidebarOpen.value }

const checkMobile = () => {
  isMobile.value = window.innerWidth < 768
  isSidebarOpen.value = !isMobile.value
}

onMounted(() => { checkMobile(); window.addEventListener('resize', checkMobile) })
onUnmounted(() => { window.removeEventListener('resize', checkMobile) })
</script>

<template>
  <div class="h-screen bg-[#F0FDF4] flex font-sans overflow-hidden">

    <!-- Mobile Backdrop -->
    <div
      v-if="isSidebarOpen && isMobile"
      class="fixed inset-0 bg-slate-900/10 backdrop-blur-sm z-30"
      @click="toggleSidebar"
    />

    <!-- Sidebar -->
    <aside
      class="bg-white text-slate-700 transition-all duration-300 ease-[cubic-bezier(0.25,0.8,0.25,1)] flex flex-col z-40 fixed md:relative h-full border-r border-emerald-100"
      :class="isSidebarOpen ? 'w-[280px] translate-x-0' : '-translate-x-full md:translate-x-0 md:w-[88px]'"
    >
      <!-- Brand -->
      <div class="h-24 flex items-center justify-center">
        <div class="flex items-center gap-3 overflow-hidden px-6 transition-all duration-300" :class="isSidebarOpen ? 'w-full' : 'w-full justify-center'">
          <div class="w-10 h-10 rounded-lg bg-emerald-700 flex items-center justify-center text-white shrink-0 shadow-md">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <div v-show="isSidebarOpen" class="flex flex-col opacity-100 transition-opacity duration-300">
            <span class="text-xl font-bold tracking-tight text-slate-900 leading-none">
              Ticket <span class="text-emerald-600">Operator</span>
            </span>
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
          :class="route.path.startsWith(item.path)
            ? 'bg-emerald-700 text-white shadow-lg shadow-emerald-700/20'
            : 'text-slate-500 hover:bg-emerald-50 hover:text-emerald-800'"
        >
          <svg
            class="w-5 h-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110"
            :class="route.path.startsWith(item.path) ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600'"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"/>
          </svg>
          <span
            class="ml-3.5 font-medium text-[15px] whitespace-nowrap overflow-hidden transition-all duration-300 origin-left"
            :class="isSidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 hidden md:block'"
          >
            {{ item.name }}
          </span>
          <!-- Tooltip -->
          <div
            v-if="!isSidebarOpen"
            class="absolute left-[70px] px-3 py-1.5 bg-emerald-800 text-white text-xs font-medium rounded-md shadow-xl opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 pointer-events-none transition-all duration-200 z-50 whitespace-nowrap"
          >
            {{ item.name }}
          </div>
        </RouterLink>
      </nav>

      <!-- Profile Section -->
      <div class="p-6 mt-auto">
        <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100 hover:border-emerald-200 transition-colors group cursor-pointer">
          <div class="flex items-center gap-3" :class="isSidebarOpen ? 'justify-start' : 'justify-center'">
            <div class="relative shrink-0">
              <img :src="avatarUrl" class="w-10 h-10 rounded-full shadow-sm" :alt="userName">
              <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-emerald-50 rounded-full"></span>
            </div>
            <div v-show="isSidebarOpen" class="flex flex-col overflow-hidden min-w-0">
              <p class="text-sm font-bold text-slate-800 truncate group-hover:text-emerald-700 transition-colors">{{ userName }}</p>
              <p class="text-xs text-slate-500 truncate">{{ userEmail }}</p>
            </div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-grow flex flex-col h-full overflow-hidden w-full relative">

      <!-- Top Header -->
      <header class="h-20 bg-white/80 backdrop-blur-md border-b border-emerald-100 flex items-center justify-between px-8 z-20 sticky top-0">
        <div class="flex items-center gap-4">
          <button @click="toggleSidebar" class="p-2 -ml-2 rounded-lg text-slate-400 hover:bg-emerald-50 hover:text-emerald-700 focus:outline-none transition-all duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
          </button>
        </div>

        <div class="flex items-center space-x-4">
          <!-- View Website -->
          <a href="/" target="_blank" class="hidden md:flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-full hover:bg-emerald-100 transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            <span>View Website</span>
          </a>

          <!-- Notifications -->
          <button class="relative p-2 text-slate-400 hover:text-emerald-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-1 right-1.5 w-2 h-2 bg-rose-500 border-2 border-white rounded-full"></span>
          </button>

          <!-- Logout -->
          <button
            @click="auth.logout()"
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-500 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-full transition-all duration-200"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
          </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-grow overflow-y-auto p-8 scroll-smooth">
        <div class="max-w-[1920px] mx-auto">
          <RouterView />
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #a7f3d0; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6ee7b7; }
</style>
