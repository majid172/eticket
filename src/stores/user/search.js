import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useSearchStore = defineStore('search', () => {
    const searchParams = ref({
        from: '',
        to: '',
        date: new Date().toISOString().split('T')[0],
        returnDate: ''
    })

    const loadingTickets = ref(false)
    const tickets = ref([])
    const cities = ref([])

    async function fetchCities() {
        if (cities.value.length > 0) return; // Cache cities
        try {
            const { data } = await api.get('/routes/cities')
            cities.value = data
        } catch (err) {
            console.error('Failed to fetch cities:', err)
        }
    }

    function formatTime(timeString) {
        if (!timeString) return '';
        const [hourString, minute] = timeString.split(':');
        const hour = +hourString;
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const formattedHour = hour % 12 || 12;
        return `${formattedHour}:${minute} ${ampm}`;
    }

    async function fetchSchedules(params = null) {
        if (params) {
            searchParams.value = { ...searchParams.value, ...params }
        }
        console.log(searchParams.value);

        loadingTickets.value = true;
        try {
            let fmtDate = searchParams.value.date;
            try {
                const d = new Date(searchParams.value.date);
                if (!isNaN(d.getTime())) fmtDate = d.toISOString().split('T')[0];
            } catch (e) { }

            // Build params — only include travel_date when explicitly set
            const queryParams = {
                source_city: searchParams.value.from,
                destination_city: searchParams.value.to,
            }
            if (fmtDate) {
                queryParams.travel_date = fmtDate
            }

            const { data } = await api.get('/schedules', { params: queryParams });
            console.log(data);

            tickets.value = data.data.map(sb => {
                const sch = sb.schedule || {};
                const bs = sb.bus || {};
                const cmp = bs.company || {};
                const r = sch.route || {};

                return {
                    id: sb.id,
                    operator: cmp.company_name || 'Partner Operator',
                    busInfo: `${bs.bus_number || ''}, ${bs.bus_type || 'Coach'}`,
                    route: `${r.source_city || ''} - ${r.destination_city || ''}`,
                    departureTime: formatTime(sch.departure_time),
                    departureDate: sch.travel_date,
                    departureLocation: r.source_city,
                    duration: r.distance_km ? `${r.distance_km}km` : 'Direct',
                    arrivalTime: formatTime(sch.arrival_time),
                    arrivalDate: sch.travel_date, // simplified
                    arrivalLocation: r.destination_city,
                    price: sch.base_price || 0,
                    oldPrice: null,
                    seatsAvailable: sb.available_seats || 0,
                    seatType: sb.seat_type || 'Economy',
                    isAc: bs.bus_type === 'AC',
                    discount: null
                }
            });
        } catch (error) {
            console.error("Failed to load schedules", error);
        } finally {
            loadingTickets.value = false;
        }
    }

    const expandedTicketId = ref(null)
    const selectedSeats = ref([])
    const seatLayout = ref([])
    const loadingLayout = ref(false)
    const seatType = ref('Economy')

    const toggleSeats = async (ticketId) => {
        if (expandedTicketId.value === ticketId) {
            expandedTicketId.value = null
            selectedSeats.value = []
            seatLayout.value = []
            return
        }

        expandedTicketId.value = ticketId
        selectedSeats.value = []
        seatLayout.value = []
        loadingLayout.value = true

        try {
            const { data } = await api.get(`/schedules/${ticketId}/seats`)
            const dbSeats = data.seats || []
            seatType.value = data.seat_type || 'Economy'
            seatLayout.value = dbSeats.map(s => ({
                id: s.seat_number,
                name: s.seat_number,
                status: s.is_booked ? 'booked' : 'available'
            }))
        } catch (err) {
            console.error("Failed to load seats", err)
        } finally {
            loadingLayout.value = false
        }
    }

    const selectSeat = (seat) => {
        if (seat.status === 'booked') return

        const index = selectedSeats.value.findIndex(s => s.id === seat.id)
        if (index === -1) {
            if (selectedSeats.value.length >= 4) {
                alert('You can select maximum 4 seats')
                return
            }
            selectedSeats.value.push(seat)
        } else {
            selectedSeats.value.splice(index, 1)
        }
    }

    const isSelected = (seat) => {
        return selectedSeats.value.some(s => s.id === seat.id)
    }

    const totalPrice = computed(() => {
        if (!expandedTicketId.value) return 0
        const ticket = tickets.value.find(t => t.id === expandedTicketId.value)
        return ticket ? ticket.price * selectedSeats.value.length : 0
    })

    const availableOperators = computed(() => {
        const operators = tickets.value.map(t => t.operator)
        return [...new Set(operators)].sort()
    })

    return {
        searchParams,
        loadingTickets,
        tickets,
        cities,
        expandedTicketId,
        selectedSeats,
        seatLayout,
        loadingLayout,
        seatType,
        totalPrice,
        availableOperators,
        fetchSchedules,
        fetchCities,
        toggleSeats,
        selectSeat,
        isSelected
    }
})
