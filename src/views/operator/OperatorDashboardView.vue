<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const greeting = computed(() => {
  const h = new Date().getHours()
  if (h < 12) return 'Good morning'
  if (h < 18) return 'Good afternoon'
  return 'Good evening'
})

const stats = ref([
  {
    title: 'My Buses',
    value: '8',
    change: '+1',
    trend: 'up',
    icon: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
    iconBg: 'bg-emerald-50 text-emerald-600',
    changeColor: 'text-emerald-600 bg-emerald-50 border-emerald-100',
  },
  {
    title: "Today's Trips",
    value: '14',
    change: '+3',
    trend: 'up',
    icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    iconBg: 'bg-blue-50 text-blue-600',
    changeColor: 'text-emerald-600 bg-emerald-50 border-emerald-100',
  },
  {
    title: 'Tickets Sold',
    value: '312',
    change: '+22',
    trend: 'up',
    icon: 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
    iconBg: 'bg-indigo-50 text-indigo-600',
    changeColor: 'text-emerald-600 bg-emerald-50 border-emerald-100',
  },
  {
    title: "Today's Revenue",
    value: '৳ 48,500',
    change: '+8.4%',
    trend: 'up',
    icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    iconBg: 'bg-amber-50 text-amber-600',
    changeColor: 'text-emerald-600 bg-emerald-50 border-emerald-100',
  },
])

const recentBookings = ref([
  { id: 'BK-2201', customer: 'Arif Rahman',   route: 'Dhaka → Chittagong', date: 'Today, 08:30', amount: '৳ 1,200', status: 'Confirmed' },
  { id: 'BK-2202', customer: 'Nadia Islam',   route: 'Dhaka → Sylhet',    date: 'Today, 09:15', amount: '৳ 950',    status: 'Pending'   },
  { id: 'BK-2203', customer: 'Hasan Ali',     route: "Dhaka → Cox's Bazar", date: 'Today, 10:00', amount: '৳ 2,100', status: 'Confirmed' },
  { id: 'BK-2204', customer: 'Sumaiya Begum', route: 'Chittagong → Dhaka', date: 'Today, 11:30', amount: '৳ 1,100', status: 'Cancelled' },
])

const activeSchedules = ref([
  { bus: 'Dhaka Express (B-001)', route: 'Dhaka → Chittagong', departure: '08:00 AM', seats: '32/45', status: 'On Time' },
  { bus: 'Sylhet Voyager (B-003)', route: 'Dhaka → Sylhet',    departure: '09:30 AM', seats: '40/45', status: 'Boarding' },
  { bus: 'Cox Special (B-006)',    route: "Dhaka → Cox's Bazar", departure: '11:00 AM', seats: '18/45', status: 'On Time' },
])
</script>

