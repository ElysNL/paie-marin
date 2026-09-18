<template>
  <div class="mb-6 flex items-center justify-between border-b border-outline-variant bg-surface-container-lowest px-4 py-3 shadow-elevation-1 lg:px-6">
    <div class="flex items-center gap-3">
      <button
        type="button"
        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-on-surface-variant transition-colors hover:bg-surface-container-low lg:hidden"
        aria-label="Ouvrir le menu"
        @click="$emit('toggle-nav')"
      >
        <AppIcon name="menu" :size="20" />
      </button>
      <router-link to="/dashboard" class="flex items-center gap-2">
        <span class="grid h-8 w-8 place-items-center rounded-lg bg-primary text-on-primary">
          <AppIcon name="ship" :size="16" />
        </span>
        <span class="hidden text-base font-semibold text-on-surface sm:inline">Gestion de Paie</span>
      </router-link>
    </div>
    <div class="flex items-center gap-2">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup>
import AppIcon from '@/components/AppIcon.vue';

defineEmits(['toggle-nav']);
</script>
