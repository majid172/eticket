<script setup>
import { ref, computed } from 'vue'

const bookings = ref([
  {
    id: 'BK-24612474-53',
    customer: { name: 'Kathryn Murphy', avatar: '' },
    date: '03/25/24',
    time: '18:45',
    route: { name: 'Dhaka - Chittagong', type: 'AC Business' },
    seats: ['A1', 'A2'],
    amount: '234.00',
    status: 'Pending',
    checked: false
  },
  {
    id: 'BK-24536474-45',
    customer: { name: 'Floyd Miles', avatar: '' },
    date: '03/25/24',
    time: '12:30',
    route: { name: 'Sylhet - Dhaka', type: 'Non-AC' },
    amount: '1,784.00',
    status: 'Completed',
    checked: false
  },
  {
    id: 'BK-26466374-44',
    customer: { name: 'Courtney Henry', avatar: '' },
    date: '03/24/24',
    time: '15:20',
    route: { name: 'Dhaka - Coxs Bazar', type: 'Sleeper' },
    amount: '24.50',
    status: 'Completed',
    checked: false
  },
  {
    id: 'BK-24655532-11',
    customer: { name: 'Cody Fisher', avatar: '' },
    date: '03/23/24',
    time: '10:55',
    route: { name: 'Chittagong - Dhaka', type: 'AC Economy' },
    amount: '158.00',
    status: 'Completed',
    checked: false
  },
  {
    id: 'BK-64642415-23',
    customer: { name: 'Darlene Robertson', avatar: '' },
    date: '03/23/24',
    time: '04:30',
    route: { name: 'Dhaka - Rajshahi', type: 'AC Business' },
    amount: '785.00',
    status: 'Completed',
    checked: false
  },
  {
    id: 'BK-64641474-51',
    customer: { name: 'Albert Flores', avatar: '' },
    date: '03/22/24',
    time: '17:15',
    route: { name: 'Khulna - Dhaka', type: 'Non-AC' },
    amount: '88.95',
    status: 'Completed',
    checked: false
  },
  {
    id: 'BK-24242474-63',
    customer: { name: 'Devon Lane', avatar: '' },
    date: '03/22/24',
    time: '11:40',
    route: { name: 'Dhaka - Barisal', type: 'AC Business' },
    amount: '88.95',
    status: 'Completed',
    checked: false
  },
   {
    id: 'BK-24612424-12',
    customer: { name: 'Darrell Steward', avatar: '' },
    date: '03/21/24',
    time: '14:05',
    route: { name: 'Rangpur - Dhaka', type: 'AC Economy' },
    amount: '34.05',
    status: 'Completed',
    checked: false
  },
  {
    id: 'BK-24615374-53',
    customer: { name: 'Savannah Nguyen', avatar: '' },
    date: '03/21/24',
    time: '09:20',
    route: { name: 'Dhaka - Sylhet', type: 'Sleeper' },
    amount: '6,534.53',
    status: 'Completed',
    checked: false
  },
  {
    id: 'BK-24451474-32',
    customer: { name: 'Arlene McCoy', avatar: '' },
    date: '03/21/24',
    time: '09:15',
    route: { name: 'Cumilla - Dhaka', type: 'Non-AC' },
    amount: '834.00',
    status: 'Completed',
    checked: false
  }
])

const searchQuery = ref('')
const currentPage = ref(2) // Matching the screenshot example
const allChecked = ref(false)

const getInitials = (name) => {
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
}

const toggleAll = () => {
    bookings.value.forEach(b => b.checked = allChecked.value)
}

const getStatusStyles = (status) => {
    if (status === 'Pending') {
        return 'bg-yellow-50 text-yellow-700'
    } else if (status === 'Completed') {
        return 'bg-green-50 text-green-700'
    } else {
        return 'bg-gray-50 text-gray-700'
    }
}

const getStatusIconColor = (status) => {
     if (status === 'Pending') {
        return 'text-yellow-500'
    } else if (status === 'Completed') {
        return 'text-green-500'
    } else {
        return 'text-gray-500'
    }
}

</script>

<template>
  <div class="p-8 max-w-[1600px] mx-auto font-sans text-slate-800 bg-white min-h-screen">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Bookings</h1>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <!-- Search -->
        <div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input 
                type="text" 
                v-model="searchQuery"
                placeholder="Search" 
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow"
            >
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 w-full md:w-auto">
             <button class="flex items-center px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                 <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                 Filters
             </button>
             <button class="flex items-center px-4 py-2.5 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors shadow-sm">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 Add Booking
             </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto rounded-lg border border-gray-100">
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
                <tr v-for="booking in bookings" :key="booking.id" class="hover:bg-gray-50/50 transition-colors group">
                    <td class="p-4">
                        <input type="checkbox" v-model="booking.checked" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-900">{{ booking.id }}</td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <!-- Avatar Placeholder -->
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 mr-3">
                                {{ getInitials(booking.customer.name) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ booking.customer.name }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        {{ booking.date }} - {{ booking.time }}
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <span class="p-1 rounded border border-gray-200 bg-white">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            </span>
                             <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-700">{{ booking.route.name }}</span>
                                <span class="text-xs text-gray-400">{{ booking.route.type }}</span>
                             </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex gap-1 flex-wrap">
                            <span v-for="seat in booking.seats" :key="seat" class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                {{ seat || 'General' }}
                            </span>
                        </div>
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-900">৳{{ booking.amount }}</td>
                    <td class="p-4">
                         <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium" :class="getStatusStyles(booking.status)">
                            <svg class="w-2 h-2 mr-1.5" :class="getStatusIconColor(booking.status)" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                            {{ booking.status }}
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
