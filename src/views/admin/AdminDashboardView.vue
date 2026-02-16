<script setup>
import { ref } from 'vue'
import DashboardBarChart from '@/components/charts/DashboardBarChart.vue'
import DashboardDonutChart from '@/components/charts/DashboardDonutChart.vue'

const timeRange = ref('Month')

// --- Stats Data (Top Cards) ---
const stats = ref([
  { 
      title: 'Total Revenue', 
      value: '৳ 350,000', 
      change: '-10.5%', 
      trend: 'down', 
      icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 
      iconBg: 'bg-blue-50 text-blue-600',
      changeColor: 'text-rose-600 bg-rose-50 border-rose-100'
  },
  { 
      title: 'Tickets Sold', 
      value: '1,245', 
      change: '+18.2%', 
      trend: 'up', 
      icon: 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z', 
      iconBg: 'bg-indigo-50 text-indigo-600',
      changeColor: 'text-emerald-600 bg-emerald-50 border-emerald-100'
  },
  { 
      title: 'Active Buses', 
      value: '42', 
      change: '+2', 
      trend: 'up', 
      icon: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 
      iconBg: 'bg-amber-50 text-amber-600',
      changeColor: 'text-emerald-600 bg-emerald-50 border-emerald-100'
  },
  { 
      title: 'Today\'s Passengers', 
      value: '185', 
      change: '+12%', 
      trend: 'up', 
      icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 
      iconBg: 'bg-purple-50 text-purple-600',
      changeColor: 'text-emerald-600 bg-emerald-50 border-emerald-100'
  }
])

// --- Chart Data (Ticket Sales Analytics) ---
const barChartData = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
  datasets: [
    {
      label: 'Ticket Sold',
       backgroundColor: '#2563EB', // Blue
       hoverBackgroundColor: '#1D4ED8',
      data: [120, 160, 220, 180, 190, 130, 80, 140, 90, 120, 220, 180],
       borderRadius: 4,
       barPercentage: 0.6,
       categoryPercentage: 0.7
    },
     {
      label: 'Total Capacity',
      backgroundColor: '#E5E7EB', // Gray
       hoverBackgroundColor: '#D1D5DB',
      data: [200, 200, 250, 200, 220, 150, 120, 180, 150, 150, 250, 200],
      borderRadius: 4,
      barPercentage: 0.6,
      categoryPercentage: 0.7
    }
  ]
}

const barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'top',
            align: 'end',
            labels: {
                usePointStyle: true,
                pointStyle: 'rectRounded',
                boxWidth: 8,
                color: '#6B7280',
                font: { size: 12 }
            }
        },
        tooltip: {
            backgroundColor: '#FFFFFF',
            titleColor: '#1F2937',
            bodyColor: '#4B5563',
            borderColor: '#E5E7EB',
            borderWidth: 1,
            displayColors: false,
            padding: 12,
            callbacks: {
                title: () => 'Monthly Stats',
                label: (context) => `${context.dataset.label}: ${context.raw}`
            }
        }
    },
    scales: {
        y: {
            border: { display: false },
            grid: { display: true, borderDash: [4, 4], color: '#F3F4F6' },
            ticks: { color: '#9CA3AF', font: { size: 11 } }
        },
        x: {
            border: { display: false },
             grid: { display: false },
             ticks: { color: '#9CA3AF', font: { size: 11 } }
        }
    }
}

// --- Chart Data (Seat Class Distribution) ---
const donutChartData = {
  labels: ['AC Business', 'AC Economy', 'Non-AC'],
  datasets: [
    {
      backgroundColor: ['#2563EB', '#60A5FA', '#BFDBFE'], // Dark Blue, Blue, Light Blue
      data: [35, 45, 20],
      borderWidth: 0,
      hoverOffset: 4
    }
  ]
}

// --- Recent Bookings Mock Data ---
const recentBookings = ref([
    { id: 'BK-7829', customer: 'John Doe', amount: '৳ 1,400', date: 'March 12, 2026', route: 'Dhaka - Chittagong', status: 'Confirmed', avatar: 'https://ui-avatars.com/api/?name=John+Doe&background=EFF6FF&color=2563EB' },
    { id: 'BK-7830', customer: 'Jane Smith', amount: '৳ 850', date: 'March 12, 2026', route: 'Dhaka - Sylhet', status: 'Pending', avatar: 'https://ui-avatars.com/api/?name=Jane+Smith&background=FEF3C7&color=D97706' },
    { id: 'BK-7831', customer: 'Rahim Uddin', amount: '৳ 2,200', date: 'March 11, 2026', route: 'Dhaka - Cox\'s Bazar', status: 'Confirmed', avatar: 'https://ui-avatars.com/api/?name=Rahim+Uddin&background=ECFDF5&color=059669' },
    { id: 'BK-7832', customer: 'Karim Khan', amount: '৳ 600', date: 'March 10, 2026', route: 'Chittagong - Dhaka', status: 'Cancelled', avatar: 'https://ui-avatars.com/api/?name=Karim+Khan&background=FEF2F2&color=DC2626' },
])
</script>

