import { defineStore } from "pinia";
import { ref } from "vue";
import api from "@/services/api";

export const useAdminBusStore = defineStore("admin-bus", () => {
    const buses = ref([]);
    const loading = ref(false);
    const errors = ref({});

    const fetchBuses = async () => {
        loading.value = true;
        try {
            const { data } = await api.get("/admin/buses");
            buses.value = data;
        } catch (error) {
            console.error("Failed to fetch buses:", error);
        } finally {
            loading.value = false;
        }
    };

    return {
        buses,
        loading,
        errors,
        fetchBuses,
    };
});
