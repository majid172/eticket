<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

const loading    = ref(true)
const saving     = ref(false)
const hasCompany = ref(false)
const successMsg = ref('')
const formErrors = ref({})

const form = reactive({
  company_name:  '',
  contact_phone: '',
  contact_email: '',
  trade_license: '',
  address:       '',
})

onMounted(async () => {
  try {
    const { data } = await api.get('/operator/company')
    hasCompany.value = data.has_company
    if (data.has_company && data.data) {
      Object.assign(form, {
        company_name:  data.data.company_name  || '',
        contact_phone: data.data.contact_phone || '',
        contact_email: data.data.contact_email || '',
        trade_license: data.data.trade_license || '',
        address:       data.data.address       || '',
      })
    }
  } catch { /* treat as no company */ }
  finally { loading.value = false }
})

async function save() {
  saving.value     = true
  formErrors.value = {}
  successMsg.value = ''
  try {
    if (hasCompany.value) {
      await api.put('/operator/company', form)
    } else {
      await api.post('/operator/company', form)
      hasCompany.value = true
    }
    successMsg.value = 'Company profile saved successfully!'
    setTimeout(() => { successMsg.value = '' }, 4000)
  } catch (err) {
    if (err.response?.status === 422) {
      formErrors.value = err.response.data.errors || {}
    } else {
      formErrors.value = { general: err.response?.data?.message || 'Something went wrong.' }
    }
  } finally { saving.value = false }
}
</script>

