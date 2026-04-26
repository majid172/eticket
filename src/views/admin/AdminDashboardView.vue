<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import DashboardBarChart from '@/components/charts/DashboardBarChart.vue'
import DashboardDonutChart from '@/components/charts/DashboardDonutChart.vue'
import api from '@/services/api'

// ── State ─────────────────────────────────────────────────────────────────────
const loading        = ref(true)
const apiError       = ref('')
const dashboardData  = ref(null)

// ── Fetch ──────────────────────────────────────────────────────────────────────
async function fetchDashboard() {
  loading.value  = true
  apiError.value = ''
  try {
    const { data } = await api.get('/admin/dashboard')
    dashboardData.value = data
  } catch (err) {
    apiError.value = err.response?.data?.message || 'Failed to load dashboard data.'
  } finally {
    loading.value = false
  }
}

onMounted(fetchDashboard)

// ── Derived Stats Cards ────────────────────────────────────────────────────────
const statCards = computed(() => {
  const s = dashboardData.value?.stats ?? {}
  return [
    {
      title: 'Total Revenue',
      value: `৳ ${Number(s.total_revenue ?? 0).toLocaleString('en-IN')}`,
      sub: `Today: ৳ ${Number(s.today_revenue ?? 0).toLocaleString('en-IN')}`,
      icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
      iconBg: 'bg-indigo-50 text-indigo-600',
      badge: `${s.confirmed_bookings ?? 0} confirmed`,
      badgeClass: 'text-indigo-600 bg-indigo-50 border-indigo-100',
      link: '/admin/bookings',
    },
    {
      title: 'Total Bookings',
      value: Number(s.total_bookings ?? 0).toLocaleString(),
      sub: `Today: ${s.today_bookings ?? 0} new`,
      icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
      iconBg: 'bg-emerald-50 text-emerald-600',
      badge: `${s.pending_bookings ?? 0} pending`,
      badgeClass: 'text-amber-600 bg-amber-50 border-amber-100',
      link: '/admin/bookings',
    },
    {
      title: 'Platform Users',
      value: Number(s.total_users ?? 0).toLocaleString(),
      sub: `${s.total_operators ?? 0} operators`,
      icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
      iconBg: 'bg-purple-50 text-purple-600',
      badge: `${s.active_companies ?? 0} companies`,
      badgeClass: 'text-emerald-600 bg-emerald-50 border-emerald-100',
      link: '/admin/users',
    },
    {
      title: 'Total Buses',
      value: Number(s.total_buses ?? 0).toLocaleString(),
      sub: 'Platform-wide fleet',
      icon: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
      iconBg: 'bg-amber-50 text-amber-600',
      badge: `${s.active_companies ?? 0} operators`,
      badgeClass: 'text-amber-600 bg-amber-50 border-amber-100',
      link: '/admin/buses',
    },
  ]
})

// ── Bar Chart: Monthly Bookings ────────────────────────────────────────────────
const barChartData = computed(() => {
  const c = dashboardData.value?.chart ?? { labels: [], bookings: [], revenue: [] }
  return {
    labels: c.labels,
    datasets: [
      {
        label: 'Bookings',
        backgroundColor: '#2563EB',
        hoverBackgroundColor: '#1D4ED8',
        data: c.bookings,
        borderRadius: 4,
        barPercentage: 0.6,
        categoryPercentage: 0.7,
      },
    ],
  }
})

const barChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true, position: 'top', align: 'end',
      labels: { usePointStyle: true, pointStyle: 'rectRounded', boxWidth: 8, color: '#6B7280', font: { size: 12 } },
    },
    tooltip: {
      backgroundColor: '#FFFFFF', titleColor: '#1F2937', bodyColor: '#4B5563',
      borderColor: '#E5E7EB', borderWidth: 1, displayColors: false, padding: 12,
      callbacks: {
        title: () => 'Monthly Stats',
        label: (ctx) => `${ctx.dataset.label}: ${ctx.raw}`,
      },
    },
  },
  scales: {
    y: { border: { display: false }, grid: { display: true, borderDash: [4, 4], color: '#F3F4F6' }, ticks: { color: '#9CA3AF', font: { size: 11 } } },
    x: { border: { display: false }, grid: { display: false }, ticks: { color: '#9CA3AF', font: { size: 11 } } },
  },
}

