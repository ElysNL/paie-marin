<template>
  <div class="mb-4 overflow-hidden rounded-lg border border-outline-variant">
    <table class="w-full border-collapse text-left">
      <thead>
        <tr class="border-b border-outline-variant bg-surface-container">
          <th
            v-for="col in columns"
            :key="col.key"
            :class="[
              'px-4 py-3 text-xs font-semibold uppercase tracking-wide text-on-surface-variant',
              col.align === 'right' ? 'text-right' : '',
              col.align === 'center' ? 'text-center' : '',
            ]"
          >
            {{ col.label }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(row, idx) in rows"
          :key="rowKey ? row[rowKey] : idx"
          class="border-b border-surface-variant transition-colors hover:bg-surface-container-low"
        >
          <td
            v-for="col in columns"
            :key="col.key"
            :class="[
              'px-4 py-3 text-sm text-on-surface',
              col.align === 'right' ? 'text-right' : '',
              col.align === 'center' ? 'text-center' : '',
              col.class || '',
            ]"
          >
            <slot :name="'cell(' + col.key + ')'" :row="row" :value="row[col.key]">
              {{ row[col.key] ?? '—' }}
            </slot>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  columns: { type: Array, required: true },
  rows: { type: Array, default: () => [] },
  rowKey: { type: String, default: 'id' },
});
</script>
