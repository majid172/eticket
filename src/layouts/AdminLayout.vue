<script setup>
import { RouterView } from 'vue-router'
</script>

<template>
  <div class="admin-layout flex h-screen">
    <aside class="w-64 bg-gray-800 text-white p-4">
        <h2 class="text-xl font-bold mb-4">Admin Panel</h2>
        <nav>
            <ul>
                <li><RouterLink to="/admin" class="block py-2 px-4 hover:bg-gray-700">Dashboard</RouterLink></li>
                 <li><RouterLink to="/" class="block py-2 px-4 hover:bg-gray-700">Go to Home</RouterLink></li>
            </ul>
        </nav>
    </aside>
    <main class="flex-1 p-8 bg-gray-100">
      <header class="bg-white shadow p-4 mb-6">
          <h1 class="text-2xl font-semibold">Dashboard</h1>
      </header>
      <RouterView />
    </main>
  </div>
</template>
