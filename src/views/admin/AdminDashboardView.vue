<script setup>
import { ref } from 'vue'

import AdminTable from '@/components/AdminTable.vue'
import AdminButton from '@/components/AdminButton.vue'

const stats = ref([
  { title: 'Total Bookings', value: '1,245', icon: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', color: 'bg-indigo-500' },
  { title: 'Total Revenue', value: '৳ 450,000', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', color: 'bg-green-500' },
  { title: 'Active Buses', value: '45', icon: 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z', color: 'bg-amber-500' },
  { title: 'New Users', value: '158', icon: 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', color: 'bg-purple-500' }
])

const columns = [
    { key: 'id', label: 'Booking ID', class: 'font-semibold text-gray-700' },
    { key: 'name', label: 'User', class: 'text-gray-600' },
    { key: 'route', label: 'Route', class: 'text-gray-600' },
    { key: 'date', label: 'Date', class: 'text-gray-500 text-sm' },
    { key: 'amount', label: 'Amount', class: 'font-bold text-gray-800' },
    { key: 'status', label: 'Status' },
    { key: 'action', label: 'Action' }
]

const recentBookings = ref([
    { id: '#BK-7829', name: 'John Doe', route: 'Dhaka - Chittagong', date: '12 Feb, 2026', amount: '৳ 1,400', status: 'Confirmed' },
    { id: '#BK-7830', name: 'Jane Smith', route: 'Dhaka - Sylhet', date: '12 Feb, 2026', amount: '৳ 850', status: 'Pending' },
    { id: '#BK-7831', name: 'Rahim Uddin', route: 'Dhaka - Cox\'s Bazar', date: '11 Feb, 2026', amount: '৳ 2,200', status: 'Confirmed' },
    { id: '#BK-7832', name: 'Karim Khan', route: 'Chittagong - Dhaka', date: '11 Feb, 2026', amount: '৳ 600', status: 'Cancelled' },
])
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Overview</h2>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div v-for="stat in stats" :key="stat.title" class="bg-white rounded-xl shadow-sm p-6 flex items-center border border-gray-100 transition-transform ">
        <div :class="[stat.color, 'p-4 rounded-lg text-white shadow-lg mr-4']">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon"></path></svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 uppercase font-semibold mb-1">{{ stat.title }}</p>
          <h3 class="text-2xl font-bold text-gray-800">{{ stat.value }}</h3>
        </div>
      </div>
    </div>

    <!-- Recent Bookings Table -->
    <AdminTable :columns="columns" :data="recentBookings">
        <template #header>
            <h3 class="font-bold text-gray-800 text-lg">Recent Bookings</h3>
            <AdminButton variant="text" size="sm">View All</AdminButton>
        </template>

        <template #cell-status="{ item }">
             <span :class="{
                'bg-green-100 text-green-700': item.status === 'Confirmed',
                'bg-yellow-100 text-yellow-700': item.status === 'Pending',
                'bg-red-100 text-red-700': item.status === 'Cancelled'
            }" class="px-2 py-1 rounded-full text-xs font-bold uppercase">
                {{ item.status }}
            </span>
        </template>

        <template #cell-action="{ item }">
            <AdminButton variant="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </AdminButton>
        </template>
    </AdminTable>
  </div>
</template>
