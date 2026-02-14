<script setup>
import { ref, computed } from 'vue'
import AdminTable from '@/components/AdminTable.vue'
import AdminButton from '@/components/AdminButton.vue'

const routes = ref([
  { id: 101, from: 'Dhaka', to: 'Chittagong', distance: '265 km', time: '5h 30m', status: 'Active' },
  { id: 102, from: 'Dhaka', to: 'Sylhet', distance: '240 km', time: '5h 00m', status: 'Active' },
  { id: 103, from: 'Dhaka', to: 'Cox\'s Bazar', distance: '395 km', time: '8h 45m', status: 'Active' },
  { id: 104, from: 'Dhaka', to: 'Khulna', distance: '180 km', time: '4h 15m', status: 'Inactive' },
])

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
  <div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
       <div>
           <h2 class="text-2xl font-bold text-gray-800">Manage Routes</h2>
           <p class="text-gray-500 text-sm">Create and manage bus routes and destinations.</p>
       </div>
       <AdminButton variant="primary" class="mt-4 md:mt-0">
           <template #icon>
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
           </template>
           Add New Route
       </AdminButton>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4">
        <div class="relative flex-grow">
            <input v-model="searchQuery" type="text" placeholder="Search routes (e.g. Dhaka)..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <select class="border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 text-gray-600">
            <option>All Status</option>
            <option>Active</option>
            <option>Inactive</option>
        </select>
    </div>

    <!-- Routes Table -->
     <AdminTable :columns="columns" :data="filteredRoutes" :itemsPerPage="10">
        <template #cell-route_info="{ item }">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-gray-800">{{ item.from }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                <span class="font-bold text-gray-800">{{ item.to }}</span>
            </div>
        </template>

        <template #cell-status="{ item }">
             <span :class="{
                'bg-emerald-100 text-emerald-700 border border-emerald-200': item.status === 'Active',
                'bg-gray-100 text-gray-600 border border-gray-200': item.status === 'Inactive'
            }" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm">
                {{ item.status }}
            </span>
        </template>

        <template #cell-actions="{ item }">
            <div class="flex justify-end space-x-2">
                <AdminButton variant="icon" title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </AdminButton>
                <AdminButton variant="icon" class="text-red-400 hover:text-red-600 hover:bg-red-50" @click="deleteRoute(item.id)" title="Delete">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </AdminButton>
            </div>
        </template>
    </AdminTable>
  </div>
</template>
