<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useBookingStore } from '@/stores/booking'
import { useSearchStore } from '@/stores/user/search'
import api from '@/services/api'

const route = useRoute()
const router = useRouter()
const bookingStore = useBookingStore()
const searchStore = useSearchStore()

const { 
  searchParams, 
  loadingTickets, 
  tickets, 
  expandedTicketId, 
  selectedSeats, 
  seatLayout, 
  loadingLayout, 
  totalPrice,
  seatType,
  availableOperators
} = storeToRefs(searchStore)

const toggleSeats = (ticketId) => searchStore.toggleSeats(ticketId)
const selectSeat = (seat) => searchStore.selectSeat(seat)
const isSelected = (seat) => searchStore.isSelected(seat)

// Watch the route query to react to changes when the user searches again from the navbar/home
watch(() => route.query, (newQuery) => {
    searchStore.fetchSchedules({
        from:       newQuery.from       || '',
        to:         newQuery.to         || '',
        date:       newQuery.date       || '',   // empty = show all future schedules
        returnDate: newQuery.returnDate || ''
    })
}, { deep: true })

onMounted(() => {
    searchStore.fetchSchedules({
        from:       route.query.from       || '',
        to:         route.query.to         || '',
        date:       route.query.date       || '',   // empty = show all future schedules
        returnDate: route.query.returnDate || ''
    })
})

const proceedToBooking = (ticket) => {
    bookingStore.setBookingDetails(
        selectedSeats.value,
        ticket,
        tickets.value.find(t => t.id === expandedTicketId.value).departureDate,
        ticket.route
    )
    bookingStore.scheduleBusId = ticket.id;
    router.push('/booking')
}

const sortOrder = ref('') // 'asc' | 'desc'
const selectedOperators = ref([])
const selectedBusTypes = ref([])

const sortedTickets = computed(() => {
    let result = [...tickets.value]
    
    // Filter by operators
    if (selectedOperators.value.length > 0) {
        result = result.filter(t => selectedOperators.value.includes(t.operator))
    }
    
    // Filter by bus type
    if (selectedBusTypes.value.length > 0) {
        result = result.filter(t => {
            const typeStr = t.isAc ? 'AC' : 'Non AC'
            return selectedBusTypes.value.includes(typeStr)
        })
    }

    if (sortOrder.value === 'asc') {
        result.sort((a, b) => a.price - b.price)
    } else if (sortOrder.value === 'desc') {
        result.sort((a, b) => b.price - a.price)
    }
    return result
})

const sortByPrice = (order) => {
    sortOrder.value = order
}

const showFilters = ref(false)
const isMobile = ref(false)

if (typeof window !== 'undefined') {
  isMobile.value = window.innerWidth < 1024
  window.addEventListener('resize', () => {
    isMobile.value = window.innerWidth < 1024
  })
}

const resetFilters = () => {
    selectedOperators.value = []
    selectedBusTypes.value = []
    sortOrder.value = ''
}
</script>

