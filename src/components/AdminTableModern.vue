<script setup>
import { computed, ref, defineProps } from 'vue'

const props = defineProps({
  columns: {
    type: Array,
    required: true, // [{ key: 'id', label: 'ID', class: '' }]
  },
  data: {
    type: Array,
    required: true
  },
  itemsPerPage: {
    type: Number,
    default: 10
  }
})

const currentPage = ref(1)

const totalPages = computed(() => {
    return Math.ceil(props.data.length / props.itemsPerPage)
})

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * props.itemsPerPage
    const end = start + props.itemsPerPage
    return props.data.slice(start, end)
})

const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++
}

const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--
}
</script>

<template>
  <div class="font-sans">
    <!-- Header/Title Slot -->
    <div v-if="$slots.header" class="mb-6 flex justify-between items-center">
       <slot name="header"></slot>
    </div>

    <!-- Modern Floating Table -->
    <div class="w-full">
      <!-- Table Header -->
      <div class="grid grid-cols-12 gap-4 px-6 py-3 mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">
        <div 
            v-for="col in columns" 
            :key="col.key" 
            :class="[col.gridSpan || 'col-span-1', col.class]"
        >
          {{ col.label }}
        </div>
      </div>

      <!-- Table Body -->
      <div class="space-y-3">
        <!-- Empty State -->
        <div v-if="data.length === 0" class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
            <div class="flex flex-col items-center justify-center text-gray-400">
                <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <span class="text-sm font-medium">No data found</span>
            </div>
        </div>

        <div 
            v-for="(item, index) in paginatedData" 
            :key="index" 
            class="group bg-white rounded-2xl p-4 shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 grid grid-cols-12 gap-4 items-center"
        >
           <div 
                v-for="col in columns" 
                :key="col.key" 
                :class="[col.gridSpan || 'col-span-1', col.bodyClass]"
                class="text-sm text-gray-600 font-medium"
            >
                <!-- Dynamic Slot -->
                <slot :name="`cell-${col.key}`" :item="item" :index="index">
                    {{ item[col.key] }}
                </slot>
           </div>
        </div>
      </div>
    </div>

    <!-- Pagination (Minimalist) -->
    <div v-if="totalPages > 1" class="mt-8 flex justify-center items-center space-x-4">
        <button 
            @click="prevPage" 
            :disabled="currentPage === 1"
            class="p-2 rounded-full bg-white text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 shadow-sm border border-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        
        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
            Page {{ currentPage }} / {{ totalPages }}
        </span>

        <button 
            @click="nextPage" 
            :disabled="currentPage === totalPages"
            class="p-2 rounded-full bg-white text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 shadow-sm border border-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>
  </div>
</template>
