@extends('layouts.master')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div>
                <style>
                    .btn-arrow {
                        position: absolute;
                        top: 0;
                        left: 0;
                        background-color: transparent !important;
                        border: 1px solid transparent !important;
                        text-transform: uppercase !important;
                        font-weight: bold !important;
                        cursor: pointer !important;
                        font-size: 17px !important;
                        color: #b51818 !important;
                    }

                    .btn-arrow:hover {
                        color: #b700ff !important;
                    }
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br><br>
            </div>
            <div class="card-header">
                <h4 class="mb-0">Liste des méritants</h4>
            </div>
            <div class="card-body">
                <!-- Sélection du filtre -->
                <div class="row justify-content-end">
                    <div class="col-md-8 p-3 border rounded bg-light d-flex align-items-center">
                        <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input" type="radio" name="filtre" id="filtreClasse" value="classe"
                                checked>
                            <label class="form-check-label" for="filtreClasse">Par classe</label>
                        </div>
                        <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input" type="radio" name="filtre" id="filtrePromotion"
                                value="promotion">
                            <label class="form-check-label" for="filtrePromotion">Par Promotion</label>
                        </div>
                        <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input" type="radio" name="filtre" id="filtreCycle" value="cycle">
                            <label class="form-check-label" for="filtreCycle">Par cycle</label>
                        </div>
                        <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input" type="radio" name="filtre" id="filtreTout" value="tout">
                            <label class="form-check-label" for="filtreTout">Tout l'établissement</label>
                        </div>
                    </div>
                </div>

                <!-- Contenu de la recherche -->
                <div class="row">
                    <!-- Colonne de gauche : filtres -->
                    <div class="col-md-3 border p-2">
                        <!-- Liste des classes -->
                        <div id="listeClasses" class="form-group" style="display: none;">
                            <div class="table-responsive" style="max-height: 200px; margin: auto; overflow-y: auto;">
                                <table class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: white;">
                                        <tr>
                                            <th><input type="checkbox" id="selectAllClasses"></th>
                                            <th>Classes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classes as $classe)
                                            <tr>
                                                <td><input type="checkbox" name="classes[]" value="{{ $classe->CODECLAS }}">
                                                </td>
                                                <td>{{ $classe->CODECLAS }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Liste des promotions -->
                        <div id="listePromotions" class="form-group" style="display: none;">
                            <div class="table-responsive" style="max-height: 200px; margin: auto; overflow-y: auto;">
                                <table class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: white;">
                                        <tr>
                                            <th><input type="checkbox" id="selectAllPromotions"></th>
                                            <th>Code</th>
                                            <th>Promotion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($promotions as $promotion)
                                            <tr>
                                                <td><input type="checkbox" name="promotions[]"
                                                        value="{{ $promotion->CODEPROMO }}"></td>
                                                <td>{{ $promotion->CODEPROMO }}</td>
                                                <td>{{ $promotion->LIBELPROMO }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Liste des cycles -->
                        <div id="listeCycles" class="form-group" style="display: none;">
                            <div class="d-flex align-items-center">
                                <label for="cycle" class="me-2"><strong>Cycle</strong></label>
                                <select class="form-control form-control-sm w-auto" id="cycle">
                                    <option value="1">Cycle 1</option>
                                    <option value="2">Cycle 2</option>
                                </select>
                            </div>
                        </div>

                        <!-- Boutons et options complémentaires -->
                        <div class="container">
                            <div class="row g-1">
                                <div class="col-md-6 mb-1">
                                    <button class="btn btn-primary btn-sm" id="searchButton">Rechercher</button>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <button class="btn btn-secondary btn-sm"
                                        onclick="printFilteredTable()">Imprimer</button>
                                </div>
                            </div>
                        </div>

                        <!-- Nombre de méritants -->
                        <div class="form-group mt-3">
                            <div class="d-flex align-items-center">
                                <label for="nombre" class="me-2"><strong>Choisir les</strong></label>
                                <input type="number" class="form-control form-control-sm" id="nombre" min="1"
                                    step="1" value="10"> Premiers
                            </div>
                        </div>

                        <!-- Période / Semestre -->
                        <div class="form-group">
                            <div class="d-flex align-items-center">
                                <label for="periode" class="me-2"><strong>Période / Semestre</strong></label>
                                <select class="form-control form-control-sm w-auto" id="periode">
                                    <option value="1">Période 1</option>
                                    <option value="2">Période 2</option>
                                    <option value="3">Période 3</option>
                                    <option value="4">Période 4</option>
                                    <option value="5">Période 5</option>
                                    <option value="6">Période 6</option>
                                    <option value="7">Période 7</option>
                                    <option value="8">Période 8</option>
                                    <option value="9">Période 9</option>
                                    <option value="AN">Annuel</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtre sur le sexe -->
                        <div class="form-group">
                            <label for="sexe"><strong>Priorité</strong></label>
                            <select class="form-control form-control-sm" id="sexe">
                                <option value="">Aucune</option>
                                <option value="2">Filles</option>
                                <option value="1">Garçons</option>
                            </select>
                        </div>

                        <!-- Filtre sur la conduite -->
                        <div class="form-group">
                            <label for="conduite_min"><strong>Exclure conduite inférieure à</strong></label>
                            <input type="number" step="0.01" class="form-control form-control-sm" id="conduite_min"
                                placeholder="0.00">
                        </div>

                        <!-- Filtre sur la matière -->
                        <div class="form-group">
                            <label for="matiere" class="mr-2"><strong>Matière</strong></label>
                            <select class="form-control form-control-sm w-auto" id="matiere">
                                <option value="moyenne_generale">SUR MOYENNE GENERALE</option>
                                @foreach ($matieres as $matiere)
                                    <option value="{{ $matiere->CODEMAT }}">{{ $matiere->LIBELMAT }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Colonne de droite : Tableau des résultats -->
                    <div class="col-md-9 border p-2">
                        <div class="mb-2">
                            <input type="text" class="form-control" style="width: 100px; display: inline-block;"
                                id="foundCount" value="0">
                            Trouvés
                            <input type="text" class="form-control" style="width: 200px; display: inline-block;"
                                id="additionalInfo">
                        </div>
                        <!-- Tableau principal existant -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped" id="elevesTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Ordre</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th id="headerNote">Moyenne</th>
                                        <th>Sexe</th>
                                        <th>Conduite</th>
                                        <th>Classe</th>
                                    </tr>
                                </thead>
                                <tbody id="elevesTableBody">
                                    <!-- Les résultats seront insérés ici via AJAX -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Tableau secondaire existant -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped" id="notesTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Ordre</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th id="Note">Moyenne</th>
                                        <th>Sexe</th>
                                        <th>Conduite</th>
                                        <th>Classe</th>
                                        <th>Matière</th>
                                        <th>Semestre</th>
                                    </tr>
                                </thead>
                                <tbody id="notesTableBody">
                                    @foreach ($eleves as $eleve)
                                        @foreach ($notes->where('MATRICULE', $eleve->MATRICULE) as $note)
                                            <tr>
                                                <td>{{ $loop->parent->iteration }}</td> {{-- Utilisation de $loop->parent pour conserver l'ordre des élèves --}}
                                                <td>{{ $eleve->NOM }}</td>
                                                <td>{{ $eleve->PRENOM }}</td>
                                                <td>{{ $note->MS1 }}</td>
                                                <td>{{ $eleve->SEXE }}</td>
                                                <td>{{ $note->NoteConduite }}</td>
                                                <td>{{ $eleve->CODECLAS }}</td>
                                                <td>{{ $note->CODEMAT }}</td>
                                                <td>{{ $note->SEMESTRE }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- Options complémentaires -->
            </div>
        </div>
    </div>
    <br><br>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Script de gestion dynamique -->
    <script>
        // Affichage dynamique des sections de filtres
        function afficherSection() {
            document.getElementById('listeClasses').style.display = 'none';
            document.getElementById('listePromotions').style.display = 'none';
            document.getElementById('listeCycles').style.display = 'none';

            const filtreSelectionne = document.querySelector('input[name="filtre"]:checked').value;
            switch (filtreSelectionne) {
                case 'classe':
                    document.getElementById('listeClasses').style.display = 'block';
                    break;
                case 'promotion':
                    document.getElementById('listePromotions').style.display = 'block';
                    break;
                case 'cycle':
                    document.getElementById('listeCycles').style.display = 'block';
                    break;
                case 'tout':
                    // Aucun filtre supplémentaire à afficher
                    break;
            }
        }

        // Ajout des écouteurs sur les boutons radio
        document.querySelectorAll('input[name="filtre"]').forEach(radio => {
            radio.addEventListener('change', afficherSection);
        });
        document.addEventListener('DOMContentLoaded', afficherSection);

        // Gestion du "Tout sélectionner" pour les classes et promotions
        document.getElementById('selectAllClasses').addEventListener("change", function() {
            document.querySelectorAll('input[name="classes[]"]').forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });
        document.getElementById('selectAllPromotions').addEventListener("change", function() {
            document.querySelectorAll('input[name="promotions[]"]').forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });

        // Recherche AJAX au clic sur "Rechercher"
        document.getElementById('searchButton').addEventListener('click', function(e) {
            e.preventDefault();
            // Réinitialiser les deux tableaux
            document.getElementById('elevesTableBody').innerHTML = '';
            document.getElementById('notesTableBody').innerHTML = '';

            // Récupération des filtres
            let filtre = document.querySelector('input[name="filtre"]:checked').value;
            let data = {
                filtre: filtre
            };

            if (filtre === 'classe') {
                let classes = [];
                document.querySelectorAll('input[name="classes[]"]:checked').forEach(el => {
                    classes.push(el.value);
                });
                data.classes = classes;
            } else if (filtre === 'promotion') {
                let promotions = [];
                document.querySelectorAll('input[name="promotions[]"]:checked').forEach(el => {
                    promotions.push(el.value);
                });
                data.promotions = promotions;
            } else if (filtre === 'cycle') {
                data.cycle = document.getElementById('cycle').value;
            }

            // Récupération des autres critères
            data.nombre = document.getElementById('nombre').value;
            data.periode = document.getElementById('periode').value;
            data.sexe = document.getElementById('sexe').value;
            data.conduite_min = document.getElementById('conduite_min').value;
            data.matiere = document.getElementById('matiere').value;

            fetch('/search-meritants', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(resultats => {
                    // Si une matière spécifique est sélectionnée, utiliser le tableau secondaire
                    if (data.matiere && data.matiere !== 'moyenne_generale') {
                        const tableBody = document.getElementById('notesTableBody');
                        resultats.forEach((item, index) => {
                            // Affichage de la note depuis la colonne MS1 de la table notes
                            let noteDisplay = item.MS1 ? item.MS1 : '-';
                            // Utiliser la relation 'eleve' pour afficher les infos de l'élève
                            let nom = item.eleve ? item.eleve.NOM : '-';
                            let prenom = item.eleve ? item.eleve.PRENOM : '-';
                            let sexe = (item.eleve && item.eleve.SEXE == 1) ? 'M' : 'F';
                            let conduite = item.eleve && item.eleve.conduite ? item.eleve.conduite :
                            '-';
                            let codeClas = item.eleve ? item.eleve.CODECLAS : '-';

                            let tr = document.createElement('tr');
                            tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${nom}</td>
                    <td>${prenom}</td>
                    <td>${noteDisplay}</td>
                    <td>${sexe}</td>
                    <td>${conduite}</td>
                    <td>${codeClas}</td>
                    <td>${item.CODEMAT ? item.CODEMAT : '-'}</td>
                    <td>${item.SEMESTRE ? item.SEMESTRE : '-'}</td>
                `;
                            tableBody.appendChild(tr);
                        });
                    } else {
                        // Cas de la moyenne générale, afficher dans le tableau principal
                        const tableBody = document.getElementById('elevesTableBody');
                        const periode = data.periode;
                        const noteKey = (periode === 'AN') ? 'MAN' : 'MS' + periode;
                        document.getElementById('headerNote').innerText = 'Moyenne';

                        resultats.forEach((eleve, index) => {
                            let noteDisplay = eleve[noteKey] ? eleve[noteKey] : '-';
                            let tr = document.createElement('tr');
                            tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${eleve.NOM}</td>
                    <td>${eleve.PRENOM}</td>
                    <td>${noteDisplay}</td>
                    <td>${eleve.SEXE == 1 ? 'M' : 'F'}</td>
                    <td>${eleve.conduite ? eleve.conduite : '-'}</td>
                    <td>${eleve.CODECLAS}</td>
                `;
                            tableBody.appendChild(tr);
                        });
                    }
                    // Mise à jour du compteur de résultats
                    document.getElementById('foundCount').value = resultats.length;
                })
                .catch(error => console.error('Erreur : ', error));
        });
    </script>
    {{-- Script pour impression --}}
    <script>
        function printFilteredTable() {
            // Insertion sécurisée de la variable PHP dans JavaScript
            const ecole = @json($params->NOMETAB);
            const dateImpression = new Date().toLocaleDateString('fr-FR');
            const titre = "Liste des Méritants";

            // Récupération de la valeur de l'input "nombre", avec valeur par défaut 10 si introuvable ou vide
            const nombreInput = document.getElementById('nombre');
            const nombre = nombreInput ? nombreInput.value : 10;
            const topMeritants = `${nombre} premiers`;

            // Récupération de la valeur du filtre sélectionné, ou 'tout' par défaut
            const filtreInput = document.querySelector('input[name="filtre"]:checked');
            const filtreSelectionne = filtreInput ? filtreInput.value : 'tout';
            let filtreText = "";
            switch (filtreSelectionne) {
                case 'classe':
                    filtreText = "Par classe";
                    break;
                case 'promotion':
                    filtreText = "Par promotion";
                    break;
                case 'cycle':
                    filtreText = "Par cycle";
                    break;
                default:
                    filtreText = "Tout l'établissement";
            }

            // Récupération de la matière et de son libellé
            const matiereSelect = document.getElementById('matiere');
            let matiereValue = matiereSelect ? matiereSelect.value : 'moyenne_generale';
            let matiereLabel = (matiereValue === 'moyenne_generale') ? "Moyenne générale" :
                (matiereSelect.querySelector(`option[value="${matiereValue}"]`)?.textContent || "Moyenne générale");

            // Récupération de la priorité (sexe)
            const sexeSelect = document.getElementById('sexe');
            const sexeValue = sexeSelect ? sexeSelect.value : "";
            const sexeLabel = sexeValue === "1" ? "Garçons" : sexeValue === "2" ? "Filles" : "Aucune";

            // Récupération de la valeur minimale de conduite
            const conduiteMinInput = document.getElementById('conduite_min');
            const conduiteMin = conduiteMinInput ? conduiteMinInput.value : "";
            const exclusionText = conduiteMin ? `Exclure conduite < ${conduiteMin}` : "Aucune exclusion";

            // Récupération du contenu HTML du tableau
            const elevesTable = document.getElementById('elevesTable');
            if (!elevesTable) {
                console.error("Table 'elevesTable' non trouvée.");
                return;
            }
            const tableHTML = elevesTable.outerHTML;

            // Ouverture d'une nouvelle fenêtre pour l'impression
            const newWin = window.open('', '_blank');
            if (!newWin) {
                alert("Impossible d'ouvrir une nouvelle fenêtre. Veuillez vérifier vos bloqueurs de pop-up.");
                return;
            }
            newWin.document.open();

            newWin.document.write(`
                <html>
                <head>
                    <title>Impression des Méritants</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                    <style>
                        @media print {
                            @page {
                                size: A4 portrait;
                                margin: 20mm;
                            }
                            body {
                                font-family: 'Arial', sans-serif;
                                font-size: 14px;
                                color: #333;
                                margin: 0;
                                padding: 0;
                            }
                            .header {
                                text-align: center;
                                margin-bottom: 20px;
                                padding-bottom: 10px;
                                border-bottom: 2px solid #000;
                            }
                            .header h1 {
                                font-size: 20px;
                                font-weight: bold;
                                margin: 0;
                            }
                            .header .sub-info {
                                font-size: 14px;
                                margin-top: 5px;
                            }
                            .info-section {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 10px;
                            }
                            .info-section div {
                                flex: 1;
                                font-size: 12px;
                                padding: 5px;
                                border-bottom: 1px dashed #ccc;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 10px;
                            }
                            th, td {
                                border: 1px solid #ddd;
                                padding: 8px;
                                text-align: left;
                            }
                            th {
                                background-color: #f8f9fa;
                                font-weight: bold;
                                text-transform: uppercase;
                            }
                            tr:nth-child(even) {
                                background-color: #f2f2f2;
                            }
                            tr:hover {
                                background-color: #ddd;
                            }
                            .footer {
                                margin-top: 30px;
                                text-align: center;
                                font-size: 12px;
                                color: #777;
                            }
                            .page-number::after {
                                content: counter(page);
                            }
                        }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    <!-- En-tête -->
                    <div class="header">
                        <h1>${ecole}</h1>
                        <div class="sub-info">Date : ${dateImpression}</div>
                    </div>
    
                    <!-- Informations générales -->
                    <div class="info-section">
                        <div><strong>${titre}</strong></div>
                        <div><strong>${topMeritants}</strong></div>
                    </div>
    
                    <div class="info-section">
                        <div>Filtre : ${filtreText}</div>
                        <div>Matière : ${matiereLabel}</div>
                    </div>
    
                    <div class="info-section">
                        <div>Priorité : ${sexeLabel}</div>
                        <div>${exclusionText}</div>
                    </div>
    
                    <!-- Tableau des élèves -->
                    ${tableHTML}
    
                    <!-- Pied de page -->
                    <div class="footer">
                        <span>Imprimé le ${dateImpression}</span> - Page <span class="page-number"></span>
                    </div>
                </body>
                </html>
            `);

            newWin.document.close();
        }
    </script>
@endsection
