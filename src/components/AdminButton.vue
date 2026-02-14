<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'danger', 'success', 'warning', 'info', 'light', 'dark', 'link', 'outline-primary', 'outline-secondary', 'outline-danger', 'outline-success', 'outline-warning', 'outline-info', 'outline-light', 'outline-dark', 'icon', 'text'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  to: {
    type: [String, Object],
    default: null
  },
  type: {
      type: String,
      default: 'button'
  },
  disabled: {
      type: Boolean,
      default: false
  },
  loading: {
      type: Boolean,
      default: false
  }
})

const emit = defineEmits(['click'])

const baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed'

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'sm': return 'px-3 py-1.5 text-xs rounded-lg'
        case 'md': return 'px-4 py-2 text-sm rounded-xl'
        case 'lg': return 'px-6 py-3 text-base rounded-xl'
        default: return 'px-4 py-2 text-sm rounded-xl'
    }
})

const variantClasses = computed(() => {
    switch (props.variant) {
        case 'primary': return 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md hover:shadow-lg focus:ring-indigo-500'
        case 'secondary': return 'bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200 focus:ring-gray-300'
        case 'danger': return 'bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 focus:ring-red-400'
        case 'success': return 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-100 focus:ring-emerald-400'
        case 'icon': return 'p-2 text-gray-400 hover:text-indigo-600 bg-transparent hover:bg-gray-50 rounded-lg'
        case 'text': return 'bg-transparent text-indigo-600 hover:text-indigo-800 hover:underline p-0'
        default: return 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md hover:shadow-lg focus:ring-indigo-500'
    }
})

const classes = computed(() => {
    return `${baseClasses} ${props.variant !== 'icon' && props.variant !== 'text' ? sizeClasses.value : ''} ${variantClasses.value}`
})

const handleClick = (e) => {
    if (!props.disabled && !props.loading) {
        emit('click', e)
    }
}
</script>

<template>
  <component 
    :is="to ? RouterLink : 'button'" 
    :to="to" 
    :type="!to ? type : null"
    :class="classes"
    :disabled="disabled || loading"
    @click="handleClick"
  >
    <!-- Loading Spinner -->
    <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>

    <!-- Icon Slot (Left) -->
    <span v-if="$slots.icon && !loading" class="mr-2 flex items-center">
        <slot name="icon"></slot>
    </span>

    <!-- Default Content -->
    <slot></slot>
  </component>
</template>
