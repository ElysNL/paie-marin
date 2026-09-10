<template>
  <div class="fixed top-4 right-4 z-50 space-y-2 w-80 max-w-[calc(100vw-2rem)]">
    <div
      v-for="toast in toasts"
      :key="toast.id"
      class="rounded-lg shadow-lg px-4 py-3 text-sm text-white flex items-start justify-between gap-3"
      :class="{
        'bg-green-600': toast.type === 'success',
        'bg-red-600': toast.type === 'error',
        'bg-blue-600': toast.type === 'info',
      }"
    >
      <span>{{ toast.message }}</span>
      <button class="opacity-80 hover:opacity-100" @click="dismiss(toast.id)">✕</button>
    </div>
  </div>
</template>

<script setup>
import { useToasts } from '@/services/toast';

const { toasts, remove } = useToasts();
const dismiss = (id) => remove(id);
</script>