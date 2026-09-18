<template>
  <component
    :is="to ? 'router-link' : 'div'"
    :to="to"
    class="block rounded-lg border border-outline-variant bg-surface-container-lowest p-6 shadow-elevation-1 transition hover:border-primary/30"
  >
    <div class="flex items-center gap-3">
      <span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
        <AppIcon :name="icon" :size="20" />
      </span>
      <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-wide text-on-surface-variant">{{ label }}</p>
        <p class="text-3xl font-bold text-on-surface">{{ value }}</p>
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
