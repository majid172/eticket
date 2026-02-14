<script setup>
import { computed, ref, defineProps, defineEmits } from 'vue'

const props = defineProps({
  columns: {
    type: Array,
    required: true,
    // Expected structure: [{ key: 'id', label: 'ID', class: '' }]
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

const goToPage = (page) => {
    currentPage.value = page
}
</script>

<template>
  <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden font-sans">
    <!-- Header/Title Slot -->
    <div v-if="$slots.header" class="px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center bg-gradient-to-r from-gray-50 to-white">
       <slot name="header"></slot>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
            <th v-for="col in columns" :key="col.key" class="px-8 py-4 font-semibold text-gray-400" :class="col.class">
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
           <!-- Empty State -->
           <tr v-if="data.length === 0">
               <td :colspan="columns.length" class="px-8 py-12 text-center text-gray-400">
                   <div class="flex flex-col items-center justify-center">
                       <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                       <span class="text-sm font-medium">No data found</span>
                   </div>
               </td>
           </tr>
           
           <tr v-for="(item, index) in paginatedData" :key="index" class="group hover:bg-indigo-50/30 transition-all duration-200">
              <td v-for="col in columns" :key="col.key" class="px-8 py-5 align-middle" :class="col.bodyClass">
                  <!-- Dynamic Slot for specific columns (e.g. #cell-status="{ item }") -->
                  <div class="transition-transform duration-200 group-hover:translate-x-1">
                      <slot :name="`cell-${col.key}`" :item="item" :index="index">
                          {{ item[col.key] }}
                      </slot>
                  </div>
              </td>
           </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="hasPagination && totalPages > 1" class="px-8 py-5 border-t border-gray-100 flex items-center justify-between bg-gray-50/30">
        <span class="text-sm text-gray-500 font-medium">Page {{ currentPage }} of {{ totalPages }}</span>
        <div class="flex space-x-2">
            <button 
                @click="prevPage" 
                :disabled="currentPage === 1"
                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm"
            >
                Previous
            </button>
            <button 
                @click="nextPage" 
                :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm"
            >
                Next
            </button>
        </div>
    </div>
  </div>
</template>
