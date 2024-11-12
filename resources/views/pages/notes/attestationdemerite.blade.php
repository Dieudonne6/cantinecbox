@extends('layouts.master')

@section('content')
    <div class="container col-11">
        <!-- Titre principal avec espacement -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <h3>Impression des attestations de mérite</h3>
            </div>
        </div>

        <div class="row">
            <!-- Colonne gauche : Table des étudiants -->
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="row">
                            <!-- Sélection du mérite à imprimer -->
                            <div class="col-md-6">
                                <label for="meritToPrint">Mérite à Imprimer</label>
                                <select id="meritToPrint" class="js-example-basic-multiple w-100">
                                    <option value="{{ $params->NoteTH }}">TABLEAU D'HONNEUR</option>
                                    <option value="{{ $params->NoteEncourage }}">ENCOURAGEMENT</option>
                                    <option value="{{ $params->NoteFelicitation }}">FÉLICITATIONS</option>
                                    <option value="incitation">CERTIFICAT D'INCITATION</option>
                                </select>
                            </div>
                            <!-- Sélection du trimestre -->
                            <div class="col-md-6">
                                <label for="trimestre">Trimestre</label>
                                <select id="trimestre" class="js-example-basic-multiple w-100">
                                    <option value="1">1er Trimestre</option>
                                    <option value="2">2ème Trimestre</option>
                                    <option value="3">3ème Trimestre</option>
                                </select>
                            </div>
                        </div>
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

                        <!-- Table pour afficher les étudiants selon les filtres sélectionnés -->
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
                                    @foreach ($notes as $note)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_eleves[]"
                                                    aria-label="Sélectionner élève"
                                                    value="{{ $note->eleve->MATRICULE ?? '' }}">
                                            </td>
                                            <td>{{ $note->eleve->NOM ?? '' }}<br>{{ $note->eleve->PRENOM ?? '' }}</td>
                                            <td>{{ $note->MS ?? '' }}</td>
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
                        <label for="classSelect">Sélectionner une classe</label>
                        <select id="classSelect" class="js-example-basic-multiple w-100 mb-3">
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                            @endforeach
                        </select>                        

                        <h5 class="text-primary mt-4">Paramétrage</h5>
                        <form class="p-3 border rounded">
                            <div class="form-group d-flex align-items-center mb-2">
                                <label for="felicitation" class="me-3" style="width: 150px;">Félicitations</label>
                                <input type="number" id="felicitation" class="form-control ms-auto"
                                    value="{{ $params->NoteFelicitation }}" style="width: 100px;">
                            </div>
                            <div class="form-group d-flex align-items-center mb-2">
                                <label for="encouragement" class="me-3" style="width: 150px;">Encouragements</label>
                                <input type="number" id="encouragement" class="form-control ms-auto"
                                    value="{{ $params->NoteEncourage }}" style="width: 100px;">
                            </div>
                            <div class="form-group d-flex align-items-center mb-2">
                                <label for="honorTable" class="me-3" style="width: 150px;">Tableau d'Honneur</label>
                                <input type="number" id="honorTable" class="form-control ms-auto"
                                    value="{{ $params->NoteTH }}" style="width: 100px;">
                            </div>
                        </form>

                        <h5 class="text-primary mt-4">Mode d'attribution des attestations</h5>
                        <div class="border p-3 rounded">
                            <div class="form-check">
                                <input type="radio" id="allMerits" name="meritMode" class="form-check-input" checked>
                                <label for="allMerits" class="form-check-label">Tous les mérites</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="meritOnly" name="meritMode" class="form-check-input">
                                <label for="meritOnly" class="form-check-label">Plus fort mérite uniquement</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-primary" onclick="printCertificates()">
                                <i class="fas fa-print me-2"></i> Imprimer les attestations
                            </button>
                            <button class="btn btn-secondary" onclick="printTemplate()">
                                <i class="fas fa-print me-2"></i> Impression modèle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-1 mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <label class="text-primary flex-grow-1">Choix du signataire:</label>
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
        </div>
    </div><br><br>

    <!-- Scripts pour ajouter de la dynamique via JavaScript -->
    <script>
        let debounceTimeout;
        function printCertificates() {
            console.log("Impression des certificats...");
        }

        function printTemplate() {
            console.log("Impression du modèle...");
        }
    </script>
@endsection
