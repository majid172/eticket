<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

// State
const bookingsList  = ref([])
const loading       = ref(false)
const apiError      = ref('')
const noCompany     = ref(false)
const searchQuery   = ref('')

// Fetch Data
async function fetchBookings() {
  loading.value   = true
  apiError.value  = ''
  noCompany.value = false
  try {
    const { data } = await api.get('/operator/bookings')
    bookingsList.value = data.data
  } catch (err) {
    const msg = err.response?.data?.message || ''
    if (err.response?.status === 403 && msg.toLowerCase().includes('company')) {
      noCompany.value = true
    } else {
      apiError.value = msg || 'Failed to load bookings.'
    }
  } finally {
    loading.value = false
  }
}

onMounted(fetchBookings)

// Filter
const filteredBookings = computed(() => {
  let result = bookingsList.value
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(b =>
      (b.booking_reference || '').toLowerCase().includes(q) ||
      (b.primary_passenger_name || '').toLowerCase().includes(q) ||
      (b.primary_passenger_phone || '').toLowerCase().includes(q) ||
      (b.schedule?.route?.source_city || '').toLowerCase().includes(q) ||
      (b.schedule?.route?.destination_city || '').toLowerCase().includes(q)
    )
  }
  return result
})
</script>

<template>
  <div class="font-sans text-gray-700">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Bookings</h1>
        <p class="text-sm text-gray-500 mt-1">View all passenger bookings for your buses.</p>
      </div>
    </div>

    <!-- No Company Setup Card -->
    <div v-if="noCompany" class="flex flex-col items-center justify-center py-8">
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm w-full max-w-lg overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
        <div class="px-10 py-10 text-center">
          <div class="mx-auto w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          <h2 class="text-xl font-bold text-gray-900 mb-2">Set Up Your Company First</h2>
          <p class="text-sm text-gray-500 leading-relaxed mb-8">
            Before you can view bookings, you need to register your <strong>company profile</strong>.
          </p>
          <button
            @click="router.push({ name: 'operator-company' })"
            class="inline-flex items-center gap-2 px-8 py-3 bg-emerald-700 text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition-colors shadow-sm shadow-emerald-700/20"
          >
            Set Up Company Profile
          </button>
        </div>
      </div>
    </div>

    <!-- API Error -->
    <div v-if="apiError" class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-5 py-3 rounded-xl">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ apiError }}
    </div>

    <!-- Container -->
    <div v-if="!noCompany" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <!-- Search Row -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Booking List</h3>
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text" placeholder="Search by PNR, name, or phone..."
            class="pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm w-full sm:w-[320px] focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-shadow"
          >
          <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center py-16">
        <div class="flex items-center gap-3 text-emerald-700">
          <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
          </svg>
          <span class="text-sm font-medium">Loading passenger bookings...</span>
        </div>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/60 border-b border-gray-100">
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">PNR</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Passenger Info</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Trip Details</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Seats</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="b in filteredBookings" :key="b.id" class="hover:bg-gray-50/40 transition-colors">
              <td class="px-6 py-4">
                <span class="text-sm font-mono font-bold text-emerald-600 block">{{ b.booking_reference }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-bold text-gray-900 block">{{ b.primary_passenger_name }}</span>
                <span class="text-xs text-gray-500 block">{{ b.primary_passenger_phone }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-medium text-gray-800 block">{{ b.schedule?.route?.source_city }} &rarr; {{ b.schedule?.route?.destination_city }}</span>
                <span class="text-xs text-emerald-600 block mt-0.5">{{ b.schedule?.travel_date }}</span>
              </td>
              <td class="px-6 py-4 max-w-[150px] truncate">
                <span class="text-sm font-medium text-gray-800">
                   <span v-for="(s, index) in b.seats" :key="s.id">{{ s.seat?.seat_number }}<span v-if="index < b.seats.length - 1">, </span></span>
                </span>
                <span class="text-xs text-gray-500 block mt-0.5">{{ b.seats?.length }} seat(s)</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-medium text-gray-800 block">৳{{ b.total_price }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize"
                      :class="{'bg-emerald-50 text-emerald-600 border-emerald-200': b.booking_status === 'confirmed', 
                               'bg-rose-50 text-rose-600 border-rose-200': b.booking_status === 'cancelled',
                               'bg-amber-50 text-amber-600 border-amber-200': b.booking_status === 'pending'
                              }">
                  {{ b.booking_status }}
                </span>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="filteredBookings.length === 0">
              <td colspan="6" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                  </div>
                  <p class="text-gray-500 font-medium">No bookings found</p>
                  <p class="text-sm text-gray-400">Wait for passengers to book tickets on your buses.</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>
