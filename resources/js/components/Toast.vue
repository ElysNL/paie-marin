<template>
  <div class="fixed right-4 top-4 z-50 w-80 max-w-[calc(100vw-2rem)] space-y-2">
    <div
      v-for="toast in toasts"
      :key="toast.id"
      class="flex items-start justify-between gap-3 rounded-lg px-4 py-3 text-sm text-white shadow-elevation-3"
      :class="{
        'bg-tertiary-container': toast.type === 'success',
        'bg-error': toast.type === 'error',
        'bg-primary-light': toast.type === 'info',
      }"
    >
      <span>{{ toast.message }}</span>
      <button class="opacity-80 hover:opacity-100" @click="dismiss(toast.id)">&#x2715;</button>
    </div>
  </div>
</template>

<script setup>
import { useToasts } from '@/services/toast';

const { toasts, remove } = useToasts();
const dismiss = (id) => remove(id);
</script>
