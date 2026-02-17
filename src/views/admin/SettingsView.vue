<script setup>
import { ref } from 'vue'

const activeTab = ref('general')
const tabs = [
    { id: 'general', name: 'General', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
    { id: 'email', name: 'Email Settings', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
    { id: 'security', name: 'Security', icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' },
    { id: 'social_analytics', name: 'Social & Analytics', icon: 'M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11' },
    { id: 'notifications', name: 'Notifications', icon: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9' },
    { id: 'seo', name: 'SEO Settings', icon: 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z' }
]

const generalSettings = ref({
    siteTitle: 'Kartly',
    siteMotto: 'Buy more , Earn more',
    favicon: null,
    lightLogo: null,
    darkLogo: null
})

const emailSettings = ref({
    mailHost: 'smtp.mailtrap.io',
    port: '2525',
    username: '',
    password: '',
    encryption: 'TLS',
    fromAddress: 'no-reply@kartly.com',
    fromName: 'Kartly System'
})

const securitySettings = ref({
    currentPassword: '',
    newPassword: '',
    confirmPassword: ''
})

const socialAnalyticsSettings = ref({
    facebook: 'https://facebook.com/kartly',
    twitter: 'https://twitter.com/kartly',
    instagram: 'https://instagram.com/kartly',
    linkedin: '',
    googleAnalyticsId: 'G-XXXXXXXXXX',
    facebookPixelId: 'XXXXXXXXXX'
})

const notificationSettings = ref({
    orderPending: true,
    orderConfirmation: true,
    orderDeclined: false,
    orderRefund: true,
    newsletter: true
})

const seoSettings = ref({
    metaTitle: 'Kartly - Best E-commerce Platform',
    metaDescription: 'Shop the best products at unbeatable prices.',
    metaKeywords: 'coding, e-commerce, shop, electronics',
    ogImage: null
})

const saveSettings = () => {
    console.log('Saving settings for:', activeTab.value)
    alert('Settings saved successfully!')
}
</script>

<template>
  <div class="min-h-screen bg-white font-sans text-gray-700">
    
    <!-- Top Breadcrumb -->
    <div class="px-8 py-4 border-b border-gray-100 flex items-center text-sm font-medium">
        <span class="text-gray-900">Business Settings</span>
        <span class="mx-2 text-blue-500">-></span>
        <span class="text-blue-500">{{ tabs.find(t => t.id === activeTab)?.name }}</span>
    </div>

    <div class="flex min-h-[calc(100vh-53px)]">
        
        <!-- Sidebar -->
        <div class="w-64 flex-shrink-0 border-r border-gray-100 py-6">
            <nav class="space-y-1">
                <button 
                    v-for="tab in tabs" 
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    class="w-full flex items-center px-6 py-3 text-sm font-medium transition-colors border-l-4"
                    :class="activeTab === tab.id ? 'border-blue-500 text-blue-500 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                >
                    <svg class="mr-3 flex-shrink-0 h-4 w-4" :class="activeTab === tab.id ? 'text-blue-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon"/>
                    </svg>
                    {{ tab.name }}
                </button>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="flex-1 bg-white">
            
            <!-- General Settings Tab -->
            <div v-if="activeTab === 'general'" class="p-8 max-w-5xl">
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">General Settings</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Site Title</label>
                            <input v-model="generalSettings.siteTitle" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Site Motto</label>
                            <input v-model="generalSettings.siteMotto" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-start">
                            <label class="text-sm font-bold text-gray-700 pt-2">Favicon</label>
                            <div>
                                <div class="relative w-24 h-24 bg-white border border-gray-200 rounded-lg flex items-center justify-center mb-2 group">
                                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <button class="absolute -top-2 -right-2 bg-white rounded-full p-0.5 shadow-sm border border-gray-200 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <button class="text-sm text-blue-500 hover:underline">Choose File</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50/50 rounded-lg">
                    <h3 class="px-4 py-3 bg-gray-100/80 text-sm font-bold text-gray-800 rounded-t-lg border-b border-gray-200">Desktop Logo</h3>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Light Mood Logo</label>
                             <div class="relative w-full h-32 bg-white border border-gray-200 rounded-lg flex items-center justify-center mb-2">
                                <span class="text-gray-400 text-xs">No image selected</span>
                            </div>
                            <button class="text-sm text-blue-500 hover:underline">Choose File</button>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dark Mood Logo</label>
                             <div class="relative w-full h-32 bg-white border border-gray-200 rounded-lg flex items-center justify-center mb-2">
                                <span class="text-gray-400 text-xs">No image selected</span>
                            </div>
                            <button class="text-sm text-blue-500 hover:underline">Choose File</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Settings Tab -->
            <div v-else-if="activeTab === 'email'" class="p-8 max-w-5xl">
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Email Configuration</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Mail Host</label>
                            <input v-model="emailSettings.mailHost" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Mail Port</label>
                            <input v-model="emailSettings.port" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Username</label>
                            <input v-model="emailSettings.username" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                         <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Password</label>
                            <input v-model="emailSettings.password" type="password" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                         <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Encryption</label>
                             <select v-model="emailSettings.encryption" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                                 <option>TLS</option>
                                 <option>SSL</option>
                             </select>
                        </div>
                         <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">From Address</label>
                            <input v-model="emailSettings.fromAddress" type="email" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                         <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">From Name</label>
                            <input v-model="emailSettings.fromName" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div v-else-if="activeTab === 'security'" class="p-8 max-w-5xl">
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Security Settings</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Current Password</label>
                            <input v-model="securitySettings.currentPassword" type="password" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">New Password</label>
                            <input v-model="securitySettings.newPassword" type="password" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Confirm Password</label>
                            <input v-model="securitySettings.confirmPassword" type="password" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social & Analytics Settings Tab -->
             <div v-else-if="activeTab === 'social_analytics'" class="p-8 max-w-5xl">
                 
                 <!-- Social Media Links -->
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Social Media Links</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Facebook URL</label>
                            <input v-model="socialAnalyticsSettings.facebook" type="url" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all" placeholder="https://facebook.com/your-page">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Twitter URL</label>
                            <input v-model="socialAnalyticsSettings.twitter" type="url" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all" placeholder="https://twitter.com/your-handle">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Instagram URL</label>
                            <input v-model="socialAnalyticsSettings.instagram" type="url" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all" placeholder="https://instagram.com/your-profile">
                        </div>
                         <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">LinkedIn URL</label>
                            <input v-model="socialAnalyticsSettings.linkedin" type="url" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all" placeholder="https://linkedin.com/company/your-company">
                        </div>
                    </div>
                </div>

                <!-- Analytics IDs -->
                 <div class="mb-8 pt-8 border-t border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Analytics Configuration</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Google Analytics ID</label>
                            <input v-model="socialAnalyticsSettings.googleAnalyticsId" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all" placeholder="G-XXXXXXXXXX">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Facebook Pixel ID</label>
                            <input v-model="socialAnalyticsSettings.facebookPixelId" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all" placeholder="XXXXXXXXXX">
                        </div>
                    </div>
                 </div>

            </div>


            <!-- Notifications Tab -->
            <div v-else-if="activeTab === 'notifications'" class="p-8 max-w-5xl">
                 <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Notification Settings</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Order Pending</h3>
                                <p class="text-xs text-gray-500">Notify when a new order is placed.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="notificationSettings.orderPending" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                         <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Order Confirmation</h3>
                                <p class="text-xs text-gray-500">Notify when an order is confirmed.</p>
                            </div>
                             <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="notificationSettings.orderConfirmation" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                         <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Order Declined</h3>
                                <p class="text-xs text-gray-500">Notify when an order is declined.</p>
                            </div>
                             <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="notificationSettings.orderDeclined" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                         <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Newsletter Subscription</h3>
                                <p class="text-xs text-gray-500">Notify when a user subscribes to the newsletter.</p>
                            </div>
                             <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="notificationSettings.newsletter" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Settings Tab -->
            <div v-else-if="activeTab === 'seo'" class="p-8 max-w-5xl">
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">SEO Configuration</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Meta Title</label>
                            <input v-model="seoSettings.metaTitle" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-start">
                            <label class="text-sm font-bold text-gray-700 pt-2">Meta Description</label>
                            <textarea v-model="seoSettings.metaDescription" rows="3" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-center">
                            <label class="text-sm font-bold text-gray-700">Meta Keywords</label>
                            <input v-model="seoSettings.metaKeywords" type="text" class="w-full px-4 py-2 bg-gray-100 border-none rounded text-sm text-gray-700 focus:ring-0 transition-all">
                        </div>
                         <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4 items-start">
                            <label class="text-sm font-bold text-gray-700 pt-2">Meta Image</label>
                            <div>
                                <div class="relative w-48 h-32 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center mb-2 group">
                                     <span class="text-gray-400 text-xs">No image selected</span>
                                </div>
                                <button class="text-sm text-blue-500 hover:underline">Choose File</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Save Button (Global) -->
             <div class="px-8 pb-8 flex justify-end">
                 <button @click="saveSettings" class="px-6 py-2 bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition-colors shadow-sm">Save Settings</button>
            </div>

        </div>
    </div>
  </div>
</template>
