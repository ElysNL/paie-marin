<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de paie {{ $bulletin->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        .container { width: 100%; padding: 15px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .header h1 { font-size: 16px; margin-bottom: 4px; }
        .header .societe { font-size: 12px; font-weight: bold; }
        .header .adresse { font-size: 9px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table td, table th { border: 1px solid #999; padding: 4px 6px; }
        table th { background-color: #e0e0e0; font-weight: bold; }
        .label { font-weight: bold; width: 40%; background-color: #f5f5f5; }
        .total-row td { font-weight: bold; background-color: #e8e8e8; }
        .net-row td { font-weight: bold; background-color: #d0d0d0; font-size: 12px; }
        .section-title { background-color: #333; color: #fff; font-weight: bold; text-align: center; }
        .signatures { display: flex; justify-content: space-between; margin-top: 30px; padding-top: 10px; }
        .signature-box { width: 45%; text-align: center; }
        .signature-line { border-top: 1px solid #333; margin-top: 50px; padding-top: 5px; }
        .exchange { margin-top: 15px; border-top: 2px solid #000; padding-top: 10px; }
        .exchange .title { font-weight: bold; font-size: 11px; margin-bottom: 5px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>BULLETIN DE PAIE MARIN</h1>
        <div class="societe">{{ $bulletin->affectation->contratArmateur->armateur->raison_sociale ?? 'SOCIÉTÉ' }}</div>
        <div class="adresse">
            {{ $bulletin->affectation->contratArmateur->armateur->adresse ?? '' }}
            {{ $bulletin->affectation->contratArmateur->armateur->email ? '| ' . $bulletin->affectation->contratArmateur->armateur->email : '' }}
        </div>
    </div>

    <table>
        <tr>
            <td class="label">NOM ET PRENOMS</td>
            <td>{{ $bulletin->employe->prenom ?? '' }} {{ $bulletin->employe->nom ?? '' }}</td>
            <td class="label">NAVIRE</td>
            <td>{{ $bulletin->navire->nom ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">N° LPM</td>
            <td>{{ $bulletin->employe->num_lpm ?? '-' }}</td>
            <td class="label">FONCTION</td>
            <td>{{ $bulletin->affectation->fonction->libelle ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">N° CNAPS</td>
            <td>{{ $bulletin->employe->num_cnaps ?? '-' }}</td>
            <td class="label">DATE DÉBUT / FIN</td>
            <td>{{ optional($bulletin->paie->date_debut)->format('d/m/Y') }} — {{ optional($bulletin->paie->date_fin)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">N° VISA CONTRAT</td>
            <td>{{ $bulletin->employe->visa_contrat ?? '-' }}</td>
            <td class="label">NOMBRE DE JOURS</td>
            <td>{{ $bulletin->total_jours }}</td>
        </tr>
    </table>

    <table>
        <tr><td class="label">VALEUR D'INDICE</td><td>{{ $bulletin->affectation->taux_journalier ?? '-' }}</td></tr>
        <tr><td class="label">INDICE MONÉTAIRE</td><td>{{ $bulletin->deviseSource->code ?? '' }}</td></tr>
        <tr><td class="label">PAIEMENT PAR JOUR</td><td>{{ number_format((float) $bulletin->affectation->taux_journalier, 2, ',', ' ') }} {{ $bulletin->deviseSource->code ?? '' }}</td></tr>
        <tr><td class="label">PAIEMENT PAR MOIS</td><td>{{ number_format((float) $bulletin->affectation->taux_journalier * 30, 2, ',', ' ') }} {{ $bulletin->deviseSource->code ?? '' }}</td></tr>
    </table>

    <table>
        <tr><th colspan="2" class="section-title">GAINS</th></tr>
        <tr><th style="text-align:left;">DÉSIGNATION</th><th style="text-align:right;">MONTANT</th></tr>
        @foreach($bulletin->elements->where('elemPaie.type', 'GAIN') as $gain)
        <tr>
            <td>{{ $gain->elemPaie->libelle ?? '' }}</td>
            <td style="text-align:right;">{{ number_format((float) $gain->montant, 2, ',', ' ') }} {{ $bulletin->deviseSource->code ?? '' }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td>TOTAL BRUT MARIN</td>
            <td style="text-align:right;">{{ number_format((float) $bulletin->total_brut, 2, ',', ' ') }} {{ $bulletin->deviseSource->code ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr><th colspan="2" class="section-title">RETENUES</th></tr>
        <tr><th style="text-align:left;">DÉSIGNATION</th><th style="text-align:right;">MONTANT</th></tr>
        @foreach($bulletin->elements->where('elemPaie.type', 'RETENUE') as $retenue)
        <tr>
            <td>{{ $retenue->elemPaie->libelle ?? '' }}</td>
            <td style="text-align:right;">{{ number_format((float) $retenue->montant, 2, ',', ' ') }} {{ $bulletin->deviseSource->code ?? '' }}</td>
        </tr>
        @endforeach
        @foreach($bulletin->cotisations as $cotisation)
        <tr>
            <td>{{ $cotisation->cotisation->libelle ?? '' }}</td>
            <td style="text-align:right;">{{ number_format((float) $cotisation->montant_salarial, 2, ',', ' ') }} {{ $bulletin->deviseSource->code ?? '' }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td>TOTAL RETENUES</td>
            <td style="text-align:right;">{{ number_format((float) $bulletin->total_retenues, 2, ',', ' ') }} {{ $bulletin->deviseSource->code ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr class="net-row">
            <td>NET À PAYER EN ARIARY</td>
            <td style="text-align:right;">{{ number_format((float) $bulletin->net_a_payer, 2, ',', ' ') }} MGA</td>
        </tr>
    </table>

    <div class="signatures">
        <div class="signature-box"><div class="signature-line">L'EMPLOYEUR</div></div>
        <div class="signature-box"><div class="signature-line">LE SALARIÉ (MARIN)</div></div>
    </div>

    @if($tauxChanges && $tauxChanges->isNotEmpty())
    <div class="exchange">
        <div class="title">TAUX DE CHANGE</div>
        @foreach($tauxChanges as $tc)
        <div>1 {{ $tc->deviseSource->code ?? '' }} = {{ number_format((float) $tc->taux, 2, ',', ' ') }} Ar</div>
        @endforeach
        @if($bulletin->deviseSource->code !== 'MGA')
        <div><strong>SOIT EN EUROS = {{ number_format((float) ($bulletin->net_a_payer / ($bulletin->taux_change ?: 1)), 2, ',', ' ') }} {{ $bulletin->deviseSource->code ?? '' }}</strong></div>
        @endif
    </div>
    @endif
</div>
</body>
</html>
