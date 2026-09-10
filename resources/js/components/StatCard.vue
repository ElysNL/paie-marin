<template>
  <component
    :is="to ? 'router-link' : 'div'"
    :to="to"
    class="block rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:border-blue-200"
  >
    <div class="flex items-center gap-3">
      <span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-blue-50 text-blue-600">
        <AppIcon :name="icon" :size="20" />
      </span>
      <div class="min-w-0">
        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ label }}</p>
        <p class="text-3xl font-bold text-gray-900">{{ value }}</p>
      </div>
    </div>
  </component>
</template>

<script setup>
import AppIcon from '@/components/AppIcon.vue';

defineProps({
  label: { type: String, required: true },
  value: { type: String, default: '' },
  icon: { type: String, default: 'circle' },
  to: { type: String, default: '' },
});
</script>