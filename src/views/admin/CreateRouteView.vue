<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const newRoute = ref({
    from: '',
    to: '',
    distance: '',
    time: '',
    status: 'Active'
})

const createRoute = () => {
    // In a real app, this would be an API call
    console.log('Creating route:', newRoute.value)
    // Mock success
    router.push('/admin/routes')
}

const cancel = () => {
    router.back()
}
</script>

<template>
  <div class="p-6 max-w-[1920px] mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <button @click="router.back()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Route</h1>
            <p class="text-sm text-gray-500">Add a new destination route to the system</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm max-w-3xl">
        <div class="p-8 space-y-8">
            
            <!-- Route Details Section -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Route Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From (Origin)</label>
                        <input v-model="newRoute.from" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-gray-400" placeholder="e.g. Dhaka">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To (Destination)</label>
                        <input v-model="newRoute.to" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-gray-400" placeholder="e.g. Chittagong">
                    </div>
                </div>
            </div>

            <!-- Logistics Section -->
            <div>
                 <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Logistics</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Distance (km)</label>
                        <div class="relative">
                            <input v-model="newRoute.distance" type="text" class="w-full pl-4 pr-12 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-gray-400" placeholder="0">
                            <span class="absolute right-4 top-2.5 text-gray-400 text-sm font-medium">km</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Time</label>
                        <input v-model="newRoute.time" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-gray-400" placeholder="e.g. 5h 30m">
                    </div>
                 </div>
            </div>

            <!-- Status Section -->
            <div>
                 <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Settings</h3>
                 <div class="flex items-center gap-4">
                     <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="newRoute.status" :true-value="'Active'" :false-value="'Inactive'" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Active Status</span>
                    </label>
                 </div>
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="bg-gray-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-gray-100 rounded-b-2xl">
            <button @click="cancel" class="px-5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button @click="createRoute" class="px-5 py-2.5 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Create Route
            </button>
        </div>
    </div>
  </div>
</template>
