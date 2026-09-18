<template>
  <div class="mb-4">
    <label v-if="label" :for="id" class="mb-1 block text-xs font-semibold tracking-wide text-on-surface-variant">{{ label }}</label>
    <select
      :id="id"
      :value="modelValue"
      :required="required"
      :disabled="disabled"
      :class="[
        'w-full rounded-lg border bg-surface-container-lowest px-3 py-2 text-sm text-on-surface outline-none transition',
        error
          ? 'border-error focus:border-error focus:ring-1 focus:ring-error/20'
          : 'border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20',
      ]"
      @change="$emit('update:modelValue', $event.target.value)"
    >
      <option v-if="placeholder" :value="null" disabled>{{ placeholder }}</option>
      <option v-for="opt in options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
    </select>
    <p v-if="error" class="mt-1 text-xs text-on-error-container">{{ Array.isArray(error) ? error[0] : error }}</p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: { type: [String, Number, null], default: null },
  label: { type: String, default: '' },
  options: { type: Array, default: () => [] },
  placeholder: { type: String, default: '' },
  required: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  error: { type: [String, Array, Object], default: '' },
  id: { type: String, default: () => 'select-' + Math.random().toString(36).slice(2, 8) },
});

defineEmits(['update:modelValue']);
</script>
