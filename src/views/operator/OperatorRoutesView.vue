<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useOperatorRouteStore } from '@/stores/operator/route'

const router = useRouter()
const store  = useOperatorRouteStore()

const { routes: routesList, loading, error: apiError, noCompany } = storeToRefs(store)

const searchQuery    = ref('')
const filteredRoutes = store.filteredRoutes(searchQuery)

onMounted(store.fetch)

// ── Form (shared for Add + Edit) ──────────────────────────
const showForm    = ref(false)
const formErrors  = ref({})
const formLoading = ref(false)
const editingId   = ref(null) // null = adding new

const emptyForm = () => ({ source_city: '', destination_city: '', distance_km: '' })
const form = reactive(emptyForm())

function openAdd() {
  editingId.value  = null
  formErrors.value = {}
  Object.assign(form, emptyForm())
  showForm.value   = true
}

function openEdit(route) {
  editingId.value  = route.id
  formErrors.value = {}
  Object.assign(form, {
    source_city:      route.source_city,
    destination_city: route.destination_city,
    distance_km:      route.distance_km ?? ''
  })
  showForm.value = true
}

async function saveForm() {
  formErrors.value = {}
  formLoading.value = true
  try {
    if (editingId.value) {
      await store.update(editingId.value, { ...form })
    } else {
      await store.create({ ...form })
    }
    showForm.value = false
  } catch (err) {
    formErrors.value = err.response?.status === 422
      ? (err.response.data.errors || {})
      : { general: err.response?.data?.message || 'Something went wrong.' }
  } finally {
    formLoading.value = false
  }
}

async function confirmDelete(route) {
  if (!confirm(`Delete route "${route.source_city} → ${route.destination_city}"?`)) return
  try {
    await store.remove(route.id)
  } catch {
    alert('Failed to delete route.')
  }
}
</script>

<template>
  <div class="font-sans text-gray-700">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">My Routes</h1>
        <p class="text-sm text-gray-500 mt-1">Manage the destinations and routes your buses service.</p>
      </div>
      <button
        v-if="!noCompany"
        @click="openAdd"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition-colors shadow-sm shadow-emerald-700/20"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Route
      </button>
    </div>

    <!-- No Company Setup Card -->
    <div v-if="noCompany" class="flex flex-col items-center justify-center py-8">
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm w-full max-w-lg overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
        <div class="px-10 py-10 text-center">
          <div class="mx-auto w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          <h2 class="text-xl font-bold text-gray-900 mb-2">Set Up Your Company First</h2>
          <p class="text-sm text-gray-500 leading-relaxed mb-8">
            Before you can add routes, you need to register your <strong>company profile</strong>.
          </p>
          <button
            @click="router.push({ name: 'operator-company' })"
            class="inline-flex items-center gap-2 px-8 py-3 bg-emerald-700 text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition-colors shadow-sm shadow-emerald-700/20"
          >
            Set Up Company Profile
          </button>
        </div>
      </div>
    </div>

    <!-- API Error -->
    <div v-if="apiError" class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-5 py-3 rounded-xl">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ apiError }}
    </div>

    <!-- Container -->
    <div v-if="!noCompany" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <!-- Search Row -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Route List</h3>
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text" placeholder="Search cities..."
            class="pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm w-full sm:w-64 focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-shadow"
          >
          <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center py-16">
        <div class="flex items-center gap-3 text-emerald-700">
          <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
          </svg>
          <span class="text-sm font-medium">Loading your routes...</span>
        </div>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/60 border-b border-gray-100">
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Source City</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Destination City</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Distance</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="r in filteredRoutes" :key="r.id" class="hover:bg-gray-50/40 transition-colors">
              <td class="px-6 py-4">
                <span class="text-sm font-bold text-gray-900">{{ r.source_city }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-bold text-gray-900">{{ r.destination_city }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border bg-slate-50 text-slate-600 border-slate-200">
                  {{ r.distance_km || 'N/A' }} km
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="inline-flex items-center gap-1">
                  <!-- Edit -->
                  <button @click="openEdit(r)"
                    class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                    title="Edit route">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <!-- Delete -->
                  <button @click="confirmDelete(r)"
                    class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                    title="Delete route">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="filteredRoutes.length === 0">
              <td colspan="4" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                  </div>
                  <p class="text-gray-500 font-medium">No routes found</p>
                  <p class="text-sm text-gray-400">Try adjusting your search or add a new route.</p>
                  <button @click="openAdd" class="mt-1 px-4 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                    Add First Route
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Slide-Over Backdrop -->
    <Transition name="backdrop">
      <div v-if="showForm" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40" @click="showForm = false" />
    </Transition>

    <!-- Slide-Over Panel -->
    <Transition name="slideover">
      <div v-if="showForm" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 flex flex-col">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
          <div>
            <h2 class="text-lg font-bold text-gray-900">{{ editingId ? 'Edit Route' : 'Add New Route' }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ editingId ? 'Update the route details.' : 'Define a new operational route.' }}</p>
          </div>
          <button @click="showForm = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="saveForm" class="flex-1 overflow-y-auto px-6 py-6 space-y-5">
          <div v-if="formErrors.general" class="flex items-center gap-2 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3 rounded-xl">
            {{ formErrors.general }}
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Source City <span class="text-rose-500">*</span></label>
            <input v-model="form.source_city" type="text" placeholder="e.g. Dhaka"
              class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
              :class="formErrors.source_city ? 'border-rose-400 bg-rose-50' : 'border-gray-200 bg-gray-50'"
            >
            <p v-if="formErrors.source_city" class="mt-1 text-xs text-rose-500">{{ formErrors.source_city[0] }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Destination City <span class="text-rose-500">*</span></label>
            <input v-model="form.destination_city" type="text" placeholder="e.g. Chittagong"
              class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
              :class="formErrors.destination_city ? 'border-rose-400 bg-rose-50' : 'border-gray-200 bg-gray-50'"
            >
            <p v-if="formErrors.destination_city" class="mt-1 text-xs text-rose-500">{{ formErrors.destination_city[0] }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Distance (km) <span class="text-rose-500">*</span></label>
            <input v-model="form.distance_km" type="number" placeholder="250"
              class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
              :class="formErrors.distance_km ? 'border-rose-400 bg-rose-50' : 'border-gray-200 bg-gray-50'"
            >
            <p v-if="formErrors.distance_km" class="mt-1 text-xs text-rose-500">{{ formErrors.distance_km[0] }}</p>
          </div>
        </form>

        <div class="px-6 py-5 border-t border-gray-100 flex items-center justify-end gap-3">
          <button @click="showForm = false" :disabled="formLoading"
            class="px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors disabled:opacity-50">
            Cancel
          </button>
          <button @click="saveForm" :disabled="formLoading"
            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-emerald-700 rounded-xl hover:bg-emerald-800 transition-colors shadow-sm disabled:opacity-70">
            <svg v-if="formLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            {{ formLoading ? 'Saving...' : (editingId ? 'Save Changes' : 'Add Route') }}
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.slideover-enter-active,
.slideover-leave-active { transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }
.slideover-enter-from,
.slideover-leave-to { transform: translateX(100%); }

.backdrop-enter-active,
.backdrop-leave-active { transition: opacity 0.25s ease; }
.backdrop-enter-from,
.backdrop-leave-to { opacity: 0; }
</style>
