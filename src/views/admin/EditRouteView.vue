<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const routeData = ref({
    from: '',
    to: '',
    distance: '',
    time: '',
    status: 'Active'
})

onMounted(() => {
    // In a real app, fetch data using route.params.id
    console.log('Fetching route data for ID:', route.params.id)
    // Mock data for demonstration
    routeData.value = {
        from: 'Dhaka',
        to: 'Chittagong',
        distance: '265',
        time: '5h 30m',
        status: 'Active'
    }
})

const updateRoute = () => {
    // In a real app, this would be an API call
    console.log('Updating route:', routeData.value)
    // Mock success
    router.push('/admin/routes')
}

const cancel = () => {
    router.back()
}
</script>

<template>
  <div class=" bg-gray-50/50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-3xl mx-auto">
        
        <!-- Navigation -->
        <nav class="flex items-center text-sm text-gray-500 mb-6">
            <button @click="router.back()" class="flex items-center hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Routes
            </button>
            <span class="mx-3 text-gray-300">/</span>
            <span class="text-gray-900 font-medium">Edit Route</span>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Route Details</h1>
            <p class="mt-2 text-gray-500">Update the endpoints, distance, or travel time for this route.</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <!-- Progress / Visual Indicator (Optional decorative element) -->
            <div class="h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 w-full"></div>

            <div class="p-8">
                <div class="grid gap-y-8 gap-x-8">
                    
                    <!-- Route Points Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 text-gray-900">
                            <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            </div>
                            <h3 class="text-lg font-semibold">Route Endpoints</h3>
                        </div>

                        <div class="relative bg-gray-50/50 p-6 rounded-xl border border-gray-100">
                            <!-- Visual Connector Line -->
                            <div class="absolute left-[39px] top-[50px] bottom-[50px] w-0.5 bg-gray-200 border-l border-dashed border-gray-300"></div>

                            <div class="space-y-6 relative">
                                <!-- Origin -->
                                <div class="grid grid-cols-1 md:grid-cols-[120px_1fr] gap-4 items-start">
                                    <label class="text-sm font-medium text-gray-700 pt-3 md:text-right">Origin</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <div class="w-2.5 h-2.5 rounded-full ring-2 ring-emerald-500 bg-white"></div>
                                        </div>
                                        <input v-model="routeData.from" type="text" class="block w-full pl-8 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all shadow-sm" placeholder="Start location (e.g. Dhaka)">
                                    </div>
                                </div>

                                <!-- Destination -->
                                <div class="grid grid-cols-1 md:grid-cols-[120px_1fr] gap-4 items-start">
                                    <label class="text-sm font-medium text-gray-700 pt-3 md:text-right">Destination</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <input v-model="routeData.to" type="text" class="block w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all shadow-sm" placeholder="End location (e.g. Chittagong)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100" />

                    <!-- Logistics -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 text-gray-900">
                             <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-semibold">Logistics Details</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Total Distance</label>
                                <div class="relative">
                                    <input v-model="routeData.distance" type="text" class="block w-full pr-12 pl-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm outline-none" placeholder="0">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">km</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Approximate driving distance.</p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Estimated Duration</label>
                                <div class="relative">
                                     <input v-model="routeData.time" type="text" class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm outline-none" placeholder="e.g. 5h 30m">
                                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Average travel time incl. stops.</p>
                            </div>
                        </div>
                    </div>

                     <hr class="border-gray-100" />

                     <!-- Status -->
                     <div class="space-y-6">
                         <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                             <div>
                                 <h4 class="text-sm font-semibold text-gray-900">Route Status</h4>
                                 <p class="text-sm text-gray-500">Make this route available for booking immediately.</p>
                             </div>
                             <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="routeData.status" :true-value="'Active'" :false-value="'Inactive'" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                            </label>
                         </div>
                     </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex items-center justify-between">
                <button @click="cancel" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg hover:bg-white transition-colors">
                    Cancel
                </button>
                <div class="flex gap-3">
                    <button @click="updateRoute" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Update Route
                    </button>
                </div>
            </div>

        </div>
    </div>
  </div>
</template>
