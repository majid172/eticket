import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'
import router from '@/router'

export const useAuthStore = defineStore('auth', () => {
  // ─── State ────────────────────────────────────────────────────────────────
  const user = ref(JSON.parse(localStorage.getItem('auth_user')) || null)
  const token = ref(localStorage.getItem('auth_token') || null)
  const loading = ref(false)
  const errors = ref({})

  // ─── Getters ──────────────────────────────────────────────────────────────
  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isOperator = computed(() => user.value?.role === 'operator')
  const isPassenger = computed(() => user.value?.role === 'user')

  // ─── Helpers ──────────────────────────────────────────────────────────────
  function setSession(userData, tokenValue) {
    user.value = userData
    token.value = tokenValue
    localStorage.setItem('auth_user', JSON.stringify(userData))
    localStorage.setItem('auth_token', tokenValue)
  }

  function clearSession() {
    user.value = null
    token.value = null
    localStorage.removeItem('auth_user')
    localStorage.removeItem('auth_token')
  }

  // ─── Actions ──────────────────────────────────────────────────────────────

  /**
   * Register a new user or operator.
   */
  async function register(payload) {
    loading.value = true
    errors.value = {}
    try {
      const { data } = await api.post('/auth/register', payload)
      setSession(data.user, data.token)
      redirectByRole(data.user.role)
      return { success: true }
    } catch (err) {
      errors.value = err.response?.data?.errors || {}
      return { success: false, message: err.response?.data?.message || 'Registration failed.' }
    } finally {
      loading.value = false
    }
  }

  /**
   * Login with email and password.
   */
  async function login(payload) {
    loading.value = true
    errors.value = {}
    try {
      const { data } = await api.post('/auth/login', payload)
      setSession(data.user, data.token)
      redirectByRole(data.user.role)
      return { success: true }
    } catch (err) {
      errors.value = err.response?.data?.errors || {}
      return { success: false, message: err.response?.data?.message || 'Login failed.' }
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch the current authenticated user profile.
   */
  async function fetchUser() {
    try {
      const { data } = await api.get('/auth/me')
      user.value = data.user
      localStorage.setItem('auth_user', JSON.stringify(data.user))
    } catch {
      clearSession()
    }
  }

  /**
   * Logout and revoke token.
   */
  async function logout() {
    try {
      await api.post('/auth/logout')
    } finally {
      clearSession()
      router.push({ name: 'login' })
    }
  }

  /**
   * Redirect the user to their role-specific panel after login/register.
   * Honors ?redirect= query param so users land back where they intended.
   */
  function redirectByRole(role) {
    // Consume the ?redirect= param set by the auth guard when bouncing to /login
    const redirectPath = router.currentRoute.value.query?.redirect

    if (role === 'admin') {
      router.push(redirectPath || { name: 'admin-dashboard' })
    } else if (role === 'operator') {
      router.push(redirectPath || { name: 'operator-dashboard' })
    } else {
      // passenger / user — send to intended page or home
      router.push(redirectPath || { name: 'home' })
    }
  }

  return {
    // state
    user, token, loading, errors,
    // getters
    isAuthenticated, isAdmin, isOperator, isPassenger,
    // actions
    register, login, logout, fetchUser,
    // helpers (exported so components/OAuth callbacks can use them)
    setSession, clearSession, redirectByRole,
  }
})
