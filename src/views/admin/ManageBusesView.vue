<script setup>
import { ref, computed } from 'vue'
import AdminTable from '@/components/AdminTable.vue'

const buses = ref([
  { id: 1, operator: 'Green Line Paribahan', route: 'Dhaka - Chittagong', type: 'AC Business Class', seats: 36, price: 1500, departure: '08:30 AM', status: 'Active' },
  { id: 2, operator: 'Hanif Enterprise', route: 'Dhaka - Cox\'s Bazar', type: 'Non-AC', seats: 48, price: 800, departure: '11:00 PM', status: 'Active' },
  { id: 3, operator: 'Ena Transport', route: 'Dhaka - Sylhet', type: 'AC Economy', seats: 40, price: 1200, departure: '07:15 AM', status: 'Maintenance' },
  { id: 4, operator: 'Shamoli Nr', route: 'Chittagong - Dhaka', type: 'Non-AC', seats: 50, price: 650, departure: '03:00 PM', status: 'Active' },
  { id: 5, operator: 'Shohagh Paribahan', route: 'Dhaka - Khulna', type: 'AC Sleeper', seats: 30, price: 1800, departure: '09:00 PM', status: 'Active' },
])

const columns = [
    { key: 'id', label: 'ID', class: 'font-semibold text-gray-700' },
    { key: 'operator_info', label: 'Operator Info' },
    { key: 'route', label: 'Route', class: 'text-gray-600' },
    { key: 'type_info', label: 'Type/Seats' },
    { key: 'departure', label: 'Departure', class: 'text-gray-600 font-medium' },
    { key: 'price', label: 'Price' },
    { key: 'status', label: 'Status' },
    { key: 'actions', label: 'Actions', class: 'text-right' }
]

const searchQuery = ref('')

const filteredBuses = computed(() => {
    if (!searchQuery.value) return buses.value
    const query = searchQuery.value.toLowerCase()
    return buses.value.filter(bus => 
        bus.operator.toLowerCase().includes(query) || 
        bus.route.toLowerCase().includes(query)
    )
})

const deleteBus = (id) => {
    if(confirm('Are you sure you want to delete this bus?')) {
        buses.value = buses.value.filter(bus => bus.id !== id)
    }
}
</script>

<template>
  <div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
       <div>
           <h2 class="text-2xl font-bold text-gray-800">Manage Buses</h2>
           <p class="text-gray-500 text-sm">Add, edit, or remove buses from the fleet.</p>
       </div>
       <button class="mt-4 md:mt-0 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition-all flex items-center">
           <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
           Add New Bus
       </button>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4">
        <div class="relative flex-grow">
            <input v-model="searchQuery" type="text" placeholder="Search by operator or route..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <select class="border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 text-gray-600">
            <option>All Status</option>
            <option>Active</option>
            <option>Maintenance</option>
            <option>Retired</option>
        </select>
         <select class="border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 text-gray-600">
            <option>All Types</option>
            <option>AC</option>
            <option>Non-AC</option>
        </select>
    </div>

    <!-- Buses Table -->
     <AdminTable :columns="columns" :data="filteredBuses" :itemsPerPage="5">
        <template #cell-operator_info="{ item }">
            <div>
                <div class="font-bold text-gray-800">{{ item.operator }}</div>
                <div class="text-xs text-gray-500">Reg: DHA-GA-1234</div>
            </div>
        </template>

        <template #cell-type_info="{ item }">
            <div>
                <div class="text-sm font-medium text-gray-700">{{ item.type }}</div>
                <div class="text-xs text-indigo-500 font-medium">{{ item.seats }} Seats</div>
            </div>
        </template>

        <template #cell-price="{ item }">
             <div class="font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg inline-block">৳{{ item.price }}</div>
        </template>

        <template #cell-status="{ item }">
             <span :class="{
                'bg-emerald-100 text-emerald-700 border border-emerald-200': item.status === 'Active',
                'bg-amber-100 text-amber-700 border border-amber-200': item.status === 'Maintenance',
                'bg-rose-100 text-rose-700 border border-rose-200': item.status === 'Retired'
            }" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm">
                {{ item.status }}
            </span>
        </template>

        <template #cell-actions="{ item }">
            <div class="flex justify-end space-x-3">
                <button class="text-gray-400 hover:text-indigo-600 transition-colors transform hover:scale-110" title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </button>
                <button @click="deleteBus(item.id)" class="text-gray-400 hover:text-rose-600 transition-colors transform hover:scale-110" title="Delete">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </template>
    </AdminTable>
    
  </div>
</template>
