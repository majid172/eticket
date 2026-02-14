<script setup>
import { onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '@/stores/booking'
import QrcodeVue from 'qrcode.vue'

const router = useRouter()
const bookingStore = useBookingStore()

onMounted(() => {
  if (!bookingStore.selectedSeats.length) {
    // router.push('/') // Redirect if no booking
  }
})

const goHome = () => {
    bookingStore.clearBookingDetails()
    router.push('/')
}

const printTicket = () => {
    window.print()
}

const qrValue = computed(() => {
    const data = {
        pnr: bookingStore.pnr,
        name: bookingStore.passengerDetails?.name,
        seats: bookingStore.selectedSeats.map(s => s.name).join(','),
        date: bookingStore.journeyDate,
        time: bookingStore.busDetails?.departureTime
    }
    return JSON.stringify(data)
})
</script>

<template>
  <div class="booking-success-view min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4 font-sans print:bg-white print:p-0 print:m-0 print:h-auto print:overflow-visible">
    
    <!-- Non-printable Success Message -->
    <div class="text-center mb-8 print:hidden">
        <div class="w-16 h-16 bg-green-100 rounded-full mx-auto flex items-center justify-center animate-bounce mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900">Booking Confirmed!</h2>
        <p class="text-gray-500">Your ticket is ready. Please download or print your boarding pass.</p>
    </div>

    <!-- Boarding Pass Ticket -->
    <div class="boarding-pass bg-white rounded-3xl shadow-xl overflow-hidden max-w-3xl w-full border border-gray-200 print:shadow-none print:border print:border-gray-300 print:w-full print:max-w-none print:rounded-none">
        
        <!-- Ticket Header -->
        <div class="bg-indigo-600 px-8 py-6 flex justify-between items-center text-white print:bg-gray-800 print:text-black print:border-b print:border-gray-300">
            <div>
                <h1 class="text-2xl font-bold tracking-wider uppercase print:text-black">Boarding Pass</h1>
                <p class="text-indigo-200 text-sm print:text-gray-600">Jatri E-Ticketing Service</p>
            </div>
            <div class="text-right">
                <p class="text-xs uppercase opacity-75 print:text-gray-600">PNR Number</p>
                <p class="text-3xl font-mono font-bold print:text-black">{{ bookingStore.pnr || 'UNASSIGNED' }}</p>
            </div>
        </div>

        <!-- Ticket Body -->
        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8 print:grid-cols-3 print:gap-4">
            
            <!-- Left Column: Main Info -->
            <div class="md:col-span-2 space-y-6 print:col-span-2">
                <!-- Journey Route -->
                <div class="flex items-center justify-between border-b-2 border-dashed border-gray-100 pb-6 print:border-gray-300">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 print:text-gray-600">From</p>
                        <p class="text-xl font-bold text-gray-800 print:text-black">{{ bookingStore.routeInfo ? bookingStore.routeInfo.split(' - ')[0] : 'Dhaka' }}</p>
                    </div>
                    <div class="flex-grow mx-4 flex flex-col items-center">
                         <div class="w-full h-0.5 bg-gray-200 relative top-3 print:bg-gray-400"></div>
                         <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center z-10 print:bg-white print:border print:border-gray-300">
                             <svg class="w-4 h-4 text-gray-500 print:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                         </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 print:text-gray-600">To</p>
                        <p class="text-xl font-bold text-gray-800 print:text-black">{{ bookingStore.routeInfo ? bookingStore.routeInfo.split(' - ').slice(-1)[0] : 'Chittagong' }}</p>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 print:text-gray-600">Date</p>
                        <p class="font-bold text-gray-800 print:text-black">{{ bookingStore.journeyDate || 'Date not set' }}</p>
                    </div>
                    <div>
                         <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 print:text-gray-600">Time</p>
                        <p class="font-bold text-gray-800 print:text-black">{{ bookingStore.busDetails?.departureTime || 'Time not set' }}</p>
                    </div>
                </div>

                 <!-- Passenger & Operator -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 print:text-gray-600">Passenger Name</p>
                        <p class="font-bold text-gray-800 capitalize print:text-black">{{ bookingStore.passengerDetails?.name || 'Guest User' }}</p>
                    </div>
                    <div>
                         <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 print:text-gray-600">Operator</p>
                        <p class="font-bold text-gray-800 print:text-black">{{ bookingStore.busDetails?.operator || 'Bus Operator' }}</p>
                    </div>
                </div>

                 <!-- Contact -->
                 <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 print:text-gray-600">Phone</p>
                        <p class="font-medium text-gray-800 print:text-black">{{ bookingStore.passengerDetails?.phone || 'N/A' }}</p>
                    </div>
                     <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 print:text-gray-600">Coach Type</p>
                        <p class="font-medium text-gray-800 print:text-black">{{ bookingStore.busDetails?.isAc ? 'AC' : 'Non-AC' }} Coach</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Seats & QR -->
            <div class="md:border-l-2 md:border-dashed border-gray-100 md:pl-8 flex flex-col justify-between print:border-l-2 print:border-gray-300 print:pl-8 print:col-span-1">
                <div>
                     <p class="text-xs text-gray-400 uppercase tracking-wider mb-2 print:text-gray-600">Seat Numbers</p>
                     <div class="flex flex-wrap gap-2">
                         <span v-for="seat in bookingStore.selectedSeats" :key="seat.id" class="px-3 py-1 bg-gray-100 text-gray-800 font-bold rounded border border-gray-200 text-sm print:bg-white print:border-gray-300 print:text-black">
                             {{ seat.name }}
                         </span>
                     </div>
                </div>
                
                 <div class="mt-8 text-center print:mt-4">
                     <p class="text-xs text-gray-400 uppercase tracking-wider mb-1 print:text-gray-600">Total Amount</p>
                     <p class="text-2xl font-bold text-indigo-600 print:text-black">৳{{ bookingStore.totalPrice }}</p>
                 </div>

                <div class="mt-8 flex justify-center opacity-80 print:mt-4 print:opacity-100">
                    <!-- Real QR Code -->
                    <div class="bg-white p-2 border border-gray-100 print:border-none">
                        <qrcode-vue :value="qrValue" :size="120" level="H" />
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 text-xs text-gray-400 flex justify-between items-center print:bg-white print:border-t print:border-gray-300 print:text-gray-600">
            <p>Generated by Jatri E-Ticketing</p>
            <p>Please arrive 15 minutes before departure.</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 flex gap-4 print:hidden">
        <button @click="printTicket" class="flex items-center bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-all transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Ticket
        </button>
        <button @click="goHome" class="flex items-center bg-white hover:bg-gray-50 text-gray-700 font-bold py-3 px-8 rounded-xl shadow-sm border border-gray-200 transition-all">
            Back to Home
        </button>
    </div>

  </div>
</template>

<style>
/* Global print styles to ensure full page layout */
@media print {
    body {
        background-color: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Hide everything that is NOT the boarding pass */
    body > * {
        display: none !important;
    }
    
    /* But show the app and its children */
    body > #app {
        display: block !important;
    }

    /* Hide the non-printable success message */
    .print\:hidden {
        display: none !important;
    }

    /* Specific targeting for the view */
    .booking-success-view {
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        height: auto !important;
        display: block !important;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
    
    /* Ensure the boarding pass is visible and styled correctly */
    .boarding-pass {
        /* Amber-50 background color: #fffbeb */
        background-color: #fffbeb !important;
        border: 2px dashed #d97706 !important; /* Amber-600 dashed border */
        box-shadow: none !important;
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        border-radius: 12px !important;
    }

    /* Adjust header for print */
    .boarding-pass .bg-indigo-600 {
        background-color: #d97706 !important; /* Amber-600 */
        color: white !important;
        border-bottom: 2px dashed white !important;
    }

    /* Adjust text colors for contrast on amber */
    .print\:text-black {
        color: #1f2937 !important; /* Gray-800 */
    }
    
    .print\:text-gray-600 {
        color: #4b5563 !important; /* Gray-600 */
    }

    /* Internal borders */
    .print\:border-gray-300 {
        border-color: #fcd34d !important; /* Amber-300 */
    }
    
    /* Adjust QR code container */
    .boarding-pass .bg-white {
        background-color: white !important; /* keep QR bg white */
    }
}
</style>
