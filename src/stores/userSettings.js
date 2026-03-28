import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useSettingsStore = defineStore('userSettings', () => {
    // ─── State ────────────────────────────────────────────────────────────────
    
    // Booking History
    const bookings = ref([])
    const loadingBookings = ref(false)
    
    // Profile & Personal Information (Synced with DB)
    const profile = ref({
        name: '',
        email: '',
        phone: '',
        emergency_contact: '',
        passengers: [] // Still using local for now or can be added to DB later
    })
    
    // Security & Privacy Settings
    const security = ref({
        currentPassword: '',
        newPassword: '',
        twoFactor: false
    })
    
    // Travel Preferences (Synced with DB)
    const preferences = ref({
        seatType: 'Window',
        busType: 'AC',
        boardingPoint: ''
    })
    
    // Notification Toggles (Synced with DB)
    const notifications = ref({
        sms: true,
        email: true,
        promo: false,
        delay: true
    })
    
    // Interface & Accessibility Config (Synced with DB)
    const config = ref({
        language: 'English',
        currency: 'BDT',
        darkMode: false,
    })

    const loadingProfile = ref(false)

    // ─── Actions ──────────────────────────────────────────────────────────────
    
    /**
     * Fetch all user profile and settings from backend
     */
    const fetchProfile = async () => {
        loadingProfile.value = true
        try {
            const { data } = await api.get('/passenger/profile')
            const { user, profile: dbProfile } = data.data
            
            // Map User basic info
            profile.value.name = user.name
            profile.value.email = user.email
            profile.value.phone = user.phone || ''
            
            // Map Profile metadata
            profile.value.emergency_contact = dbProfile.emergency_contact || ''
            
            if (dbProfile.preferences) preferences.value = { ...preferences.value, ...dbProfile.preferences }
            if (dbProfile.notifications) notifications.value = { ...notifications.value, ...dbProfile.notifications }
            if (dbProfile.config) config.value = { ...config.value, ...dbProfile.config }
            
        } catch (err) {
            console.error("Store: Failed to fetch profile", err)
        } finally {
            loadingProfile.value = false
        }
    }

    /**
     * Fetch user's booking history
     */
    const fetchBookings = async () => {
        loadingBookings.value = true
        try {
            const { data } = await api.get('/passenger/bookings')
            bookings.value = data.data || []
        } catch (err) {
            console.error("Store: Failed to fetch bookings", err)
        } finally {
            loadingBookings.value = false
        }
    }

    /**
     * Cancel an active booking
     */
    const cancelBooking = async (id) => {
        try {
            await api.delete(`/passenger/bookings/${id}`)
            await fetchBookings()
            return { success: true }
        } catch (err) {
            console.error("Store: Failed to cancel booking", id, err)
            throw err
        }
    }

    /**
     * Update settings in the database
     */
    const updateSettings = async (section, payload) => {
        console.log(`Store: Updating ${section} in DB...`, payload)
        
        try {
            // We can send partial updates or the whole object.
            // ProfileController.php expects 'preferences', 'notifications', etc.
            const response = await api.put('/passenger/profile', {
                [section]: payload,
                // If the section is 'profile', we might need to send individual user fields
                ...(section === 'profile' ? {
                    name: profile.value.name,
                    email: profile.value.email,
                    phone: profile.value.phone,
                    emergency_contact: profile.value.emergency_contact
                } : {})
            })
            
            // Refresh local state with confirmed data from server
            const { user, profile: dbProfile } = response.data.data
            profile.value.name = user.name
            profile.value.email = user.email
            profile.value.phone = user.phone || ''
            profile.value.emergency_contact = dbProfile.emergency_contact || ''
            
            if (dbProfile.preferences) preferences.value = dbProfile.preferences
            if (dbProfile.notifications) notifications.value = dbProfile.notifications
            if (dbProfile.config) config.value = dbProfile.config

            return { success: true, message: response.data.message }
        } catch (err) {
            console.error(`Store: Failed to update ${section}`, err)
            throw err
        }
    }

    return {
        // State
        bookings,
        loadingBookings,
        profile,
        security,
        preferences,
        notifications,
        config,
        loadingProfile,
        
        // Actions
        fetchProfile,
        fetchBookings,
        cancelBooking,
        updateSettings
    }
})
