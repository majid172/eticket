<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('all')
const searchQuery = ref('')
const allChecked = ref(false)

const tabs = [
    { id: 'all', name: 'All Buses', icon: 'M4 6h16M4 12h16M4 18h16' },
    { id: 'Active', name: 'Active', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
    { id: 'Maintenance', name: 'Maintenance', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
    { id: 'Retired', name: 'Retired', icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' }
]

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

const filteredBuses = computed(() => {
    let result = buses.value

    // Filter by Tab (Status)
    if (activeTab.value !== 'all') {
        result = result.filter(bus => bus.status === activeTab.value)
    }

    // Filter by Search
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        result = result.filter(bus => 
            bus.operator.toLowerCase().includes(query) ||
            bus.regNo.toLowerCase().includes(query) ||
            bus.route.toLowerCase().includes(query)
        )
    }

    return result
})

const toggleAll = () => {
    filteredBuses.value.forEach(b => b.checked = allChecked.value)
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
  <div class="bg-white font-sans text-gray-700">
    
    <!-- Top Breadcrumb -->
    <div class="px-8 py-4 border-b border-gray-100 flex items-center text-sm font-medium">
        <span class="text-gray-900">Buses</span>
        <span class="mx-2 text-blue-500">-></span>
        <span class="text-blue-500">{{ tabs.find(t => t.id === activeTab)?.name }}</span>
    </div>

    <div class="flex">
        
        <!-- Sidebar -->
        <div class="w-64 flex-shrink-0 border-r border-gray-100 py-6">
            <nav class="space-y-1">
                <button 
                    v-for="tab in tabs" 
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    class="w-full flex items-center px-6 py-3 text-sm font-medium transition-colors border-l-4"
                    :class="activeTab === tab.id ? 'border-blue-500 text-blue-500 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                >
                    <svg class="mr-3 flex-shrink-0 h-4 w-4" :class="activeTab === tab.id ? 'text-blue-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon"/>
                    </svg>
                    {{ tab.name }}
                </button>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="flex-1 bg-white p-8">
            
            <!-- Content Header -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                 <h2 class="text-lg font-bold text-gray-800">{{ tabs.find(t => t.id === activeTab)?.name }} List</h2>
                 
                 <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-grow md:flex-grow-0">
                        <input 
                            type="text" 
                            v-model="searchQuery"
                            placeholder="Search buses..." 
                            class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm w-full md:w-64 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-shadow"
                        >
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                   
                 </div>
            </div>

            <!-- Table -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="p-4 w-12 pl-6">
                                 <input type="checkbox" v-model="allChecked" @change="toggleAll" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Bus Info</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Route</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type & Seats</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Departure</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="bus in filteredBuses" :key="bus.id" class="hover:bg-gray-50/50 transition-colors group">
                            <td class="p-4 pl-6">
                                <input type="checkbox" v-model="bus.checked" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900">{{ bus.operator }}</span>
                                    <span class="text-xs text-gray-500 font-mono mt-0.5">{{ bus.regNo }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                 <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-700">{{ bus.route }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">{{ bus.type }}</span>
                                    <span class="text-xs text-blue-600 font-medium mt-0.5">{{ bus.seats }} Seats</span>
                                </div>
                            </td>
                            <td class="p-4 text-sm font-medium text-gray-600">
                                {{ bus.departure }}
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-800 text-sm">৳{{ bus.price }}</span>
                            </td>
                            <td class="p-4">
                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border" :class="getStatusStyles(bus.status)">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="getStatusIconColor(bus.status)"></span>
                                    {{ bus.status }}
                                </span>
                            </td>
                            <td class="p-4 text-right pr-6">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <button class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Bus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" @click="deleteBus(bus.id)" title="Delete Bus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredBuses.length === 0">
                            <td colspan="8" class="p-8 text-center text-gray-500">
                                No buses found matching your criteria.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
  </div>
</template>
