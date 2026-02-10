<script setup>
import { ref, onMounted } from 'vue'

const fromLocation = ref('')
const toLocation = ref('')
const journeyDate = ref('')
const cities = ref([])

const fetchCities = async () => {
  // Simulator API call
  // In a real application, replace this with:
  // const response = await fetch('YOUR_API_ENDPOINT/cities')
  // cities.value = await response.json()
  
  // Mock data for demonstration
  setTimeout(() => {
    cities.value = [
      { id: 1, name: 'New York' },
      { id: 2, name: 'Los Angeles' },
      { id: 3, name: 'Chicago' },
      { id: 4, name: 'Houston' },
      { id: 5, name: 'Phoenix' },
      { id: 6, name: 'Philadelphia' },
      { id: 7, name: 'San Antonio' },
      { id: 8, name: 'San Diego' },
      { id: 9, name: 'Dallas' },
      { id: 10, name: 'San Jose' }
    ]
  }, 500)
}

import { useRouter } from 'vue-router'
const router = useRouter()

const handleSearch = () => {
    router.push({
        name: 'search',
        query: {
            from: fromLocation.value,
            to: toLocation.value,
            date: journeyDate.value
        }
    })
}

onMounted(() => {
  fetchCities()
})

const featuredEvents = [
  {
    id: 1,
    title: 'New York to Boston',
    date: 'Daily',
    location: 'Express Service',
    price: '$35',
    image: 'https://images.unsplash.com/photo-1570125909232-eb2b9b149dd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    category: 'Popular'
  },
  {
    id: 2,
    title: 'San Francisco to Los Angeles',
    date: 'Daily',
    location: 'Sleeper & Seater',
    price: '$65',
    image: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    category: 'Best Seller'
  },
  {
    id: 3,
    title: 'London to Manchester',
    date: 'Weekends',
    location: 'Luxury Coach',
    price: '$40',
    image: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    category: 'Luxury'
  },
  {
    id: 4,
    title: 'Chicago to Detroit',
    date: 'Daily',
    location: 'Business Class',
    price: '$50',
    image: 'https://images.unsplash.com/photo-1570125909232-eb2b9b149dd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    category: 'Economy'
  }
]