<template>
  <div class="p-6 min-h-screen">

    <!-- Welcome Banner -->
    <div class="mb-8 bg-gradient-to-r from-emerald-700 to-teal-600 rounded-2xl p-6 text-white shadow-lg shadow-emerald-700/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
      <div>
        <p class="text-emerald-200 text-sm mb-1">{{ greeting }},</p>
        <h1 class="text-2xl font-bold">{{ auth.user?.name || 'Operator' }} 👋</h1>
        <p class="text-emerald-200 text-sm mt-1">Here's what's happening with your fleet today.</p>
      </div>
      <div class="flex gap-3">
        <RouterLink to="/operator/buses" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
          Manage Buses
        </RouterLink>
        <RouterLink to="/operator/schedules" class="px-4 py-2 bg-white text-emerald-700 text-sm font-bold rounded-xl hover:bg-emerald-50 transition-all duration-200">
          View Schedules
        </RouterLink>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div v-for="stat in stats" :key="stat.title"
        class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center hover:shadow-md transition-shadow"
      >
        <div :class="[stat.iconBg, 'w-12 h-12 rounded-full flex items-center justify-center shrink-0 mr-4']">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon"/>
          </svg>
        </div>
        <div class="flex-grow">
          <p class="text-sm text-gray-500 font-medium mb-1">{{ stat.title }}</p>
          <div class="flex items-center gap-2">
            <h3 class="text-2xl font-bold text-gray-900">{{ stat.value }}</h3>
            <span :class="[stat.changeColor, 'text-xs font-medium px-1.5 py-0.5 rounded border flex items-center gap-0.5']">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
              </svg>
              {{ stat.change }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Two-column section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

      <!-- Active Schedules -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
          <h3 class="text-lg font-bold text-gray-900">Active Schedules</h3>
          <RouterLink to="/operator/schedules" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium transition-colors">View all →</RouterLink>
        </div>
        <div class="divide-y divide-gray-50">
          <div v-for="s in activeSchedules" :key="s.bus" class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
              </svg>
            </div>
            <div class="flex-grow min-w-0">
              <p class="text-sm font-bold text-gray-800 truncate">{{ s.bus }}</p>
              <p class="text-xs text-gray-500 truncate">{{ s.route }}</p>
            </div>
            <div class="text-right shrink-0">
              <p class="text-sm font-semibold text-gray-700">{{ s.departure }}</p>
              <p class="text-xs text-gray-400">{{ s.seats }} seats</p>
            </div>
            <span :class="{
              'bg-emerald-50 text-emerald-700 border-emerald-200': s.status === 'On Time',
              'bg-amber-50 text-amber-700 border-amber-200': s.status === 'Boarding',
            }" class="text-xs font-bold px-2.5 py-1 rounded-full border shrink-0">{{ s.status }}</span>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col gap-4">
        <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
        <RouterLink to="/operator/buses" class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition-colors group">
          <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          <span class="font-medium text-sm">Add New Bus</span>
        </RouterLink>
        <RouterLink to="/operator/schedules" class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 transition-colors group">
          <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <span class="font-medium text-sm">Create Schedule</span>
        </RouterLink>
        <RouterLink to="/operator/bookings" class="flex items-center gap-3 p-4 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition-colors group">
          <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          <span class="font-medium text-sm">View All Bookings</span>
        </RouterLink>
        <RouterLink to="/operator/settings" class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-700 transition-colors group">
          <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          </svg>
          <span class="font-medium text-sm">Settings</span>
        </RouterLink>
      </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="text-lg font-bold text-gray-900">Recent Bookings</h3>
        <RouterLink to="/operator/bookings" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium transition-colors">View all →</RouterLink>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100">
              <th class="p-4 pl-6 text-xs font-semibold text-gray-500">Booking ID</th>
              <th class="p-4 text-xs font-semibold text-gray-500">Customer</th>
              <th class="p-4 text-xs font-semibold text-gray-500">Route</th>
              <th class="p-4 text-xs font-semibold text-gray-500">Date</th>
              <th class="p-4 text-xs font-semibold text-gray-500">Amount</th>
              <th class="p-4 text-xs font-semibold text-gray-500">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="b in recentBookings" :key="b.id" class="hover:bg-gray-50/50 transition-colors">
              <td class="p-4 pl-6 text-sm font-bold text-gray-900">{{ b.id }}</td>
              <td class="p-4 text-sm font-medium text-gray-700">{{ b.customer }}</td>
              <td class="p-4 text-sm text-gray-600">{{ b.route }}</td>
              <td class="p-4 text-sm text-gray-500">{{ b.date }}</td>
              <td class="p-4 text-sm font-bold text-gray-900">{{ b.amount }}</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border"
                  :class="{
                    'text-amber-600 bg-amber-50 border-amber-200': b.status === 'Pending',
                    'text-emerald-600 bg-emerald-50 border-emerald-200': b.status === 'Confirmed',
                    'text-rose-600 bg-rose-50 border-rose-200': b.status === 'Cancelled',
                  }">
                  <span class="w-1.5 h-1.5 rounded-full mr-1.5"
                    :class="{
                      'bg-amber-500': b.status === 'Pending',
                      'bg-emerald-500': b.status === 'Confirmed',
                      'bg-rose-500': b.status === 'Cancelled',
                    }"></span>
                  {{ b.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
