import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useOperatorBookingStore = defineStore('operator-booking', () => {
    // ── State ─────────────────────────────────────────────
    const bookingsList = ref([])
    const loading = ref(false)
    const error = ref('')
    const noCompany = ref(false)

    // ── Getters ───────────────────────────────────────────
    const filteredBookings = (query) => computed(() => {
        let result = bookingsList.value
        if (query?.value) {
            const q = query.value.toLowerCase()
            result = result.filter(b =>
                (b.booking_reference || '').toLowerCase().includes(q) ||
                (b.primary_passenger_name || b.user?.name || '').toLowerCase().includes(q) ||
                (b.primary_passenger_phone || b.user?.phone || '').toLowerCase().includes(q) ||
                (b.schedule?.route?.source_city || b.schedule_bus?.schedule?.route?.source_city || '').toLowerCase().includes(q) ||
                (b.schedule?.route?.destination_city || b.schedule_bus?.schedule?.route?.destination_city || '').toLowerCase().includes(q)
            )
        }
        return result
    })

    // ── Actions ────────────────────────────────────────────
    async function fetchBookings() {
        loading.value = true
        error.value = ''
        noCompany.value = false
        try {
            const { data } = await api.get('/operator/bookings')
            bookingsList.value = data.data ?? data
        } catch (err) {
            const msg = err.response?.data?.message || ''
            if (err.response?.status === 403 && msg.toLowerCase().includes('company')) {
                noCompany.value = true
            } else {
                error.value = msg || 'Failed to load bookings.'
            }
        } finally {
            loading.value = false
        }
    }

    async function fetchBookingDetails(id) {
        try {
            const { data } = await api.get(`/operator/bookings/${id}`)
            return data.data ?? data
        } catch (err) {
            throw err
        }
    }

    async function updateBookingStatus(id, payload) {
        try {
            const { data } = await api.put(`/operator/bookings/${id}/status`, payload)
            const updated = data.booking ?? data.data ?? data
            const idx = bookingsList.value.findIndex(b => b.id === id)
            if (idx !== -1) {
                bookingsList.value[idx] = updated
            }
            return updated
        } catch (err) {
            throw err
        }
    }

    async function deleteBooking(id) {
        try {
            await api.delete(`/operator/bookings/${id}`)
            bookingsList.value = bookingsList.value.filter(b => b.id !== id)
        } catch (err) {
            throw err
        }
    }

    return {
        // state
        bookingsList, loading, error, noCompany,
        // getters
        filteredBookings,
        // actions
        fetchBookings, fetchBookingDetails, updateBookingStatus, deleteBooking
    }
})
