@extends('layouts.master')
@section('content')
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
                <h5 class="mb-2">Extraction des Notes</h5></br>

                <!-- Ligne pour les boutons et les checkboxes -->
                <div class="row mb-4 align-items-center">
                    <div class="col-auto">
                        <div class="d-flex align-items-center">
                            <div class="form-check form-check-inline mr-3">
                                <input class="form-check-input toggle-column" type="checkbox" id="toggleMoyInt" data-column="moy-int" checked>
                                <label class="form-check-label" for="toggleMoyInt">Moy int</label>
                            </div>
                            <div class="form-check form-check-inline mr-3">
                                <input class="form-check-input toggle-column" type="checkbox" id="toggleDEV1" data-column="dev1" checked>
                                <label class="form-check-label" for="toggleDEV1">DEV 1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input toggle-column" type="checkbox" id="toggleDEV2" data-column="dev2" checked>
                                <label class="form-check-label" for="toggleDEV2">DEV 2</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary" onclick="exportExcel()">Exporter</button>
                        <button class="btn btn-primary ml-3" onclick="printTable()">Imprimer</button>
                        <!-- Bouton d'export Excel -->
                        {{-- <a href="{{ route('notes.exportExcel', [
                                'periode' => request('periode'),
                                'classe'  => request('classe'),
                                'matiere' => request('matiere')
                            ]) }}"
                           class="btn btn-primary">
                            Exporter en Excel
                        </a> --}}
                    </div>
                </div></br>
                
                <!-- Zone à imprimer -->
                <div class="container" id="printArea">

                    <h5 class="mb-4">Classe : {{ $classe }} | Matiere : {{ $nomMatiere->LIBELMAT }} | {{ $periodLabel }} : {{ $periode }}</h5></br>
                    
                    <!-- Tableau des notes -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                {{-- <th>Classe</th>
                                <th>Matiere</th> --}}
                                <th>MATRICULE</th>
                                <th>Nom et Prenom</th>
                                <th class="moy-int">Moy Inter</th>
                                <th class="dev1">DEV 1</th>
                                <th class="dev2">DEV 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notes->groupBy('MATRICULE') as $matricule => $studentNotes)
                                @php
                                    // Calcul de la moyenne des interros pour cet élève
                                    $totalInterro = $studentNotes->sum('interro');
                                    $countInterro = $studentNotes->count();
                                    $moyenneInterro = $countInterro ? number_format($totalInterro / $countInterro, 2) : 0;
                                    // On suppose que DEV1, DEV2 et DEV3 sont identiques pour chaque enregistrement d'un élève
                                    $firstNote = $studentNotes->first();
                                @endphp
                                <tr>
                                    {{-- <td>{{ $firstNote->eleve->CODECLAS }}</td>
                                    <td>{{ $nomMatiere->LIBELMAT }}</td> --}}
                                    <td>{{ $firstNote->eleve->MATRICULEX }}</td>
                                    <td>{{ $firstNote->eleve->NOM .' '. $firstNote->eleve->PRENOM }}</td>
                                    <td class="moy-int">
                                        {{ ($firstNote->MI == 21 || $firstNote->MI == -1) ? '**.**' : round($firstNote->MI, 2) }}
                                      </td>
                                      <td class="dev1">
                                        {{ ($firstNote->DEV1 == 21 || $firstNote->DEV1 == -1) ? '**.**' : $firstNote->DEV1 }}
                                      </td>
                                      <td class="dev2">
                                        {{ ($firstNote->DEV2 == 21 || $firstNote->DEV2 == -1) ? '**.**' : $firstNote->DEV2 }}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Script JavaScript pour la gestion des colonnes (optionnel si vous souhaitez conserver l'affichage dynamique) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-column').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const columnClass = this.getAttribute('data-column');
                document.querySelectorAll('.' + columnClass).forEach(function(cell) {
                    cell.style.display = checkbox.checked ? '' : 'none';
                });
            });
        });
    });
</script>

<script>
    function exportExcel() {
        // Récupérer l'état des checkboxes
        var exportMoy = document.getElementById("toggleMoyInt").checked ? 1 : 0;
        var exportDev1 = document.getElementById("toggleDEV1").checked ? 1 : 0;
        var exportDev2 = document.getElementById("toggleDEV2").checked ? 1 : 0;

        // Construire l'URL avec les paramètres actuels (période, classe, matiere, etc.)
        var url = "{{ route('notes.exportExcel') }}";
        url += "?periode={{ request('periode') }}";
        url += "&classe={{ request('classe') }}";
        url += "&matiere={{ request('matiere') }}";
        url += "&exportMoy=" + exportMoy;
        url += "&exportDev1=" + exportDev1;
        url += "&exportDev2=" + exportDev2;

        // Rediriger vers l'URL d'export
        window.location.href = url;
    }
</script>

<script>
    //  Script JavaScript pour la gestion des colonnes et de l'impression 
    // Fonction pour imprimer uniquement la zone "printArea"
    function printTable() {
        // Récupération du contenu à imprimer
        var printContents = document.getElementById('printArea').innerHTML;
        // Sauvegarde du contenu original de la page
        var originalContents = document.body.innerHTML;
        // Remplace le contenu de la page par la zone à imprimer
        document.body.innerHTML = printContents;
        // Lancement de la boîte de dialogue d'impression
        window.print();
        // Restauration du contenu original
        document.body.innerHTML = originalContents;
        // Optionnel : recharger la page pour réactiver les scripts
        window.location.reload();
    }
</script>


@endsection
