import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useUserRouteStore = defineStore('user-route', () => {

    // ── State ───────────────────────────────────────────────────────────
    const popularRoutes = ref([])
    const allRoutes     = ref([])

    const loadingPopular = ref(false)
    const loadingAll     = ref(false)

    const errorPopular = ref(null)
    const errorAll     = ref(null)

    // ── Getters ─────────────────────────────────────────────────────────

    /** Top 4 routes by distance (already sorted from API) */
    const topRoutes = computed(() => popularRoutes.value.slice(0, 4))

    /** All unique city names derived from loaded popular routes */
    const popularCities = computed(() => {
        const cities = new Set()
        popularRoutes.value.forEach(r => {
            cities.add(r.source_city)
            cities.add(r.destination_city)
        })
        return [...cities].sort()
    })

    // ── Actions ─────────────────────────────────────────────────────────

    /**
     * Fetch top 4 popular routes (ordered by distance DESC).
     * Caches result — won't refetch if data already loaded.
     */
    async function fetchPopularRoutes(force = false) {
        if (!force && popularRoutes.value.length > 0) return

        loadingPopular.value = true
        errorPopular.value   = null
        try {
            const { data } = await api.get('/routes/popular')
            popularRoutes.value = data
        } catch (err) {
            errorPopular.value = err.response?.data?.message || 'Failed to load popular routes.'
        } finally {
            loadingPopular.value = false
        }
    }

    /**
     * Fetch all active public routes (flat list, no auth required).
     */
    async function fetchAllRoutes(force = false) {
        if (!force && allRoutes.value.length > 0) return

        loadingAll.value = true
        errorAll.value   = null
        try {
            const { data } = await api.get('/routes/popular') // reuse endpoint; extend if needed
            allRoutes.value = data
        } catch (err) {
            errorAll.value = err.response?.data?.message || 'Failed to load routes.'
        } finally {
            loadingAll.value = false
        }
    }

    /** Reset store (useful on logout / page switch) */
    function reset() {
        popularRoutes.value = []
        allRoutes.value     = []
        errorPopular.value  = null
        errorAll.value      = null
    }

    return {
        // state
        popularRoutes,
        allRoutes,
        loadingPopular,
        loadingAll,
        errorPopular,
        errorAll,
        // getters
        topRoutes,
        popularCities,
        // actions
        fetchPopularRoutes,
        fetchAllRoutes,
        reset,
    }
})
