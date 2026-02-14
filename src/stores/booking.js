import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useBookingStore = defineStore('booking', () => {
  const selectedSeats = ref([])
  const busDetails = ref(null)
  const journeyDate = ref(null)
  const routeInfo = ref(null)
  const passengerDetails = ref(null)
  const pnr = ref(null)

  const totalPrice = computed(() => {
    if (!busDetails.value || !selectedSeats.value.length) return 0
    return busDetails.value.price * selectedSeats.value.length
  })

  // Initial selection from search results
  const setBookingDetails = (seats, bus, date, route) => {
    selectedSeats.value = seats
    busDetails.value = bus
    journeyDate.value = date
    routeInfo.value = route
  }

  // Final confirmation with passenger info
  const confirmBooking = (passenger) => {
    passengerDetails.value = passenger
    // Generate a random 6-character PNR
    pnr.value = Math.random().toString(36).substring(2, 8).toUpperCase()
  }

  const clearBookingDetails = () => {
    selectedSeats.value = []
    busDetails.value = null
    journeyDate.value = null
    routeInfo.value = null
    passengerDetails.value = null
    pnr.value = null
  }

  return {
    selectedSeats,
    busDetails,
    journeyDate,
    routeInfo,
    passengerDetails,
    pnr,
    totalPrice,
    setBookingDetails,
    confirmBooking,
    clearBookingDetails
  }
})
