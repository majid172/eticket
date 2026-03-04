import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useOperatorScheduleStore = defineStore('operator-schedule', () => {

    // ── State ─────────────────────────────────────────────
    const schedules = ref([])
    const routes = ref([])
    const buses = ref([])
    const loading = ref(false)
    const error = ref('')
    const noCompany = ref(false)

    // ── Getters ───────────────────────────────────────────
    const filteredSchedules = (query) => computed(() => {
        if (!query?.value) return schedules.value
        const q = query.value.toLowerCase()
        return schedules.value.filter(s =>
            (s.schedule?.route?.source_city || '').toLowerCase().includes(q) ||
            (s.schedule?.route?.destination_city || '').toLowerCase().includes(q) ||
            (s.bus?.bus_number || '').toLowerCase().includes(q)
        )
    })

    // ── Actions ────────────────────────────────────────────
    async function fetch() {
        loading.value = true
        error.value = ''
        noCompany.value = false

        try {
            const [schedRes, routesRes, busesRes] = await Promise.all([
                api.get('/operator/schedules'),
                api.get('/operator/routes'),
                api.get('/operator/buses'),
            ])
            schedules.value = schedRes.data.data ?? schedRes.data
            routes.value = routesRes.data.data ?? routesRes.data
            buses.value = busesRes.data.data ?? busesRes.data
        } catch (err) {
            const msg = err.response?.data?.message || ''
            const is403 = err.response?.status === 403
            if (is403 && msg.toLowerCase().includes('company')) {
                noCompany.value = true
            } else {
                error.value = msg || 'Failed to load schedules.'
            }
        } finally {
            loading.value = false
        }
    }

    async function create(payload) {
        const { data } = await api.post('/operator/schedules', payload)
        const created = data.schedule_bus ?? data.data ?? data
        schedules.value.unshift(created)
        return created
    }

    async function update(id, payload) {
        const { data } = await api.put(`/operator/schedules/${id}`, payload)
        const updated = data.schedule_bus ?? data.data ?? data
        const idx = schedules.value.findIndex(s => s.id === id)
        if (idx !== -1) schedules.value[idx] = { ...schedules.value[idx], ...updated }
    }

    async function remove(id) {
        await api.delete(`/operator/schedules/${id}`)
        schedules.value = schedules.value.filter(s => s.id !== id)
    }

    return {
        // state
        schedules, routes, buses, loading, error, noCompany,
        // getters
        filteredSchedules,
        // actions
        fetch, create, update, remove,
    }
})
