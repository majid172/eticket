import { createRouter, createWebHistory } from 'vue-router'
import UserLayout from '@/layouts/UserLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import HomeView from '@/views/user/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // ─── Public / Passenger ─────────────────────────────────────────────────
    {
      path: '/',
      component: UserLayout,
      children: [
        { path: '', name: 'home', component: HomeView },
        { path: 'about', name: 'about', component: () => import('@/views/user/AboutView.vue') },
        { path: 'contact', name: 'contact', component: () => import('@/views/user/ContactView.vue') },
        { path: 'search', name: 'search', component: () => import('@/views/user/SearchResultsView.vue') },
        {
          path: 'booking',
          name: 'booking',
          meta: { requiresAuth: true, roles: ['user', 'passenger'] },
          component: () => import('@/views/user/BookingView.vue'),
        },
        {
          path: 'booking/success',
          name: 'booking-success',
          meta: { requiresAuth: true, roles: ['user', 'passenger'] },
          component: () => import('@/views/user/BookingSuccessView.vue'),
        },
        {
          path: 'settings',
          name: 'user-settings',
          meta: { requiresAuth: true },
          component: () => import('@/views/user/SettingsView.vue'),
        },
      ],
    },

    // ─── Admin Panel ─────────────────────────────────────────────────────────
    {
      path: '/admin',
      component: AdminLayout,
      meta: { requiresAuth: true, roles: ['admin'] },
      children: [
        { path: '', redirect: '/admin/dashboard' },
        { path: 'dashboard', name: 'admin-dashboard', component: () => import('@/views/admin/AdminDashboardView.vue') },
        { path: 'buses', name: 'admin-buses', component: () => import('@/views/admin/ManageBusesView.vue') },
        { path: 'routes', name: 'admin-routes', component: () => import('@/views/admin/ManageRoutesView.vue') },
        { path: 'routes/create', name: 'admin-routes-create', component: () => import('@/views/admin/CreateRouteView.vue') },
        { path: 'routes/:id', name: 'admin-routes-edit', component: () => import('@/views/admin/EditRouteView.vue') },
        { path: 'bookings', name: 'admin-bookings', component: () => import('@/views/admin/BookingsView.vue') },
        { path: 'users', name: 'admin-users', component: () => import('@/views/admin/UsersView.vue') },
        { path: 'users/:id', name: 'admin-users-edit', component: () => import('@/views/admin/EditUserView.vue') },
        { path: 'settings', name: 'admin-settings', component: () => import('@/views/admin/SettingsView.vue') },
      ],
    },

    // ─── Operator Panel ──────────────────────────────────────────────────────
    {
      path: '/operator',
      component: () => import('@/layouts/OperatorLayout.vue'),
      meta: { requiresAuth: true, roles: ['operator'] },
      children: [
        { path: '', redirect: '/operator/dashboard' },
        { path: 'dashboard', name: 'operator-dashboard', component: () => import('@/views/operator/OperatorDashboardView.vue') },
        { path: 'buses', name: 'operator-buses', component: () => import('@/views/operator/OperatorBusesView.vue') },
        { path: 'schedules', name: 'operator-schedules', component: () => import('@/views/operator/OperatorSchedulesView.vue') },
        { path: 'bookings', name: 'operator-bookings', component: () => import('@/views/operator/OperatorBookingsView.vue') },
        { path: 'settings', name: 'operator-settings', component: () => import('@/views/operator/OperatorSettingsView.vue') },
        { path: 'company', name: 'operator-company', component: () => import('@/views/operator/OperatorCompanyView.vue') },
      ],
    },

    // ─── Auth ────────────────────────────────────────────────────────────────
    { path: '/login', name: 'login', meta: { guestOnly: true }, component: () => import('@/views/LoginView.vue') },
    { path: '/register', name: 'register', meta: { guestOnly: true }, component: () => import('@/views/RegisterView.vue') },

    // ─── 404 ─────────────────────────────────────────────────────────────────
    { path: '/:pathMatch(.*)*', redirect: '/' },
  ],
})

// ─── Navigation Guards ────────────────────────────────────────────────────────
router.beforeEach((to) => {
  const token = localStorage.getItem('auth_token')
  const user = JSON.parse(localStorage.getItem('auth_user') || 'null')
  const role = user?.role

  // 1. Guest-only routes — redirect already-logged-in users to their dashboard
  if (to.meta.guestOnly && token) {
    if (role === 'admin') return { name: 'admin-dashboard' }
    if (role === 'operator') return { name: 'operator-dashboard' }
    return { name: 'home' }
  }

  // 2. Protected routes — redirect unauthenticated users to login
  if (to.meta.requiresAuth && !token) {
    return { name: 'login', query: { redirect: to.fullPath } }
  }

  // 3. Role-restricted routes — send each role to their own area
  if (to.meta.roles && token) {
    if (!to.meta.roles.includes(role)) {
      if (role === 'admin') return { name: 'admin-dashboard' }
      if (role === 'operator') return { name: 'operator-dashboard' }
      return { name: 'home' }
    }
  }
})

export default router
