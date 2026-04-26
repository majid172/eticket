<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'

// ── State ────────────────────────────────────────────────────────────────────
const subscriptions  = ref([])
const loading        = ref(false)
const apiError       = ref('')
const searchQuery    = ref('')
const filterStatus   = ref('all')
const actionLoading  = ref(null) // tracks which row is loading

// ── Fetch ─────────────────────────────────────────────────────────────────────
async function fetchSubscriptions() {
  loading.value  = true
  apiError.value = ''
  try {
    const { data } = await api.get('/admin/subscriptions')
    subscriptions.value = data.data ?? data
  } catch (err) {
    apiError.value = err.response?.data?.message || 'Failed to load subscriptions.'
  } finally {
    loading.value = false
  }
}

onMounted(fetchSubscriptions)

// ── Computed ──────────────────────────────────────────────────────────────────
const filtered = computed(() => {
  let list = subscriptions.value
  if (filterStatus.value !== 'all') {
    list = list.filter(s => s.subscription_status === filterStatus.value)
  }
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(s =>
      (s.company_name || '').toLowerCase().includes(q) ||
      (s.user?.name  || '').toLowerCase().includes(q)
    )
  }
  return list
})

const summaryStats = computed(() => ({
  total:    subscriptions.value.length,
  active:   subscriptions.value.filter(s => s.subscription_status === 'active').length,
  expiring: subscriptions.value.filter(s => s.subscription_status === 'expiring').length,
  overdue:  subscriptions.value.filter(s => s.subscription_status === 'overdue').length,
  monthlyRevenue: subscriptions.value
    .filter(s => s.subscription_status === 'active')
    .reduce((sum, s) => sum + (parseFloat(s.monthly_fee) || 0), 0)
    .toFixed(0)
}))

