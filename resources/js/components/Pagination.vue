<template>
  <nav v-if="last > 1" class="mt-4 flex items-center justify-center gap-2">
    <button
      :disabled="current <= 1"
      @click="emit('page-change', current - 1)"
      class="px-3 py-1 rounded border disabled:opacity-40"
    >Précédent</button>
    <span class="px-2">Page {{ current }} / {{ last }}</span>
    <button
      :disabled="current >= last"
      @click="emit('page-change', current + 1)"
      class="px-3 py-1 rounded border disabled:opacity-40"
    >Suivant</button>
  </nav>
</template>

<script setup>
defineProps({
  current: { type: Number, default: 1 },
  last: { type: Number, default: 1 },
});

const emit = defineEmits(['page-change']);
</script>
