<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const userId = route.params.id

const form = ref({
    name: '',
    email: '',
    phone: '',
    role: 'user',
    status: 'active'
})

const isLoading = ref(false)

// Simulation of fetching user data
onMounted(() => {
    // In a real app, fetch from API using userId
    // For now, valid dummy data to visualize the design
    if (userId) {
        // Simulating data load
        form.value = {
            name: 'Kathryn Murphy',
            email: 'kathryn@example.com',
            phone: '(555) 123-4567',
            role: 'operator', 
            status: 'active'
        }
    }
})

const handleSubmit = async () => {
    isLoading.value = true
    // Simulate API call
    setTimeout(() => {
        isLoading.value = false
        // Here we would typically update the user via API
        router.push('/admin/users')
    }, 1000)
}
</script>

<template>
  <div class="p-8 max-w-[1600px] mx-auto font-sans text-slate-800 bg-white">
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <div class="flex items-center text-sm text-gray-500 mb-2">
            <router-link to="/admin/users" class="hover:text-gray-900 transition-colors">Users</router-link>
            <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-900 font-medium">Edit User</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Edit User Profile</h1>
        <p class="text-sm text-gray-500 mt-1">Manage user account details and system permissions.</p>
    </div>

    <!-- Main Content -->
    <div class="w-full">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <!-- Form Header with Avatar Placeholder -->
            <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-2xl font-bold text-gray-400 shadow-inner">
                    {{ form.name ? form.name.charAt(0).toUpperCase() : 'U' }}
                </div>
                <div>
                     <h2 class="text-lg font-semibold text-gray-900">{{ form.name || 'User Name' }}</h2>
                     <p class="text-sm text-gray-500">{{ form.email || 'user@example.com' }}</p>
                     <span class="inline-flex mt-2 items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                        ID: {{ userId || 'NEW' }}
                     </span>
                </div>
            </div>

            <form @submit.prevent="handleSubmit" class="p-8 space-y-8">
                
                <!-- Personal Information Section -->
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Personal Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-black focus:ring-1 focus:ring-black transition-all"
                                placeholder="e.g. John Doe"
                                required
                            >
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input 
                                v-model="form.email" 
                                type="email" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-black focus:ring-1 focus:ring-black transition-all"
                                placeholder="e.g. john@example.com"
                                required
                            >
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input 
                                v-model="form.phone" 
                                type="tel" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-black focus:ring-1 focus:ring-black transition-all"
                                placeholder="e.g. +1 (555) 000-0000"
                            >
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Permissions Section -->
                <div>
                     <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Access & Permissions
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">System Role</label>
                            <div class="relative">
                                <select 
                                    v-model="form.role"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-black focus:ring-1 focus:ring-black transition-all appearance-none cursor-pointer"
                                >
                                    <option value="user">Passenger</option>
                                    <option value="operator">Operator (Company Owner)</option>
                                    <option value="admin">Administrator</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Controls what feature the user can access.</p>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Account Status</label>
                             <div class="relative">
                                <select 
                                    v-model="form.status"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-black focus:ring-1 focus:ring-black transition-all appearance-none cursor-pointer"
                                >
                                    <option value="active">Active</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">If blocked, user will be logged out immediately.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <button 
                        type="button" 
                        @click="router.push('/admin/users')"
                        class="px-6 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-100 transition-all"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        :disabled="isLoading"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-black rounded-full hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black disabled:opacity-70 disabled:cursor-not-allowed transition-all shadow-lg shadow-gray-200"
                    >
                        <span v-if="isLoading" class="flex items-center">
                             <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving Changes...
                        </span>
                        <span v-else>Save Changes</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
  </div>
</template>
