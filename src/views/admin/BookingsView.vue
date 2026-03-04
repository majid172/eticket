<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'

const bookings = ref([])
const loading = ref(false)
const apiError = ref('')

async function fetchBookings() {
    loading.value = true
    apiError.value = ''
    try {
        const { data } = await api.get('/admin/bookings')
        bookings.value = data.data || data
    } catch(err) {
        apiError.value = err.response?.data?.message || 'Failed to fetch bookings'
    } finally {
        loading.value = false
    }
}

onMounted(fetchBookings)

const searchQuery = ref('')
const allChecked = ref(false)

const getInitials = (name) => {
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
}

const toggleAll = () => {
    bookings.value.forEach(b => b.checked = allChecked.value)
}

const getStatusStyles = (status) => {
    const s = status?.toLowerCase()
    if (s === 'pending') {
        return 'bg-yellow-50 text-yellow-700'
    } else if (s === 'confirmed' || s === 'completed') {
        return 'bg-green-50 text-green-700'
    } else if (s === 'cancelled') {
        return 'bg-rose-50 text-rose-700'
    } else {
        return 'bg-gray-50 text-gray-700'
    }
}

const getStatusIconColor = (status) => {
    const s = status?.toLowerCase()
     if (s === 'pending') {
        return 'text-yellow-500'
    } else if (s === 'confirmed' || s === 'completed') {
        return 'text-green-500'
    } else if (s === 'cancelled') {
        return 'text-rose-500'
    } else {
        return 'text-gray-500'
    }
}

const filteredBookings = computed(() => {
    if (!searchQuery.value) return bookings.value
    const query = searchQuery.value.toLowerCase()
    return bookings.value.filter(booking => 
        (booking.booking_reference || '').toLowerCase().includes(query) || 
        (booking.primary_passenger_name || '').toLowerCase().includes(query) ||
        (booking.primary_passenger_phone || '').toLowerCase().includes(query)
    )
})

</script>

<template>
  <div class="p-8 max-w-[1600px] mx-auto font-sans text-slate-800 bg-white">
    
    <!-- Header & Toolbar Combined -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        
        <!-- Left: Title & Badge -->
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">Bookings</h1>
            <span class="px-2.5 py-0.5 rounded-md bg-yellow-50 text-yellow-700 text-xs font-bold border border-yellow-100">
                {{ bookings.length }} Found
            </span>
        </div>

        <!-- Center: Search Pill -->
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input 
                type="text" 
                v-model="searchQuery"
                placeholder="Search (Ctrl+K)" 
                class="block w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-full text-sm placeholder-gray-500 focus:outline-none focus:bg-white focus:border-gray-300 focus:ring-0 transition-all hover:bg-gray-100"
            >
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2 w-full md:w-auto">
             <button class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                 Filter
             </button>
             <button class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                 Sort
             </button>
        </div>
    </div>

    <!-- API Error -->
    <div v-if="apiError" class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-5 py-3 rounded-xl">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ apiError }}
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center items-center py-12">
        <svg class="w-8 h-8 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
    </div>

    <!-- Table Container -->
    <div v-else class="overflow-x-auto rounded-lg border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100">
                    <th class="p-4 w-12">
                         <input type="checkbox" v-model="allChecked" @change="toggleAll" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Booking Ref</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer Name</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Route Info</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Seats</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <tr v-for="booking in filteredBookings" :key="booking.id" class="hover:bg-gray-50/50 transition-colors group">
                    <td class="p-4">
                        <input type="checkbox" v-model="booking.checked" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-900">{{ booking.booking_reference }}</td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <!-- Avatar Placeholder -->
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 mr-3">
                                {{ getInitials(booking.primary_passenger_name || 'U N') }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-700">{{ booking.primary_passenger_name }}</span>
                                <span class="text-xs text-gray-500">{{ booking.primary_passenger_phone }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        {{ booking.schedule?.travel_date }}<br>
                        <span class="text-xs text-blue-600">{{ booking.schedule?.departure_time }}</span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <span class="p-1 rounded border border-gray-200 bg-white">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            </span>
                             <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-700">{{ booking.schedule?.route?.source_city }} &rarr; {{ booking.schedule?.route?.destination_city }}</span>
                                <span class="text-xs text-gray-400">{{ booking.schedule?.bus?.bus_name }} ({{ booking.schedule?.bus?.bus_type }})</span>
                             </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex gap-1 flex-wrap">
                            <span v-for="seat in booking.seats" :key="seat.id" class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                {{ seat.seat?.seat_number }}
                            </span>
                        </div>
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-900">৳{{ booking.total_price }}</td>
                    <td class="p-4">
                         <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium capitalize" :class="getStatusStyles(booking.booking_status)">
                            <svg class="w-2 h-2 mr-1.5" :class="getStatusIconColor(booking.booking_status)" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                            {{ booking.booking_status }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button class="text-gray-400 hover:text-blue-600 transition-colors" title="Download Ticket">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </button>
                            <button class="text-gray-400 hover:text-red-600 transition-colors" title="Delete Booking">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="filteredBookings.length === 0">
                    <td colspan="9" class="p-8 text-center text-gray-500">
                        No platform bookings found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-between items-center">
         <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
         </button>
         
         <div class="flex gap-2">
             <button class="w-8 h-8 flex items-center justify-center rounded-lg text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors">1</button>
             <button class="w-8 h-8 flex items-center justify-center rounded-lg text-sm bg-gray-100 font-bold text-gray-900 shadow-sm">2</button>
             <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-sm">...</span>
             <button class="w-8 h-8 flex items-center justify-center rounded-lg text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors">7</button>
             <button class="w-8 h-8 flex items-center justify-center rounded-lg text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors">8</button>
         </div>

         <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
         </button>
    </div>

  </div>
</template>
