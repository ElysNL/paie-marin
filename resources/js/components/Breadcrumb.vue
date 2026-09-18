<template>
  <nav v-if="items.length" aria-label="Fil d'Ariane" class="mb-4 text-sm">
    <ol class="flex flex-wrap items-center gap-1 text-gray-500">
      <li v-for="(item, index) in items" :key="index" class="flex items-center gap-1">
        <AppIcon v-if="index > 0" name="chevron-right" :size="14" />
        <router-link
          v-if="item.to && !isLast(index)"
          class="text-gray-600 hover:text-blue-600"
          :to="item.to"
        >
          {{ item.label }}
        </router-link>
        <span v-else class="font-medium" :class="isLast(index) ? 'text-gray-900' : ''">
          {{ item.label }}
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import AppIcon from '@/components/AppIcon.vue';

const props = defineProps({
  items: { type: Array, default: () => [] },
});

const isLast = (index) => index === props.items.length - 1;
</script>