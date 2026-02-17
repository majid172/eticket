import { createRouter, createWebHistory } from 'vue-router'
import UserLayout from '@/layouts/UserLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import HomeView from '@/views/user/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: UserLayout,
      children: [
        {
          path: '',
          name: 'home',
          component: HomeView,
        },
        {
          path: 'about',
          name: 'about',
          // route level code-splitting
          // this generates a separate chunk (About.[hash].js) for this route
          // which is lazy-loaded when the route is visited.
          component: () => import('@/views/user/AboutView.vue'),
        },
        {
          path: 'search',
          name: 'search',
          component: () => import('@/views/user/SearchResultsView.vue'),
        },
        {
          path: 'booking',
          name: 'booking',
          component: () => import('@/views/user/BookingView.vue'),
        },
        {
          path: 'booking/success',
          name: 'booking-success',
          component: () => import('@/views/user/BookingSuccessView.vue'),
        },
        {
          path: 'settings',
          name: 'user-settings',
          component: () => import('@/views/user/SettingsView.vue'),
        },
      ],
    },
    {
      path: '/admin',
      component: AdminLayout,
      children: [
        {
          path: '', // loads on /admin
          redirect: '/admin/dashboard'
        },
        {
          path: 'dashboard',
          name: 'admin-dashboard',
          component: () => import('@/views/admin/AdminDashboardView.vue'),
        },
        {
          path: 'buses',
          name: 'admin-buses',
          component: () => import('@/views/admin/ManageBusesView.vue'),
        },
        {
          path: 'routes',
          name: 'admin-routes',
          component: () => import('@/views/admin/ManageRoutesView.vue'),
        },
        {
          path: 'routes/create',
          name: 'admin-routes-create',
          component: () => import('@/views/admin/CreateRouteView.vue'),
        },
        {
          path: 'routes/:id', // matches /admin/routes/123
          name: 'admin-routes-edit',
          component: () => import('@/views/admin/EditRouteView.vue'),
        },
        {
          path: 'bookings',
          name: 'admin-bookings',
          component: () => import('@/views/admin/BookingsView.vue'),
        },
        {
          path: 'users',
          name: 'admin-users',
          component: () => import('@/views/admin/UsersView.vue'),
        },
        {
          path: 'settings',
          name: 'admin-settings',
          component: () => import('@/views/admin/SettingsView.vue'),
        },
      ],
    },
  ],
})

export default router
