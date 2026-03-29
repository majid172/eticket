<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useOperatorBookingStore } from '@/stores/operator/booking'

const router = useRouter()
const bookingStore = useOperatorBookingStore()

// State
const searchQuery = ref('')
const actionLoading = ref(false)
const selectedBooking = ref(null)
const viewModalOpen = ref(false)
const statusModalOpen = ref(false)
const newStatus = ref('')

// Filter
const filteredBookings = bookingStore.filteredBookings(searchQuery)

// Actions
async function openViewModal(booking) {
  try {
    actionLoading.value = true
    const details = await bookingStore.fetchBookingDetails(booking.id)
    selectedBooking.value = details
    viewModalOpen.value = true
  } catch(err) {
    alert('Failed to load booking details.')
  } finally {
    actionLoading.value = false
  }
}

function openStatusModal(booking) {
  selectedBooking.value = booking
  newStatus.value = booking.booking_status
  statusModalOpen.value = true
}

async function handleUpdateStatus() {
  if(!selectedBooking.value) return
  try {
    actionLoading.value = true
    await bookingStore.updateBookingStatus(selectedBooking.value.id, { booking_status: newStatus.value })
    statusModalOpen.value = false
  } catch(err) {
    alert('Failed to update status.')
  } finally {
    actionLoading.value = false
  }
}

async function handleDelete(id) {
  if(!confirm('Are you sure you want to permanently delete this booking? This cannot be undone.')) return
  try {
    actionLoading.value = true
    await bookingStore.deleteBooking(id)
  } catch(err) {
    alert('Failed to delete booking.')
  } finally {
    actionLoading.value = false
  }
}