<template>
  <div class="p-6 max-w-[1920px] mx-auto min-h-screen">
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div v-for="stat in stats" :key="stat.title" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center">
        <div :class="[stat.iconBg, 'w-12 h-12 rounded-full flex items-center justify-center shrink-0 mr-4']">
           <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon"></path></svg>
        </div>
        
        <div class="flex-grow">
           <p class="text-sm text-gray-500 font-medium mb-1">{{ stat.title }}</p>
           <div class="flex items-center gap-2">
               <h3 class="text-2xl font-bold text-gray-900">{{ stat.value }}</h3>
               <span :class="[stat.changeColor, 'text-xs font-medium px-1.5 py-0.5 rounded border flex items-center gap-0.5']">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.trend === 'up' ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3'"></path>
                    </svg>
                   {{ stat.change }}
               </span>
           </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Bar Chart: Ticket Sales Analytics -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                     <h3 class="text-lg font-bold text-gray-900">Sales Overview</h3>
                     <div class="flex items-center mt-2 gap-2">
                         <span class="text-2xl font-bold text-gray-900">1,245</span>
                         <span class="text-sm font-medium text-emerald-600 flex items-center">
                             <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                             12.5%
                         </span>
                         <span class="text-xs text-gray-400">vs last month</span>
                     </div>
                </div>
                <div class="flex gap-2">
                     <button class="px-3 py-1.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                         Month <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                     </button>
                </div>
            </div>
            
            <div class="h-[280px]">
                <DashboardBarChart :chartData="barChartData" :chartOptions="barChartOptions" />
            </div>
        </div>

        <!-- Donut Chart: Seat Class Distribution -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900">Ticket Class Sold</h3>
                 <button class="p-1.5 text-gray-400 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                 </button>
            </div>
            
            <div class="flex-grow relative min-h-[200px] mb-6">
                <DashboardDonutChart :chartData="donutChartData" />
                 <!-- Center Text Override within Component if needed, but styling is external here effectively via the div in component -->
            </div>

            <div class="space-y-4">
                 <div class="flex justify-between items-center text-sm">
                     <div class="flex items-center gap-2">
                         <span class="w-2.5 h-2.5 rounded-full bg-[#2563EB]"></span>
                         <span class="text-gray-600 font-medium">AC Business</span>
                     </div>
                     <span class="font-bold text-gray-800">35%</span>
                 </div>
                 <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-50">
                     <div class="flex items-center gap-2">
                         <span class="w-2.5 h-2.5 rounded-full bg-[#60A5FA]"></span>
                         <span class="text-gray-600 font-medium">AC Economy</span>
                     </div>
                     <span class="font-bold text-gray-800">45%</span>
                 </div>
                 <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-50">
                     <div class="flex items-center gap-2">
                         <span class="w-2.5 h-2.5 rounded-full bg-[#BFDBFE]"></span>
                         <span class="text-gray-600 font-medium">Non-AC</span>
                     </div>
                     <span class="font-bold text-gray-800">20%</span>
                 </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-lg font-bold text-gray-900">Recent Bookings</h3>
            
            <div class="flex gap-3 w-full md:w-auto">
                <div class="relative flex-grow md:flex-grow-0">
                    <input type="text" placeholder="Search booking ID..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm w-full md:w-64 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-shadow">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="p-4 w-12 pl-6">
                            <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </th>
                        <th class="p-4 text-xs font-semibold text-gray-500">Booking ID</th>
                        <th class="p-4 text-xs font-semibold text-gray-500">Customer</th>
                        <th class="p-4 text-xs font-semibold text-gray-500">Route</th>
                        <th class="p-4 text-xs font-semibold text-gray-500">Date</th>
                        <th class="p-4 text-xs font-semibold text-gray-500">Amount</th>
                        <th class="p-4 text-xs font-semibold text-gray-500">Status</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                     <tr v-for="item in recentBookings" :key="item.id" class="hover:bg-gray-50/50 transition-colors">
                        <td class="p-4 pl-6">
                            <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </td>
                        <td class="p-4 text-sm font-bold text-gray-900">{{ item.id }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                 <div class="w-8 h-8 rounded-full bg-gray-100 overflow-hidden">
                                     <img :src="item.avatar" alt="Avatar" class="w-full h-full object-cover">
                                 </div>
                                 <span class="text-sm font-bold text-gray-900">{{ item.customer }}</span>
                            </div>
                        </td>
                         <td class="p-4 text-sm font-medium text-gray-700">{{ item.route }}</td>
                         <td class="p-4 text-sm font-medium text-gray-500">{{ item.date }}</td>
                         <td class="p-4 text-sm font-bold text-gray-900">{{ item.amount }}</td>
                         <td class="p-4">
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border" 
                                :class="{
                                    'text-amber-600 bg-amber-50 border-amber-200': item.status === 'Pending',
                                    'text-emerald-600 bg-emerald-50 border-emerald-200': item.status === 'Confirmed',
                                    'text-rose-600 bg-rose-50 border-rose-200': item.status === 'Cancelled'
                                }">
                                 <span class="w-1.5 h-1.5 rounded-full mr-1.5" 
                                     :class="{
                                         'bg-amber-500': item.status === 'Pending',
                                         'bg-emerald-500': item.status === 'Confirmed',
                                         'bg-rose-500': item.status === 'Cancelled'
                                     }"></span>
                                 {{ item.status }}
                             </span>
                         </td>
                         <td class="p-4 text-right pr-6">
                             <div class="flex items-center justify-end gap-3">
                                 <button class="text-gray-400 hover:text-indigo-600 transition-colors">
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                 </button>
                                 <button class="text-gray-400 hover:text-green-600 transition-colors">
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                 </button>
                                 <button class="text-gray-400 hover:text-rose-600 transition-colors">
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                 </button>
                             </div>
                         </td>
                     </tr>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</template>