<template>
  <div class="search-results-view bg-gray-50 min-h-screen pb-12 font-sans">
    <!-- Top Search Bar Summary -->
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-14 lg:top-16 z-30 backdrop-blur-md bg-white/90 transition-all duration-300">
      <div class="container mx-auto px-4 py-3 md:py-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
          <div class="flex items-center space-x-2 md:space-x-4 flex-grow w-full md:w-auto bg-gray-50 p-2 rounded-xl border border-gray-100">
             <div class="flex items-center text-gray-700 font-medium text-sm md:text-base truncate">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-indigo-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                {{ searchParams.from }} 
             </div>
             <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
             <div class="flex items-center text-gray-700 font-medium text-sm md:text-base truncate">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                {{ searchParams.to }}
             </div>
          </div>
          
          <div class="flex items-center justify-between w-full md:w-auto gap-2">
            <div class="flex space-x-1">
              <div class="bg-indigo-50 border border-indigo-100 rounded-lg px-2 px-3 py-1.5 text-xs md:text-sm font-semibold text-indigo-700 shadow-sm">{{ searchParams.date }}</div>
            </div>

            <div class="flex gap-2">
                <button @click="showFilters = !showFilters" class="lg:hidden bg-gray-100 border border-gray-200 text-gray-700 px-3 py-1.5 rounded-lg font-bold text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filters
                </button>
                <button class="bg-indigo-600 text-white px-3 md:px-6 py-1.5 rounded-lg font-bold text-xs shadow-sm whitespace-nowrap">
                   Modify
                </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Process Steps -->
    <div class="bg-gray-900 text-white py-3 text-[10px] md:text-sm shadow-md">
      <div class="container mx-auto px-4 flex items-center justify-center space-x-2 md:space-x-8">
          <div class="flex items-center space-x-1 md:space-x-2 text-indigo-300">
             <div class="w-4 h-4 md:w-6 md:h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-[8px] md:text-xs text-xs">1</div>
             <span class="font-bold">Select Ticket</span>
          </div>
          <svg class="w-3 h-3 md:w-4 md:h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          <div class="flex items-center space-x-1 md:space-x-2 text-gray-400">
             <div class="w-4 h-4 md:w-6 md:h-6 rounded-full bg-gray-700 text-gray-400 flex items-center justify-center font-bold text-[8px] md:text-xs">2</div>
             <span class="font-medium">Details</span>
          </div>
          <svg class="w-3 h-3 md:w-4 md:h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          <div class="flex items-center space-x-1 md:space-x-2 text-gray-400">
             <div class="w-4 h-4 md:w-6 md:h-6 rounded-full bg-gray-700 text-gray-400 flex items-center justify-center font-bold text-[8px] md:text-xs">3</div>
             <span class="font-medium">Payment</span>
          </div>
      </div>
    </div>

    <div class="container mx-auto px-4 py-6 md:py-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div :class="[
            'w-full lg:w-1/4 space-y-6 lg:sticky lg:top-40 h-fit transition-all duration-300',
            isMobile && !showFilters ? 'hidden' : 'block',
            isMobile ? 'fixed inset-0 z-[60] bg-white p-6 overflow-y-auto' : ''
        ]">
           <div class="bg-white lg:p-6 rounded-2xl lg:shadow-sm lg:border lg:border-gray-100">
               <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                  <h3 class="font-bold text-lg text-gray-900">Filters</h3>
                  <div class="flex items-center gap-4">
                    <button @click="resetFilters" class="text-indigo-600 text-xs font-bold uppercase hover:text-indigo-800 transition-colors">Reset</button>
                    <button v-if="isMobile" @click="showFilters = false" class="p-2 -mr-2 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                  </div>
               </div>

               <!-- Filter Groups -->
               <div class="space-y-6">
                  <!-- Bus Type -->
                  <div>
                      <h4 class="font-bold text-xs text-gray-500 uppercase mb-3">Bus Type</h4>
                      <div class="space-y-3">
                          <label class="flex items-center space-x-3 cursor-pointer group">
                              <input type="checkbox" value="AC" v-model="selectedBusTypes" class="form-checkbox text-indigo-600 rounded-md focus:ring-indigo-500 w-5 h-5 border-gray-300">
                              <span class="text-gray-700 text-sm group-hover:text-indigo-600 transition-colors">AC</span>
                          </label>
                           <label class="flex items-center space-x-3 cursor-pointer group">
                              <input type="checkbox" value="Non AC" v-model="selectedBusTypes" class="form-checkbox text-indigo-600 rounded-md focus:ring-indigo-500 w-5 h-5 border-gray-300">
                              <span class="text-gray-700 text-sm group-hover:text-indigo-600 transition-colors">Non AC</span>
                          </label>
                      </div>
                  </div>

                  <!-- Operator -->
                  <div>
                      <h4 class="font-bold text-xs text-gray-500 uppercase mb-3">Operator</h4>
                      <div class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                          <label v-for="operator in availableOperators" :key="operator" class="flex items-center space-x-3 cursor-pointer group">
                              <input type="checkbox" :value="operator" v-model="selectedOperators" class="form-checkbox text-indigo-600 rounded-md focus:ring-indigo-500 w-5 h-5 border-gray-300">
                              <span class="text-gray-700 text-sm group-hover:text-indigo-600 transition-colors">{{ operator }}</span>
                          </label>
                          <div v-if="availableOperators.length === 0" class="text-xs text-gray-400">No operators available</div>
                      </div>
                  </div>

                   <!-- Boarding/Dropping -->
                  <div>
                      <h4 class="font-bold text-xs text-gray-500 uppercase mb-3">Boarding Point</h4>
                      <div class="relative">
                          <input type="text" placeholder="Search Boarding" class="w-full text-sm border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-2.5 border bg-gray-50 transition-shadow">
                          <svg class="w-4 h-4 text-gray-400 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                      </div>
                  </div>
                  
                  <!-- Departure Time -->
                  <div>
                      <h4 class="font-bold text-xs text-gray-500 uppercase mb-3">Departure Time</h4>
                      <div class="space-y-2">
                          <button class="w-full flex items-center justify-between p-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left group">
                              <div>
                                  <div class="font-bold text-sm group-hover:text-indigo-700">Afternoon</div>
                                  <div class="text-xs text-gray-500">12:00 PM - 06:00 PM</div>
                              </div>
                              <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                          </button>
                           <button class="w-full flex items-center justify-between p-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left group">
                              <div>
                                  <div class="font-bold text-sm group-hover:text-indigo-700">Evening</div>
                                  <div class="text-xs text-gray-500">After 06:00 PM</div>
                              </div>
                              <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                          </button>
                      </div>
                  </div>
               </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full lg:w-3/4 space-y-6">
           <!-- Sort Bar -->
           <div class="flex justify-end space-x-3">
               <span class="text-sm text-gray-500 self-center mr-2">Sort by:</span>
               <button 
                   @click="sortByPrice('asc')" 
                   :class="[
                       'px-4 py-2 text-xs font-bold uppercase transition-colors rounded-lg border',
                       sortOrder === 'asc' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'bg-white border-gray-200 text-gray-600 hover:border-indigo-500 hover:text-indigo-600'
                   ]">
                   Price: Low to High
               </button>
               <button 
                   @click="sortByPrice('desc')" 
                   :class="[
                       'px-4 py-2 text-xs font-bold uppercase transition-colors rounded-lg border',
                       sortOrder === 'desc' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'bg-white border-gray-200 text-gray-600 hover:border-indigo-500 hover:text-indigo-600'
                   ]">
                   Price: High to Low
               </button>
           </div>

           <!-- Loading States -->
           <div v-if="loadingTickets" class="py-12 flex justify-center items-center h-48">
              <svg class="w-8 h-8 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                 <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                 <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
           </div>
           
           <div v-else-if="tickets.length === 0" class="py-12 bg-white rounded-2xl border border-gray-100 flex flex-col items-center justify-center text-gray-500">
               <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
               <h3 class="text-lg font-bold text-gray-800">No schedules found</h3>
               <p class="text-sm">Try modifying your search or picking a different date.</p>
           </div>

           <!-- Ticket List -->
           <div v-else v-for="ticket in sortedTickets" :key="ticket.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between">
                        <!-- Bus Info -->
                        <div class="flex-grow md:w-1/3 mb-4 md:mb-0">
                            <div class="flex items-start mb-1">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 mr-4 border border-indigo-100 flex-shrink-0">
                                    <!-- Logo Placeholder -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg group-hover:text-indigo-600 transition-colors">{{ ticket.operator }}</h3>
                                    <div class="flex flex-wrap gap-2 mt-1 mb-1">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-600 mobile-hidden">{{ ticket.isAc ? 'AC' : 'Non-AC' }}</span>
                                        <span class="text-xs text-gray-500">{{ ticket.busInfo }}</span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-1.447-.894L15 7m0 13V7"></path></svg>
                                        {{ ticket.route }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="flex-grow md:w-1/3 flex items-center justify-center space-x-6 mb-4 md:mb-0">
                            <div class="text-center">
                                <div class="font-extrabold text-gray-800 text-xl">{{ ticket.departureTime }}</div>
                                <div class="text-xs text-gray-500">{{ ticket.departureDate }}</div>
                                <div class="text-xs font-medium text-indigo-600 mt-1">{{ ticket.departureLocation }}</div>
                            </div>
                            <div class="flex flex-col items-center w-24">
                                <div class="text-xs text-gray-400 mb-1">{{ ticket.duration }}</div>
                                <div class="w-full h-0.5 bg-gray-200 relative">
                                    <div class="absolute -top-1.5 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-white rounded-full flex items-center justify-center border-2 border-indigo-500">
                                        <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                             <div class="text-center">
                                <div class="font-extrabold text-gray-800 text-xl">{{ ticket.arrivalTime }}</div>
                                <div class="text-xs text-gray-500">{{ ticket.arrivalDate }}</div>
                                <div class="text-xs font-medium text-purple-600 mt-1">{{ ticket.arrivalLocation }}</div>
                            </div>
                        </div>

                        <!-- Price & Book -->
                        <div class="flex-grow md:w-1/6 flex flex-col items-end justify-center pl-4 border-l border-gray-100">
                            <div class="text-right mb-2">
                                <div v-if="ticket.oldPrice" class="text-xs text-gray-400 line-through">৳{{ ticket.oldPrice }}</div>
                                <div class="font-bold text-gray-900 text-2xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">৳{{ ticket.price }}</div>
                            </div>
                            
                            <button @click="toggleSeats(ticket.id)" class="bg-gray-900 hover:bg-indigo-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:scale-105 w-full md:w-auto text-sm">
                                {{ expandedTicketId === ticket.id ? 'Close' : 'View Seats' }}
                            </button>
                            <div class="text-xs text-amber-600 mt-2 font-medium flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                {{ ticket.seatsAvailable }} Seats Left
                            </div>
                        </div>
                    </div>

                    <!-- Discount Badge -->
                    <div v-if="ticket.discount" class="mt-4 inline-flex items-center bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                        <svg class="w-4 h-4 text-indigo-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                        <span class="text-indigo-600 font-bold text-xs">{{ ticket.discount }}</span>
                    </div>
                </div>

                <!-- View Seats Expandable Section -->
                <div v-if="expandedTicketId === ticket.id" class="border-t border-gray-100 bg-gray-50 p-6 animate-fade-in-down">
                   <div class="flex flex-col lg:flex-row gap-8">
                       <!-- Seat Layout -->
                       <div class="lg:w-1/2">
                           <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                               <div class="flex justify-between items-center mb-4">
                                   <div class="flex items-center gap-4 text-xs font-medium text-gray-600">
                                       <div class="flex items-center"><div class="w-4 h-4 bg-white border border-gray-300 rounded mr-1"></div> Available</div>
                                       <div class="flex items-center"><div class="w-4 h-4 bg-indigo-600 rounded mr-1"></div> Selected</div>
                                       <div class="flex items-center"><div class="w-4 h-4 bg-gray-300 rounded mr-1"></div> Booked</div>
                                   </div>
                                    <!-- Steering Wheel Icon -->
                                   <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                               </div>
                               
                               <!-- Loading layout flag -->
                               <div v-if="loadingLayout" class="h-32 flex items-center justify-center">
                                  <svg class="w-6 h-6 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                               </div>                                <!-- Dynamic seats mapped by seatLayout structure -->
                               <div v-else :class="[
                                   'grid gap-3 max-w-xs mx-auto text-center',
                                   seatType === 'Business' ? 'grid-cols-4' : 'grid-cols-5'
                               ]">
                                   <!-- Driver Row -->
                                   <div :class="seatType === 'Business' ? 'col-span-4 h-8' : 'col-span-5 h-8'"></div>

                                   <template v-for="(seat, index) in seatLayout" :key="seat.id">
                                       <!-- ECONOMY: Aisle spacing after every 2 seats (Col 3 is empty) -->
                                       <div v-if="seatType === 'Economy' && index > 0 && index % 2 === 0 && index % 4 !== 0" class="col-span-1"></div>
                                       
                                       <!-- BUSINESS: Aisle spacing for rows A-H (Col 2 is empty) -->
                                       <!-- Business has 3 seats per row (except last row I which has 4). 
                                            Rows A-H seats are indices 0-23. 
                                            For row A: index 0 (A1), spacer, index 1 (A2), index 2 (A3).
                                       -->
                                       <div v-if="seatType === 'Business' && index < 24 && index % 3 === 1" class="col-span-1 order-none"></div>
                                       <!-- Wait, index % 3 === 1 means it's the second seat of the row. 
                                            If I want A1 [spacer] A2 A3:
                                            Col 1: A1 (index 0)
                                            Col 2: spacer (if index % 3 === 1, we insert spacer BEFORE index 1)
                                       -->

                                       <button 
                                           @click="selectSeat(seat)"
                                           :disabled="seat.status === 'booked'"
                                           :class="[
                                               'w-10 h-10 rounded-lg flex items-center justify-center text-xs font-bold transition-all duration-200',
                                               seat.status === 'booked' ? 'bg-gray-200 text-gray-400 cursor-not-allowed' :
                                               isSelected(seat) ? 'bg-indigo-600 text-white shadow-md transform scale-105' :
                                               'bg-white border border-gray-200 text-gray-700 hover:border-indigo-500 hover:text-indigo-600 hover:shadow-sm'
                                           ]"
                                       >
                                           {{ seat.name }}
                                       </button>
                                   </template>
                               </div>
                           </div>
                       </div>

                       <!-- Selection Details -->
                       <div class="lg:w-1/2 flex flex-col">
                           <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex-grow">
                               <h4 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Boarding & Dropping</h4>
                               <div class="space-y-4 mb-6">
                                   <div>
                                       <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Boarding Point</label>
                                       <select class="w-full text-sm border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-2 bg-gray-50">
                                           <option>{{ ticket.departureLocation }} - Counter (07:30 PM)</option>
                                           <option>Gabtoli - Counter (08:00 PM)</option>
                                       </select>
                                   </div>
                                   <div>
                                       <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dropping Point</label>
                                       <select class="w-full text-sm border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-2 bg-gray-50">
                                           <option>{{ ticket.arrivalLocation }} - Counter (05:00 AM)</option>
                                           <option>Dampara - Counter (05:30 AM)</option>
                                       </select>
                                   </div>
                               </div>

                               <h4 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Fare Details</h4>
                               <div class="space-y-2 mb-6 text-sm">
                                   <div class="flex justify-between">
                                       <span class="text-gray-500">Seat(s) selected:</span>
                                       <span class="font-medium text-gray-900">{{ selectedSeats.map(s => s.name).join(', ') || 'None' }}</span>
                                   </div>
                                   <div class="flex justify-between">
                                       <span class="text-gray-500">Price per seat:</span>
                                       <span class="font-medium text-gray-900">৳{{ ticket.price }}</span>
                                   </div>
                                   <div class="flex justify-between border-t border-dashed border-gray-200 pt-2 mt-2">
                                       <span class="font-bold text-gray-800">Total Amount:</span>
                                       <span class="font-bold text-indigo-600 text-xl">৳{{ totalPrice }}</span>
                                   </div>
                               </div>

                               <button 
                                   @click="proceedToBooking(ticket)"
                                   :disabled="selectedSeats.length === 0"
                                   class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                               >
                                   CONTINUE TO BOOKING
                               </button>
                           </div>
                       </div>
                   </div>
               </div>

               <!-- Bottom Bar -->
              <div class="bg-gray-50 px-6 py-3 flex flex-wrap gap-4 text-xs font-medium border-t border-gray-100">
                  <button class="text-gray-500 hover:text-indigo-600 transition-colors">Cancellation Policy</button>
                  <button class="text-gray-500 hover:text-indigo-600 transition-colors">Boarding Point</button>
                  <button class="text-gray-500 hover:text-indigo-600 transition-colors">Dropping Point</button>
                  <button class="text-gray-500 hover:text-indigo-600 transition-colors">Amenities</button>
                  <div class="ml-auto flex items-center text-gray-500">
                      <span class="mr-2 text-indigo-600 font-bold border border-indigo-200 bg-indigo-50 rounded-full px-2 py-0.5 text-[10px]">INFO</span>
                      Departs from {{ ticket.departureLocation }} at {{ ticket.departureTime }}
                  </div>
              </div>
           </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in-down {
    animation: fadeInDown 0.3s ease-out;
}
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>