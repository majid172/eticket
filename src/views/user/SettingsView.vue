<script setup>
import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useSettingsStore } from '@/stores/userSettings'

// Using Pinia Store (Restoring dynamic database integration)
const settingsStore = useSettingsStore()
const { 
    bookings, 
    loadingBookings, 
    profile, 
    security, 
    preferences, 
    notifications, 
    config,
    loadingProfile 
} = storeToRefs(settingsStore)

const activeTab = ref('bookings')

const tabs = [
  { id: 'bookings', label: 'My Bookings', icon: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' },
  { id: 'profile', label: 'Account & Profile', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
  { id: 'security', label: 'Security & Privacy', icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' },
  { id: 'preferences', label: 'Travel Preferences', icon: 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z' },
  { id: 'notifications', label: 'Notifications', icon: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9' },
  { id: 'accessibility', label: 'Language & Accessibility', icon: 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129' },
  { id: 'account', label: 'Account Management', icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' }
]

// Handlers that interface with the Store
const handleSave = async (section) => {
    try {
        let payload;
        if (section === 'profile') {
            payload = profile.value;
        } else if (section === 'preferences') {
            payload = preferences.value;
        } else if (section === 'notifications') {
            payload = notifications.value;
        } else if (section === 'config') {
            payload = config.value;
        } else if (section === 'security') {
            payload = {
                current_password: security.value.currentPassword,
                new_password: security.value.newPassword,
                new_password_confirmation: security.value.newPassword
            };
        }

        const result = await settingsStore.updateSettings(section, payload)
        if (result.success) {
            alert(result.message)
            if (section === 'security') {
                security.value.currentPassword = ''
                security.value.newPassword = ''
            }
        }
    } catch (err) {
        alert("Failed to save settings: " + (err.response?.data?.message || err.message))
    }
}

const handleCancelBooking = async (id) => {
    if (!confirm("Are you sure you want to cancel this booking?")) return
    try {
        await settingsStore.cancelBooking(id)
        alert("Booking cancelled successfully")
    } catch (err) {
        alert(err.response?.data?.message || "Failed to cancel booking")
    }
}

onMounted(async () => {
    await Promise.all([
        settingsStore.fetchProfile(),
        settingsStore.fetchBookings()
    ])
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-7xl mx-auto">
      
      <div class="mb-10">
          <h1 class="text-xl font-medium text-gray-900 tracking-tight">Settings</h1>
          <p class="text-[13px] text-slate-500 font-normal mt-1 italic">Manage your preferences and travel history.</p>
      </div>

      <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar Navigation -->
        <div class="w-full lg:w-72 flex-shrink-0">
            <nav class="space-y-1.5 bg-white rounded-2xl shadow-sm border border-gray-100 p-3 sticky top-10">
                <button 
                    v-for="tab in tabs" 
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    class="w-full flex items-center px-4 py-3 text-[13px] font-medium rounded-xl transition-all duration-300"
                    :class="activeTab === tab.id ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50'"
                >
                    <svg class="mr-3 flex-shrink-0 h-4 w-4" :class="activeTab === tab.id ? 'text-white' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon"/>
                    </svg>
                    {{ tab.label }}
                </button>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="flex-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-10 min-h-[500px]">
                
                <!-- My Bookings -->
                <div v-show="activeTab === 'bookings'" class="space-y-6 animate-fade-in">
                    <div class="border-b border-gray-50 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 uppercase italic tracking-tighter">My Bookings</h2>
                        <p class="text-[12px] text-slate-500 mt-1 font-normal">Manage your upcoming and past journeys.</p>
                    </div>

                    <div v-if="loadingBookings" class="flex flex-col justify-center items-center py-20 gap-4">
                        <div class="w-6 h-6 border-2 border-slate-100 border-t-indigo-600 rounded-full animate-spin"></div>
                        <p class="text-[11px] font-medium text-slate-400 uppercase tracking-widest">Loading trips...</p>
                    </div>

                    <div v-else-if="bookings.length === 0" class="text-center py-20 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                        <p class="text-slate-500 font-medium text-sm">You haven't made any bookings yet.</p>
                        <router-link to="/" class="text-indigo-600 text-[11px] font-medium uppercase tracking-widest mt-2 inline-block hover:underline">Start Booking Now</router-link>
                    </div>

                    <div v-else class="space-y-6">
                        <div v-for="booking in bookings" :key="booking.id" class="border border-gray-100 rounded-2xl overflow-hidden bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
                            <!-- Gorgeous flat header -->
                            <div class="px-6 py-4 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="bg-indigo-600 w-3 h-3 rounded-full"></div>
                                    <span class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-500">Bus : <span class="text-gray-900 ml-1 font-mono tracking-normal">{{ booking.schedule_bus.bus.bus_name }} ({{ booking.schedule_bus.bus.bus_number }})</span></span>
                                </div>
                                <span :class="{'text-emerald-600 bg-emerald-50': booking.booking_status === 'confirmed', 'text-rose-600 bg-rose-50': booking.booking_status === 'cancelled', 'text-amber-600 bg-amber-50': booking.booking_status === 'pending'}" class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-current/20">
                                    {{ booking.booking_status }}
                                </span>
                            </div>

                            <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                                <!-- Route Information -->
                                <div class="md:col-span-5 space-y-2">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 leading-none">{{ booking.schedule_bus.schedule.route.source_city || 'Origin' }}</p>
                                        </div>
                                        <div class="text-indigo-600 bg-indigo-50 w-6 h-6 rounded-full flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </div>
                                        <div class="flex-1 text-right">
                                            <p class="text-sm font-medium text-gray-900 leading-none">{{ booking.schedule_bus.schedule.route.destination_city || 'Dest.' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 text-[10px] font-medium text-slate-400 tracking-tight">
                                        <div class="flex items-center gap-1.5">{{ booking.schedule_bus.schedule.travel_date || 'N/A' }}</div>
                                        <div class="flex items-center gap-1.5">({{ booking.schedule_bus.schedule.departure_time || 'N/A' }} - {{ booking.schedule_bus.schedule.arrival_time || 'N/A' }})</div>
                                    </div>
                                </div>

                                <!-- Seat Details -->
                                <div class="md:col-span-3 border-t md:border-t-0 md:border-l border-gray-100 pt-5 md:pt-0 md:pl-6">
                                    <p class="text-[9px] font-medium text-slate-400 uppercase tracking-widest mb-1.5">Booked Seats</p>
                                    <div class="flex flex-wrap gap-1">
                                        <span v-for="seat in booking.seats" :key="seat.id" class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-md font-medium text-[9px]">
                                            {{ seat.seat_number }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Financials & Action -->
                                <div class="md:col-span-4 flex items-center justify-between md:justify-end gap-8 border-t md:border-t-0 pt-5 md:pt-0 md:pl-6 md:border-l border-gray-100">
                                    <div class="text-right">
                                        <p class="text-[9px] font-medium text-slate-400 uppercase tracking-widest leading-none mb-1">Total Paid</p>
                                        <p class="text-base font-medium text-gray-900 tracking-tight">৳{{ booking.total_amount }}</p>
                                    </div>
                                    <button v-if="booking.booking_status !== 'cancelled'" @click="handleCancelBooking(booking.id)" class="text-rose-500 w-8 h-8 rounded-lg flex items-center justify-center hover:bg-rose-50 transition-colors" title="Cancel Booking">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                <!-- Account & Profile -->
                <div v-show="activeTab === 'profile'" class="space-y-10 animate-fade-in">
                    <div class="border-b border-gray-50 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 uppercase italic tracking-tighter">Identity Profile</h2>
                        <p class="text-[12px] text-slate-500 mt-1 font-normal">Manage your personal data for faster bookings.</p>
                    </div>

                    <div class="flex items-center gap-8">
                         <div class="relative">
                             <img :src="`https://ui-avatars.com/api/?name=${profile.name}&size=120&background=6366f1&color=fff`" class="w-20 h-20 rounded-2xl border-4 border-white shadow-sm">
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900 text-sm">Verification Badge</h3>
                            <p class="text-[10px] text-emerald-500 font-medium uppercase tracking-widest mt-0.5">Verified Passenger Account</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                            <input v-model="profile.name" type="text" class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:bg-white transition-all px-4 py-3 text-sm font-medium">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">Email</label>
                            <input v-model="profile.email" type="email" class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:bg-white transition-all px-4 py-3 text-sm font-medium">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">Phone</label>
                            <input v-model="profile.phone" type="tel" class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:bg-white transition-all px-4 py-3 text-sm font-medium">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">Emergency Contact</label>
                            <input v-model="profile.emergency_contact" type="tel" class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:bg-white transition-all px-4 py-3 text-sm font-medium" placeholder="Safety number">
                        </div>
                    </div>

                    <div class="flex justify-end pt-6 border-t border-gray-50">
                        <button @click="handleSave('profile')" class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-[11px] font-medium uppercase tracking-widest shadow-md shadow-indigo-50 transition-all active:scale-95">Update Profile</button>
                    </div>
                </div>

                <!-- Security & Privacy -->
                <div v-show="activeTab === 'security'" class="space-y-10 animate-fade-in">
                     <div class="border-b border-gray-50 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 uppercase italic tracking-tighter">Security Vault</h2>
                        <p class="text-[12px] text-slate-500 mt-1 font-normal">Protect your identity and credentials.</p>
                    </div>

                    <div class="space-y-8 max-w-xl">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">Current Password</label>
                            <input v-model="security.currentPassword" type="password" class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:bg-white transition-all px-4 py-3 text-sm font-medium">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">New Secure Password</label>
                            <input v-model="security.newPassword" type="password" class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:bg-white transition-all px-4 py-3 text-sm font-medium">
                        </div>
                        
                        <div class="pt-8 border-t border-gray-50">
                             <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                <div>
                                    <h3 class="font-medium text-gray-900 uppercase italic tracking-tighter text-sm">Two-Factor Authenticator</h3>
                                    <p class="text-[11px] text-slate-500 font-normal mt-0.5">Extra verification layer for critical terminal access.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer scale-110">
                                    <input type="checkbox" v-model="security.twoFactor" class="sr-only peer">
                                    <div class="w-11 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                     <div class="flex justify-end pt-8 border-t border-gray-50">
                        <button @click="handleSave('security')" class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-[11px] font-medium uppercase tracking-widest shadow-md shadow-indigo-50 transition-all active:scale-95">Update Security</button>
                    </div>
                </div>

                 <!-- Travel Preferences -->
                <div v-show="activeTab === 'preferences'" class="space-y-10 animate-fade-in">
                    <div class="border-b border-gray-50 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 uppercase italic tracking-tighter">Journey Preferences</h2>
                        <p class="text-[12px] text-slate-500 mt-1 font-normal">Fine-tune your default booking parameters.</p>
                    </div>

                    <div class="space-y-8 max-lg">
                        <div>
                             <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1 mb-3 block">Optimum Seat Positioning</label>
                             <div class="grid grid-cols-3 gap-4">
                                 <button class="border rounded-2xl py-3 px-3 text-[11px] font-medium uppercase tracking-widest transition-all" :class="preferences.seatType === 'Window' ? 'border-indigo-600 bg-indigo-600 text-white shadow-md' : 'border-gray-50 bg-gray-50 text-slate-400 hover:border-gray-100'" @click="preferences.seatType = 'Window'">Window</button>
                                 <button class="border rounded-2xl py-3 px-3 text-[11px] font-medium uppercase tracking-widest transition-all" :class="preferences.seatType === 'Aisle' ? 'border-indigo-600 bg-indigo-600 text-white shadow-md' : 'border-gray-50 bg-gray-50 text-slate-400 hover:border-gray-100'" @click="preferences.seatType = 'Aisle'">Aisle</button>
                                 <button class="border rounded-2xl py-3 px-3 text-[11px] font-medium uppercase tracking-widest transition-all" :class="preferences.seatType === 'Sleeper' ? 'border-indigo-600 bg-indigo-600 text-white shadow-md' : 'border-gray-50 bg-gray-50 text-slate-400 hover:border-gray-100'" @click="preferences.seatType = 'Sleeper'">Sleeper</button>
                             </div>
                        </div>

                         <div class="space-y-1.5">
                             <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">Fleet Category Protocol</label>
                             <select v-model="preferences.busType" class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:bg-white transition-all px-4 py-3 text-sm font-medium outline-none">
                                 <option>AC</option>
                                 <option>Non-AC</option>
                                 <option>Scania</option>
                                 <option>Hyundai</option>
                             </select>
                        </div>
                    </div>
                    <div class="flex justify-end pt-8 border-t border-gray-50">
                        <button @click="handleSave('preferences')" class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-[11px] font-medium uppercase tracking-widest shadow-md shadow-indigo-50 transition-all active:scale-95">Set Journey Defaults</button>
                    </div>
                </div>

                <!-- Notifications -->
                <div v-show="activeTab === 'notifications'" class="space-y-10 animate-fade-in">
                    <div class="border-b border-gray-50 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 uppercase italic tracking-tighter">Notification Hub</h2>
                        <p class="text-[12px] text-slate-500 mt-1 font-normal">Control external terminal updates.</p>
                    </div>

                    <div class="space-y-4 max-w-2xl">
                         <div v-for="(val, key) in notifications" :key="key" class="flex items-center justify-between bg-white p-5 rounded-2xl border border-gray-100 shadow-sm border-l-4 border-l-indigo-600">
                            <div>
                                <h3 class="font-medium text-gray-900 uppercase italic tracking-tighter text-[13px]">
                                    {{ key.toUpperCase() }} SERVICE ALERTS
                                </h3>
                                <p class="text-[11px] text-slate-500 font-normal">Automated transit status and credential alerts.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer scale-110">
                                <input type="checkbox" v-model="notifications[key]" class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end pt-8 border-t border-gray-50">
                        <button @click="handleSave('notifications')" class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-[11px] font-medium uppercase tracking-widest shadow-md shadow-indigo-50 transition-all active:scale-95">Save Channel Config</button>
                    </div>
                </div>

                <!-- Language & Accessibility -->
                <div v-show="activeTab === 'accessibility'" class="space-y-10 animate-fade-in">
                     <div class="border-b border-gray-50 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 uppercase italic tracking-tighter">Interface Deployment</h2>
                        <p class="text-[12px] text-slate-500 mt-1 font-normal">Customize the visual language and regional settings.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-2xl">
                        <div class="space-y-1.5">
                             <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">Terminal Language</label>
                             <select v-model="config.language" class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:bg-white transition-all px-4 py-3 text-sm font-medium">
                                 <option>English</option>
                                 <option>Bangla</option>
                             </select>
                        </div>
                         <div class="space-y-1.5">
                             <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">Universal Currency</label>
                             <select v-model="config.currency" class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:bg-white transition-all px-4 py-3 text-sm font-medium">
                                 <option>BDT</option>
                                 <option>USD</option>
                             </select>
                        </div>
                    </div>

                     <div class="space-y-5 pt-8 border-t border-gray-50">
                         <div class="flex items-center justify-between max-w-2xl bg-slate-900 p-5 rounded-3xl shadow-lg">
                            <div>
                                <h3 class="font-medium text-white uppercase italic tracking-widest text-[13px]">NOCTURNAL PROTOCOL</h3>
                                <p class="text-[11px] text-slate-400 font-normal mt-0.5">Switch to dark deployment for low-light terminal operation.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer scale-110">
                                <input type="checkbox" v-model="config.darkMode" class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-500"></div>
                            </label>
                        </div>
                     </div>
                </div>

                <!-- Account Management -->
                 <div v-show="activeTab === 'account'" class="space-y-10 animate-fade-in">
                    <div class="border-b border-gray-50 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 uppercase italic tracking-tighter">Terminal Management</h2>
                        <p class="text-[12px] text-slate-500 mt-1 font-normal">Control your lifecycle and datasets.</p>
                    </div>

                    <div class="space-y-6 max-w-2xl">
                        <button class="w-full flex items-center justify-between p-5 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-slate-900 group transition-all duration-500">
                             <div class="text-left">
                                 <h3 class="font-medium text-gray-900 uppercase italic tracking-tighter text-[13px] group-hover:text-white transition-colors">Manifest Data Export</h3>
                                 <p class="text-[11px] text-slate-500 font-normal mt-0.5 group-hover:text-slate-400 transition-colors">Download a complete JSON/CSV record of your travel history.</p>
                             </div>
                             <div class="text-indigo-600 group-hover:text-white transition-colors">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                             </div>
                        </button>
                        
                        <div class="pt-10 mt-10 border-t border-gray-100">
                            <h3 class="text-rose-600 font-medium uppercase italic tracking-widest text-[13px] mb-2">IDENTITY TERMINATION</h3>
                            <p class="text-[12px] text-slate-500 font-normal mb-6 leading-relaxed">Account destruction is irreversible. All validated credentials and transit manifest records will be purged from the central database.</p>
                            <button class="bg-rose-50 text-rose-600 border border-rose-100 px-6 py-2.5 rounded-xl text-[11px] font-medium uppercase tracking-widest shadow-sm hover:bg-rose-600 hover:text-white transition-all">Destroy Account</button>
                        </div>
                    </div>
                 </div>


                 <!-- Loader for Profile -->
                 <div v-if="loadingProfile" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-50 flex flex-col items-center justify-center rounded-3xl">
                     <div class="w-12 h-12 border-4 border-gray-100 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
                     <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Syncing Identity...</p>
                 </div>

            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1); }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Custom Scrollbar */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }
</style>