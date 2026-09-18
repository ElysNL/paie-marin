<template>
  <div class="mb-4">
    <label v-if="label" :for="id" class="mb-1 block text-xs font-semibold tracking-wide text-on-surface-variant">{{ label }}</label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      :class="[
        'w-full rounded-lg border bg-surface-container-lowest px-3 py-2 text-sm text-on-surface outline-none transition',
        'placeholder:text-on-surface-variant/50',
        error
          ? 'border-error focus:border-error focus:ring-1 focus:ring-error/20'
          : 'border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20',
      ]"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <p v-if="error" class="mt-1 text-xs text-on-error-container">{{ Array.isArray(error) ? error[0] : error }}</p>
    <p v-else-if="hint" class="mt-1 text-xs text-on-surface-variant">{{ hint }}</p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: { type: [String, Number], default: '' },
  label: { type: String, default: '' },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  required: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  error: { type: [String, Array, Object], default: '' },
  hint: { type: String, default: '' },
  id: { type: String, default: () => 'input-' + Math.random().toString(36).slice(2, 8) },
});

defineEmits(['update:modelValue']);
</script>
