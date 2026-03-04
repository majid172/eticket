import { defineStore } from 'pinia'
import { ref } from 'vue'
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

            const { data } = await api.get('/schedules', {
                params: {
                    source_city: searchParams.value.from,
                    destination_city: searchParams.value.to,
                    travel_date: fmtDate
                }
            });
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

    return {
        searchParams,
        loadingTickets,
        tickets,
        cities,
        fetchSchedules,
        fetchCities
    }
})