const busOperators = [
  { id: 1, name: 'GreenLine', logo: 'https://jessoreinfo.com/wp-content/uploads/2025/06/Green-Line-Paribahan-Jessore.png' },
  { id: 2, name: 'Nirala', logo: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvNW_PBBSli0RiWZP856X8Ucj5nQLivPcp8pAMQXzJ7diJ09mLTi-4KZoFhqaJu8mj_Dk&usqp=CAU' },
  { id: 3, name: 'Hanif', logo: 'https://hanifenterprisebd.com/images/hanif/bus-1.jpg' },
  { id: 4, name: 'Ena', logo: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTne55ReqI9IHQwd15VM6WHePWP-bBGR_TYSA&s' },
  { id: 5, name: 'Shyamoli', logo: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80' },
  { id: 6, name: 'Saintmartin', logo: 'https://images.unsplash.com/photo-1570125909232-eb2b9b149dd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80' }
]
</script>

<template>
  <div class="home-view">
    <!-- Hero Section -->
    <section class="relative h-[600px] flex items-center justify-center bg-gray-900 overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Luxury Bus" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-gray-900/50"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <span class="inline-block py-1 px-3 rounded-full bg-indigo-600/30 border border-indigo-500 text-indigo-300 text-sm font-semibold mb-6 backdrop-blur-sm">Travel in Comfort</span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight leading-tight">
                Discover & Book <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Seamless Bus Journeys</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-10 max-w-2xl mx-auto">
                Secure your tickets for the best routes and destinations. Enjoy safe, comfortable, and affordable travel.
            </p>
            
            <!-- Search Bar -->
            <div class="bg-white/10 backdrop-blur-md p-2 rounded-3xl border border-white/20 flex flex-col md:flex-row max-w-4xl mx-auto shadow-2xl">
                <!-- From Field -->
                <div class="flex-grow px-4 py-3 border-b md:border-b-0 md:border-r border-gray-600 relative">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wide ml-8 mb-1">From</label>
                    <div class="relative flex items-center">
                        <svg class="w-5 h-5 text-indigo-400 absolute left-0 ml-1 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <select v-model="fromLocation" class="bg-transparent text-white focus:outline-none w-full pl-8 pr-8 font-medium appearance-none cursor-pointer">
                            <option value="" class="bg-gray-800 text-gray-400">Select Departure</option>
                            <option v-for="city in cities" :key="city.id" :value="city.name" class="bg-gray-800 text-white hover:bg-gray-700 py-2">{{ city.name }}</option>
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-0 mr-1 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- To Field -->
                <div class="flex-grow px-4 py-3 border-b md:border-b-0 md:border-r border-gray-600 relative">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wide ml-8 mb-1">To</label>
                    <div class="relative flex items-center">
                        <svg class="w-5 h-5 text-purple-400 absolute left-0 ml-1 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                         <select v-model="toLocation" class="bg-transparent text-white focus:outline-none w-full pl-8 pr-8 font-medium appearance-none cursor-pointer">
                            <option value="" class="bg-gray-800 text-gray-400">Select Destination</option>
                            <option v-for="city in cities" :key="city.id" :value="city.name" class="bg-gray-800 text-white hover:bg-gray-700 py-2">{{ city.name }}</option>
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-0 mr-1 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Date Field -->
                <div class="px-4 py-3 border-b md:border-b-0 md:border-r border-gray-600 md:w-48 relative">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wide ml-8 mb-1">Journey Date</label>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-pink-400 mr-3 absolute pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <input v-model="journeyDate" type="date" class="bg-transparent text-white placeholder-gray-400 focus:outline-none w-full pl-8 font-medium [color-scheme:dark] cursor-pointer">
                    </div>
                </div>

                <div class="p-2 flex items-center">
                    <button @click="handleSearch" class="w-full md:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-2xl px-8 py-4 font-bold transition-all transform hover:scale-105 shadow-lg flex items-center justify-center">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 rounded-2xl bg-gray-50 hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors duration-300">
                        <svg class="w-7 h-7 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Instant Confirmation</h3>
                    <p class="text-gray-600">Get your tickets delivered to your email instantly after booking. No waiting times.</p>
                </div>
                <div class="p-6 rounded-2xl bg-gray-50 hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors duration-300">
                        <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Secure Payments</h3>
                    <p class="text-gray-600">We use industry-standard encryption to ensure your personal and payment details are safe.</p>
                </div>
                <div class="p-6 rounded-2xl bg-gray-50 hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 mx-auto bg-pink-100 rounded-full flex items-center justify-center mb-6 group-hover:bg-pink-600 transition-colors duration-300">
                        <svg class="w-7 h-7 text-pink-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Our dedicated support team is available round the clock to assist you with any queries.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Popular Routes</h2>
                    <p class="text-gray-600">Top destinations for your next journey.</p>
                </div>
                <button class="hidden md:inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">
                    View all routes
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div v-for="event in featuredEvents" :key="event.id" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="relative h-48 overflow-hidden">
                        <img :src="event.image" :alt="event.title" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur text-xs font-bold text-gray-900 rounded-full uppercase tracking-wide">
                                {{ event.category }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-gray-500 text-xs font-medium mb-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ event.date }}
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ event.title }}</h3>
                        <div class="flex items-center text-gray-500 text-sm mb-4">
                             <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ event.location }}
                        </div>
                        <div class="flex items-center justify-between mt-4 text-sm pt-4 border-t border-gray-100">
                            <span class="text-gray-500">Starting from</span>
                            <span class="text-xl font-bold text-indigo-600">{{ event.price }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
             <div class="mt-8 text-center md:hidden">
                <button class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">
                    View all routes
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Bus Operators Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Top Bus Operators</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">We partner with the best in the business to ensure a safe and comfortable journey for you.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 items-center">
                <div v-for="operator in busOperators" :key="operator.id" class="flex flex-col items-center group">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-100 group-hover:border-indigo-500 transition-colors p-1">
                        <img :src="operator.logo" :alt="operator.name" class="w-full h-full object-cover rounded-full grayscale group-hover:grayscale-0 transition-all duration-300">
                    </div>
                    <span class="mt-4 font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">{{ operator.name }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter CTA -->
    <!-- Newsletter CTA -->
    <section class="py-24 relative overflow-hidden bg-gray-900">
        <!-- Abstract Background -->
        <div class="absolute inset-0 opacity-20">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="url(#grad1)" />
                <defs>
                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:rgb(56, 189, 248);stop-opacity:1" />
                        <stop offset="100%" style="stop-color:rgb(99, 102, 241);stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto bg-white/5 backdrop-blur-lg rounded-3xl p-8 md:p-12 border border-white/10 shadow-2xl">
                <div class="text-center">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 tracking-tight">
                        Start Your Journey with Us
                    </h2>
                    <p class="text-gray-300 text-lg mb-8 max-w-2xl mx-auto">
                        Subscribe for exclusive travel deals, new route announcements, and faster booking experiences.
                    </p>
                    
                    <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                        <input type="email" placeholder="Enter your email address" class="flex-grow px-6 py-3.5 rounded-xl bg-gray-800 border border-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all">
                        <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700 text-white font-bold shadow-lg transform transition hover:scale-105 active:scale-95">
                            Join Now
                        </button>
                    </form>
                    <p class="mt-4 text-xs text-gray-500">
                        By subscribing, you agree to our Terms & Privacy Policy.
                    </p>
                </div>
            </div>
        </div>
    </section>
  </div>
</template>
