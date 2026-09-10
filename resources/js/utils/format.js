export function formatMoney(value, currency = 'MGA') {
    const n = Number(value) || 0;
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    }).format(n);
}

export function formatDate(value) {
    if (!value) return '—';
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return String(value);
    return d.toLocaleDateString('fr-FR');
}

export const statutBadge = {
    brouillon: 'bg-gray-200 text-gray-700',
    calcule: 'bg-blue-100 text-blue-700',
    controle: 'bg-yellow-100 text-yellow-700',
    valide: 'bg-green-100 text-green-700',
    cloture: 'bg-purple-100 text-purple-700',
    en_cours: 'bg-blue-100 text-blue-700',
    remboursee: 'bg-green-100 text-green-700',
    annulee: 'bg-red-100 text-red-700',
    actif: 'bg-green-100 text-green-700',
    termine: 'bg-gray-200 text-gray-700',
    annule: 'bg-red-100 text-red-700',
};

export const statutLibelle = {
    brouillon: 'Brouillon',
    calcule: 'Calculée',
    controle: 'Contrôle',
    valide: 'Validée',
    cloture: 'Clôturée',
    en_cours: 'En cours',
    remboursee: 'Remboursée',
    annulee: 'Annulée',
    actif: 'Actif',
    termine: 'Terminé',
    annule: 'Annulé',
};