// ── Donut Chart: Booking Status Distribution ───────────────────────────────────
const donutChartData = computed(() => {
  const d = dashboardData.value?.status_distribution ?? { confirmed: 0, pending: 0, cancelled: 0 }
  return {
    labels: ['Confirmed', 'Pending', 'Cancelled'],
    datasets: [{
      backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
      data: [d.confirmed, d.pending, d.cancelled],
      borderWidth: 0,
      hoverOffset: 4,
    }],
  }
})

const donutLegend = computed(() => {
  const d = dashboardData.value?.status_distribution ?? { confirmed: 0, pending: 0, cancelled: 0 }
  const total = (d.confirmed + d.pending + d.cancelled) || 1
  return [
    { label: 'Confirmed', color: '#10B981', count: d.confirmed, pct: Math.round(d.confirmed / total * 100) },
    { label: 'Pending',   color: '#F59E0B', count: d.pending,   pct: Math.round(d.pending   / total * 100) },
    { label: 'Cancelled', color: '#EF4444', count: d.cancelled, pct: Math.round(d.cancelled / total * 100) },
  ]
})

// ── Recent Bookings ────────────────────────────────────────────────────────────
const recentBookings = computed(() => dashboardData.value?.recent_bookings ?? [])

// ── Helpers ────────────────────────────────────────────────────────────────────
const statusClass = (status) => ({
  confirmed: 'text-emerald-600 bg-emerald-50 border-emerald-200',
  pending:   'text-amber-600 bg-amber-50 border-amber-200',
  cancelled: 'text-rose-600 bg-rose-50 border-rose-200',
})[status] ?? 'text-gray-600 bg-gray-50 border-gray-200'

const statusDot = (status) => ({
  confirmed: 'bg-emerald-500',
  pending:   'bg-amber-500',
  cancelled: 'bg-rose-500',
})[status] ?? 'bg-gray-400'

const avatarUrl = (name) =>
  `https://ui-avatars.com/api/?name=${encodeURIComponent(name || 'U')}&background=EFF6FF&color=2563EB&size=40`

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '—'
</script>

