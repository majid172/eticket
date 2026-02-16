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
        return 'bg-emerald-50 text-emerald-600 border-emerald-200'
    } else if (status === 'Maintenance') {
        return 'bg-amber-50 text-amber-600 border-amber-200'
    } else if (status === 'Retired') {
        return 'bg-rose-50 text-rose-600 border-rose-200'
    } else {
        return 'bg-gray-50 text-gray-600 border-gray-200'
    }
}

const getStatusIconColor = (status) => {
     if (status === 'Active') {
        return 'bg-emerald-500'
    } else if (status === 'Maintenance') {
        return 'bg-amber-500'
    } else if (status === 'Retired') {
        return 'bg-rose-500'
    } else {
        return 'bg-gray-500'
    }
}

const deleteBus = (id) => {
    if(confirm('Are you sure you want to delete this bus?')) {
        buses.value = buses.value.filter(bus => bus.id !== id)
    }
}
</script>

<template>
  <div class="p-6 max-w-[1920px] mx-auto min-h-screen">
    
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Buses Management</h2>
            <div class="flex items-center mt-1 gap-2 text-sm text-slate-500">
                <span>Total Buses: <span class="font-bold text-slate-900">{{ buses.length }}</span></span>
                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                <span class="text-emerald-600 font-medium">Active: {{ buses.filter(b => b.status === 'Active').length }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
             <div class="relative flex-grow md:flex-grow-0">
                <input 
                    type="text" 
                    v-model="searchQuery"
                    placeholder="Search buses..." 
                    class="pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm w-full md:w-64 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-shadow"
                >
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
             <button class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                 Filter
             </button>

             <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors flex items-center gap-2 shadow-sm">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 Add Bus
             </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="p-4 w-12 pl-6">
                             <input type="checkbox" v-model="allChecked" @change="toggleAll" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bus Info</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Route</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type & Seats</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Departure</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr v-for="bus in buses" :key="bus.id" class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-4 pl-6">
                            <input type="checkbox" v-model="bus.checked" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900">{{ bus.operator }}</span>
                                <span class="text-xs text-gray-500 font-mono mt-0.5">{{ bus.regNo }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                             <div class="flex items-center gap-2">
                                <span class="p-1.5 rounded-md bg-gray-100 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-1.447-.894L15 7m0 13V7"></path></svg>
                                </span>
                                <span class="text-sm font-medium text-gray-700">{{ bus.route }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ bus.type }}</span>
                                <span class="text-xs text-indigo-600 font-medium mt-0.5">{{ bus.seats }} Seats</span>
                            </div>
                        </td>
                        <td class="p-4 text-sm font-medium text-gray-600">
                            {{ bus.departure }}
                        </td>
                        <td class="p-4">
                            <span class="font-bold text-slate-800 text-sm">৳{{ bus.price }}</span>
                        </td>
                        <td class="p-4">
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border" :class="getStatusStyles(bus.status)">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="getStatusIconColor(bus.status)"></span>
                                {{ bus.status }}
                            </span>
                        </td>
                        <td class="p-4 text-right pr-6">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Bus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" @click="deleteBus(bus.id)" title="Delete Bus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

  </div>
</template>