// ── Actions ───────────────────────────────────────────────────────────────────
async function updateSubscriptionStatus(companyId, newStatus) {
  if (!confirm(`Set subscription to "${newStatus}" for this company?`)) return
  actionLoading.value = companyId
  try {
    await api.put(`/admin/companies/${companyId}/subscription`, { subscription_status: newStatus })
    await fetchSubscriptions()
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to update subscription.')
  } finally {
    actionLoading.value = null
  }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
const statusConfig = {
  active:   { label: 'Active',   cls: 'bg-emerald-50 text-emerald-700 border-emerald-200', dot: 'bg-emerald-500' },
  expiring: { label: 'Expiring', cls: 'bg-amber-50 text-amber-700 border-amber-200',       dot: 'bg-amber-500'   },
  overdue:  { label: 'Overdue',  cls: 'bg-rose-50 text-rose-700 border-rose-200',          dot: 'bg-rose-500'    },
  inactive: { label: 'Inactive', cls: 'bg-gray-100 text-gray-600 border-gray-200',         dot: 'bg-gray-400'    },
}
const getStatus = (s) => statusConfig[s] ?? statusConfig.inactive

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A'
</script>

<template>
  <div class="p-8 max-w-[1600px] mx-auto font-sans text-slate-800 bg-white">

    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Subscription Billing</h1>
        <p class="text-sm text-gray-500 mt-1">Manage operator subscriptions · monthly revenue · suspend on non-payment</p>
      </div>
      <button @click="fetchSubscriptions" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Refresh
      </button>
    </div>

    <!-- Revenue Model Notice -->
    <div class="mb-6 bg-slate-900 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div class="flex items-start gap-3">
        <div class="w-9 h-9 bg-indigo-500/20 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
          <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <p class="text-white font-semibold text-sm">Admin Revenue Model</p>
          <p class="text-slate-400 text-xs mt-0.5">Monthly subscription fee per active operator company · No per-ticket cut · Operator keeps 100% of ticket sales</p>
        </div>
      </div>
      <div class="text-right shrink-0">
        <p class="text-xs text-slate-500 uppercase tracking-wider">Monthly Revenue</p>
        <p class="text-2xl font-black text-white">৳{{ summaryStats.monthlyRevenue }}</p>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 cursor-pointer hover:shadow-md transition-shadow" @click="filterStatus = 'all'">
        <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div><p class="text-xs text-gray-500 font-medium">Total Companies</p><p class="text-2xl font-bold text-gray-900">{{ summaryStats.total }}</p></div>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 cursor-pointer hover:shadow-md transition-shadow" @click="filterStatus = 'active'">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div><p class="text-xs text-gray-500 font-medium">Active</p><p class="text-2xl font-bold text-gray-900">{{ summaryStats.active }}</p></div>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 cursor-pointer hover:shadow-md transition-shadow" @click="filterStatus = 'expiring'">
        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div><p class="text-xs text-gray-500 font-medium">Expiring Soon</p><p class="text-2xl font-bold text-gray-900">{{ summaryStats.expiring }}</p></div>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 cursor-pointer hover:shadow-md transition-shadow" @click="filterStatus = 'overdue'">
        <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div><p class="text-xs text-gray-500 font-medium">Overdue</p><p class="text-2xl font-bold text-gray-900">{{ summaryStats.overdue }}</p></div>
      </div>
    </div>

    <!-- Error -->
    <div v-if="apiError" class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-5 py-3 rounded-xl">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ apiError }}
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <!-- Toolbar -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4 border-b border-gray-100">
        <div class="flex items-center gap-2 flex-wrap">
          <button v-for="opt in ['all','active','expiring','overdue']" :key="opt"
            @click="filterStatus = opt"
            :class="filterStatus === opt ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
            class="px-3 py-1.5 rounded-lg text-xs font-semibold capitalize transition-colors">
            {{ opt === 'all' ? 'All' : opt.charAt(0).toUpperCase() + opt.slice(1) }}
          </button>
        </div>
        <div class="relative">
          <input v-model="searchQuery" type="text" placeholder="Search company or operator..."
            class="pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm w-full sm:w-72 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-shadow">
          <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-16">
        <div class="flex items-center gap-3 text-indigo-600">
          <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
          <span class="text-sm font-medium">Loading subscriptions...</span>
        </div>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/60 border-b border-gray-100">
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Company</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Operator</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Monthly Fee</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Expires</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Last Payment</th>
              <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="s in filtered" :key="s.id" class="hover:bg-gray-50/40 transition-colors">
              <td class="px-6 py-4">
                <p class="text-sm font-bold text-gray-900">{{ s.company_name }}</p>
                <p class="text-xs text-gray-500">{{ s.registration_number || '—' }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-medium text-gray-800">{{ s.user?.name || '—' }}</p>
                <p class="text-xs text-gray-400">{{ s.user?.email || '' }}</p>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border capitalize"
                  :class="getStatus(s.subscription_status).cls">
                  <span class="w-1.5 h-1.5 rounded-full" :class="getStatus(s.subscription_status).dot"></span>
                  {{ getStatus(s.subscription_status).label }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-semibold text-gray-900">৳{{ s.monthly_fee ?? '—' }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm text-gray-600">{{ formatDate(s.subscription_expires_at) }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm text-gray-600">{{ formatDate(s.last_payment_at) }}</span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <!-- Suspend (overdue / active) -->
                  <button v-if="s.subscription_status !== 'inactive'"
                    @click="updateSubscriptionStatus(s.id, 'inactive')"
                    :disabled="actionLoading === s.id"
                    class="px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-colors disabled:opacity-50"
                    title="Suspend subscription">
                    <svg v-if="actionLoading === s.id" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span v-else>Suspend</span>
                  </button>
                  <!-- Activate (inactive / overdue) -->
                  <button v-if="s.subscription_status !== 'active'"
                    @click="updateSubscriptionStatus(s.id, 'active')"
                    :disabled="actionLoading === s.id"
                    class="px-3 py-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition-colors disabled:opacity-50">
                    <svg v-if="actionLoading === s.id" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span v-else>Activate</span>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="filtered.length === 0">
              <td colspan="7" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                  </div>
                  <p class="text-gray-500 font-medium">No subscriptions found</p>
                  <p class="text-sm text-gray-400">Try changing the filter or search term.</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Monthly Revenue Summary Footer -->
    <div class="mt-6 bg-gray-50 rounded-2xl border border-gray-100 p-6">
      <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Monthly Revenue Summary</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div>
          <p class="text-xs text-gray-500">Active Subscriptions</p>
          <p class="text-lg font-bold text-gray-900">{{ summaryStats.active }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-500">Projected MRR</p>
          <p class="text-lg font-bold text-emerald-600">৳{{ summaryStats.monthlyRevenue }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-500">At Risk (Expiring)</p>
          <p class="text-lg font-bold text-amber-600">{{ summaryStats.expiring }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-500">Non-Payment (Overdue)</p>
          <p class="text-lg font-bold text-rose-600">{{ summaryStats.overdue }}</p>
        </div>
      </div>
    </div>

  </div>
</template>
