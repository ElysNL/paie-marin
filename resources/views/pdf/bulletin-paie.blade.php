<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de paie {{ $bulletin->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .info { width: 100%; margin-bottom: 20px; }
        .info table { width: 100%; border-collapse: collapse; }
        .info td { padding: 4px 8px; }
        .info .label { font-weight: bold; width: 40%; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        table.data th { background-color: #eee; }
        table.data tr.total td { font-weight: bold; background-color: #f5f5f5; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bulletin de Paie N° {{ $bulletin->id }}</h1>
        <p>Période : {{ $bulletin->paie->periode ?? '' }} ({{ optional($bulletin->paie->date_debut)->format('d/m/Y') }} - {{ optional($bulletin->paie->date_fin)->format('d/m/Y') }})</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td class="label">Employé :</td>
                <td>{{ $bulletin->employe->nom_complet ?? '' }}</td>
                <td class="label">Matricule :</td>
                <td>{{ $bulletin->employe->matricule ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Navire :</td>
                <td>{{ $bulletin->navire->nom ?? '' }}</td>
                <td class="label">Fonction :</td>
                <td>{{ $bulletin->affectation->fonction->libelle ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Devise :</td>
                <td>{{ $bulletin->deviseSource->code ?? '' }}</td>
                <td class="label">Taux de change :</td>
                <td>{{ $bulletin->taux_change ?? '-' }}</td>
            </tr>
        </table>
    </div>

    @if($bulletin->elements->isNotEmpty())
    <table class="data">
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Type</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bulletin->elements as $element)
            <tr>
                <td>{{ $element->elemPaie->libelle ?? '' }}</td>
                <td>{{ $element->elemPaie->type ?? '' }}</td>
                <td style="text-align: right;">{{ number_format((float)$element->montant, 2, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <table class="data">
        <tbody>
            <tr class="total">
                <td colspan="2">Total gains (BRUT)</td>
                <td style="text-align: right;">{{ number_format((float)$bulletin->total_brut, 2, ',', ' ') }}</td>
            </tr>
            <tr>
                <td colspan="2">Cotisations salariales</td>
                <td style="text-align: right;">{{ number_format((float)$bulletin->total_cotisations_salariales, 2, ',', ' ') }}</td>
            </tr>
            <tr>
                <td colspan="2">Total retenues</td>
                <td style="text-align: right;">{{ number_format((float)$bulletin->total_retenues, 2, ',', ' ') }}</td>
            </tr>
            <tr class="total">
                <td colspan="2">NET à payer</td>
                <td style="text-align: right;">{{ number_format((float)$bulletin->net_a_payer, 2, ',', ' ') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Coût total employeur : {{ number_format((float)$bulletin->cout_total_employeur, 2, ',', ' ') }} {{ $bulletin->deviseSource->code ?? '' }}
    </div>
</body>
</html>
