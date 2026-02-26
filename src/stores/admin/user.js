import { defineStore } from "pinia";
import { ref } from "vue";
import api from "@/services/api";

export const useUserStore = defineStore("user", () => {
    const users = ref([]);
    const user = ref(null);
    const loading = ref(false);
    const errors = ref({});

    const fetchUsers = async () => {
        try {
            const { data } = await api.get("/admin/users");
            users.value = data;
        } catch (error) {
            console.log(error);
        }
    };

    const getUser = async (id) => {
        loading.value = true;
        try {
            const { data } = await api.get(`/admin/users/${id}`);
            console.log(data);

            user.value = data;
            return data;
        } catch (error) {
            console.log(error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    const updateUser = async (id, payload) => {
        loading.value = true;
        errors.value = {};
        console.log(payload);

        try {
            const { data } = await api.put(`/admin/users/${id}`, payload);
            return data;
        } catch (error) {
            errors.value = error.response?.data?.errors || {};
            console.log(error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    const deleteUser = async (id) => {
        try {
            await api.delete(`/admin/users/${id}`);
            users.value = users.value.filter(u => u.id !== id);
        } catch (error) {
            console.log(error);
            throw error;
        }
    };

    return {
        users,
        user,
        loading,
        errors,
        fetchUsers,
        getUser,
        updateUser,
        deleteUser,
    };

});