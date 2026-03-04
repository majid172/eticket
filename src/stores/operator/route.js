import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useOperatorRouteStore = defineStore('operator-route', () => {

    // ── State ─────────────────────────────────────────────
    const routes = ref([])
    const loading = ref(false)
    const error = ref('')
    const noCompany = ref(false)

    // ── Getters ───────────────────────────────────────────
    const filteredRoutes = (query) => computed(() => {
        if (!query?.value) return routes.value
        const q = query.value.toLowerCase()
        return routes.value.filter(r =>
            (r.source_city || '').toLowerCase().includes(q) ||
            (r.destination_city || '').toLowerCase().includes(q)
        )
    })

    // ── Actions ────────────────────────────────────────────
    async function fetch() {
        loading.value = true
        error.value = ''
        noCompany.value = false
        try {
            const { data } = await api.get('/operator/routes')
            routes.value = data.data ?? data
        } catch (err) {
            const msg = err.response?.data?.message || ''
            const is403 = err.response?.status === 403
            if (is403 && msg.toLowerCase().includes('company')) {
                noCompany.value = true
            } else {
                error.value = msg || 'Failed to load routes.'
            }
        } finally {
            loading.value = false
        }
    }

    async function create(payload) {
        const { data } = await api.post('/operator/routes', payload)
        const created = data.data ?? data
        routes.value.unshift(created)
        return created
    }

    async function update(id, payload) {
        const { data } = await api.put(`/operator/routes/${id}`, payload)
        const updated = data.data ?? data
        const idx = routes.value.findIndex(r => r.id === id)
        if (idx !== -1) routes.value[idx] = updated
        return updated
    }

    async function remove(id) {
        await api.delete(`/operator/routes/${id}`)
        routes.value = routes.value.filter(r => r.id !== id)
    }

    return {
        // state
        routes, loading, error, noCompany,
        // getters
        filteredRoutes,
        // actions
        fetch, create, update, remove,
    }
})
