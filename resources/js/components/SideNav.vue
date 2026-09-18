<template>
  <aside class="hidden w-64 shrink-0 flex-col border-r border-gray-700 bg-gray-800 lg:flex">
    <SideNavContent />
  </aside>

  <teleport to="body">
    <div v-if="open" class="fixed inset-0 z-40 lg:hidden">
      <div class="absolute inset-0 bg-black/40" @click="$emit('close')"></div>
      <aside class="absolute inset-y-0 left-0 flex w-64 flex-col border-r border-gray-700 bg-gray-800 shadow-lg">
        <SideNavContent @close="$emit('close')" />
      </aside>
    </div>
  </teleport>
</template>

<script setup>
import SideNavContent from '@/components/SideNavContent.vue';

defineProps({
  open: { type: Boolean, default: false },
});

defineEmits(['close']);
</script>