// Fetch Data
onMounted(() => {
  bookingStore.fetchBookings()
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
    <div v-if="bookingStore.noCompany" class="flex flex-col items-center justify-center py-8">
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
    <div v-if="bookingStore.error" class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-5 py-3 rounded-xl">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ bookingStore.error }}
    </div>

    <!-- Container -->
    <div v-if="!bookingStore.noCompany" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
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
      <div v-if="bookingStore.loading" class="flex items-center justify-center py-16">
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
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Bus</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Passenger Info</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Trip Details</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Seats</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="b in filteredBookings" :key="b.id" class="hover:bg-gray-50/40 transition-colors">
              <td class="px-6 py-4">
                <span class="text-sm font-mono font-bold text-emerald-600 block">{{ b.booking_reference }} </span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-bold text-gray-900 block">{{ b.schedule_bus.bus.bus_number }}</span>
                <span class="text-xs text-gray-500 block">Bus Type: {{ b.schedule_bus.bus.bus_type
 }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-bold text-gray-900 block">{{ b.user.name }}</span>
                <span class="text-xs text-gray-500 block">{{ b.user.phone }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-medium text-gray-800 block">{{b.schedule_bus.schedule.route.source_city}} &rarr; {{ b.schedule_bus.schedule.route.destination_city }}  ({{ b.schedule_bus.schedule.route.distance_km }} km)</span>
                <span class="text-xs text-emerald-600 block mt-0.5">{{ b.schedule_bus.schedule.travel_date }} ({{ b.schedule_bus.schedule.departure_time }} - {{ b.schedule_bus.schedule.arrival_time }})</span>
              </td>
              <td class="px-6 py-4 max-w-[200px]">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5 min-w-[90px]">Booked Seats</span>
                <div class="flex flex-wrap gap-1.5">
                  <span v-for="s in b.seats" :key="s.id || s.seat_number" class="inline-flex items-center justify-center px-2.5 py-1 bg-slate-100 text-blue-600 text-[11px] font-bold rounded-md border border-slate-200/60">
                    {{ s.seat?.seat_number || s.seat_number }}
                  </span>
                  <span v-if="!b.seats || b.seats.length === 0" class="text-xs text-gray-400">N/A</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-medium text-gray-800 block">৳{{ b.total_amount }}</span>
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
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <button @click="openViewModal(b)" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </button>
                  <button @click="openStatusModal(b)" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Update Status">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                  </button>
                  <button @click="handleDelete(b.id)" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Booking">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="filteredBookings.length === 0">
              <td colspan="7" class="px-6 py-16 text-center">
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

    <!-- View Details Modal -->
    <div v-if="viewModalOpen && selectedBooking" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm">
      <div class="bg-white rounded-2xl w-full max-w-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
          <h3 class="text-lg font-bold text-gray-900">Booking Details</h3>
          <button @click="viewModalOpen = false" class="text-gray-400 hover:text-gray-600 p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="p-6 overflow-y-auto">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- PNR & Status -->
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
              <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Booking Ref (PNR)</p>
              <p class="text-lg font-mono font-bold text-emerald-600">{{ selectedBooking.booking_reference }}</p>
              <div class="mt-4 flex gap-2">
                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold border capitalize"
                      :class="{'bg-emerald-50 text-emerald-600 border-emerald-200': selectedBooking.booking_status === 'confirmed', 'bg-rose-50 text-rose-600 border-rose-200': selectedBooking.booking_status === 'cancelled', 'bg-amber-50 text-amber-600 border-amber-200': selectedBooking.booking_status === 'pending'}">
                  {{ selectedBooking.booking_status }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold border capitalize bg-slate-50 text-slate-600 border-slate-200">
                  Paid: {{ selectedBooking.payment_status || 'N/A' }}
                </span>
              </div>
            </div>

            <!-- Passenger -->
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
              <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Passenger Info</p>
              <p class="font-bold text-gray-900">{{ selectedBooking.user?.name || selectedBooking.primary_passenger_name }}</p>
              <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>{{ selectedBooking.user?.phone || selectedBooking.primary_passenger_phone }}</p>
            </div>

            <!-- Route Info -->
            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm md:col-span-2">
              <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-4">Trip Information</p>
              <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                 <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-1">Route</p>
                    <p class="font-bold text-gray-900">{{ selectedBooking.schedule_bus?.schedule?.route?.source_city }} &rarr; {{ selectedBooking.schedule_bus?.schedule?.route?.destination_city }}</p>
                 </div>
                 <div class="w-px h-10 bg-gray-100 hidden sm:block"></div>
                 <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-1">Date & Time</p>
                    <p class="font-bold text-gray-900">{{ selectedBooking.schedule_bus?.schedule?.travel_date }}</p>
                    <p class="text-xs text-gray-500">{{ selectedBooking.schedule_bus?.schedule?.departure_time }} - {{ selectedBooking.schedule_bus?.schedule?.arrival_time }}</p>
                 </div>
                 <div class="w-px h-10 bg-gray-100 hidden sm:block"></div>
                 <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-1">Bus Engine</p>
                    <p class="font-bold text-gray-900">{{ selectedBooking.schedule_bus?.bus?.bus_number }}</p>
                    <p class="text-xs text-gray-500">{{ selectedBooking.schedule_bus?.bus?.bus_type }}</p>
                 </div>
              </div>
            </div>
            
            <!-- Seats -->
             <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm md:col-span-2">
              <div class="flex items-center justify-between mb-4">
                 <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Reserved Seats ({{ selectedBooking.seats?.length || 0 }})</p>
                 <p class="font-bold text-xl text-gray-900">৳{{ selectedBooking.total_amount }}</p>
              </div>
              <div class="flex flex-wrap gap-2">
                 <span v-for="s in selectedBooking.seats" :key="s.id || s.seat_number" class="inline-flex items-center justify-center px-4 py-2 bg-slate-50 text-[#1e619c] text-sm font-bold rounded-lg border border-slate-200">
                  {{ s.seat?.seat_number || s.seat_number }}
                </span>
                <span v-if="!selectedBooking.seats || selectedBooking.seats.length === 0" class="text-sm text-gray-400">No seat details logged.</span>
              </div>
             </div>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
          <button @click="viewModalOpen = false" class="px-6 py-2.5 bg-gray-200 text-gray-800 text-sm font-bold rounded-xl hover:bg-gray-300 transition-colors">Close Details</button>
        </div>
      </div>
    </div>

    <!-- Status Update Modal -->
    <div v-if="statusModalOpen && selectedBooking" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm">
      <div class="bg-white rounded-2xl w-full max-w-sm shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
          <h3 class="text-lg font-bold text-gray-900">Update Status</h3>
          <button @click="statusModalOpen = false" class="text-gray-400 hover:text-gray-600 p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="p-6">
          <p class="text-sm text-gray-500 mb-4">Set the new status for booking <span class="font-mono font-bold text-emerald-600">{{ selectedBooking.booking_reference }}</span></p>
          <select v-model="newStatus" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-emerald-500 focus:outline-none mb-6 capitalize">
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="cancelled">Cancelled</option>
          </select>
          <div class="flex items-center justify-end gap-3">
            <button @click="statusModalOpen = false" class="px-4 py-2.5 text-gray-600 hover:bg-gray-100 font-bold text-sm rounded-xl transition-colors">Cancel</button>
            <button @click="handleUpdateStatus" :disabled="actionLoading" class="px-6 py-2.5 bg-emerald-600 text-white font-bold text-sm rounded-xl hover:bg-emerald-700 transition-colors disabled:opacity-50 flex items-center gap-2">
               <svg v-if="actionLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
               {{ actionLoading ? 'Saving...' : 'Save Status' }}
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
