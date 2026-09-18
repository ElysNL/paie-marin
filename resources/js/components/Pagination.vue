<template>
  <nav v-if="last > 1" class="mt-4 flex items-center justify-center gap-2">
    <AppButton variant="secondary" :disabled="current <= 1" @click="emit('page-change', current - 1)">
      Précédent
    </AppButton>
    <span class="px-2 text-sm text-on-surface-variant">Page {{ current }} / {{ last }}</span>
    <AppButton variant="secondary" :disabled="current >= last" @click="emit('page-change', current + 1)">
      Suivant
    </AppButton>
  </nav>
</template>

<script setup>
import AppButton from '@/components/AppButton.vue';

defineProps({
  current: { type: Number, default: 1 },
  last: { type: Number, default: 1 },
});

const emit = defineEmits(['page-change']);
</script>