<template>
  <div class="p-6 max-w-[1920px] mx-auto min-h-screen">

    <!-- "Admin has no access to" Notice -->
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 flex items-start gap-3">
      <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <div>
        <p class="text-sm font-bold text-amber-800">Admin has no access to:</p>
        <p class="text-xs text-amber-700 mt-0.5">Schedules · individual booking management · cancellations — all owned by operator. <span class="font-semibold">Admin revenue = subscription fees only.</span></p>
      </div>
    </div>

    <!-- API Error -->
    <div v-if="apiError" class="mb-6 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-5 py-3 rounded-xl">
      <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ apiError }}
      <button @click="fetchDashboard" class="ml-auto text-xs font-bold underline">Retry</button>
    </div>

    <!-- Skeleton Loader -->
    <div v-if="loading" class="animate-pulse space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="i in 4" :key="i" class="bg-white rounded-2xl h-28 border border-gray-100 shadow-sm"></div>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl h-80 border border-gray-100 shadow-sm"></div>
        <div class="bg-white rounded-2xl h-80 border border-gray-100 shadow-sm"></div>
      </div>
      <div class="bg-white rounded-2xl h-64 border border-gray-100 shadow-sm"></div>
    </div>

    <!-- Dashboard Content -->
    <div v-else-if="dashboardData">

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <RouterLink
          v-for="stat in statCards" :key="stat.title"
          :to="stat.link || '#'"
          class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group"
        >
          <div :class="[stat.iconBg, 'w-12 h-12 rounded-full flex items-center justify-center shrink-0 mr-4 group-hover:scale-110 transition-transform']">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon"/></svg>
          </div>
          <div class="flex-grow min-w-0">
            <p class="text-sm text-gray-500 font-medium mb-1 truncate">{{ stat.title }}</p>
            <h3 class="text-2xl font-bold text-gray-900 leading-tight">{{ stat.value }}</h3>
            <div class="flex items-center gap-2 mt-1.5">
              <span :class="[stat.badgeClass, 'text-xs font-semibold px-2 py-0.5 rounded border']">{{ stat.badge }}</span>
            </div>
            <p v-if="stat.sub" class="text-[11px] text-gray-400 mt-1">{{ stat.sub }}</p>
          </div>
        </RouterLink>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Bar Chart: Monthly Bookings -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <div class="flex justify-between items-start mb-6">
            <div>
              <h3 class="text-lg font-bold text-gray-900">Monthly Bookings</h3>
              <div class="flex items-center mt-2 gap-2">
                <span class="text-2xl font-bold text-gray-900">{{ dashboardData.stats.total_bookings.toLocaleString() }}</span>
                <span class="text-xs text-emerald-600 font-medium">All time</span>
              </div>
            </div>
            <button @click="fetchDashboard" class="p-1.5 text-gray-400 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors" title="Refresh">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
          </div>
          <div class="h-[280px]">
            <DashboardBarChart :chartData="barChartData" :chartOptions="barChartOptions" />
          </div>
        </div>

        <!-- Donut Chart: Booking Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900">Booking Status</h3>
          </div>

          <div class="flex-grow relative min-h-[200px] mb-4">
            <DashboardDonutChart :chartData="donutChartData" />
          </div>

          <div class="space-y-3">
            <div v-for="item in donutLegend" :key="item.label"
              class="flex justify-between items-center text-sm border-t border-gray-50 pt-2 first:border-t-0 first:pt-0"
            >
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: item.color }"></span>
                <span class="text-gray-600 font-medium">{{ item.label }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="font-bold text-gray-800">{{ item.count }}</span>
                <span class="text-xs text-gray-400">({{ item.pct }}%)</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Bookings Table -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
          <h3 class="text-lg font-bold text-gray-900">Recent Bookings</h3>
          <RouterLink to="/admin/bookings" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
            View all →
          </RouterLink>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50/50 border-b border-gray-100">
                <th class="p-4 pl-6 text-xs font-semibold text-gray-500">PNR</th>
                <th class="p-4 text-xs font-semibold text-gray-500">Passenger</th>
                <th class="p-4 text-xs font-semibold text-gray-500">Route</th>
                <th class="p-4 text-xs font-semibold text-gray-500">Bus</th>
                <th class="p-4 text-xs font-semibold text-gray-500">Date</th>
                <th class="p-4 text-xs font-semibold text-gray-500">Amount</th>
                <th class="p-4 text-xs font-semibold text-gray-500">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">

              <!-- Empty state -->
              <tr v-if="recentBookings.length === 0">
                <td colspan="7" class="p-12 text-center text-gray-400 text-sm">No bookings found.</td>
              </tr>

              <tr v-for="item in recentBookings" :key="item.id" class="hover:bg-gray-50/50 transition-colors">
                <td class="p-4 pl-6 font-mono text-xs font-bold text-indigo-600">{{ item.pnr }}</td>
                <td class="p-4">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-100 overflow-hidden shrink-0">
                      <img :src="avatarUrl(item.customer)" :alt="item.customer" class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0">
                      <p class="text-sm font-bold text-gray-900 truncate">{{ item.customer }}</p>
                      <p class="text-xs text-gray-400 truncate">{{ item.email }}</p>
                    </div>
                  </div>
                </td>
                <td class="p-4 text-sm font-medium text-gray-700">{{ item.route }}</td>
                <td class="p-4 text-sm text-gray-500">{{ item.bus }}</td>
                <td class="p-4 text-sm text-gray-500">{{ formatDate(item.date) }}</td>
                <td class="p-4 text-sm font-bold text-gray-900">৳{{ Number(item.amount).toLocaleString() }}</td>
                <td class="p-4">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize"
                    :class="statusClass(item.status)">
                    <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="statusDot(item.status)"></span>
                    {{ item.status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</template>
