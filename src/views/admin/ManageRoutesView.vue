<script setup>
import { ref, computed } from 'vue'
import AdminTable from '@/components/AdminTable.vue'
import AdminButton from '@/components/AdminButton.vue'

const routes = ref([
  { id: 101, from: 'Dhaka', to: 'Chittagong', distance: '265 km', time: '5h 30m', status: 'Active', checked: false },
  { id: 102, from: 'Dhaka', to: 'Sylhet', distance: '240 km', time: '5h 00m', status: 'Active', checked: false },
  { id: 103, from: 'Dhaka', to: 'Cox\'s Bazar', distance: '395 km', time: '8h 45m', status: 'Active', checked: false },
  { id: 104, from: 'Dhaka', to: 'Khulna', distance: '180 km', time: '4h 15m', status: 'Inactive', checked: false },
])

const allChecked = ref(false)

const toggleAll = () => {
    routes.value.forEach(r => r.checked = allChecked.value)
}

const columns = [
    { key: 'id', label: 'ID', class: 'font-semibold text-gray-700 w-24' },
    { key: 'route_info', label: 'Route Name' },
    { key: 'distance', label: 'Distance', class: 'text-gray-600' },
    { key: 'time', label: 'Est. Time', class: 'text-gray-600' },
    { key: 'status', label: 'Status' },
    { key: 'actions', label: 'Actions', class: 'text-right' }
]

const searchQuery = ref('')

const filteredRoutes = computed(() => {
    if (!searchQuery.value) return routes.value
    const query = searchQuery.value.toLowerCase()
    return routes.value.filter(route => 
        route.from.toLowerCase().includes(query) || 
        route.to.toLowerCase().includes(query)
    )
})

const deleteRoute = (id) => {
    if(confirm('Are you sure you want to delete this route?')) {
        routes.value = routes.value.filter(route => route.id !== id)
    }
}
</script>

<template>
  <div class="p-8 max-w-[1600px] mx-auto font-sans text-slate-800 bg-white ">
    
    <!-- Header & Toolbar Combined -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        
        <!-- Left: Title & Badge -->
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">Routes</h1>
            <span class="px-2.5 py-0.5 rounded-md bg-yellow-50 text-yellow-700 text-xs font-bold border border-yellow-100">
                {{ routes.length }} Routes
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
             <RouterLink to="/admin/routes/create" class="flex items-center px-4 py-2 bg-black text-white rounded-full text-sm font-medium hover:bg-gray-800 transition-colors shadow-sm ml-1">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 Add Route
             </RouterLink>
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
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Route Details</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Distance</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Est. Time</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <tr v-for="route in filteredRoutes" :key="route.id" class="hover:bg-gray-50/50 transition-colors group">
                    <td class="p-4">
                        <input type="checkbox" v-model="route.checked" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </td>
                    <td class="p-4 text-sm font-medium text-gray-900">{{ route.id }}</td>
                    <td class="p-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-semibold text-gray-800">{{ route.from }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            <span class="text-sm font-semibold text-gray-800">{{ route.to }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-600">{{ route.distance }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ route.time }}</td>
                    <td class="p-4">
                         <span :class="{
                            'bg-emerald-50 text-emerald-700': route.status === 'Active',
                            'bg-gray-50 text-gray-600': route.status === 'Inactive'
                        }" class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full" :class="route.status === 'Active' ? 'bg-emerald-500' : 'bg-gray-500'"></span>
                            {{ route.status }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button class="text-gray-400 hover:text-red-600 transition-colors" @click="deleteRoute(route.id)" title="Delete">
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

