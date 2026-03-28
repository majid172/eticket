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
  // PERSISTence Restoration: Restore from localStorage if store is empty (e.g., after refresh)
  if (!bookingStore.bookingId) {
    const savedId = localStorage.getItem('last_booking_id');
    const savedPnr = localStorage.getItem('last_pnr');
    if (savedId) {
        bookingStore.bookingId = savedId;
        bookingStore.pnr = savedPnr;
    }
  }
})

const goHome = () => {
    bookingStore.clearBookingDetails()
    localStorage.removeItem('last_booking_id');
    localStorage.removeItem('last_pnr');
    router.push('/')
}

const printTicket = () => {
    window.print()
}

const downloadPdf = async () => {
    if (!bookingStore.bookingId) {
        alert('Missing booking ID. Please try again.');
        return;
    }
    
    downloading.value = true;
    try {
        const response = await api.get(`/passenger/bookings/${bookingStore.bookingId}/ticket`, {
            responseType: 'blob'
        });
        
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `e-Ticket-${bookingStore.pnr || 'receipt'}.pdf`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    } catch (err) {
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
  <div class="booking-success-view bg-[#f1f5f9] min-h-screen flex flex-col items-center py-10 px-4 font-sans print:bg-white print:py-0 print:px-0 print:min-h-0">
    
    <!-- This header will be hidden on PRINT -->
    <div class="text-center mb-8 print:hidden animate-fade-in">
        <div class="w-14 h-14 bg-indigo-600 rounded-2xl mx-auto flex items-center justify-center mb-4 shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Booking Finalized</h2>
        <p class="text-[12px] text-slate-500 font-bold uppercase tracking-widest mt-1">Ready for download</p>
    </div>

    <!-- The Ticket Section - ONLY this remains on PRINT -->
    <div class="ticket-container max-w-2xl w-full">
        <div class="ticket-card bg-white rounded-2xl shadow-[0_15px_50px_-12px_rgba(0,0,0,0.1)] overflow-hidden border border-slate-100 print:shadow-none print:border-2 print:border-black print:rounded-none">
            
            <!-- Ticket Header -->
            <div class="bg-slate-900 px-6 py-3 flex justify-between items-end text-white relative overflow-hidden print:bg-black print:border-b print:border-black">
                <div class="relative z-10">
                    <h1 class="text-[10px] font-bold tracking-[0.2em] uppercase text-indigo-400 mb-0.5 print:text-white">Boarding Pass</h1>
                    <p class="text-xl font-black tracking-tighter leading-none uppercase">e-Ticket</p>
                </div>
                <div class="text-right relative z-10">
                    <p class="text-[9px] uppercase tracking-widest text-slate-400 mb-0.5 print:text-white">PNR Reference</p>
                    <p class="text-2xl font-mono font-black text-white leading-none">{{ bookingStore.pnr || '---' }}</p>
                </div>
            </div>

            <!-- Ticket Body -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6 print:p-8">
                <!-- Left Details -->
                <div class="md:col-span-3 space-y-6">
                    <!-- Journey Route -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 print:border-black">
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 print:text-black">From (Origin)</p>
                            <p class="text-base font-black text-slate-900 tracking-tight">{{ bookingStore.routeInfo ? bookingStore.routeInfo.split(' - ')[0] : 'Origin' }}</p>
                        </div>
                        <div class="flex-1 px-4 flex flex-col items-center">
                             <div class="w-full h-[1px] bg-slate-200 relative top-2.5 print:bg-black"></div>
                             <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center z-10 border border-slate-100 print:border-black">
                                 <svg class="w-4 h-4 text-indigo-500 print:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                             </div>
                        </div>
                        <div class="flex-1 text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 print:text-black">To (Destination)</p>
                            <p class="text-base font-black text-slate-900 tracking-tight">{{ bookingStore.routeInfo ? bookingStore.routeInfo.split(' - ').slice(-1)[0] : 'Destination' }}</p>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-y-6 gap-x-12">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 print:text-black">Journey Date</p>
                            <p class="text-sm font-black text-slate-800">{{ bookingStore.journeyDate || '---' }}</p>
                        </div>
                        <div>
                             <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 print:text-black">Departure Time</p>
                            <p class="text-sm font-black text-slate-800">{{ bookingStore.busDetails?.departureTime || '---' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 print:text-black">Passenger Name</p>
                            <p class="text-sm font-black text-slate-800 truncate uppercase">{{ bookingStore.passengerDetails?.name || 'GUEST' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 print:text-black">Bus Service</p>
                            <p class="text-sm font-black text-slate-800 truncate uppercase">{{ bookingStore.busDetails?.operator || '---' }}</p>
                        </div>
                    </div>

                     <!-- Footer Info -->
                     <div class="pt-4 border-t border-slate-100 flex justify-between print:border-black">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 print:text-black">Coach & Class</p>
                            <p class="text-xs font-bold text-slate-600 uppercase">{{ bookingStore.busDetails?.isAc ? 'Premium AC' : 'Economy' }} Coach</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 print:text-black">Contact No.</p>
                            <p class="text-xs font-bold text-slate-600">{{ bookingStore.passengerDetails?.phone || '---' }}</p>
                        </div>
                    </div>
                </div>

                <!-- QR & Seat Section -->
                <div class="md:border-l border-slate-100 md:pl-6 flex flex-col justify-between items-center print:border-black print:border-l">
                    <div class="text-center w-full">
                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 print:text-black">Seat Numbers</p>
                         <div class="flex flex-wrap justify-center gap-2">
                             <span v-for="seat in bookingStore.selectedSeats" :key="seat.id" class="px-3 py-1 bg-slate-900 text-white font-black rounded text-[11px] min-w-[32px] print:bg-black">
                                 {{ seat.name }}
                             </span>
                         </div>
                    </div>
                    
                     <div class="mt-8 bg-slate-50 w-full p-3 rounded-xl border border-slate-100 print:bg-white print:border-black print:rounded-none">
                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 text-center print:text-black">Total Fare Rate</p>
                         <p class="text-xl font-black text-slate-900 text-center leading-none">৳{{ bookingStore.totalPrice }}</p>
                     </div>

                    <div class="mt-8 bg-white p-1.5 border border-slate-100 rounded-lg print:border-black print:rounded-none">
                        <qrcode-vue :value="qrValue" :size="65" level="H" />
                    </div>
                </div>
            </div>

            <!-- Validation Footer -->
            <div class="bg-slate-50 px-6 py-2 flex justify-between items-center border-t border-slate-100 print:bg-white print:border-black">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest print:text-black">Digital Validation Active</span>
                <span class="text-[9px] font-bold text-slate-400 italic print:text-black">Jatri E-Ticketing System &copy; 2026</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Hidden on Print) -->
    <div class="mt-10 flex flex-wrap justify-center gap-4 print:hidden">
        <button @click="downloadPdf" :disabled="downloading" class="flex items-center bg-indigo-600 hover:bg-slate-900 text-white font-bold py-3.5 px-10 rounded-full shadow-lg transition-all active:scale-95 disabled:opacity-50">
            <svg v-if="!downloading" class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <span class="text-xs uppercase tracking-widest">{{ downloading ? 'Saving...' : 'Download Softcopy' }}</span>
        </button>

        <button @click="printTicket" class="flex items-center bg-slate-900 hover:bg-black text-white font-bold py-3.5 px-10 rounded-full shadow-lg transition-all active:scale-95">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span class="text-xs uppercase tracking-widest">Print Receipt</span>
        </button>

        <button @click="goHome" class="flex items-center bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-10 rounded-full transition-all">
            <span class="text-xs uppercase tracking-widest">Back Home</span>
        </button>
    </div>

  </div>
</template>

<style scoped>
@media print {
    /* Only print the ticket container */
    body * {
        visibility: hidden;
    }
    .ticket-container, .ticket-container * {
        visibility: visible;
    }
    .ticket-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: none !important;
        margin: 0 !important;
    }

    /* Force high-contrast monochromatic styles */
    .bg-slate-900 {
        background-color: black !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .bg-indigo-600, .bg-slate-50, .bg-white {
        background-color: white !important;
        color: black !important;
    }

    .border-slate-100, .border-b, .border-t, .border-l {
        border-color: black !important;
    }

    .text-indigo-400, .text-slate-400, .text-indigo-500, .text-slate-600 {
        color: black !important;
    }

    canvas {
        filter: grayscale(100%) contrast(200%) !important;
    }
}
</style>
