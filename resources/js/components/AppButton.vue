<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center gap-1.5 rounded-lg px-4 py-2 text-sm font-medium transition-all',
      'disabled:cursor-not-allowed disabled:opacity-40',
      variantClasses,
    ]"
  >
    <svg v-if="loading" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
    </svg>
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: { type: String, default: 'primary', validator: (v) => ['primary', 'secondary', 'danger', 'text'].includes(v) },
  type: { type: String, default: 'button' },
  disabled: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
});

const variantClasses = computed(() => {
  const map = {
    primary: 'bg-primary-light text-on-primary hover:bg-primary shadow-elevation-1 hover:shadow-elevation-2',
    secondary: 'border border-outline-variant bg-surface-container-lowest text-on-surface hover:bg-surface-container-low',
    danger: 'bg-error text-on-error hover:bg-error/90 shadow-elevation-1 hover:shadow-elevation-2',
    text: 'bg-transparent text-primary hover:bg-primary/8 px-3 py-1.5',
  };
  return map[props.variant];
});
</script>
