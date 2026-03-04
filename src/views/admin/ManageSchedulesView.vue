<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'

const schedulesList = ref([])
const loading       = ref(false)
const apiError      = ref('')
const searchQuery   = ref('')

async function fetchSchedules() {
  loading.value  = true
  apiError.value = ''
  try {
    const { data } = await api.get('/admin/schedules')
    // Assuming backend returns paginated array
    schedulesList.value = data.data || data
  } catch (err) {
    apiError.value = err.response?.data?.message || 'Failed to load platform schedules.'
  } finally {
    loading.value = false
  }
}

onMounted(fetchSchedules)

const filteredSchedules = computed(() => {
  let result = schedulesList.value
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(s =>
      (s.route?.source_city || '').toLowerCase().includes(q) ||
      (s.route?.destination_city || '').toLowerCase().includes(q) ||
      (s.bus?.bus_number || '').toLowerCase().includes(q)
    )
  }
  return result
})
</script>

<template>
  <div class="p-8 max-w-[1600px] mx-auto font-sans text-slate-800 bg-white">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">Platform Schedules</h1>
            <span class="px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100">
                {{ schedulesList.length }} Schedules
            </span>
        </div>
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Search schedules..." 
                class="block w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-full text-sm placeholder-gray-500 focus:outline-none focus:bg-white focus:border-gray-300 focus:ring-0 transition-all hover:bg-gray-100"
            >
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
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Route Info</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bus & Price</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <tr v-for="s in filteredSchedules" :key="s.id" class="hover:bg-gray-50/50 transition-colors group">
                    <td class="p-4">
                        <span class="text-sm font-bold text-gray-900 block">{{ s.route?.source_city }} &rarr; {{ s.route?.destination_city }}</span>
                    </td>
                    <td class="p-4">
                        <span class="text-sm font-medium text-gray-800 block">{{ s.travel_date }}</span>
                        <span class="text-xs text-emerald-600 block mt-0.5">{{ s.departure_time }} - {{ s.arrival_time }}</span>
                    </td>
                    <td class="p-4">
                        <span class="text-sm font-medium text-gray-800 block">{{ s.bus?.bus_number }}</span>
                        <span class="text-xs text-gray-500 block">৳{{ s.base_price }}</span>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize"
                              :class="s.status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 
                                      s.status === 'completed' ? 'bg-blue-50 text-blue-600 border-blue-200' :
                                      'bg-rose-50 text-rose-600 border-rose-200'">
                          {{ s.status }}
                        </span>
                    </td>
                </tr>
                <tr v-if="filteredSchedules.length === 0">
                    <td colspan="4" class="p-8 text-center text-gray-500">
                        No schedules found on the platform.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>
