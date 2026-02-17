<script setup>
import { ref } from 'vue'

const activeTab = ref('profile')

const tabs = [
  { id: 'profile', label: 'Account & Profile', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
  { id: 'security', label: 'Security & Privacy', icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' },
  { id: 'payment', label: 'Payment Settings', icon: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' },
  { id: 'preferences', label: 'Travel Preferences', icon: 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z' }, // Using a bookmark-like icon for preferences
  { id: 'notifications', label: 'Notifications', icon: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9' },
  { id: 'accessibility', label: 'Language & Accessibility', icon: 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129' },
  { id: 'refunds', label: 'Refunds & Cancellations', icon: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15' },
  { id: 'support', label: 'Support & Help', icon: 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z' },
  { id: 'account', label: 'Account Management', icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' }
]

// Mock Data Models
const profile = ref({
    name: 'John Doe',
    email: 'john.doe@example.com',
    phone: '+880 1700 000000',
    emergencyContact: '',
    passengers: [
        { name: 'Jane Doe', age: 28, gender: 'Female' }
    ]
})

const security = ref({
    currentPassword: '',
    newPassword: '',
    twoFactor: false
})

const preferences = ref({
    seatType: 'Window',
    busType: 'AC',
    boardingPoint: 'Gabtoli'
})

const notifications = ref({
    sms: true,
    email: true,
    promo: false,
    delay: true
})

const settings = ref({
    language: 'English',
    currency: 'BDT',
    darkMode: false,
    largeText: false
})

const save = (section) => {
    console.log(`Saving ${section}...`)
    alert(`${section} settings saved!`)
}

</script>

<template>
  <div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-7xl mx-auto">
      
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Settings</h1>

      <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar Navigation -->
        <div class="w-full lg:w-72 flex-shrink-0">
            <nav class="space-y-1 bg-white rounded-xl shadow-sm border border-gray-100 p-2">
                <button 
                    v-for="tab in tabs" 
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200"
                    :class="activeTab === tab.id ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                >
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" :class="activeTab === tab.id ? 'text-indigo-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon"/>
                    </svg>
                    {{ tab.label }}
                </button>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="flex-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                
                <!-- Account & Profile -->
                <div v-show="activeTab === 'profile'" class="space-y-8">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Account & Profile</h2>
                        <p class="text-sm text-gray-500 mt-1">Manage your personal information and saved passengers.</p>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="relative">
                             <img src="https://ui-avatars.com/api/?name=John+Doe&size=100" class="w-20 h-20 rounded-full border-4 border-white shadow-md">
                             <button class="absolute bottom-0 right-0 bg-indigo-600 text-white p-1.5 rounded-full hover:bg-indigo-700 transition-colors">
                                 <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                             </button>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Profile Photo</h3>
                            <p class="text-xs text-gray-500">JPG, GIF or PNG. Max size of 800K</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input v-model="profile.name" type="text" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm border px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input v-model="profile.email" type="email" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm border px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input v-model="profile.phone" type="tel" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm border px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact</label>
                            <input v-model="profile.emergencyContact" type="tel" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm border px-3 py-2" placeholder="Contact number">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-medium text-gray-900">Saved Passengers</h3>
                            <button class="text-indigo-600 text-sm font-medium hover:text-indigo-800">+ Add New</button>
                        </div>
                        <div class="space-y-3">
                            <div v-for="(passenger, idx) in profile.passengers" :key="idx" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                        {{ passenger.name.charAt(0) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ passenger.name }}</p>
                                        <p class="text-xs text-gray-500">{{ passenger.gender }}, {{ passenger.age }} years</p>
                                    </div>
                                </div>
                                <button class="text-gray-400 hover:text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button @click="save('Profile')" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm">Save Changes</button>
                    </div>
                </div>

                <!-- Security & Privacy -->
                <div v-show="activeTab === 'security'" class="space-y-8">
                     <div>
                        <h2 class="text-xl font-bold text-gray-900">Security & Privacy</h2>
                        <p class="text-sm text-gray-500 mt-1">Keep your account secure.</p>
                    </div>

                    <div class="space-y-6 max-w-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input v-model="security.currentPassword" type="password" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm border px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input v-model="security.newPassword" type="password" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm border px-3 py-2">
                        </div>
                        
                        <div class="pt-4 border-t border-gray-100">
                             <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">Two-Factor Authentication</h3>
                                    <p class="text-xs text-gray-500 mt-1">Add an extra layer of security to your account.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="security.twoFactor" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>

                         <div class="pt-4 border-t border-gray-100">
                             <h3 class="font-medium text-gray-900 mb-3">Active Sessions</h3>
                             <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 flex items-center justify-between">
                                 <div class="flex items-center gap-3">
                                     <div class="text-gray-400">
                                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                     </div>
                                     <div>
                                         <p class="text-sm font-medium text-gray-900">Windows PC</p>
                                         <p class="text-xs text-green-600">Active now • Dhaka, BD</p>
                                     </div>
                                 </div>
                                 <button class="text-xs text-red-600 font-medium hover:underline">Log out</button>
                             </div>
                        </div>
                    </div>
                     <div class="flex justify-end pt-4">
                        <button @click="save('Security')" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm">Save Changes</button>
                    </div>
                </div>

                 <!-- Travel Preferences -->
                <div v-show="activeTab === 'preferences'" class="space-y-8">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Travel Preferences</h2>
                        <p class="text-sm text-gray-500 mt-1">Customize your booking experience.</p>
                    </div>

                    <div class="space-y-6 max-w-lg">
                        <div>
                             <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Seat Type</label>
                             <div class="grid grid-cols-3 gap-3">
                                 <button class="border rounded-lg py-2 px-3 text-sm text-center transition-all" :class="preferences.seatType === 'Window' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 font-medium' : 'border-gray-200 text-gray-600 hover:border-gray-300'" @click="preferences.seatType = 'Window'">Window</button>
                                 <button class="border rounded-lg py-2 px-3 text-sm text-center transition-all" :class="preferences.seatType === 'Aisle' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 font-medium' : 'border-gray-200 text-gray-600 hover:border-gray-300'" @click="preferences.seatType = 'Aisle'">Aisle</button>
                                 <button class="border rounded-lg py-2 px-3 text-sm text-center transition-all" :class="preferences.seatType === 'Sleeper' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 font-medium' : 'border-gray-200 text-gray-600 hover:border-gray-300'" @click="preferences.seatType = 'Sleeper'">Sleeper</button>
                             </div>
                        </div>

                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Bus Type</label>
                             <select v-model="preferences.busType" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm border px-3 py-2">
                                 <option>AC</option>
                                 <option>Non-AC</option>
                                 <option>Scania</option>
                                 <option>Hyundai</option>
                             </select>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button @click="save('Preferences')" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm">Save Changes</button>
                    </div>
                </div>

                 <!-- Notifications -->
                <div v-show="activeTab === 'notifications'" class="space-y-8">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Notifications</h2>
                        <p class="text-sm text-gray-500 mt-1">Manage how we communicate with you.</p>
                    </div>

                    <div class="space-y-5">
                         <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900">SMS Notifications</h3>
                                <p class="text-xs text-gray-500">Receive booking details via SMS.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="notifications.sms" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                         <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900">Email Notifications</h3>
                                <p class="text-xs text-gray-500">Receive tickets and invoices via email.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="notifications.email" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900">Promotional Offers</h3>
                                <p class="text-xs text-gray-500">Receive updates about discounts and campaigns.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="notifications.promo" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Language & Accessibility -->
                <div v-show="activeTab === 'accessibility'" class="space-y-8">
                     <div>
                        <h2 class="text-xl font-bold text-gray-900">Language & Accessibility</h2>
                        <p class="text-sm text-gray-500 mt-1">Customize the interface.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                             <select v-model="settings.language" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm border px-3 py-2">
                                 <option>English</option>
                                 <option>Bangla</option>
                             </select>
                        </div>
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                             <select v-model="settings.currency" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm border px-3 py-2">
                                 <option>BDT</option>
                                 <option>USD</option>
                             </select>
                        </div>
                    </div>

                     <div class="space-y-5 pt-4 border-t border-gray-100">
                         <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900">Dark Mode</h3>
                                <p class="text-xs text-gray-500">Switch to a dark theme for low-light environments.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="settings.darkMode" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                     </div>
                </div>

                <!-- Account Management -->
                 <div v-show="activeTab === 'account'" class="space-y-8">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Account Management</h2>
                        <p class="text-sm text-gray-500 mt-1">Control your data and account access.</p>
                    </div>

                    <div class="space-y-4">
                        <button class="w-full flex items-center justify-between p-4 bg-gray-50 rounded-lg group hover:bg-gray-100 transition-colors text-left border border-gray-100">
                             <div>
                                 <h3 class="font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">Export Data</h3>
                                 <p class="text-xs text-gray-500">Download a copy of your booking history.</p>
                             </div>
                             <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </button>
                        
                        <div class="pt-6 mt-6 border-t border-gray-100">
                            <h3 class="text-red-600 font-bold mb-2">Danger Zone</h3>
                            <p class="text-sm text-gray-500 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                            <button class="bg-red-50 text-red-600 border border-red-100 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors">Delete Account</button>
                        </div>
                    </div>
                 </div>

                 <!-- Placeholder for other tabs -->
                 <div v-if="['payment', 'refunds', 'support'].includes(activeTab)" class="flex flex-col items-center justify-center py-12 text-center text-gray-500">
                     <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                     <h3 class="text-lg font-medium text-gray-900 mb-1">Coming Soon</h3>
                     <p class="text-sm">This section is currently under development.</p>
                 </div>

            </div>
        </div>
      </div>
    </div>
  </div>
</template>
