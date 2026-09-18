<template>
  <span
    :class="[
      'inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold',
      colorClasses,
    ]"
  >
    {{ label || statutLibelle[statut] || statut }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  statut: { type: String, required: true },
  label: { type: String, default: '' },
});

const statutLibelle = {
  brouillon: 'Brouillon',
  calcule: 'Calculée',
  valide: 'Validée',
  cloture: 'Clôturée',
  en_cours: 'En cours',
  remboursee: 'Remboursée',
  annulee: 'Annulée',
  actif: 'Actif',
  termine: 'Terminé',
  annule: 'Annulé',
};

const colorClasses = computed(() => {
  const map = {
    brouillon: 'bg-surface-container-high text-on-surface-variant',
    calcule: 'bg-secondary-container text-on-secondary',
    valide: 'bg-tertiary-fixed text-on-tertiary-fixed',
    cloture: 'bg-surface-container-high text-on-surface-variant',
    en_cours: 'bg-secondary-container text-on-secondary',
    remboursee: 'bg-tertiary-fixed text-on-tertiary-fixed',
    annulee: 'bg-error-container text-on-error-container',
    actif: 'bg-tertiary-fixed text-on-tertiary-fixed',
    termine: 'bg-surface-container-high text-on-surface-variant',
    annule: 'bg-error-container text-on-error-container',
  };
  return map[props.statut] || 'bg-surface-container-high text-on-surface-variant';
});
</script>
