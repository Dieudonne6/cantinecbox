@extends('layouts.master')
@section('content')
<style>
    .table-container {
        /* overflow-x: auto; */
        margin-bottom: 20px;
        display: block; /* Assurez-vous que le conteneur est un bloc */
    }
    /*     table {
        width: 100%;
        min-width: 12px;
        border-collapse: collapse;
        table-layout: fixed;
    } */
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
        white-space: nowrap;
    }
    .reduction-header {
        text-align: center;
        font-weight: bold;
    }
    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 10; /* Assurer que le footer soit au-dessus des autres éléments */
    }

    /* Styles individuels pour chaque colonne */
    .col-small { width: 80px; }
    .col-medium { width: 120px; }
    .col-large { width: 200px; }
    .col-xlarge { width: 250px; }
    .col-nom { width: 150px; } /* Nouvelle classe pour Nom */
    .col-code-groupe { width: 100px; } /* Nouvelle classe pour Code Groupe */
    .col-statut { width: 80px; } /* Nouvelle classe pour Statut */

    /* Styles pour l'impression */
    @media print {
        .reduction-profile {
            page-break-before: always;
        }
    }
    
    .reduction-profile {
        display: none;
        transition: all 0.3s ease;
    }
</style>

<body>
<div class="main-content">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div>
                <style>
                    .btn-arrow {
                        position: absolute;
                        top: 0px;
                        /* Ajustez la position verticale */
                        left: 0px;
                        /* Positionnez à gauche */
                        background-color: transparent !important;
                        border: 1px !important;
                        text-transform: uppercase !important;
                        font-weight: bold !important;
                        cursor: pointer !important;
                        font-size: 17px !important;
                        /* Taille de l'icône */
                        color: #b51818 !important;
                        /* Couleur de l'icône */
                    }
            
                    .btn-arrow:hover {
                        color: #b700ff !important;
                        /* Couleur au survol */
                    }
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br>
                <br>                                     
            </div>
            <div class="card-body">
                <h1 class="text-center">Liste des élèves par profils</h1>
                <h4>Groupe: {{ $typeclasse->where('TYPECLASSE', $typeClasse)->first()->LibelleType }}</h4>
                <div class="form-group row">
                    <div class="col-9">
                        <select class="js-example-basic-multiple w-100" multiple="multiple" id="champ" name="CODECLAS[]" data-placeholder="Sélectionnez une réduction">         
                          <option value=""></option>
                          @foreach ($reductions as $reduction)
                          <option value="{{$reduction->CodeReduction}}">{{$reduction->LibelleReduction}}</option>
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
                <br></br>
                
                    <div class="reduction-profile" style="display: none; {{ $loop->first ? '' : 'page-break-before: always;' }}" data-reduction="{{ $reduction->CodeReduction }}">
                        <h5>Profil de réduction: {{ $reduction->LibelleReduction }}</h5>
                        <div {{-- class="table-container" --}}>
                            <table style="width: 100%; min-width: 12px; border-collapse: collapse; table-layout: fixed;">

                                <thead>
                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap; width: 50px;">N°</th>
                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap; width: 100px;">Nom</th>
                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap; width: 150px;">Prénoms</th>
                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap; width: 80px;">Code Groupe</th>
                                    <th style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap; width: 80px;">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($elevesParReduction[$reduction->CodeReduction]) && count($elevesParReduction[$reduction->CodeReduction]) > 0)
                                        @foreach ($elevesParReduction[$reduction->CodeReduction] as $eleve)
                                            <tr>
                                                <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $loop->iteration }}</td>
                                                <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $eleve->NOM }}</td>
                                                <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $eleve->PRENOM }}</td>
                                                <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">{{ $eleve->CODECLAS }}</td>
                                                <td style="border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap;">
                                                    @if ($eleve->STATUTG == 1)
                                                        Nouveau
                                                    @elseif ($eleve->STATUTG == 2)
                                                        Ancien
                                                    @elseif ($eleve->STATUTG == 3)
                                                        Transféré
                                                    @else
                                                        Inconnu
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
</body>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser Select2
        $('.js-example-basic-multiple').select2();
        
        const selectElement = document.querySelector('select[name="CODECLAS[]"]');
        const submitBtn = document.getElementById('applySelectionBtn'); // Utilisez le nouvel ID
        const printBtn = document.getElementById('printBtn'); // Bouton d'impression

        // Gérer le clic sur le bouton "Appliquer la sélection"
        submitBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du bouton
            
            const selectedValues = Array.from(selectElement.selectedOptions).map(option => option.value);
            
            // Parcourir tous les profils et afficher/cacher en fonction de la sélection
            document.querySelectorAll('.reduction-profile').forEach(profile => {
                if (selectedValues.includes(profile.dataset.reduction)) {
                    profile.style.display = 'block'; // Afficher si la réduction est sélectionnée
                } else {
                    profile.style.display = 'none'; // Cacher si la réduction ne correspond pas
                }
            });
        });

        // Gérer le clic sur le bouton "Imprimer"
        printBtn.addEventListener('click', function() {
            // Masquer les boutons et le champ de sélection
            document.querySelector('.form-group').style.display = 'none'; // Masquer le groupe de formulaire
            document.querySelectorAll('.btn').forEach(btn => btn.style.display = 'none'); // Masquer tous les boutons
                        
            var content = document.querySelector('.card').innerHTML; // Sélectionne le contenu de la classe card
            var originalContent = document.body.innerHTML; // Sauvegarde le contenu complet de la page

            document.body.innerHTML = content; // Remplace tout le contenu par la section sélectionnée
            window.print(); // Lance l'impression

            document.body.innerHTML = originalContent; // Restaure le contenu original après impression
            document.querySelector('.form-group').style.display = 'block'; // Masquer le groupe de formulaire
            document.querySelectorAll('.btn').forEach(btn => btn.style.display = 'block');
        });
    });
</script>

@endsection
