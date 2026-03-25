import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import { useAuthStore } from './auth'

export const useRegisterStore = defineStore('register', () => {
  const auth = useAuthStore()

  // ─── State ────────────────────────────────────────────────────────────────
  const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user' // Default as per backend requirement
  })

  const loading = ref(false)
  const errorMsg = ref('')
  const errors = ref({})

  // ─── Actions ──────────────────────────────────────────────────────────────

  /**
   * Submit the registration form.
   */
  async function submit() {
    loading.value = true
    errorMsg.value = ''
    errors.value = {}

    try {
      const result = await auth.register({ ...form })

      if (!result.success) {
        errorMsg.value = result.message || 'Registration failed.'
        // Errors are already populated in auth store, but we can sync them here too if preferred
        errors.value = auth.errors || {}
        return { success: false }
      }

      return { success: true }
    } catch (err) {
      errorMsg.value = 'An unexpected error occurred.'
      return { success: false }
    } finally {
      loading.value = false
    }
  }

  /**
   * Reset the form and error states.
   */
  function reset() {
    form.name = ''
    form.email = ''
    form.password = ''
    form.password_confirmation = ''
    form.role = 'user'
    errorMsg.value = ''
    errors.value = {}
  }

  return {
    form,
    loading,
    errorMsg,
    errors,
    submit,
    reset
  }
})
