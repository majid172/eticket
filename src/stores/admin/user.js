import { defineStore } from "pinia";
import { ref } from "vue";
import api from "@/services/api";

export const useUserStore = defineStore("user", () => {
    const users = ref([]);

    const fetchUsers = async () => {
        try {
            const response = await api.get("/admin/users");
            users.value = response.data.data;
        } catch (error) {
            console.log(error);
        }
    };

    return {
        users,
        fetchUsers,
    };

});