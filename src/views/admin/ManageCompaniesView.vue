<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'

const companiesList = ref([])
const loading       = ref(false)
const apiError      = ref('')
const searchQuery   = ref('')

async function fetchCompanies() {
  loading.value  = true
  apiError.value = ''
  try {
    const { data } = await api.get('/admin/companies')
    // Assuming backend returns paginated response
    companiesList.value = data.data || data
  } catch (err) {
    apiError.value = err.response?.data?.message || 'Failed to load companies.'
  } finally {
    loading.value = false
  }
}

onMounted(fetchCompanies)

const filteredCompanies = computed(() => {
  let result = companiesList.value
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(c =>
      (c.company_name || '').toLowerCase().includes(q) ||
      (c.contact_email || '').toLowerCase().includes(q)
    )
  }
  return result
})

async function updateStatus(id, newStatus) {
    if(!confirm(`Are you sure you want to mark this company as ${newStatus}?`)) return;
    try {
        await api.put(`/admin/companies/${id}/status`, { status: newStatus })
        await fetchCompanies()
    } catch(err) {
        alert(err.response?.data?.message || "Failed to update status")
    }
}
</script>

<template>
  <div class="p-8 max-w-[1600px] mx-auto font-sans text-slate-800 bg-white">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">Manage Companies</h1>
            <span class="px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100">
                {{ companiesList.length }} Companies
            </span>
        </div>
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Search by name or email..." 
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
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Company Info</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact details</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Operator</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <tr v-for="c in filteredCompanies" :key="c.id" class="hover:bg-gray-50/50 transition-colors group">
                    <td class="p-4">
                        <span class="text-sm font-bold text-gray-900 block">{{ c.company_name }}</span>
                        <span class="text-xs text-gray-500">Reg: {{ c.registration_number || 'N/A' }}</span>
                    </td>
                    <td class="p-4">
                        <span class="text-sm text-gray-700 block">{{ c.contact_email }}</span>
                        <span class="text-xs text-gray-500">{{ c.contact_phone }}</span>
                    </td>
                    <td class="p-4">
                        <span class="text-sm font-medium text-gray-700">{{ c.user?.name || 'Unknown' }}</span>
                    </td>
                    <td class="p-4">
                         <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium capitalize" 
                               :class="{'bg-green-50 text-green-700': c.status === 'active', 
                                        'bg-red-50 text-red-700': c.status === 'blocked',
                                        'bg-yellow-50 text-yellow-700': c.status === 'pending'
                                       }">
                            {{ c.status }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <select @change="updateStatus(c.id, $event.target.value)" 
                                class="text-xs border rounded p-1 text-gray-600 bg-white shadow-sm focus:outline-none">
                            <option value="" disabled selected>Change Status</option>
                            <option value="active" v-if="c.status !== 'active'">Activate</option>
                            <option value="blocked" v-if="c.status !== 'blocked'">Block</option>
                        </select>
                    </td>
                </tr>
                <tr v-if="filteredCompanies.length === 0">
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        No companies found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>
