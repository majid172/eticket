<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '@/stores/booking'

const router = useRouter()
const bookingStore = useBookingStore()

const passenger = ref({
  name: '',
  email: '',
  phone: '',
  gender: 'male',
  age: ''
})

onMounted(() => {
  if (!bookingStore.selectedSeats.length) {
    router.push('/search')
  }
})

const confirmBooking = () => {
  // meaningful validation could go here
  if (!passenger.value.name || !passenger.value.phone) {
    alert('Please fill in Name and Phone number')
    return
  }
  
  // Save passenger details to store and generate PNR
  bookingStore.confirmBooking(passenger.value)
  
  router.push('/booking/success')
}
</script>

<template>
  <div class="booking-view bg-gray-50 min-h-screen py-12 font-sans">
    <div class="container mx-auto px-4">
      
      <!-- Back Button -->
      <button @click="router.back()" class="mb-6 flex items-center text-gray-600 hover:text-indigo-600 transition-colors font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Search
      </button>

      <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Passenger Details Form -->
        <div class="w-full lg:w-2/3">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4">
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Passenger Details
              </h2>
            </div>
            
            <div class="p-8 space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                  <input v-model="passenger.name" type="text" placeholder="Enter full name" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-3 bg-gray-50">
                </div>

                <!-- Phone -->
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                  <input v-model="passenger.phone" type="tel" placeholder="Enter phone number" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-3 bg-gray-50">
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                  <input v-model="passenger.email" type="email" placeholder="Enter email address" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-3 bg-gray-50">
                </div>

                <!-- Age -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Age</label>
                    <input v-model="passenger.age" type="number" placeholder="Enter age" class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-3 bg-gray-50">
                </div>
              </div>

               <!-- Gender -->
               <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">Gender</label>
                  <div class="flex space-x-6">
                      <label class="flex items-center space-x-3 cursor-pointer">
                          <input type="radio" v-model="passenger.gender" value="male" class="form-radio text-indigo-600 focus:ring-indigo-500 w-5 h-5 border-gray-300">
                          <span class="text-gray-700">Male</span>
                      </label>
                      <label class="flex items-center space-x-3 cursor-pointer">
                          <input type="radio" v-model="passenger.gender" value="female" class="form-radio text-indigo-600 focus:ring-indigo-500 w-5 h-5 border-gray-300">
                          <span class="text-gray-700">Female</span>
                      </label>
                       <label class="flex items-center space-x-3 cursor-pointer">
                          <input type="radio" v-model="passenger.gender" value="other" class="form-radio text-indigo-600 focus:ring-indigo-500 w-5 h-5 border-gray-300">
                          <span class="text-gray-700">Other</span>
                      </label>
                  </div>
               </div>

            </div>
          </div>
        </div>

        <!-- Booking Summary -->
        <div class="w-full lg:w-1/3">
           <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
              <div class="bg-gray-900 px-6 py-4">
                 <h3 class="text-lg font-bold text-white">Booking Summary</h3>
              </div>
              <div class="p-6">
                 <div v-if="bookingStore.busDetails" class="mb-6 border-b border-gray-100 pb-6">
                    <h4 class="font-bold text-lg text-gray-900 mb-1">{{ bookingStore.busDetails.operator }}</h4>
                    <p class="text-sm text-gray-500 mb-2">{{ bookingStore.busDetails.busInfo }}</p>
                    <div class="flex justify-between items-center text-sm">
                        <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded font-semibold">{{ bookingStore.journeyDate }}</span>
                         <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded font-semibold">{{ bookingStore.busDetails.departureTime }}</span>
                    </div>
                 </div>

                 <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                       <span class="text-gray-500">Route</span>
                       <span class="font-medium text-gray-900 text-right w-1/2">{{ bookingStore.routeInfo }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                       <span class="text-gray-500">Selected Seats</span>
                       <span class="font-medium text-gray-900">{{ bookingStore.selectedSeats.map(s => s.name).join(', ') }}</span>
                    </div>
                 </div>

                 <div class="bg-gray-50 p-4 rounded-xl mb-6">
                    <div class="flex justify-between items-center mb-2">
                       <span class="text-gray-600">Subtotal</span>
                       <span class="font-bold text-gray-900">৳{{ bookingStore.totalPrice }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2 text-sm text-green-600">
                       <span>Discount</span>
                       <span>-৳0</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 mt-3 flex justify-between items-center">
                       <span class="font-bold text-gray-800 text-lg">Total Pay</span>
                       <span class="font-bold text-indigo-600 text-xl">৳{{ bookingStore.totalPrice }}</span>
                    </div>
                 </div>

                 <button @click="confirmBooking" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    CONFIRM & BOOK
                 </button>
              </div>
           </div>
        </div>

      </div>
    </div>
  </div>
</template>
