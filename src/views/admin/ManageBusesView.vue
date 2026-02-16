<script setup>
import { ref, computed } from 'vue'

const buses = ref([
 {
    id: 'BUS-24612474',
    operator: 'Green Line Paribahan',
    regNo: 'DHA-GA-1234',
    route: 'Dhaka - Chittagong',
    type: 'AC Business Class',
    seats: 36,
    price: 1500,
    departure: '08:30 AM',
    status: 'Active',
    checked: false
  },
  {
    id: 'BUS-24536474',
    operator: 'Hanif Enterprise',
    regNo: 'DHA-BA-5678',
    route: 'Dhaka - Cox\'s Bazar',
    type: 'Non-AC',
    seats: 48,
    price: 800,
    departure: '11:00 PM',
    status: 'Active',
    checked: false
  },
  {
    id: 'BUS-26466374',
    operator: 'Ena Transport',
    regNo: 'DHA-KA-9012',
    route: 'Dhaka - Sylhet',
    type: 'AC Economy',
    seats: 40,
    price: 1200,
    departure: '07:15 AM',
    status: 'Maintenance',
    checked: false
  },
  {
    id: 'BUS-24655532',
    operator: 'Shamoli Nr',
    regNo: 'DHA-PA-3456',
    route: 'Chittagong - Dhaka',
    type: 'Non-AC',
    seats: 50,
    price: 650,
    departure: '03:00 PM',
    status: 'Active',
    checked: false
  },
  {
    id: 'BUS-64642415',
    operator: 'Shohagh Paribahan',
    regNo: 'DHA-HA-7890',
    route: 'Dhaka - Khulna',
    type: 'AC Sleeper',
    seats: 30,
    price: 1800,
    departure: '09:00 PM',
    status: 'Active',
    checked: false
  }
])

const searchQuery = ref('')
const allChecked = ref(false)

const toggleAll = () => {
    buses.value.forEach(b => b.checked = allChecked.value)
}

const getStatusStyles = (status) => {
    if (status === 'Active') {
        return 'bg-emerald-50 text-emerald-700'
    } else if (status === 'Maintenance') {
        return 'bg-amber-50 text-amber-700'
    } else if (status === 'Retired') {
        return 'bg-rose-50 text-rose-700'
    } else {
        return 'bg-gray-50 text-gray-700'
    }
}

const getStatusIconColor = (status) => {
     if (status === 'Active') {
        return 'text-emerald-500'
    } else if (status === 'Maintenance') {
        return 'text-amber-500'
    } else if (status === 'Retired') {
        return 'text-rose-500'
    } else {
        return 'text-gray-500'
    }
}

const deleteBus = (id) => {
    if(confirm('Are you sure you want to delete this bus?')) {
        buses.value = buses.value.filter(bus => bus.id !== id)
    }
}
</script>

<template>
  <div class="p-8 max-w-[1600px] mx-auto font-sans text-slate-800 bg-white">
    
    <!-- Header & Toolbar Combined -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        
        <!-- Left: Title & Badge -->
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">Buses</h1>
            <span class="px-2.5 py-0.5 rounded-md bg-yellow-50 text-yellow-700 text-xs font-bold border border-yellow-100">
                {{ buses.length }} Buses
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
             <button class="flex items-center px-4 py-2 bg-black text-white rounded-full text-sm font-medium hover:bg-gray-800 transition-colors shadow-sm ml-1">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 Add Bus
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
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bus ID</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Operator Info</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Route</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type/Seats</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Departure</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <tr v-for="bus in buses" :key="bus.id" class="hover:bg-gray-50/50 transition-colors group">
                    <td class="p-4">
                        <input type="checkbox" v-model="bus.checked" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-900">{{ bus.id }}</td>
                    <td class="p-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-800">{{ bus.operator }}</span>
                            <span class="text-xs text-gray-400">{{ bus.regNo }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                         {{ bus.route }}
                    </td>
                    <td class="p-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-700">{{ bus.type }}</span>
                            <span class="text-xs text-indigo-500 font-medium">{{ bus.seats }} Seats</span>
                        </div>
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-900">
                        {{ bus.departure }}
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-indigo-700 bg-indigo-50 px-2 py-1 rounded inline-block text-sm">৳{{ bus.price }}</div>
                    </td>
                    <td class="p-4">
                         <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium" :class="getStatusStyles(bus.status)">
                            <svg class="w-2 h-2 mr-1.5" :class="getStatusIconColor(bus.status)" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                            {{ bus.status }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit Bus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button class="text-gray-400 hover:text-red-600 transition-colors" @click="deleteBus(bus.id)" title="Delete Bus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

  </div>
</template>
