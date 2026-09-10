<?php

return [

    /**
     * Devise de paiement par défaut utilisée pour les bulletins de paie.
     */
    'devise_paiement' => env('PAIE_DEVISE_PAIEMENT', 'MGA'),

    /**
     * Montant de l'abattement appliqué par charge (personne à charge)
     * lors du calcul de l'IGR. Modifiable selon la réglementation en vigueur.
     */
    'abattement_par_charge' => env('PAIE_ABATTEMENT_PAR_CHARGE', 2000),

    /**
     * Prime de navigation forfaitaire appliquée par défaut à chaque bulletin.
     */
    'prime_navigation' => env('PAIE_PRIME_NAVIGATION', 5000),

];
