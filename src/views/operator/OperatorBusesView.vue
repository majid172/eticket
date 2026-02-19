<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

// ─── Tabs ─────────────────────────────────────────────────────────────────────
const activeTab = ref('all')
const tabs = [
  { id: 'all',         label: 'All Buses',   icon: 'M4 6h16M4 12h16M4 18h16' },
  { id: 'active',      label: 'Active',      icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
  { id: 'maintenance', label: 'Maintenance', icon: 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 4a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4' },
  { id: 'inactive',    label: 'Inactive',    icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' },
]

// ─── State ─────────────────────────────────────────────────────────────────────
const buses       = ref([])
const loading     = ref(false)
const apiError    = ref('')
const noCompany   = ref(false)
const searchQuery = ref('')

// ─── Fetch buses from backend ──────────────────────────────────────────────────
async function fetchBuses() {
  loading.value   = true
  apiError.value  = ''
  noCompany.value = false
  try {
    const { data } = await api.get('/operator/buses')
    buses.value = data.data
  } catch (err) {
    const msg = err.response?.data?.message || ''
    if (err.response?.status === 403 && msg.toLowerCase().includes('company')) {
      noCompany.value = true
    } else {
      apiError.value = msg || 'Failed to load buses.'
    }
  } finally {
    loading.value = false
  }
}

onMounted(fetchBuses)

// ─── Computed: filter ──────────────────────────────────────────────────────────
const filteredBuses = computed(() => {
  let result = buses.value
  if (activeTab.value !== 'all') result = result.filter(b => b.status === activeTab.value)
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(b =>
      (b.bus_name  || '').toLowerCase().includes(q) ||
      (b.bus_number|| '').toLowerCase().includes(q)
    )
  }
  return result
})

// ─── Slide-over form ──────────────────────────────────────────────────────────
const showForm    = ref(false)
const editingId   = ref(null)
const formErrors  = ref({})
const formLoading = ref(false)

const emptyForm = () => ({
  bus_name: '', bus_number: '', bus_type: 'AC', total_seats: '', status: 'active'
})
const form = reactive(emptyForm())

const busTypes    = ['AC', 'Non-AC', 'Sleeper', 'Seater']
const busStatuses = ['active', 'maintenance', 'inactive']

function openAdd() {
  editingId.value = null
  formErrors.value = {}
  Object.assign(form, emptyForm())
  showForm.value = true
}

function openEdit(bus) {
  editingId.value = bus.id
  formErrors.value = {}
  Object.assign(form, {
    bus_name:    bus.bus_name    || '',
    bus_number:  bus.bus_number  || '',
    bus_type:    bus.bus_type    || 'AC',
    total_seats: bus.total_seats || '',
    status:      bus.status      || 'active',
  })
  showForm.value = true
}

async function saveForm() {
  formErrors.value = {}
  formLoading.value = true
  try {
    if (editingId.value) {
      await api.put(`/operator/buses/${editingId.value}`, form)
    } else {
      await api.post('/operator/buses', form)
    }
    showForm.value = false
    await fetchBuses()
  } catch (err) {
    if (err.response?.status === 422) {
      formErrors.value = err.response.data.errors || {}
    } else {
      formErrors.value = { general: err.response?.data?.message || 'Something went wrong.' }
    }
  } finally {
    formLoading.value = false
  }
}

async function deleteBus(id) {
  if (!confirm('Delete this bus from your fleet?')) return
  try {
    await api.delete(`/operator/buses/${id}`)
    await fetchBuses()
  } catch {
    alert('Failed to delete bus. Please try again.')
  }
}

// ─── Status helpers ───────────────────────────────────────────────────────────
const statusClass = (s) => ({
  active:      'bg-emerald-50 text-emerald-600 border-emerald-200',
  maintenance: 'bg-amber-50 text-amber-600 border-amber-200',
  inactive:    'bg-rose-50 text-rose-600 border-rose-200',
}[s] || 'bg-gray-50 text-gray-600 border-gray-200')

const dotClass = (s) => ({
  active: 'bg-emerald-500', maintenance: 'bg-amber-500', inactive: 'bg-rose-500',
}[s] || 'bg-gray-400')

</script>

<template>
  <div class="font-sans text-gray-700">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">My Buses</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your fleet — add, edit and monitor bus status.</p>
      </div>
      <button
        v-if="!noCompany"
        @click="openAdd"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition-colors shadow-sm shadow-emerald-700/20"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Bus
      </button>
    </div>

    <!-- ── No Company Setup Card ─────────────────────────────────────────────── -->
    <div v-if="noCompany" class="flex flex-col items-center justify-center py-8">
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm w-full max-w-lg overflow-hidden">
        <!-- Top gradient band -->
        <div class="h-1.5 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
        <div class="px-10 py-10 text-center">
          <!-- Icon -->
          <div class="mx-auto w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          <h2 class="text-xl font-bold text-gray-900 mb-2">Set Up Your Company First</h2>
          <p class="text-sm text-gray-500 leading-relaxed mb-8">
            Before you can add buses, you need to register your <strong>company profile</strong>.
            It only takes a minute and unlocks all operator features.
          </p>
          <!-- Steps -->
          <div class="flex items-start gap-3 text-left bg-gray-50 rounded-xl p-4 mb-8">
            <div class="flex flex-col gap-3 w-full text-sm text-gray-600">
              <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold flex-shrink-0">1</span>
                Fill in your company name &amp; contact details
              </div>
              <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>
                Save your profile to activate the account
              </div>
              <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold flex-shrink-0">3</span>
                Come back here to start adding buses
              </div>
            </div>
          </div>
          <button
            @click="router.push({ name: 'operator-company' })"
            class="inline-flex items-center gap-2 px-8 py-3 bg-emerald-700 text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition-colors shadow-sm shadow-emerald-700/20"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Set Up Company Profile
          </button>
        </div>
      </div>
    </div>

    <!-- API Error Banner -->
    <div v-if="apiError" class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-5 py-3 rounded-xl">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ apiError }}
    </div>

    <!-- Tabs + Search Row -->
    <div v-if="!noCompany" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 pt-5 pb-0 border-b border-gray-100">
        <!-- Tabs -->
        <div class="flex gap-1">
          <button
            v-for="tab in tabs" :key="tab.id"
            @click="activeTab = tab.id"
            class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 transition-all"
            :class="activeTab === tab.id
              ? 'border-emerald-600 text-emerald-700 bg-emerald-50/60'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon"/>
            </svg>
            {{ tab.label }}
            <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold"
              :class="activeTab === tab.id ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
              {{ tab.id === 'all' ? buses.length : buses.filter(b => b.status === tab.id).length }}
            </span>
          </button>
        </div>
        <!-- Search -->
        <div class="relative pb-3">
          <input
            v-model="searchQuery"
            type="text" placeholder="Search buses..."
            class="pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm w-64 focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-shadow"
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
          <span class="text-sm font-medium">Loading your fleet...</span>
        </div>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/60 border-b border-gray-100">
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Bus Info</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Type & Seats</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="bus in filteredBuses" :key="bus.id" class="hover:bg-gray-50/40 transition-colors">
              <td class="px-6 py-4">
                <div class="flex flex-col">
                  <span class="text-sm font-bold text-gray-900">{{ bus.bus_name || '—' }}</span>
                  <span class="text-xs text-gray-400 font-mono mt-0.5">{{ bus.bus_number }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex flex-col">
                  <span class="text-sm font-medium text-gray-800">{{ bus.bus_type }}</span>
                  <span class="text-xs text-emerald-600 font-medium mt-0.5">{{ bus.total_seats }} seats</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize" :class="statusClass(bus.status)">
                  <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="dotClass(bus.status)"></span>
                  {{ bus.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-1">
                  <button @click="openEdit(bus)" title="Edit"
                    class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button @click="deleteBus(bus.id)" title="Delete"
                    class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="filteredBuses.length === 0">
              <td colspan="4" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                  </div>
                  <p class="text-gray-500 font-medium">No buses found</p>
                  <p class="text-sm text-gray-400">Try adjusting your search or add a new bus.</p>
                  <button @click="openAdd" class="mt-1 px-4 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                    Add First Bus
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── Slide-Over Backdrop ──────────────────────────────────────────────── -->
    <Transition name="backdrop">
      <div
        v-if="showForm"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40"
        @click="showForm = false"
      />
    </Transition>

    <!-- ── Slide-Over Panel ─────────────────────────────────────────────────── -->
    <Transition name="slideover">
      <div
        v-if="showForm"
        class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 flex flex-col"
      >
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
          <div>
            <h2 class="text-lg font-bold text-gray-900">{{ editingId ? 'Edit Bus' : 'Add New Bus' }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ editingId ? 'Update your bus details.' : 'Add a new bus to your fleet.' }}</p>
          </div>
          <button @click="showForm = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Form Body -->
        <form @submit.prevent="saveForm" class="flex-1 overflow-y-auto px-6 py-6 space-y-5">

          <!-- General Error -->
          <div v-if="formErrors.general" class="flex items-center gap-2 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3 rounded-xl">
            {{ formErrors.general }}
          </div>

          <!-- Bus Name -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bus Name</label>
            <input v-model="form.bus_name" type="text" placeholder="e.g. Dhaka Express"
              class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
              :class="formErrors.bus_name ? 'border-rose-400 bg-rose-50' : 'border-gray-200 bg-gray-50'"
            >
            <p v-if="formErrors.bus_name" class="mt-1 text-xs text-rose-500">{{ formErrors.bus_name[0] }}</p>
          </div>

          <!-- Registration No -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Registration No. <span class="text-rose-500">*</span></label>
            <input v-model="form.bus_number" type="text" placeholder="e.g. DHA-GA-1234"
              class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-mono"
              :class="formErrors.bus_number ? 'border-rose-400 bg-rose-50' : 'border-gray-200 bg-gray-50'"
            >
            <p v-if="formErrors.bus_number" class="mt-1 text-xs text-rose-500">{{ formErrors.bus_number[0] }}</p>
          </div>

          <!-- Type & Seats -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bus Type <span class="text-rose-500">*</span></label>
              <select v-model="form.bus_type"
                class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                <option v-for="t in busTypes" :key="t" :value="t">{{ t }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Total Seats <span class="text-rose-500">*</span></label>
              <input v-model="form.total_seats" type="number" min="1" placeholder="40"
                class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                :class="formErrors.total_seats ? 'border-rose-400 bg-rose-50' : 'border-gray-200 bg-gray-50'"
              >
              <p v-if="formErrors.total_seats" class="mt-1 text-xs text-rose-500">{{ formErrors.total_seats[0] }}</p>
            </div>
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
            <div class="flex gap-3">
              <label
                v-for="s in busStatuses" :key="s"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl border-2 cursor-pointer transition-all text-sm font-medium capitalize"
                :class="form.status === s
                  ? s === 'active'      ? 'border-emerald-500 bg-emerald-50 text-emerald-700'
                    : s === 'maintenance' ? 'border-amber-400 bg-amber-50 text-amber-700'
                    : 'border-rose-400 bg-rose-50 text-rose-700'
                  : 'border-gray-200 bg-gray-50 text-gray-500 hover:border-gray-300'"
              >
                <input type="radio" :value="s" v-model="form.status" class="sr-only">
                <span class="w-2 h-2 rounded-full"
                  :class="s === 'active' ? 'bg-emerald-500' : s === 'maintenance' ? 'bg-amber-500' : 'bg-rose-500'">
                </span>
                {{ s }}
              </label>
            </div>
          </div>
        </form>

        <!-- Footer -->
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
            {{ formLoading ? 'Saving...' : editingId ? 'Save Changes' : 'Add Bus' }}
          </button>
        </div>
      </div>
    </Transition>

  </div>
</template>

<style scoped>
/* Slide-over animation */
.slideover-enter-active,
.slideover-leave-active { transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }
.slideover-enter-from,
.slideover-leave-to { transform: translateX(100%); }

/* Backdrop animation */
.backdrop-enter-active,
.backdrop-leave-active { transition: opacity 0.25s ease; }
.backdrop-enter-from,
.backdrop-leave-to { opacity: 0; }
</style>
