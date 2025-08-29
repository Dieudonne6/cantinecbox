@extends('layouts.master')
@section('content')
<style>
    .table-container {
        margin-bottom: 20px;
        display: block;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
        white-space: nowrap;
    }
    .reduction-profile { display: none; }

    /* Styles individuels pour chaque colonne */
    .col-small { width: 80px; }
    .col-medium { width: 120px; }
    .col-large { width: 200px; }
    .col-xlarge { width: 250px; }
    .col-nom { width: 150px; }
    .col-code-groupe { width: 100px; }
    .col-statut { width: 80px; }

    /* Bouton retour */
    .btn-arrow {
        position: absolute;
        top: 0px;
        left: 0px;
        background-color: transparent !important;
        border: 1px !important;
        text-transform: uppercase !important;
        font-weight: bold !important;
        cursor: pointer !important;
        font-size: 17px !important;
        color: #b51818 !important;
    }
    .btn-arrow:hover { color: #b700ff !important; }

    /* Styles pour impression */
    @media print {
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            color: #000;
        }
        .reduction-profile {
            page-break-before: always;
            margin-bottom: 20px;
        }
        .reduction-profile h5 {
            text-align: center;
            font-size: 16pt;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            white-space: nowrap;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
    }
</style>

<div class="main-content">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <div class="card-body">
                <h1 class="text-center">Liste des élèves par profils</h1>
                <h4>Groupe: {{ $typeclasse->where('TYPECLASSE', $typeClasse)->first()->LibelleType }}</h4>

                <div class="form-group row">
                    <div class="col-9">
                        <select class="js-example-basic-multiple w-100" multiple="multiple" id="champ" name="CODECLAS[]" data-placeholder="Sélectionnez une réduction">
                            <option value=""></option>
                            @foreach ($reductions as $reduction)
                                <option value="{{ $reduction->CodeReduction }}">{{ $reduction->LibelleReduction }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <button class="btn btn-primary w-100" id="applySelectionBtn">Appliquer la sélection</button>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <button class="btn btn-secondary" id="printBtn">Imprimer</button>
                    </div>
                </div>

                @foreach ($reductions as $reduction)
                    <div class="reduction-profile" data-reduction="{{ $reduction->CodeReduction }}">
                        <h5 class="text-center" style="margin-bottom:15px; text-transform: uppercase;">
                            Profil de réduction: {{ $reduction->LibelleReduction }}
                        </h5>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="col-small">N°</th>
                                        <th class="col-nom">Nom</th>
                                        <th class="col-large">Prénoms</th>
                                        <th class="col-code-groupe">Code Groupe</th>
                                        <th class="col-statut">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($elevesParReduction[$reduction->CodeReduction]) && count($elevesParReduction[$reduction->CodeReduction]) > 0)
                                        @foreach ($elevesParReduction[$reduction->CodeReduction] as $eleve)
                                            <tr>
                                                <td class="col-small">{{ $loop->iteration }}</td>
                                                <td class="col-nom">{{ $eleve->NOM }}</td>
                                                <td class="col-large">{{ $eleve->PRENOM }}</td>
                                                <td class="col-code-groupe">{{ $eleve->CODECLAS }}</td>
                                                <td class="col-statut">
                                                    @if ($eleve->STATUTG == 1) Nouveau
                                                    @elseif ($eleve->STATUTG == 2) Ancien
                                                    @elseif ($eleve->STATUTG == 3) Transféré
                                                    @else Inconnu
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">Aucun élève trouvé pour cette réduction.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.js-example-basic-multiple').select2();

    const selectElement = document.querySelector('select[name="CODECLAS[]"]');
    const submitBtn = document.getElementById('applySelectionBtn');
    const printBtn = document.getElementById('printBtn');

    submitBtn.addEventListener('click', function(event) {
        event.preventDefault();
        const selectedValues = Array.from(selectElement.selectedOptions).map(option => option.value);
        document.querySelectorAll('.reduction-profile').forEach(profile => {
            profile.style.display = selectedValues.includes(profile.dataset.reduction) ? 'block' : 'none';
        });
    });

    printBtn.addEventListener('click', function() {
        const visibleProfiles = Array.from(document.querySelectorAll('.reduction-profile'))
            .filter(profile => profile.style.display !== 'none')
            .map(profile => profile.innerHTML)
            .join('<div style="page-break-after: always;"></div>');

        if (!visibleProfiles) {
            alert('Veuillez sélectionner au moins une réduction à imprimer.');
            return;
        }

        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Impression</title>');
        printWindow.document.write('<style>body { font-family: Arial, sans-serif; font-size: 12pt; } table { width: 100%; border-collapse: collapse; margin-bottom: 20px; } th, td { border: 1px solid #333; padding: 8px; text-align: left; white-space: nowrap; } th { background-color: #f0f0f0; font-weight: bold; }</style>');
        printWindow.document.write(`<h2 style="text-align:center;">État des droits constatés - Groupe: {{ $typeclasse->where("TYPECLASSE", $typeClasse)->first()->LibelleType }}</h2><hr>`);
        printWindow.document.write('<body>');
        printWindow.document.write(visibleProfiles);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
});
</script>
@endsection
