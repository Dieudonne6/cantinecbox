@extends('layouts.master')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <!-- Carte principale -->
        <div class="card">
            <!-- En-tête de la carte -->
            <div class="card-header">
                <h4 class="mb-0">Impression des plus méritants</h4>
            </div>

            <!-- Corps de la carte -->
            <div class="card-body">
                <!-- Ligne pour le choix du filtre (par classe, promotion, cycle, tout) -->
                <div class="row justify-content-end">
                    <div class="col-md-8 p-3 border rounded bg-light d-flex align-items-center">
                        <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input" type="radio" name="filtre" id="filtreClasse" value="classe" checked>
                            <label class="form-check-label" for="filtreClasse">Par classe</label>
                        </div>
                        <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input" type="radio" name="filtre" id="filtrePromotion" value="promotion">
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

                <!-- Conteneur principal : colonne de gauche + tableau à droite -->
                <div class="row">
                    <!-- Colonne de gauche -->
                    <div class="col-md-3 border p-2">

                        <!-- Liste des classes -->
                        <div id="listeClasses" class="form-group" style="display: none;">
                            <div class="table-responsive" style="max-height: 200px; margin: auto; overflow-y: auto; position: relative;">
                                <table class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: white; z-index: 1000;">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>Classes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classes as $classe)
                                        <tr>
                                            <td><input type="checkbox" name="classes[]" value="{{ $classe['CODECLAS'] }}"></td>
                                            <td>{{ $classe['CODECLAS'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Liste des promotions -->
                        <div id="listePromotions" class="form-group" style="display: none;">
                            <div class="table-responsive" style="max-height: 200px; margin: auto; overflow-y: auto; position: relative;">
                                <table class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: white; z-index: 1000;">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>Code</th>
                                            <th>Promotion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($promotions as $promotion)
                                        <tr>
                                            <td><input type="checkbox" name="promotions[]" value="{{ $promotion['CODEPROMO'] }}"></td>
                                            <td>{{ $promotion['CODEPROMO'] }}</td>
                                            <td>{{ $promotion['LIBELPROMO'] }}</td>
                                        @endforeach
                                        <!-- Ajoutez d'autres lignes si nécessaire -->
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

                        <!-- Groupe de boutons (Rechercher, Imprimer, etc.) -->
                        <div class="container">
                            <div class="row g-1">
                                <div class="col-md-6 mb-1">
                                    <button class="btn btn-primary btn-sm w-150">Rechercher</button>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <button class="btn btn-secondary btn-sm w-100">Imprimer</button>
                                </div>
                            </div>
                        </div>

                        <!-- Choix du nombre de méritants (Top X) -->
                        <div class="form-group mt-3">
                            <div class="d-flex align-items-center">
                                <label for="nombre" class="me-2"><strong>Choisir les</strong></label>
                                <input type="number" class="form-control form-control-sm" id="nombre" min="1"
                                    step="1" placeholder="10"> Premiers
                            </div>
                        </div>

                        <!-- Période -->
                        <div class="form-group">
                            <div class="d-flex align-items-center">
                                <label for="periode" class="me-2"><strong>Période</strong></label>
                                <select class="form-control form-control-sm w-auto" id="periode">
                                    <option>Période 1</option>
                                    <option>Période 2</option>
                                    <option>Période 3</option>
                                    <option>Période 4</option>
                                    <option>Période 5</option>
                                    <option>Période 6</option>
                                    <option>Période 7</option>
                                    <option>Période 8</option>
                                    <option>Période 9</option>
                                    <option>Annuel</option>
                                </select>
                            </div>

                        </div>

                        <!-- Pointé (Sexe) -->
                        <div class="form-group">
                            <label for="sexe"><strong>Priorité</strong></label>
                            <select class="form-control form-control-sm" id="sexe">
                                <option>Aucune</option>
                                <option>Filles</option>
                                <option>Garçons</option>
                            </select>
                        </div>

                        <!-- Moyenne min (ou Terre Période) -->
                        <div class="form-group">
                            <label for="moyenne_min"><strong>Exclure conduite inférieure à</strong></label>
                            <input type="number" step="0.01" class="form-control form-control-sm" id="moyenne_min"
                                placeholder="0.00">
                        </div>
                    </div>

                    <!-- Colonne de droite (tableau) -->
                    <div class="col-md-9 border p-2">
                        <!-- Indicateur du nombre de résultats trouvés -->
                        <div class="mb-2">
                            <input type="text" class="form-control" style="width: 100px; display: inline-block;"
                                value="0">
                            Trouvés
                            <input type="text" class="form-control" style="width: 200px; display: inline-block;">
                        </div>


                        <!-- Tableau des résultats -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Ordre</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Moyenne</th>
                                        <th>Sexe</th>
                                        <th>Conduite</th>
                                        <th>Classe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleves as $eleve)
                                    <tr>
                                        <td><input type="checkbox" name="eleves[]" value="{{ $eleve->MATRICULE }}"></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $eleve->NOM }}</td>
                                        <td>{{ $eleve->PRENOM }}</td>
                                        <td>{{ $eleve->moyenne ?? '-' }}</td>
                                        <td>{{ $eleve->SEXE }}</td>
                                        <td>{{ $eleve->conduite ?? '-' }}</td>
                                        <td>{{ $eleve->CODECLAS }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- Fin row -->
                <div class="row">
                    <!-- Matière -->
                    <div class="form-group d-flex align-items-center col-md-6 mb-1">
                        <label for="matiere" class="mr-2"><strong>Matière</strong></label>
                        <select class="form-control form-control-sm w-auto" id="matiere">
                            <option value="moyenne_generale">SUR MOYENNE GENERALE</option>
                            @foreach ($matieres as $matiere)
                            <option value="{{ $matiere ->CODEMAT }}">{{ $matiere ->LIBELMAT }}</option>
                            @endforeach
                            <!-- Ajoutez d'autres matières si nécessaire -->
                        </select>
                    </div>
                    <div class="col-md-6 mb-1">
                        <button class="btn btn-danger btn-sm">Supprimer les non sélectionnés</button>
                    </div>
                </div>
            </div><!-- Fin card-body -->
        </div><!-- Fin card -->
    </div><!-- Fin container -->
    <br>
    <br>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Script de gestion de l'affichage en fonction du filtre choisi -->
   <!-- Script de gestion de l'affichage -->
   <script>
    // Fonction pour afficher la section correspondant à l'option sélectionnée
    function afficherSection() {
      // Masquer toutes les sections
      document.getElementById('listeClasses').style.display = 'none';
      document.getElementById('listePromotions').style.display = 'none';
      document.getElementById('listeCycles').style.display = 'none';

      // Récupérer la valeur du filtre sélectionné
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
          // Aucune section n'est affichée
          break;
      }
    }

    // Ajout des écouteurs sur les boutons radio
    const radios = document.querySelectorAll('input[name="filtre"]');
    radios.forEach(radio => {
      radio.addEventListener('change', afficherSection);
    });

    // Affichage initial lors du chargement de la page (le filtre "Par classe" est déjà sélectionné)
    document.addEventListener('DOMContentLoaded', afficherSection);

    // Gérer le "Tout sélectionner"
    selectAll.addEventListener("change", function () {
        document.querySelectorAll('input[name="classes[]"]').forEach((checkbox) => {
            checkbox.checked = selectAll.checked;
        });
    })
  </script>
@endsection
