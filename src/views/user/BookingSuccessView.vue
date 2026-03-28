<script setup>
import { onMounted, computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '@/stores/booking'
import api from '@/services/api'
import QrcodeVue from 'qrcode.vue'

const router = useRouter()
const bookingStore = useBookingStore()
const downloading = ref(false)

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

const downloadPdf = async () => {
    console.log('Download initiated for bookingId:', bookingStore.bookingId);
    if (!bookingStore.bookingId) {
        alert('Missing booking ID. Please try again.');
        return;
    }
    
    downloading.value = true;
    try {
        const response = await api.get(`/passenger/bookings/${bookingStore.bookingId}/ticket`, {
            responseType: 'blob'
        });
        
        console.log('PDF response received:', response.status);
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `e-Ticket-${bookingStore.pnr || 'receipt'}.pdf`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    } catch (err) {
        console.error('Failed to download PDF:', err);
        const errorMsg = err.response?.data?.message || err.message;
        alert('Failed to download PDF: ' + errorMsg);
    } finally {
        downloading.value = false;
    }
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
  <div class="booking-success-view bg-[#f8fafc] flex flex-col items-center py-12 px-6 font-sans print:bg-white print:p-0">
    
    <!-- Sophisticated Success Header -->
    <div class="text-center mb-6 print:hidden animate-fade-in">
        <div class="w-12 h-12 bg-emerald-50 rounded-full mx-auto flex items-center justify-center mb-3 border border-emerald-100 shadow-sm">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Booking Confirmed</h2>
        <p class="text-[11px] text-slate-500 font-medium">Your e-ticket has been generated successfully.</p>
    </div>

    <!-- Compact Premium Ticket -->
    <div class="ticket-card bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden max-w-2xl w-full border border-slate-100 print:shadow-none print:border-slate-200">
        
        <!-- Ticket Header (Compact) -->
        <div class="bg-slate-900 px-6 py-2 flex justify-between items-end text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative z-10">
                <h1 class="text-[10px] font-bold tracking-[0.2em] uppercase text-indigo-400 mb-1">Boarding Pass</h1>
                <p class="text-lg font-black tracking-tight leading-none uppercase">e-Ticket</p>
            </div>
            <div class="text-right relative z-10">
                <p class="text-[9px] uppercase tracking-widest text-slate-400 mb-0.5">PNR Reference</p>
                <p class="text-xl font-mono font-black text-indigo-400 leading-none">{{ bookingStore.pnr || '---' }}</p>
            </div>
        </div>

        <!-- Ticket Body (Condensed) -->
        <div class="p-3 grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <!-- Left Info Section -->
            <div class="md:col-span-3 space-y-5">
                <!-- Journey Route (Compact style) -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-50">
                    <div class="flex-1">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Departure</p>
                        <p class="text-sm font-black text-slate-800 tracking-tight">{{ bookingStore.routeInfo ? bookingStore.routeInfo.split(' - ')[0] : 'Origin' }}</p>
                    </div>
                    <div class="flex-1 px-4 flex flex-col items-center">
                         <div class="w-full h-[1px] bg-slate-100 relative top-2"></div>
                         <div class="w-5 h-5 bg-white rounded-full flex items-center justify-center z-10 border border-slate-100">
                             <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                         </div>
                    </div>
                    <div class="flex-1 text-right">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Arrival</p>
                        <p class="text-sm font-black text-slate-800 tracking-tight">{{ bookingStore.routeInfo ? bookingStore.routeInfo.split(' - ').slice(-1)[0] : 'Destination' }}</p>
                    </div>
                </div>

                <!-- 2x2 Grid for Specifics (Small text) -->
                <div class="grid grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Journey Date</p>
                        <p class="text-xs font-bold text-slate-700">{{ bookingStore.journeyDate || '---' }}</p>
                    </div>
                    <div>
                         <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Departure Time</p>
                        <p class="text-xs font-bold text-slate-700">{{ bookingStore.busDetails?.departureTime || '---' }}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Passenger</p>
                        <p class="text-xs font-bold text-slate-700 truncate capitalize">{{ bookingStore.passengerDetails?.name || 'Guest' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Bus Service</p>
                        <p class="text-xs font-bold text-slate-700 truncate">{{ bookingStore.busDetails?.operator || '---' }}</p>
                    </div>
                </div>

                 <!-- Footer Details Row -->
                 <div class="pt-4 border-t border-slate-50 flex justify-between">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Coach Info</p>
                        <p class="text-xs font-medium text-slate-600">{{ bookingStore.busDetails?.isAc ? 'Premium AC' : 'Economy' }} Coach</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Contact</p>
                        <p class="text-xs font-medium text-slate-600">{{ bookingStore.passengerDetails?.phone || '---' }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Visual Section (Seat & QR) -->
            <div class="md:border-l border-slate-50 md:pl-6 flex flex-col justify-between">
                <div>
                     <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Selected Seats</p>
                     <div class="flex flex-wrap gap-1.5">
                         <span v-for="seat in bookingStore.selectedSeats" :key="seat.id" class="px-2 py-0.5 bg-slate-50 text-slate-700 font-bold rounded border border-slate-100 text-[11px] min-w-[32px] text-center">
                             {{ seat.name }}
                         </span>
                     </div>
                </div>
                
                 <div class="mt-6 bg-indigo-50/50 p-3 rounded-xl border border-indigo-100/50">
                     <p class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest mb-1 text-center">Total Fare</p>
                     <p class="text-lg font-black text-indigo-600 text-center leading-none">৳{{ bookingStore.totalPrice }}</p>
                 </div>

                <div class="mt-6 flex justify-center">
                    <div class="bg-white p-1.5 border border-slate-100 rounded-lg shadow-sm">
                        <qrcode-vue :value="qrValue" :size="70" level="H" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer / Security Note -->
        <div class="bg-slate-50 px-6 py-1.5 flex justify-between items-center border-t border-slate-100">
            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Digital Boarding Validated</span>
            <span class="text-[8px] font-medium text-slate-400 italic">Jatri E-Ticketing System &copy; 2026</span>
        </div>
    </div>

    <!-- Action Buttons (Refined) -->
    <div class="mt-4 flex flex-wrap justify-center gap-2 print:hidden animate-slide-up">
        <button @click="downloadPdf" :disabled="downloading" class="group relative flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all active:scale-95 disabled:opacity-50">
            <svg v-if="!downloading" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <svg v-else class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-xs uppercase tracking-wide">{{ downloading ? 'Downloading...' : 'Download Ticket' }}</span>
        </button>

        <button @click="printTicket" class="flex items-center bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all active:scale-95">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span class="text-xs uppercase tracking-wide">Print Paper</span>
        </button>

        <button @click="goHome" class="flex items-center bg-white hover:bg-slate-50 text-slate-600 font-bold py-2.5 px-6 rounded-xl shadow-sm border border-slate-200 transition-all active:scale-95">
            <span class="text-xs uppercase tracking-wide">Back Home</span>
        </button>
    </div>

  </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.6s ease-out; }
.animate-slide-up { animation: slideUp 0.8s ease-out; }

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@media print {
    body {
        background-color: white !important;
        -webkit-print-color-adjust: economy !important;
        print-color-adjust: economy !important;
        color: black !important;
    }

    .booking-success-view {
        padding: 0 !important;
        margin: 0 !important;
        background: white !important;
    }

    .ticket-card {
        border: 1px solid black !important;
        box-shadow: none !important;
        color: black !important;
        max-width: none !important;
        margin: 0 auto !important;
        background: white !important;
        page-break-inside: avoid !important;
    }

    /* Force monochromatic elements */
    .bg-slate-900, .bg-indigo-600, .bg-emerald-50, .bg-indigo-50\/50 {
        background: white !important;
        color: black !important;
        border-bottom: 1px solid black !important;
    }

    .text-indigo-400, .text-emerald-500, .text-indigo-600, .text-slate-400 {
        color: black !important;
    }

    .border-slate-100, .border-indigo-100\/50, .border-slate-50 {
        border-color: #ddd !important;
    }

    .ticket-card p, .ticket-card h1, .ticket-card span {
        color: black !important;
    }

    /* Hide decorative elements */
    .bg-indigo-500\/10, .blur-2xl {
        display: none !important;
    }

    /* Ensure QR code is black and white */
    canvas {
        filter: grayscale(100%) contrast(200%) !important;
    }
}
</style>
