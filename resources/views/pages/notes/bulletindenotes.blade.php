@extends('layouts.master')
@section('content')
<!-- Inclusion du CSS de DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- Inclusion de jQuery et du JS de DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edition des Bulletins de notes</h4>
      <div class="row">
        <div class="col-md-3" style="border-right: 1px solid #000000 !important; width: 50% !important;">
          <!-- Contenu de la première colonne -->
          <div class="row" style="background-color: #844fc1; color: #fff; border-radius: 8px; width: 100%;">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paramOption" id="calculMoyennes" checked>
              <label class="form-check-label" for="calculMoyennes" style="margin-left: 11px !important;">
                Calcul des moyennes
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paramOption" id="editionBulletin">
              <label class="form-check-label" for="editionBulletin" style="margin-left: 11px !important;">
                Édition Bulletin
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paramOption" id="listeMerite">
              <label class="form-check-label" for="listeMerite" style="margin-left: 11px !important;">
                Liste par ordre de mérite
              </label>
            </div>
          </div>
          <br>
          <div class="row" id="selectionBlock">
            <h5>Sélection</h5>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paramselection" id="parclasse" checked>
              <label class="form-check-label" for="parclasse">
                Par classe
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paramselection" id="pareleve">
              <label class="form-check-label" for="pareleve">
                Par élève
              </label>
            </div>
          </div>
          
          <div class="row">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="archive" id="archive">
              <label class="form-check-label" for="archive">
                Archive pdf automatique
              </label>
            </div>
          </div>
          <br>
          <div class="row">
            <h5>Signature</h5>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="signature" id="ccc" checked>
              <label class="form-check-label" for="ccc">
                CCC
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="signature" id="directeur">
              <label class="form-check-label" for="directeur">
                Directeur 2
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="signature" id="cc">
              <label class="form-check-label" for="cc">
                CC
              </label>
            </div>
          </div>
          <br>
          <div class="row">
            <h5>Bonus</h5>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="bonus" id="tous" checked>
              <label class="form-check-label" for="tous">
                Tous
              </label>
            </div>
          </div>
          <br>
          <div class="row">
            <h5>Système de bonification</h5>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="bonification" id="integral">
              <label class="form-check-label" for="integral">
                Intégral
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="bonification" id="intervalle" checked>
              <label class="form-check-label" for="intervalle">
                Intervalle
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="bonification" id="none">
              <label class="form-check-label" for="none">
                Aucun
              </label>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="form-group d-flex">
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="range0" name="range0" value="0" step="0.25">
              <p style="margin-top: 10px;">--</p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="range1" name="range1" value="11" step="0.25">
              <p style="margin-top: 10px;">--></p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="value1" name="value1" value="1" step="0.25">
            </div>
            <div class="form-group d-flex">
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="range2" name="range2" value="11" step="0.25">
              <p style="margin-top: 10px;">--</p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="value2" name="value2" value="12" step="0.25">
              <p style="margin-top: 10px;">--></p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="value2" name="value2" value="2" step="0.25">
            </div>
            <div class="form-group d-flex">
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="range3" name="range3" value="12" step="0.25">
              <p style="margin-top: 10px;">--</p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="value3" name="value3" value="14" step="0.25">
              <p style="margin-top: 10px;">--></p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="value3" name="value3" value="3" step="0.25">
            </div>
            <div class="form-group d-flex">
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="range4" name="range4" value="14" step="0.25">
              <p style="margin-top: 10px;">--</p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="value4" name="value4" value="3" step="0.25">
              <p style="margin-top: 10px;">--></p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="value4" name="value4" value="4" step="0.25">
            </div>
            <div class="form-group d-flex">
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="range5" name="range5" value="16" step="0.25">
              <p style="margin-top: 10px;">--</p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="value5" name="value5" value="4" step="0.25">
              <p style="margin-top: 10px;">--></p>
              <input type="number" class="form-control bonification-input" style="width: 28% !important" id="value5" name="value5" value="5" step="0.25">
            </div>
          </div>
          <div class="row">
            <div class="form-group">
              <label for="effectif">Effectif en cours</label>
              <input type="number" class="form-control" style="width: 30% !important" id="effectif" name="effectif" value="0" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6" style="border-right: 1px solid #000000 !important;">
          <!-- Contenu de la deuxième colonne -->
          <div class="form-group">
            <label for="typeenseigne">Choisir un groupe</label>
            <select class="js-example-basic-multiple custom-select-width w-auto" id="typeenseigne" name="typeenseigne">
              <option value="">Sélectionner un type d'enseignement</option>
              @foreach ($typeenseigne as $type)
              <option value="{{ $type->type }}">{{ $type->type }}</option>
              @endforeach
            </select>
          </div>
          <div class="d-flex justify-content-center align-items-center" style="margin-right: 70px !important; gap: 20px;">
            <p class="mb-0" style="white-space: nowrap;">À partir de :</p>
            <input type="text" class="form-control" id="apartirde" name="apartirde" style="width: 430px !important">
          </div>
          <div>
            <br>
            <div class="table-responsive mb-4">
              <table id="tableClasses" class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 10% !important;">
                      <input type="checkbox" name="selected_classes[]" value="all" style="margin-left: 9px !important; margin-top: -15px !important;" onclick="selectAllCheckboxes(this)">
                    </th>
                    <th>Classes</th>
                    <th>Effectif</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($classes as $classe)
                  <tr>
                    <td><input type="checkbox" name="selected_classes[]" value="{{ $classe->CODECLAS }}" style="margin-left: 10px !important; margin-top: -7px !important;"></td>
                    <td>{{ $classe->CODECLAS }}</td>
                    <td>{{ $classe->eleves_count }}</td>
                    @endforeach
                  </tbody>
                </table>
                <table class="table table-striped display" id="myTab" style="width: 70% !important;">
                  <thead>
                    <tr>
                      <th style="width: 10% !important;">
                        <input type="checkbox" name="selected_eleves[]" value="all" style="margin-left: 9px !important; margin-top: -15px !important;" onclick="selectAllCheckboxes1(this)">
                      </th>
                      <th>Nom</th>
                      <th>Prénom</th>
                      <th>Classe</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($eleves as $eleve)
                    <tr>
                      <td><input type="checkbox" name="selected_eleves[]" value="{{ $eleve->MATRICULE }}"></td>
                      <td>{{ $eleve->NOM }}</td>
                      <td>{{ $eleve->PRENOM }}</td>
                      <td>{{ $eleve->CODECLAS }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <br>
            <div class="row">
              <h5>Message affiché au bas du bulletin</h5>
              <div id="editor" class="editor" contenteditable="true">
                Tapez ici pour tester la barre d'outils...
              </div>
              <style>
                .toolbar {
                  display: flex;
                  align-items: center;
                  border: 1px solid #ccc;
                  padding: 5px;
                  background-color: #f1f1f1;
                }
                
                .toolbar select, .toolbar button, .toolbar input[type="color"] {
                  margin-right: 5px;
                }
                
                .icon {
                  font-family: Arial, sans-serif;
                  padding: 5px;
                  cursor: pointer;
                }
                
                .icon:hover {
                  background-color: #ddd;
                }
                
                .editor {
                  margin-top: 10px;
                  border: 1px solid #ccc;
                  padding: 10px;
                  min-height: 100px;
                }
              </style>
            </head>
            <body>
              
              <div class="toolbar">
                <!-- Police de caractères -->
                <select id="fontSelect" onchange="changeFont(this.value)" style="width: 50% !important;">
                  <option>MS Shell Dlg</option>
                  <option>Arial</option>
                  <option>Verdana</option>
                </select>
                
                <!-- Taille de police -->
                <select id="fontSizeSelect" onchange="changeFontSize(this.value)">
                  <option>16px</option>
                  <option>20px</option>
                  <option>24px</option>
                  <option>28px</option>
                </select>
                
                <!-- Boutons de style -->
                <button class="icon" onclick="document.execCommand('bold', false, '')">G</button> <!-- Gras -->
                <button class="icon" onclick="document.execCommand('italic', false, '')">I</button> <!-- Italique -->
                <button class="icon" onclick="document.execCommand('underline', false, '')">S</button> <!-- Souligné -->
                
                <!-- Barré et couleur -->
                <button class="icon" onclick="document.execCommand('strikethrough', false, '')">Ƀ</button> <!-- Barré -->
                <input type="color" onchange="changeColor(this.value)" />
                
                <!-- Alignement -->
                <button class="icon" onclick="document.execCommand('justifyLeft', false, '')">L</button> <!-- Aligné à gauche -->
                <button class="icon" onclick="document.execCommand('justifyCenter', false, '')">C</button> <!-- Centré -->
                <button class="icon" onclick="document.execCommand('justifyRight', false, '')">R</button> <!-- Aligné à droite -->
                <button class="icon" onclick="document.execCommand('justifyFull', false, '')">J</button> <!-- Justifié -->
                
                <!-- Bouton de réinitialisation -->
              </div>
              
              <!-- Zone d'édition -->
              
              <script>
                function changeFont(font) {
                  document.execCommand('fontName', false, font);
                }
                
                function changeFontSize(size) {
                  document.execCommand("styleWithCSS", true);
                  document.execCommand('fontSize', false, '7');  // Set a base size
                  var selectedText = window.getSelection();
                  if (selectedText.rangeCount) {
                    var range = selectedText.getRangeAt(0);
                    var span = document.createElement('span');
                    span.style.fontSize = size ;
                    range.surroundContents(span);
                  }
                }
                
                function changeColor(color) {
                  document.execCommand('foreColor', false, color);
                }
              </script>
              <br>
              <div class="d-flex justify-content-end align-items-center" style="margin-top: 40px !important;">
                <button class="btn btn-primary">Sauvegarder</button>
              </div>
            </div>
            
          </div>
          <div class="col-md-3">
            <!-- Contenu de la troisième colonne -->
            <div class="row">
              <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#optionsEdition">Afficher les options d'édition</button>
            </div>
            <br>
            <div class="row" style="border: 1px solid #844fc1 !important; border-radius: 4px !important; background-color: #844fc1; color: #fff;">
                <div class="form-group d-flex align-items-center" style="margin-top: 10px !important;">
                  <label for="periode">Période</label>
                  <select class="form-select" id="periode" name="periode" style="margin-left: 20px !important;">
                    <option value="">Sélectionner une période</option>
                    <option value="1">1ère période</option>
                    <option value="2">2ème période</option>
                    <option value="3">3ème période</option>
                    <option value="4">4ème période</option>
                    <option value="5">5ème période</option>
                    <option value="6">6ème période</option>
                    <option value="7">7ème période</option>
                    <option value="8">8ème période</option>
                    <option value="9">9ème période</option>
                  </select>
                </div>
              <br>
                <div class="form-group d-flex align-items-center">
                  <label for="conduite">Quel est le n° de CONDUITE ?</label>
                  <input type="number" class="form-control" id="conduite" name="conduite" value="0" style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                  <button class="btn btn-primary" style="margin-left: 5px !important;padding: 0.5rem;background-color: #fff; color: #000;" data-bs-toggle="modal" data-bs-target="#listematiere">Voir</button>
                </div>
                <br>
                <div class="form-group d-flex align-items-center">
                  <label for="eps">Quel est le n° de EPS ?</label>
                  <input type="number" class="form-control" id="eps" name="eps" value="0" style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                  <button class="btn btn-primary" style="margin-left: 5px !important;padding: 0.5rem;background-color: #fff; color: #000;" data-bs-toggle="modal" data-bs-target="#listematiere">Voir</button>
                </div>
                <div class="form-group d-flex align-items-center">
                  <label for="nbabsence">Nb. Absence autorisée</label>
                  <input type="number" class="form-control w-25" id="nbabsence" name="nbabsence" value="0" style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                </div>
                <p style="margin-left: 10px !important;">(Mettre -1 pour débrancher cette option)</p>
              <br>
              <button class="btn btn-secondary" style="">Sauvegarder ces informations</button>
            </div>
            <br>
            <div class="row">
              <button class="btn btn-primary mb-2"  data-bs-toggle="modal" data-bs-target="#configdecisionconseil">Configurer les bulletins</button>
              <br>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#configdecisionjury">Configurer décisions du jury (LMD)</button>
            </div>
            <br>
              <div class="row" style="background-color: #844fc1; border: 1px solid #ffffff; border-radius: 4px; color: #fff;">
                <legend style="font-weight: bold; color: #fff; font-size: 1rem;">Mentions du Conseil</legend>
                <div class="">
                  <div class="form-group d-flex justify-content-between mb-2">
                    <label for="felicitation">Félicitations</label>
                    <input type="number" class="form-control" id="felicitation" name="felicitation" value="0" style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                  </div>
                  <div class="form-group d-flex justify-content-between mb-2">
                    <label for="encouragements">Encouragements</label>
                    <input type="number"  class="form-control" id="encouragements" name="encouragements" value="0" style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                  </div>
                  <div class="form-group d-flex justify-content-between mb-2">
                    <label for="tableaudhonneur">Tableau d'honneur</label>
                    <input type="number" class="form-control" id="tableaudhonneur" name="tableaudhonneur" value="0" style="width: 50px !important; padding: 0; height: 2rem;">
                    
                  </div>
                  <div class="form-group d-flex justify-content-between mb-2">
                    <label for="avertissement">Avertissement/Travail</label>
                    <input type="number"  class="form-control" id="avertissement" name="avertissement" value="0" style="width: 50px !important; padding: 0; height: 2rem;">
                  </div>
                  <div class="form-group d-flex justify-content-between mb-1">
                    <label for="blame">Blâme/Travail</label>
                    <input type="number" class="form-control" id="blame" name="blame" value="0" style="width: 50px !important; padding: 0; height: 2rem;">
                  </div>
                </div>
              </div>
              <div class="row" style="background-color: #844fc1; border: 1px solid #ffffff !important; border-radius: 4px !important; color: #fff;">
                <legend style="font-weight: bold; color: #fff; font-size: 1rem;">Calcul moyenne annuelle</legend>
                <div class="form-group d-flex align-items-center mb-2">
                  <label for="pondTrim1">Pondération Trimestre 1</label>
                  <input type="number" class="form-control" id="pondTrim1" name="pondTrim1" value="0" style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                  <input type="checkbox" class="form-check-input" name="pondTrim1" id="pondTrim1" style="right: 1rem !important;">
                </div>
                <div class="form-group d-flex align-items-center mb-2">
                  <label for="pondTrim2">Pondération Trimestre 2</label>
                  <input type="number" class="form-control" id="pondTrim2" name="pondTrim2" value="0" style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                  <input type="checkbox" class="form-check-input" name="pondTrim2" id="pondTrim2" style="right: 1rem !important;">
                </div>
                <div class="form-group d-flex align-items-center mb-1">
                  <label for="pondTrim3">Pondération Trimestre 3</label>
                  <input type="number" class="form-control" id="pondTrim3" name="pondTrim3" value="0" style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                  <input type="checkbox" class="form-check-input" name="pondTrim3" id="pondTrim3" style="right: 1rem !important;;">
                </div>
              </div>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <div class="align-items-center me-3">
            <button class="btn btn-secondary">Annuler</button>
          </div>
          <div class="align-items-center me-3">
            <button class="btn btn-secondary">Corriger Coefficients</button>
          </div>
          <div class="align-items-center me-3">
            <button class="btn btn-primary" id="calculerClasses">Calculer classes</button>
          </div>
          <div class="align-items-center me-3">
            <button class="btn btn-primary" id="imprimerClasses" disabled>Imprimer classes</button>
          </div>
          <div class="align-items-center">
            <button class="btn btn-primary" id="listeMeriteBtn" disabled>Liste par ordre de mérite</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal pour afficher les élèves -->
  <div class="modal fade" id="listematiere" tabindex="-1" aria-labelledby="listematiereLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="listematiereLabel">Liste des matières</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-striped" style="width: 50% !important; margin-left:80px !important;">
            <thead>
              <tr>
                <th>Code matière</th>
                <th>Libellé matière</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($matieres as $matiere)
              <tr>
                <td>{{ $matiere->CODEMAT }}</td>
                <td>{{ $matiere->LIBELMAT }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modale des options d'édition -->
  <div class="modal fade" id="optionsEdition" tabindex="-1" aria-labelledby="optionsEditionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabelmodif">Liste des options d'édition</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="entete" id="entete">
                <label class="form-check-label edition-label" for="entete">Imprimer l'entête</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="fond" id="fond">
                <label class="form-check-label edition-label" for="fond">Imprimer le fond</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="note_conduite" id="note_conduite">
                <label class="form-check-label edition-label" for="note_conduite">Intéger la note de conduite dans le calcul</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="matricule" id="matricule">
                <label class="form-check-label edition-label" for="matricule">Imprimer le matricule sur le bulletin</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="annuler_matiere" id="annuler_matiere">
                <label class="form-check-label edition-label" for="annuler_matiere">Annuler la matière si aucun devoir</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="rang_matiere" id="rang_matiere">
                <label class="form-check-label edition-label" for="rang_matiere">Imprimer le rang par matière</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="rang_general" id="rang_general">
                <label class="form-check-label edition-label" for="rang_general">Imprimer le rang général</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="masquer_devoir3" id="masquer_devoir3">
                <label class="form-check-label edition-label" for="masquer_devoir3">Masquer la colonne Devoir 3</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="appreciation_prof" id="appreciation_prof">
                <label class="form-check-label edition-label" for="appreciation_prof">Imprimer l'appréciation du professeur</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="photo_par_logo" id="photo_par_logo">
                <label class="form-check-label edition-label" for="photo_par_logo">Remplacer la photo par le logo</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="note_test" id="note_test">
                <label class="form-check-label edition-label" for="note_test">Gérer la note de Test (ou Compo)</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="mention_conseil" id="mention_conseil">
                <label class="form-check-label edition-label" for="mention_conseil">Cocher les mentions du conseil</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="decision_conseil" id="decision_conseil">
                <label class="form-check-label edition-label" for="decision_conseil">Imprimer la décision du conseil</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="recalculer" id="recalculer">
                <label class="form-check-label edition-label" for="recalculer">Recalculer avant impression</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="appreciation_directeur" id="appreciation_directeur">
                <label class="form-check-label edition-label" for="appreciation_directeur">Imprimer l'appréciation du directeur</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-primary">Confirmer</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modale de configuration des bulletins -->
  <div class="modal fade" id="configdecisionconseil" tabindex="-1" aria-labelledby="configdecisionconseilLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabelmodif">Configuration de la décision du Conseil des Profs</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex" style="margin-left: 50px !important;">
            <label for="config_promo" style="margin-top: 15px !important;">Promotion à configurer</label>
            <select class="form-select w-50" id="config_promo" name="config_promo" style="margin-left: 20px !important;">
              <option value="">Sélectionner une promotion</option>
              @foreach ($promotions as $promotion)
              <option value="{{ $promotion->CODEPROMO }}">{{ $promotion->LIBELPROMO }}</option>
              @endforeach
            </select>
            <button class="btn btn-secondary" style="margin-left: 20px !important;">Sauver</button>
          </div>
          <br>
          <div class="row">
            <div style="background-color: #35d966; border-radius: 5px !important; width: 82% !important; margin-left: 5px !important;">
              <h4>Statut - Non redoublant</h4>
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuenrd1" name="valuenrd1" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuenrd2" name="valuenrd2" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionnrd1" name="mentionnrd1" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuenrd3" name="valuenrd3" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuenrd4" name="valuenrd4" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionnrd2" name="mentionnrd2" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuenrd5" name="valuenrd5" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuenrd6" name="valuenrd6" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionnrd3" name="mentionnrd3" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuenrd7" name="valuenrd7" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuenrd8" name="valuenrd8" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionnrd4" name="mentionnrd4" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuenrd9" name="valuenrd9" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuenrd10" name="valuenrd10" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionnrd5" name="mentionnrd5" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuenrd11" name="valuenrd11" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuenrd12" name="valuenrd12" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionnrd6" name="mentionnrd6" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
          </div>
          <br>
          <div class="row">
            <div style="background-color: #35d966; border-radius: 5px !important; width: 82% !important; margin-left: 5px !important;">
              <h4>Statut - Redoublant</h4>
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuered1" name="valuered1" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuered2" name="valuered2" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionred1" name="mentionred1" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuered3" name="valuered3" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuered4" name="valuered4" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionred2" name="mentionred2" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuered5" name="valuered5" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuered6" name="valuered6" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionred3" name="mentionred3" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuered7" name="valuered7" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuered8" name="valuered8" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionred4" name="mentionred4" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuered9" name="valuered9" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuered10" name="valuered10" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionred5" name="mentionred5" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
            <div class="d-flex" style="margin-top: 10px !important;">
              <input type="number" class="form-control" id="valuered11" name="valuered11" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
              <input type="number" class="form-control" id="valuered12" name="valuered12" value="0" style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
              <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
              <input type="text" class="form-control" id="mentionred6" name="mentionred6" style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
            </div>
          </div>
          <br>
          <div class="modal-footer d-flex justify-content-end">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal de configuration de la décision du Jury -->
  <div class="modal fade" id="configdecisionjury" tabindex="-1" aria-labelledby="configdecisionjuryLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Configuration de la décision du Conseil des Profs</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Choix d'application de la décision:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="decisionType" id="decisionUE" checked>
              <label class="form-check-label" for="decisionUE">Décision sur nombre de UE validés</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="decisionType" id="decisionCredits">
              <label class="form-check-label" for="decisionCredits">Décision sur nombre de crédits acquis</label>
            </div>
          </div>
          <div class="d-flex">
            <label for="anneeDecision" style="margin-top: 15px !important;">Quelle année:</label>
            <select class="form-select w-50" id="anneeDecision" style="margin-left: 20px !important;">
              <option value="1">1ère année</option>
              <option value="2">2ème année</option>
              <option value="3">3ème année</option>
              <option value="4">4ème année</option>
            </select>
          </div>
          <br>
          <div class="d-flex">
            <h5 for="intervalle" style="background-color: #bdbdbd; border-radius: 5px !important; width: 25% !important; margin-left: 100px !important; text-align: center !important;">Intervalle</h5>
            <h5 for="decision" style="background-color: #bdbdbd; border-radius: 5px !important; width: 50% !important; margin-left: 100px !important; text-align: center !important;">Décision</h5>
          </div>
          <br>
          <div class="d-flex">
            <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00" style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
            <input type="number" class="form-control" id="percentage2" name="percentage2" value="0.00" style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
            <input type="text" class="form-control" id="decision1" name="decision1" style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
          </div>
          <br>
          <div class="d-flex">
            <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00" style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
            <input type="number" class="form-control" id="percentage2" name="percentage2" value="0.00" style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
            <input type="text" class="form-control" id="decision1" name="decision1" style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
          </div>
          <br>
          <div class="d-flex">
            <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00" style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
            <input type="number" class="form-control" id="percentage2" name="percentage2" value="0.00" style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
            <input type="text" class="form-control" id="decision1" name="decision1" style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
          </div>
          <br>
          <div class="d-flex">
            <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00" style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
            <input type="number" class="form-control" id="percentage2" name="percentage2" value="0.00" style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
            <input type="text" class="form-control" id="decision1" name="decision1" style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
          </div>
          <br>
          <div class="d-flex">
            <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00" style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
            <input type="number" class="form-control" id="percentage2" name="percentage2" value="0.00" style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
            <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
            <input type="text" class="form-control" id="decision1" name="decision1" style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
          </div>
          <br>            
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            <button type="button" class="btn btn-primary">Valider</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    function selectAllCheckboxes(source) {
      checkboxes = document.getElementsByName('selected_classes[]');
      for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
      }
    }
    function selectAllCheckboxes1(source) {
      checkboxes = document.getElementsByName('selected_eleves[]');
      for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
      }
    }
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const updateUI = () => {
        const selectedOption = document.querySelector('input[name="paramOption"]:checked').id;
        const selectionBlock = document.getElementById('selectionBlock');
        const calculerClassesBtn = document.getElementById('calculerClasses');
        const imprimerClassesBtn = document.getElementById('imprimerClasses');
        const listeMeriteBtn = document.getElementById('listeMeriteBtn');
        const selectionInputs = selectionBlock.querySelectorAll('input'); // Sélectionner tous les inputs dans le bloc
        
        // Réinitialiser tous les contrôles
        selectionInputs.forEach(input => input.disabled = false); // Activer tous les inputs
        calculerClassesBtn.disabled = false;
        imprimerClassesBtn.disabled = false;
        listeMeriteBtn.disabled = false;
        
        // Appliquer la logique de désactivation en fonction de l'option sélectionnée
        if (selectedOption === 'calculMoyennes') {
          selectionInputs.forEach(input => input.disabled = true); // Désactiver les inputs dans le bloc de sélection
          listeMeriteBtn.disabled = true;
          imprimerClassesBtn.disabled = true;
        } else if (selectedOption === 'editionBulletin') {
          selectionInputs.forEach(input => input.disabled = false);
          listeMeriteBtn.disabled = true;
          calculerClassesBtn.disabled = true;
        } else if (selectedOption === 'listeMerite') {
          selectionInputs.forEach(input => input.disabled = true);
          calculerClassesBtn.disabled = true;
          imprimerClassesBtn.disabled = true;
        }
      };
      
      // Écouter les changements sur les boutons radio
      document.querySelectorAll('input[name="paramOption"]').forEach(radio => {
        radio.addEventListener('change', updateUI);
      });
      
      // Initialiser l'UI au chargement de la page
      updateUI();
    });
    document.addEventListener('DOMContentLoaded', function () {
      const bonificationInputs = document.querySelectorAll('.bonification-input'); // Sélectionner tous les inputs de bonification
      
      const updateInputState = () => {
        const isIntervalSelected = document.getElementById('intervalle').checked;
        bonificationInputs.forEach(input => {
          input.disabled = !isIntervalSelected; // Activer ou désactiver les inputs basé sur la sélection de 'Intervalle'
        });
      };
      
      // Écouter les changements sur les boutons radio de bonification
      document.querySelectorAll('input[name="bonification"]').forEach(radio => {
        radio.addEventListener('change', updateInputState);
      });
      
      // Initialiser l'état des inputs au chargement de la page
      updateInputState();
    });
    document.addEventListener('DOMContentLoaded', function () {
      const tableClasses = document.getElementById('tableClasses');
      const tableEleves = document.getElementById('tableEleves');
      const radioParClasse = document.getElementById('parclasse');
      const radioParEleve = document.getElementById('pareleve');
      
      const toggleTables = () => {
        if (radioParClasse.checked) {
          tableClasses.style.display = '';
          tableEleves.style.display = 'none';
        } else if (radioParEleve.checked) {
          tableClasses.style.display = 'none';
          tableEleves.style.display = '';
        }
      };
      
      // Écouter les changements sur les boutons radio
      document.querySelectorAll('input[name="paramselection"]').forEach(radio => {
        radio.addEventListener('change', toggleTables);
      });
      
      // Initialiser l'état des tables au chargement de la page
      toggleTables();
    });
    document.addEventListener('DOMContentLoaded', function () {
      const tableClasses = document.getElementById('tableClasses');
      const tableEleves = document.getElementById('myTab');
      const radioParClasse = document.getElementById('parclasse');
      const radioParEleve = document.getElementById('pareleve');
      
      const toggleTables = () => {
        if (radioParClasse.checked) {
          tableClasses.style.display = '';
          tableEleves.style.display = 'none';
        } else if (radioParEleve.checked) {
          tableClasses.style.display = 'none';
          tableEleves.style.display = '';
        }
      };
      
      // Écouter les changements sur les boutons radio
      document.querySelectorAll('input[name="paramselection"]').forEach(radio => {
        radio.addEventListener('change', toggleTables);
      });
      
      // Initialiser l'état des tables au chargement de la page
      toggleTables();
    });
    $(document).ready(function() {
      // Supposons que vous avez un bouton ou un radio bouton pour afficher le tableau
      $('input[name="paramselection"]').on('change', function() {
        // Vérifiez si le tableau doit être affiché
        if ($('#pareleve').is(':checked')) {
          $('#myTab').show(); // Assurez-vous que le tableau est visible
          // Initialisez DataTables
          $('#myTab').DataTable({
            destroy: true
          });
        } else {
          $('#myTab').hide();
        }
      });
    });
    
  </script>
  <style>
    .form-check {
      margin: 0 45% 0 0 !important;
    }
    .footer {
      display: none;
    }
    .edition-label {
      margin-left: 10px !important;
    }
    .table-container {
      width: 100%;
      overflow-x: auto;
      margin: 20px auto;
    }
  </style>
  @endsection