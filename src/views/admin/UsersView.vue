<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useUserStore } from '@/stores/admin/user'
import { storeToRefs } from 'pinia'

const userStore = useUserStore();
const { users } = storeToRefs(userStore);

onMounted(() => {
    userStore.fetchUsers();
})

const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(20)
const allChecked = ref(false)

const filteredUsers = computed(() => {
    if (!searchQuery.value) return users.value;
    const query = searchQuery.value.toLowerCase();
    return users.value.filter(user => 
        user.name.toLowerCase().includes(query) || 
        user.email.toLowerCase().includes(query) ||
        user.id.toString().includes(query)
    );
});

watch(searchQuery, () => {
    currentPage.value = 1;
});

const totalPages = computed(() => {
    return Math.ceil(filteredUsers.value.length / itemsPerPage.value);
});

const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    return filteredUsers.value.slice(start, start + itemsPerPage.value);
});

const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
};

const goToPage = (page) => {
    currentPage.value = page;
};
const getInitials = (name) => {
    if (!name) return '??';
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: '2-digit',
        day: '2-digit',
        year: '2-digit'
    });
}

const toggleAll = () => {
    users.value.forEach(u => u.checked = allChecked.value)
}

const getStatusStyles = (status) => {
    const s = status?.toLowerCase();
    if (s === 'active') {
        return 'bg-green-50 text-green-700'
    } else if (s === 'inactive') {
        return 'bg-gray-50 text-gray-700'
    } else if (s === 'blocked' || s === 'banned') {
        return 'bg-red-50 text-red-700'
    } else if (s === 'pending') {
        return 'bg-yellow-50 text-yellow-700'
    } else {
        return 'bg-gray-50 text-gray-700'
    }
}

const getStatusIconColor = (status) => {
    const s = status?.toLowerCase();
    if (s === 'active') {
        return 'text-green-500'
    } else if (s === 'inactive') {
        return 'text-gray-500'
    } else if (s === 'blocked' || s === 'banned') {
        return 'text-red-500'
    } else if (s === 'pending') {
        return 'text-yellow-500'
    } else {
        return 'text-gray-500'
    }
}

const getRoleColor = (role) => {
    const r = role?.toLowerCase();
    switch(r) {
        case 'operator':
        case 'company owner': return 'text-purple-600 bg-purple-50 px-2 py-0.5 rounded text-xs font-semibold';
        case 'admin': return 'text-blue-600 bg-blue-50 px-2 py-0.5 rounded text-xs font-semibold';
        case 'passenger': return 'text-slate-600 bg-slate-50 px-2 py-0.5 rounded text-xs font-semibold';
        default: return 'text-gray-600 bg-gray-50 px-2 py-0.5 rounded text-xs font-semibold';
    }
}

const capitalize = (str) => {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

</script>

<template>
  <div class="p-8 max-w-[1600px] mx-auto font-sans text-slate-800 bg-white ">
    
    <!-- Header & Toolbar Combined -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        
        <!-- Left: Title & Badge -->
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">Users</h1>
            <span class="px-2.5 py-0.5 rounded-md bg-yellow-50 text-yellow-700 text-xs font-bold border border-yellow-100">
                {{ users.length }} Users
            </span>
        </div>

        <!-- Center: Search Pill -->
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input 
                type="text" 
                v-model="searchQuery"
                placeholder="Search (Ctrl+K)" 
                class="block w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-full text-sm placeholder-gray-500 focus:outline-none focus:bg-white focus:border-gray-300 focus:ring-0 transition-all hover:bg-gray-100"
            >
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2 w-full md:w-auto">
             <button class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                 Filter
             </button>
             <button class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                 Sort
             </button>
             <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 Add User
             </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto rounded-lg border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100">
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">SL.</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name & Email</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined Date</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <tr v-for="(user, index) in paginatedUsers" :key="user.id" class="hover:bg-gray-50/50 transition-colors group">
                    <td class="p-4 text-sm font-medium text-gray-900">#{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 mr-3">
                                {{ getInitials(user.name) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-700">{{ user.name }}</span>
                                <span class="text-xs text-gray-400">{{ user.email }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                         <span :class="getRoleColor(user.role)">
                            {{ capitalize(user.role) }}
                         </span>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        {{ formatDate(user.created_at) }}
                    </td>
                    <td class="p-4">
                         <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium" :class="getStatusStyles(user.status)">
                            <svg class="w-2 h-2 mr-1.5" :class="getStatusIconColor(user.status)" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                            {{ capitalize(user.status) }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <router-link :to="{ name: 'admin-users-edit', params: { id: user.id } }" class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit User">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </router-link>
                            <button class="text-gray-400 hover:text-red-600 transition-colors" title="Delete User">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="mt-6 flex justify-between items-center">
         <button @click="prevPage" :disabled="currentPage === 1" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
         </button>
         
         <div class="flex gap-2">
             <button 
                 v-for="page in totalPages" 
                 :key="page" 
                 @click="goToPage(page)"
                 :class="[
                     'w-8 h-8 flex items-center justify-center rounded-lg text-sm transition-colors',
                     currentPage === page ? 'bg-gray-100 font-bold text-gray-900 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
                 ]"
             >
                 {{ page }}
             </button>
         </div>

         <button @click="nextPage" :disabled="currentPage === totalPages" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
         </button>
    </div>

  </div>
</template>