<template>
  <div class="font-sans text-gray-700">

    <!-- Loading skeleton -->
    <div v-if="loading" class="animate-pulse space-y-6">
      <div class="h-32 bg-gray-100 rounded-2xl"></div>
      <div class="h-80 bg-gray-100 rounded-2xl"></div>
    </div>

    <template v-else>

      <!-- ── Hero Header ──────────────────────────────────────────────────────── -->
      <div class="relative bg-gradient-to-r from-emerald-700 to-teal-500 rounded-2xl overflow-hidden mb-6 px-6 py-4">
        <!-- Subtle decorative rings -->
        <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute right-16 bottom-0 w-16 h-16 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative flex items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <!-- Icon chip -->
            <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/25 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
              <!-- Setup Required badge -->
              <span v-if="!hasCompany"
                class="inline-flex items-center gap-1.5 bg-amber-400/20 border border-amber-300/40 text-amber-200 text-[11px] font-semibold px-2.5 py-0.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-300 animate-pulse"></span>
                Setup Required
              </span>
              <div>
                <p class="text-base font-bold text-white leading-tight">
                  {{ hasCompany ? (form.company_name || 'Company Profile') : 'Set Up Your Company' }}
                </p>
                <p class="text-emerald-100 text-xs mt-0.5">
                  {{ hasCompany
                    ? 'Manage your registered company details'
                    : 'Register your company to start adding buses and schedules' }}
                </p>
              </div>
            </div>
          </div>

          <!-- My Buses shortcut -->
          <button v-if="hasCompany"
            @click="router.push({ name: 'operator-buses' })"
            class="flex-shrink-0 inline-flex items-center gap-1.5 px-3.5 py-2 bg-white/15 hover:bg-white/25 text-white text-xs font-semibold rounded-lg border border-white/20 transition-colors backdrop-blur-sm"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            My Buses
          </button>
        </div>
      </div>

      <!-- ── Main Form Card ───────────────────────────────────────────────────── -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <!-- Alerts -->
        <div class="px-8 pt-6" v-if="successMsg || formErrors.general">
          <div v-if="successMsg"
            class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3.5 rounded-xl mb-0">
            <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ successMsg }}
          </div>
          <div v-if="formErrors.general"
            class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3.5 rounded-xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ formErrors.general }}
          </div>
        </div>

        <form @submit.prevent="save">

          <!-- ── Section 1: Company Identity ─────────────────────────────────── -->
          <div class="px-8 pt-8 pb-6 border-b border-gray-50">
            <div class="flex flex-col lg:flex-row gap-8">
              <!-- Section label -->
              <div class="lg:w-64 flex-shrink-0">
                <div class="flex items-center gap-2 mb-1">
                  <div class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                  </div>
                  <h3 class="text-sm font-bold text-gray-800">Company Identity</h3>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed mt-1 lg:pr-4">
                  Your official business name and trade license registration details.
                </p>
              </div>
              <!-- Fields -->
              <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Company Name -->
                <div class="sm:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Company Name <span class="text-rose-500">*</span>
                  </label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                      </svg>
                    </span>
                    <input v-model="form.company_name" type="text" placeholder="e.g. Green Line Paribahan Ltd."
                      class="w-full pl-11 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                      :class="formErrors.company_name ? 'border-rose-300 bg-rose-50' : 'border-gray-200 bg-gray-50/60'">
                  </div>
                  <p v-if="formErrors.company_name" class="mt-1.5 text-xs text-rose-500">{{ formErrors.company_name[0] }}</p>
                </div>
                <!-- Trade License -->
                <div class="sm:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Trade License No.
                    <span class="text-gray-400 font-normal text-xs">(optional)</span>
                  </label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </span>
                    <input v-model="form.trade_license" type="text" placeholder="e.g. TL-2024-XXXXXX"
                      class="w-full pl-11 pr-4 py-3 border border-gray-200 bg-gray-50/60 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ── Section 2: Contact Information ──────────────────────────────── -->
          <div class="px-8 py-6 border-b border-gray-50">
            <div class="flex flex-col lg:flex-row gap-8">
              <div class="lg:w-64 flex-shrink-0">
                <div class="flex items-center gap-2 mb-1">
                  <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                  </div>
                  <h3 class="text-sm font-bold text-gray-800">Contact Information</h3>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed mt-1 lg:pr-4">
                  How passengers and admins can reach your company.
                </p>
              </div>
              <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Phone -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Phone Number <span class="text-rose-500">*</span>
                  </label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                      </svg>
                    </span>
                    <input v-model="form.contact_phone" type="text" placeholder="+880 1XXX-XXXXXX"
                      class="w-full pl-11 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                      :class="formErrors.contact_phone ? 'border-rose-300 bg-rose-50' : 'border-gray-200 bg-gray-50/60'">
                  </div>
                  <p v-if="formErrors.contact_phone" class="mt-1.5 text-xs text-rose-500">{{ formErrors.contact_phone[0] }}</p>
                </div>
                <!-- Email -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email Address
                    <span class="text-gray-400 font-normal text-xs">(optional)</span>
                  </label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                      </svg>
                    </span>
                    <input v-model="form.contact_email" type="email" placeholder="contact@company.com"
                      class="w-full pl-11 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                      :class="formErrors.contact_email ? 'border-rose-300 bg-rose-50' : 'border-gray-200 bg-gray-50/60'">
                  </div>
                  <p v-if="formErrors.contact_email" class="mt-1.5 text-xs text-rose-500">{{ formErrors.contact_email[0] }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- ── Section 3: Office Address ────────────────────────────────────── -->
          <div class="px-8 py-6 border-b border-gray-50">
            <div class="flex flex-col lg:flex-row gap-8">
              <div class="lg:w-64 flex-shrink-0">
                <div class="flex items-center gap-2 mb-1">
                  <div class="w-6 h-6 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </div>
                  <h3 class="text-sm font-bold text-gray-800">Office Address</h3>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed mt-1 lg:pr-4">
                  Your company's registered office or depot location.
                </p>
              </div>
              <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Full Address
                  <span class="text-gray-400 font-normal text-xs">(optional)</span>
                </label>
                <div class="relative">
                  <span class="absolute left-4 top-3.5 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </span>
                  <textarea v-model="form.address" rows="3"
                    placeholder="House/Road, Area, City, District"
                    class="w-full pl-11 pr-4 py-3 border border-gray-200 bg-gray-50/60 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition resize-none">
                  </textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- ── Footer ────────────────────────────────────────────────────────── -->
          <div class="px-8 py-5 bg-gray-50/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <!-- Info pill (first-time only) -->
            <div v-if="!hasCompany" class="flex items-center gap-2 text-xs text-gray-500">
              <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              After saving, you can immediately start adding buses and schedules.
            </div>
            <p v-else class="text-xs text-gray-400"><span class="text-rose-400">*</span> Required fields</p>

            <button type="submit" :disabled="saving"
              class="inline-flex items-center gap-2 px-7 py-3 bg-emerald-700 text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition-colors shadow-sm shadow-emerald-700/20 disabled:opacity-70 ml-auto">
              <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              {{ saving ? 'Saving...' : hasCompany ? 'Update Profile' : 'Save & Activate' }}
            </button>
          </div>

        </form>
      </div>

    </template>
  </div>
</template>
