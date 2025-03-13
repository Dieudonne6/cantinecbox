@extends('layouts.master')

@section('content')
    <div class="container col-11">
        <!-- Titre principal avec espacement -->
        <div class="card mb-4">
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
            <div class="card-body text-center">
                <h3>Impression des attestations de mérite</h3>
            </div>
        </div>

        <div class="row">
            <!-- Colonne gauche : Table des étudiants -->
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <form id="filterForm" method="GET" action="{{ route('attestationfiltere') }}">
                            <div class="row">
                                <!-- Sélection du mérite à afficher -->
                                <div class="col-md-4">
                                    <label for="meritToPrint">Mérite à Imprimer</label>
                                    <select id="meritToPrint" name="merit" class="js-example-basic-multiple w-100"
                                        onchange="this.form.submit()">
                                        <option value="{{ $params->NoteTH }}"
                                            @if (request('merit') == $params->NoteTH) selected @endif>TABLEAU D'HONNEUR</option>
                                        <option value="{{ $params->NoteEncourage }}"
                                            @if (request('merit') == $params->NoteEncourage) selected @endif>ENCOURAGEMENT</option>
                                        <option value="{{ $params->NoteFelicitation }}"
                                            @if (request('merit') == $params->NoteFelicitation) selected @endif>FÉLICITATIONS</option>
                                        <option value="incitation" @if (request('merit') == 'incitation') selected @endif>
                                            CERTIFICAT D'INCITATION</option>
                                    </select>
                                </div>

                                <!-- Sélection du trimestre -->
                                <div class="col-md-4">
                                    <label for="trimestre">Trimestre</label>
                                    <select id="trimestre" name="trimestre" class="js-example-basic-multiple w-100"
                                        onchange="this.form.submit()">
                                        <option value="1" @if (request('trimestre') == '1') selected @endif>1er
                                            Trimestre</option>
                                        <option value="2" @if (request('trimestre') == '2') selected @endif>2ème
                                            Trimestre</option>
                                        <option value="3" @if (request('trimestre') == '3') selected @endif>3ème
                                            Trimestre</option>
                                    </select>
                                </div>

                                <!-- Sélection de la classe -->
                                <div class="col-md-4">
                                    <label for="classSelect">Classe</label>
                                    <select id="classSelect" name="CODECLAS" class="js-example-basic-multiple w-100 mb-3"
                                        onchange="this.form.submit()">
                                        @foreach ($classes as $classe)
                                            <option value="{{ $classe->CODECLAS }}"
                                                @if (request('CODECLAS') == $classe->CODECLAS) selected @endif>{{ $classe->CODECLAS }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Section des boutons radio pour le choix du mérite -->
                            <div class="border p-2 rounded mt-3 d-flex align-items-center">
                                <div class="form-check ms-4">
                                    <input type="radio" id="allMerits" name="meritMode" class="form-check-input"
                                        value="allMerits" @if (request('meritMode') === 'allMerits') checked @endif
                                        onchange="this.form.submit()">
                                    <label for="allMerits" class="form-check-label">Tous les mérites</label>
                                </div>
                                <div class="form-check ms-auto">
                                    <input type="radio" id="meritOnly" name="meritMode" class="form-check-input"
                                        value="meritOnly" @if (request('meritMode') === 'meritOnly') checked @endif
                                        onchange="this.form.submit()">
                                    <label for="meritOnly" class="form-check-label">Plus fort mérite uniquement</label>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <style>
                            .table-limited-height {
                                max-height: 540px;
                                overflow-y: auto;
                            }

                            .table-limited-height::-webkit-scrollbar {
                                width: 8px;
                            }

                            .table-limited-height::-webkit-scrollbar-thumb {
                                background-color: #007bff;
                                border-radius: 4px;
                            }
                        </style>

                        <!-- Table pour afficher les élèves selon les filtres sélectionnés -->
                        <div class="table-limited-height">
                            <table class="table table-bordered table-hover text-center">
                                <caption>Liste des étudiants selon les filtres sélectionnés</caption>
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Choix</th>
                                        <th>Nom et Prénom</th>
                                        <th>Moy</th>
                                    </tr>
                                </thead>
                                <tbody id="tablemerite">
                                    @foreach ($eleves as $eleve)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_eleves[]"
                                                    aria-label="Sélectionner élève" value="{{ $eleve->MATRICULE ?? '' }}">
                                            </td>
                                            <td>{{ $eleve->NOM ?? '' }}<br>{{ $eleve->PRENOM ?? '' }}</td>
                                            <td>{{ $eleve->{'MS' . $trimestre} ?? '' }}</td>
                                            <!-- Affiche la colonne dynamique selon le trimestre -->
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Options et impression -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-primary mt-4">Paramétrage</h5>
                        <form class="p-3 border rounded">
                            <div class="form-group d-flex align-items-center mb-2">
                                <label for="felicitation" class="me-3"
                                    style="width: 150px; margin-bottom: 0;">Félicitations</label>
                                <input type="number" id="felicitation" class="form-control ms-auto"
                                    value="{{ $params->NoteFelicitation }}"
                                    style="width: 80px; font-size: 14px; text-align: center; height: 36px;" readonly>
                            </div>
                            <div class="form-group d-flex align-items-center mb-2">
                                <label for="encouragement" class="me-3"
                                    style="width: 150px; margin-bottom: 0;">Encouragements</label>
                                <input type="number" id="encouragement" class="form-control ms-auto"
                                    value="{{ $params->NoteEncourage }}"
                                    style="width: 80px; font-size: 14px; text-align: center; height: 36px;" readonly>
                            </div>
                            <div class="form-group d-flex align-items-center mb-2">
                                <label for="honorTable" class="me-3" style="width: 150px; margin-bottom: 0;">Tableau
                                    d'Honneur</label>
                                <input type="number" id="honorTable" class="form-control ms-auto"
                                    value="{{ $params->NoteTH }}"
                                    style="width: 80px; font-size: 14px; text-align: center; height: 36px;" readonly>
                            </div>
                        </form>

                        <h5 class="text-primary mt-4">Choix du signataire:</h5>
                        <div class="border p-3 rounded">
                            <div class="form-check form-check-inline flex-grow-1 text-center">
                                <input type="radio" id="signNone" name="signChoice" class="form-check-input" checked>
                                <label for="signNone" class="form-check-label">Aucun</label>
                            </div>
                            <div class="form-check form-check-inline flex-grow-1 text-center">
                                <input type="radio" id="signCCC" name="signChoice" class="form-check-input">
                                <label for="signCCC" class="form-check-label">CCC</label>
                            </div>
                            <div class="form-check form-check-inline flex-grow-1 text-center">
                                <input type="radio" id="sign" name="signChoice" class="form-check-input">
                                <label for="sign" class="form-check-label">Sign</label>
                            </div>
                            <div class="form-check form-check-inline flex-grow-1 text-center">
                                <input type="radio" id="signCC" name="signChoice" class="form-check-input">
                                <label for="signCC" class="form-check-label">CC</label>
                            </div>
                        </div>

                        <!-- Boutons d'impression -->
                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-primary" onclick="printCertificates()">
                                <i class="fas fa-print me-2"></i> Imprimer les attestations des élèves choisis
                            </button>
                            <button class="btn btn-secondary" onclick="printTemplate()">
                                <i class="fas fa-print me-2"></i> Impression d'un modèle d'attestation
                            </button>
                        </div>

                        <!-- Script JavaScript pour déclencher l'impression -->
                        <script>
                            function printCertificates() {
                                // Récupérer les élèves sélectionnés
                                let selectedEleves = [];
                                document.querySelectorAll('input[name="selected_eleves[]"]:checked').forEach(function(checkbox) {
                                    selectedEleves.push(checkbox.value);
                                });

                                // Vérifier si des élèves sont sélectionnés
                                if (selectedEleves.length === 0) {
                                    alert("Veuillez sélectionner au moins un élève pour l'impression.");
                                    return;
                                }

                                // Récupérer le choix de signature
                                let signChoice = document.querySelector('input[name="signChoice"]:checked').id;

                                // Récupérer le trimestre sélectionné
                                let trimestre = document.getElementById('trimestre').value;

                                // Créer une URL pour l'impression avec les paramètres requis
                                let printUrl = "{{ route('attestation.print') }}" +
                                    "?eleves=" + selectedEleves.join(',') +
                                    "&signChoice=" + signChoice +
                                    "&trimestre=" + trimestre; // Ajouter le trimestre à l'URL

                                // Ouvrir l'impression dans une nouvelle fenêtre
                                window.open(printUrl, '_blank');
                            }



                            function printTemplate() {
                                // Rediriger vers la route de l'impression du modèle d'attestation
                                window.open("{{ route('attestation.template') }}", '_blank');
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div><br><br>

    <!-- Scripts pour ajouter de la dynamique via JavaScript -->


    <script>
        document.querySelectorAll('select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                // Optionnel : afficher un spinner ou message de chargement
                document.getElementById('loadingSpinner').style.display = 'block';
            });
        });
    </script>
@endsection
