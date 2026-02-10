<script setup>
</script>

<template>
  <div class="dashboard-view">
    <p>Welcome to the Admin Dashboard!</p>
    <div class="stats grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="stat-card bg-white p-4 shadow rounded">
            <h3 class="font-bold text-gray-500">Total Users</h3>
            <p class="text-2xl font-semibold">1,234</p>
        </div>
        <div class="stat-card bg-white p-4 shadow rounded">
            <h3 class="font-bold text-gray-500">Total Revenue</h3>
            <p class="text-2xl font-semibold">$12,345</p>
        </div>
        <div class="stat-card bg-white p-4 shadow rounded">
            <h3 class="font-bold text-gray-500">New Orders</h3>
            <p class="text-2xl font-semibold">56</p>
        </div>
    </div>
  </div>
</template>
