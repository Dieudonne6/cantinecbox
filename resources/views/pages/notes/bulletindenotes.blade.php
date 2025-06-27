@extends('layouts.master')
@section('content')
    <!-- Inclusion du CSS de DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Inclusion de jQuery et du JS de DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div>
                <style> 
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

                    .btn-arrow:hover {
                        color: #b700ff !important;
                    }

                    /* Styles responsifs */
                    @media (max-width: 768px) {
                        .col-md-3 {
                            margin-bottom: 20px;
                            border-right: none !important;
                            border-bottom: 1px solid #000000 !important;
                        }

                        .table-responsive {
                            margin-bottom: 15px;
                        }

                        .form-group {
                            margin-bottom: 15px;
                        }

                        .btn {
                            margin-bottom: 10px;
                        }
                    }

                    .form-check {
                        margin-left: 45px !important;
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

                    /* Styles pour les colonnes */
                    .column-section {
                        padding: 15px;
                        height: 100%;
                    }

                    /* Styles pour les boutons */
                    .action-buttons {
                        display: flex;
                        flex-direction: column;
                        gap: 10px;
                    }

                    .action-buttons .btn {
                        width: 100%;
                        margin-bottom: 10px;
                        white-space: normal;
                    }

                    /* Styles pour les inputs */
                    .form-control {
                        max-width: 100%;
                    }

                    /* Styles pour les modales */
                    .modal-dialog {
                        max-width: 95%;
                        margin: 1.75rem auto;
                    }

                    @media (max-width: 576px) {
                        .modal-dialog {
                            max-width: 100%;
                            margin: 1rem;
                        }
                    }
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br>
                <br>
            </div>
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Edition des Bulletins de notes</h4>
                    
                </div>

                <form id="formBulletin" action="{{ route('printbulletindenotes') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                   
                    <div class="row">
                        <div class="col-12 col-md-3 column-section">
                            <!-- Contenu de la première colonne -->
                            <div class="row" id="selectionBlock">
                                <h5>Impression vers</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paramselection" id="ecran"
                                        value="ecran" checked>
                                    <label class="form-check-label" for="ecran">
                                        Ecran
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paramselection" value="imprimante"
                                        id="imprimante" >
                                    <label class="form-check-label" for="imprimante">
                                        Imprimante
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paramselection" name="pdf"
                                        id="pdf">
                                    <label class="form-check-label" for="pdf">
                                        Pdf
                                    </label>
                                </div>

                            </div>
                            <br>
                            <div class="row">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="archive" id="archive" checked>
                                    <label class="form-check-label" for="archive">
                                        Archive pdf automatique
                                    </label>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <h5>Signature</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="signature" value="CCC"
                                        id="ccc" checked>
                                    <label class="form-check-label" for="ccc">
                                        CCC
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="signature" value="Directeur 2"
                                        id="directeur">
                                    <label class="form-check-label" for="directeur">
                                        Directeur 2
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="signature" value="CC"
                                        id="cc">
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
                                    <input class="form-check-input" type="radio" name="bonificationType" value="integral"
                                        id="integral">
                                    <label class="form-check-label" for="integral">
                                        Intégral
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bonificationType"
                                        value="intervalle" id="intervalle" checked>
                                    <label class="form-check-label" for="intervalle">
                                        Intervalle
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bonificationType"
                                        value="Aucun" id="none">
                                    <label class="form-check-label" for="none">
                                        Aucun
                                    </label>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group d-flex">
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[0][start]"
                                        id="bonification[0][start]" value="0" step="0.25" readonly>
                                    <p style="margin-top: 10px;">--</p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[0][end]"
                                        id="bonification[0][end]" value="11" step="0.25">
                                    <p style="margin-top: 10px;">--></p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[0][note]"
                                        id="bonification[0][note]" value="1" step="0.25">
                                </div>
                                <div class="form-group d-flex">
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[1][start]"
                                        id="bonification[1][start]" value="11" step="0.25">
                                    <p style="margin-top: 10px;">--</p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[1][end]"
                                        id="bonification[1][end]" value="12" step="0.25">
                                    <p style="margin-top: 10px;">--></p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[1][note]"
                                        id="bonification[1][note]" value="2" step="0.25">
                                </div>
                                <div class="form-group d-flex">
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[2][start]"
                                        id="bonification[2][start]" value="12" step="0.25">
                                    <p style="margin-top: 10px;">--</p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[2][end]"
                                        id="bonification[2][end]" value="14" step="0.25">
                                    <p style="margin-top: 10px;">--></p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[2][note]"
                                        id="bonification[2][note]" value="3" step="0.25">
                                </div>
                                <div class="form-group d-flex">
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[3][start]"
                                        id="bonification[3][start]" value="14" step="0.25">
                                    <p style="margin-top: 10px;">--</p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[3][end]"
                                        id="bonification[3][end]" value="16" step="0.25">
                                    <p style="margin-top: 10px;">--></p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[3][note]"
                                        id="bonification[3][note]" value="4" step="0.25">
                                </div>
                                <div class="form-group d-flex">
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[4][start]"
                                        id="bonification[4][start]" value="16" step="0.25">
                                    <p style="margin-top: 10px;">--</p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[4][end]"
                                        id="bonification[4][end]" value="20" step="0.25" readonly>
                                    <p style="margin-top: 10px;">--></p>
                                    <input type="number" class="form-control bonification-input"
                                        style="width: 28% !important" name="bonification[4][note]"
                                        id="bonification[4][note]" value="5" step="0.25">
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        document.querySelectorAll('.bonification-input').forEach((input, index, array) => {
                                            if (index < array.length - 1) {
                                                document.getElementById(`bonification[${index}][end]`).value = document.getElementById(
                                                    `bonification[${index + 1}][start]`).value;
                                            }
                                        });
                                    });
                                </script>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="effectif">Effectif en cours</label>
                                    <input type="number" class="form-control" style="width: 30% !important"
                                        id="effectif" name="effectif" value="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 column-section">
                            <!-- Contenu de la deuxième colonne -->
                            <div class="form-group">
                                <label for="groupe">Sélectionner un type d'enseignement</label>
                                <select class="js-example-basic-multiple custom-select-width" id="groupe" 
                                    onchange="fetchClasses(this.value)" style="width: 300px;">
                                    @foreach ($classesg as $classeg)
                                        <option value="{{ $classeg->LibelleGroupe }}" 
                                            {{ $classeg->LibelleGroupe == 'Ens. General' ? 'selected' : '' }}>
                                            {{ $classeg->LibelleGroupe }}</option>
                                    @endforeach
                                </select>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Déclencher l'événement change au chargement pour charger les classes d'Ens. General
                                        const groupeSelect = document.getElementById('groupe');
                                        if(groupeSelect.value === 'Ens. General') {
                                            fetchClasses('Ens. General');
                                        }
                                    });
                                </script>
                            </div>
                            <div>
                                <br>
                                <div class="table-responsive mb-4" style="max-height: 350px; margin: auto; overflow-y: auto;">
                                    <table id="tableClasses" class="table table-bordered">
                                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                            <tr>
                                                <th style="width: 10% !important;">
                                                    <input type="checkbox" name="selected_classes[]" value="all"
                                                        style="margin-left: 9px !important; margin-top: -15px !important;"
                                                        onclick="selectAllCheckboxes(this)">
                                                </th>
                                                <th>Classes</th>
                                                <th>Effectif</th>
                                            </tr>
                                        </thead>
                                        <tbody id="classTableBody">
                                            {{-- <tr>
                                                  <td colspan="3" class="text-center">Sélectionnez un type pour afficher les classes</td>
                                            </tr> --}}
                                            <!-- Les classes correspondant au groupe sélectionné seront insérées ici -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <h5>Message affiché au bas du bulletin</h5>
                                <div id="editor" class="editor" contenteditable="true"></div>
                                <input type="hidden" name="msgEnBasBulletin" id="messageEd">
                                <style>
                                    .toolbar {
                                        display: flex;
                                        align-items: center;
                                        border: 1px solid #ccc;
                                        padding: 5px;
                                        background-color: #f1f1f1;
                                    }

                                    .toolbar select,
                                    .toolbar button,
                                    .toolbar input[type="color"] {
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
                                        <select id="fontSelect" onchange="changeFont(this.value)"
                                            style="width: 50% !important;">
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
                                        <button type="button" class="icon"
                                            onclick="document.execCommand('bold', false, '')">G</button> <!-- Gras -->
                                        <button type="button" class="icon"
                                            onclick="document.execCommand('italic', false, '')">I</button>
                                        <!-- Italique -->
                                        <button type="button" class="icon"
                                            onclick="document.execCommand('underline', false, '')">S</button>
                                        <!-- Souligné -->

                                        <!-- Barré et couleur -->
                                        <button type="button" class="icon"
                                            onclick="document.execCommand('strikethrough', false, '')">Ƀ</button>
                                        <!-- Barré -->
                                        <input type="color" onchange="changeColor(this.value)" />

                                        <!-- Alignement -->
                                        <button type="button" class="icon"
                                            onclick="document.execCommand('justifyLeft', false, '')">L</button>
                                        <!-- Aligné à gauche -->
                                        <button type="button" class="icon"
                                            onclick="document.execCommand('justifyCenter', false, '')">C</button>
                                        <!-- Centré -->
                                        <button type="button" class="icon"
                                            onclick="document.execCommand('justifyRight', false, '')">R</button>
                                        <!-- Aligné à droite -->
                                        <button type="button" class="icon"
                                            onclick="document.execCommand('justifyFull', false, '')">J</button>
                                        <!-- Justifié -->

                                        <!-- Bouton de réinitialisation -->
                                    </div>

                                    <!-- Zone d'édition -->

                                    <script>
                                        function getMessage() {
                                            var editorContent = document.getElementById('editor').innerHTML;
                                            document.getElementById('messageEd').value = editorContent;
                                            console.log(editorContent);
                                        }

                                        function changeFont(font) {
                                            document.execCommand('fontName', false, font);
                                        }

                                        function changeFontSize(size) {
                                            document.execCommand("styleWithCSS", true);
                                            document.execCommand('fontSize', false, '7'); // Set a base size
                                            var selectedText = window.getSelection();
                                            if (selectedText.rangeCount) {
                                                var range = selectedText.getRangeAt(0);
                                                var span = document.createElement('span');
                                                span.style.fontSize = size;
                                                range.surroundContents(span);
                                            }
                                        }

                                        function changeColor(color) {
                                            document.execCommand('foreColor', false, color);
                                        }
                                    </script>
                                    <br>
                                    <div class="d-flex justify-content-end align-items-center"
                                        style="margin-top: 40px !important;">
                                        <button type="button" class="btn btn-primary"
                                            onclick="getMessage()">Sauvegarder</button>
                                    </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 column-section">
                            <!-- Contenu de la troisième colonne -->
                            <div class="row"
                                style="border: 1px solid #844fc1 !important; border-radius: 4px !important; background-color: #844fc1; color: #fff;">
                                <div class="form-group d-flex align-items-center" style="margin-top: 10px !important;">
                                    <label for="periode">Période</label>
                                    <select class="form-select" id="periode" name="periode"
                                        style="margin-left: 20px !important;">
                                        <option value="">Sélectionner une période</option>
                                        <option value="1" selected>1ère période</option>
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
                                    <label for="interligne">Interligne (mm)</label>
                                    <input type="number" class="form-control" id="interligne" name="interligne"
                                        value="7"
                                        style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                                </div>
                                <br>
                                <div class="form-group d-flex align-items-center">
                                    <label for="conduite">Code CONDUITE ?</label>
                                    <input type="number" class="form-control" id="conduite" name="conduite"
                                        value="{{ session('conduite', 0) }}"
                                        style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                                    <button class="btn btn-primary" type="button"
                                        style="margin-left: 5px !important;padding: 0.5rem;background-color: #fff; color: #000;"
                                        data-bs-toggle="modal" data-bs-target="#listematiere">Voir</button>
                                </div>
                                <br>
                                <div class="form-group d-flex align-items-center">
                                    <label for="eps">Code EPS ?</label>
                                    <input type="number" class="form-control" id="eps" name="eps"
                                        value="{{ session('eps', 0) }}"
                                        style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                                    <button class="btn btn-primary" type="button"
                                        style="margin-left: 5px !important;padding: 0.5rem;background-color: #fff; color: #000;"
                                        data-bs-toggle="modal" data-bs-target="#listematiere">Voir</button>
                                </div>
                                <div class="form-group d-flex align-items-center">
                                    <label for="nbabsence">Nb. Absence autorisée</label>
                                    <input type="number" class="form-control w-25" id="nbabsence" name="nbabsence"
                                        value="{{ session('nbabsence', -1) }}"
                                        style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                                </div>
                                <p style="margin-left: 10px !important;">(Mettre -1 pour débrancher cette option)</p>
                                <br>
                                <button class="btn btn-secondary" type="button" style="">Sauvegarder ces
                                    informations</button>
                            </div>
                            <br>
                            <div class="row">
                                {{-- <button class="btn btn-primary mb-2" type="button" data-bs-toggle="modal"
                                    data-bs-target="#configdecisionconseil">Configurer décisions du conseil</button>
                                <br> --}}
                                {{-- <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                    data-bs-target="#configdecisionjury">Configurer décisions du jury (LMD)</button> --}}
                            </div>
                            <br>
                            @if ($typean == 1)
                                <div class="row"
                                    style="background-color: #844fc1; border: 1px solid #ffffff !important; border-radius: 4px !important; color: #fff;">
                                    <legend style="font-weight: bold; color: #fff; font-size: 1rem;">Calcul moyenne
                                        annuelle</legend>
                                    <div class="form-group d-flex align-items-center mb-2">
                                        <label for="pondTrim1">Pondération Semestre 1</label>
                                        <input type="number" class="form-control" id="pondTrim1" name="pondTrim1"
                                            value="1"
                                            style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                                        {{-- <input type="checkbox" class="form-check-input" name="pondTrim1" id="pondTrim1" style="right: 1rem !important;"> --}}
                                    </div>
                                    <div class="form-group d-flex align-items-center mb-2">
                                        <label for="pondTrim2">Pondération Semestre 2</label>
                                        <input type="number" class="form-control" id="pondTrim2" name="pondTrim2"
                                            value="1"
                                            style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                                        {{-- <input type="checkbox" class="form-check-input" name="pondTrim2" id="pondTrim2" style="right: 1rem !important;"> --}}
                                    </div>

                                </div>
                            @else
                                <div class="row"
                                    style="background-color: #844fc1; border: 1px solid #ffffff !important; border-radius: 4px !important; color: #fff;">
                                    <legend style="font-weight: bold; color: #fff; font-size: 1rem;">Calcul moyenne
                                        annuelle</legend>
                                    <div class="form-group d-flex align-items-center mb-2">
                                        <label for="pondTrim1">Pondération Trimestre 1</label>
                                        <input type="number" class="form-control" id="pondTrim1" name="pondTrim1"
                                            value="1"
                                            style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                                        {{-- <input type="checkbox" class="form-check-input" name="pondTrim1" id="pondTrim1" style="right: 1rem !important;"> --}}
                                    </div>
                                    <div class="form-group d-flex align-items-center mb-2">
                                        <label for="pondTrim2">Pondération Trimestre 2</label>
                                        <input type="number" class="form-control" id="pondTrim2" name="pondTrim2"
                                            value="1"
                                            style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                                        {{-- <input type="checkbox" class="form-check-input" name="pondTrim2" id="pondTrim2" style="right: 1rem !important;"> --}}
                                    </div>
                                    <div class="form-group d-flex align-items-center mb-1">
                                        <label for="pondTrim3">Pondération Trimestre 3</label>
                                        <input type="number" class="form-control" id="pondTrim3" name="pondTrim3"
                                            value="1"
                                            style="margin-left: 10px; width: 50px !important; padding: 0; height: 2rem;">
                                        {{-- <input type="checkbox" class="form-check-input" name="pondTrim3" id="pondTrim3" style="right: 1rem !important;;"> --}}
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="col-12 col-md-2 column-section">
                            <!-- Nouvelle quatrième colonne -->
                            <div class="action-buttons">
                                <button class="btn btn-secondary" type="button" id="btnOptions" data-bs-toggle="modal" data-bs-target="#optionsEdition">
                                    <i class="fas fa-cog"></i> Options d'édition
                                </button>
                                <button class="btn btn-primary" type="submit" id="imprimerClasses" style="display: none;">
                                    <i class="fas fa-print"></i> Imprimer bulletins
                                </button>
                                <label for="imageUpload" class="btn btn-primary mb-0">
                                    <i class="fas fa-image"></i> Déposer une image de fond
                                </label>
                                <input type="file" name="image" id="imageUpload" accept="image/*" style="display: none;">
                              
                                {{-- <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                    data-bs-target="#configdecisionconseil">
                                    <i class="fas fa-tasks"></i> Configurer décisions du conseil
                                </button> --}}
                                @if ($typean == 1)
                                    {{-- <button class="btn btn-warning" type="button" data-bs-toggle="modal"
                                        data-bs-target="#configdecisionjury">
                                        <i class="fas fa-gavel"></i> Configurer décisions du jury (LMD)
                                    </button> --}}
                                @endif
                            </div>
                           
                        </div>
                    </div>
                </form>

                <div id="resultMessage"></div> <!-- Pour afficher le message de confirmation ou l'erreur -->

            </div>
        </div>

        <!-- Modal pour afficher les élèves -->
        <div class="modal fade" id="listematiere" tabindex="-1" aria-labelledby="listematiereLabel"
            aria-hidden="true">
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
    </div>

    <!-- Modale des options d'édition -->
    <div class="modal fade" id="optionsEdition" tabindex="-1" aria-labelledby="optionsEditionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabelmodif">Liste des options d'édition</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Ajouter l'action vers la nouvelle route et spécifier method="POST" -->
                <form id="formedition" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Formulaire du modal -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="entete" id="entete" checked>
                                    <label class="form-check-label edition-label" for="entete">Imprimer l'entête</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fond" id="fond" checked>
                                    <label class="form-check-label edition-label" for="fond">Imprimer le fond</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="note_conduite"
                                        id="note_conduite" checked>
                                    <label class="form-check-label edition-label" for="note_conduite">Intégrer la note de
                                        conduite dans le calcul</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="matricule" id="matricule" checked>
                                    <label class="form-check-label edition-label" for="matricule">Imprimer le matricule
                                        sur le bulletin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="annuler_matiere"
                                        id="annuler_matiere" checked>
                                    <label class="form-check-label edition-label" for="annuler_matiere">Annuler la matière
                                        si aucun devoir</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="rang_matiere"
                                        id="rang_matiere" checked>
                                    <label class="form-check-label edition-label" for="rang_matiere">Imprimer le rang par
                                        matière</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="rang_general"
                                        id="rang_general" checked>
                                    <label class="form-check-label edition-label" for="rang_general">Imprimer le rang
                                        général</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="masquer_devoir3"
                                        id="masquer_devoir3" checked>
                                    <label class="form-check-label edition-label" for="masquer_devoir3">Masquer la colonne
                                        Devoir 3</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="appreciation_prof"
                                        id="appreciation_prof" checked>
                                    <label class="form-check-label edition-label" for="appreciation_prof">Imprimer
                                        l'appréciation du professeur</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="photo_par_logo"
                                        id="photo_par_logo" checked>
                                    <label class="form-check-label edition-label" for="photo_par_logo">Remplacer la photo
                                        par le logo</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="note_test" id="note_test">
                                    <label class="form-check-label edition-label" for="note_test">Gérer la note de Test
                                        (ou Compo)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mention_conseil"
                                        id="mention_conseil" checked>
                                    <label class="form-check-label edition-label" for="mention_conseil">Cocher les
                                        mentions du conseil</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="decision_conseil"
                                        id="decision_conseil" checked>
                                    <label class="form-check-label edition-label" for="decision_conseil">Imprimer la
                                        décision du conseil</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="recalculer" id="recalculer" checked>
                                    <label class="form-check-label edition-label" for="recalculer">Recalculer avant
                                        impression</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="appreciation_directeur"
                                        id="appreciation_directeur" checked>
                                    <label class="form-check-label edition-label" for="appreciation_directeur">Imprimer
                                        l'appréciation du directeur</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formedition').addEventListener('submit', function(event) {
            event.preventDefault();

            // Créer un objet FormData à partir du formulaire
            let formData = new FormData(this);

            fetch('{{ route('optionsbulletindenotes') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur du serveur : ' + response.status);
                    }
                    return response.text(); // On attend du texte en retour, pas du JSON
                })
                .then(data => {

                    // Afficher un message de succès
                    alert('Les options d\'édition ont été enregistrées avec succès');

                    document.getElementById('btnOptions').style.display = 'none';

                    document.getElementById('imprimerClasses').style.display = 'inline-block';

                    // Fermer le modal
                    $('#optionsEdition').modal('hide');
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de l\'enregistrement des options.');
                });
        });
    </script>
    <!-- Modale de configuration des bulletins -->
    <div class="modal fade" id="configdecisionconseil" tabindex="-1" aria-labelledby="configdecisionconseilLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabelmodif">Configuration de la décision du Conseil des
                        Profs</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex" style="margin-left: 50px !important;">
                        <label for="config_promo" style="margin-top: 15px !important;">Promotion à configurer</label>
                        <select class="form-select w-50" id="config_promo" name="config_promo"
                            style="margin-left: 20px !important;">
                            <option value="">Sélectionner une promotion</option>
                            @foreach ($promotions as $promotion)
                                <option value="{{ $promotion->CODEPROMO }}">{{ $promotion->LIBELPROMO }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-secondary" style="margin-left: 20px !important;">Sauver</button>
                    </div>
                    <br>
                    <div class="row">
                        <div
                            style="background-color: #35d966; border-radius: 5px !important; width: 82% !important; margin-left: 5px !important;">
                            <h4>Statut - Non redoublant</h4>
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuenrd1" name="valuenrd1" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuenrd2" name="valuenrd2" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionnrd1" name="mentionnrd1"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuenrd3" name="valuenrd3" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuenrd4" name="valuenrd4" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionnrd2" name="mentionnrd2"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuenrd5" name="valuenrd5" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuenrd6" name="valuenrd6" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionnrd3" name="mentionnrd3"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuenrd7" name="valuenrd7" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuenrd8" name="valuenrd8" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionnrd4" name="mentionnrd4"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuenrd9" name="valuenrd9" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuenrd10" name="valuenrd10" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionnrd5" name="mentionnrd5"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuenrd11" name="valuenrd11" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuenrd12" name="valuenrd12" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionnrd6" name="mentionnrd6"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div
                            style="background-color: #35d966; border-radius: 5px !important; width: 82% !important; margin-left: 5px !important;">
                            <h4>Statut - Redoublant</h4>
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuered1" name="valuered1" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuered2" name="valuered2" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionred1" name="mentionred1"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuered3" name="valuered3" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuered4" name="valuered4" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionred2" name="mentionred2"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuered5" name="valuered5" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuered6" name="valuered6" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionred3" name="mentionred3"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuered7" name="valuered7" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuered8" name="valuered8" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionred4" name="mentionred4"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuered9" name="valuered9" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuered10" name="valuered10" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionred5" name="mentionred5"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
                        </div>
                        <div class="d-flex" style="margin-top: 10px !important;">
                            <input type="number" class="form-control" id="valuered11" name="valuered11" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---</p>
                            <input type="number" class="form-control" id="valuered12" name="valuered12" value="0"
                                style="margin-left: 20px !important; width: 10% !important; border-color: #35d966;">
                            <p style="margin-left: 20px !important; margin-top: 10px !important;">---></p>
                            <input type="text" class="form-control" id="mentionred6" name="mentionred6"
                                style="margin-left: 20px !important; width: 50% !important; border-color: #35d966;">
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
    <div class="modal fade" id="configdecisionjury" tabindex="-1" aria-labelledby="configdecisionjuryLabel"
        aria-hidden="true">
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
                            <label class="form-check-label" for="decisionCredits">Décision sur nombre de crédits
                                acquis</label>
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
                        <h5 for="intervalle"
                            style="background-color: #bdbdbd; border-radius: 5px !important; width: 25% !important; margin-left: 100px !important; text-align: center !important;">
                            Intervalle</h5>
                        <h5 for="decision"
                            style="background-color: #bdbdbd; border-radius: 5px !important; width: 50% !important; margin-left: 100px !important; text-align: center !important;">
                            Décision</h5>
                    </div>
                    <br>
                    <div class="d-flex">
                        <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00"
                            style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
                        <input type="number" class="form-control" id="percentage2" name="percentage2" value="0.00"
                            style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
                        <input type="text" class="form-control" id="decision1" name="decision1"
                            style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
                    </div>
                    <br>
                    <div class="d-flex">
                        <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00"
                            style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
                        <input type="number" class="form-control" id="percentage2" name="percentage2" value="0.00"
                            style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
                        <input type="text" class="form-control" id="decision1" name="decision1"
                            style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
                    </div>
                    <br>
                    <div class="d-flex">
                        <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00"
                            style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
                        <input type="number" class="form-control" id="percentage2" name="percentage2" value="0.00"
                            style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
                        <input type="text" class="form-control" id="decision1" name="decision1"
                            style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
                    </div>
                    <br>
                    <div class="d-flex">
                        <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00"
                            style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
                        <input type="number" class="form-control" id="percentage2" name="percentage2" value="0.00"
                            style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
                        <input type="text" class="form-control" id="decision1" name="decision1"
                            style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
                    </div>
                    <br>
                    <div class="d-flex">
                        <input type="number" class="form-control" id="percentage1" name="percentage1" value="0.00"
                            style="margin-left: 100px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 10px !important; margin-top: 10px !important;">---</p>
                        <input type="number" class="form-control" id="percentage2" name="percentage2"
                            value="0.00"
                            style="margin-left: 20px !important; width: 10% !important; border-color: #000000;">
                        <p style="margin-left: 50px !important; margin-top: 10px !important;">---></p>
                        <input type="text" class="form-control" id="decision1" name="decision1"
                            style="margin-left: 100px !important; width: 40% !important; border-color: #000000;">
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
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <script>
        function selectAllCheckboxes(source) {
            checkboxes = document.getElementsByName('selected_classes[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bonificationInputs = document.querySelectorAll(
                '.bonification-input'); // Sélectionner tous les inputs de bonification

            const updateInputState = () => {
                const isIntervalSelected = document.getElementById('intervalle').checked;
                bonificationInputs.forEach(input => {
                    input.disabled = !
                        isIntervalSelected; // Activer ou désactiver les inputs basé sur la sélection de 'Intervalle'
                });
            };

            // Écouter les changements sur les boutons radio de bonification
            document.querySelectorAll('input[name="bonification"]').forEach(radio => {
                radio.addEventListener('change', updateInputState);
            });

            // Initialiser l'état des inputs au chargement de la page
            updateInputState();
        });
    </script>
    {{-- sxript de soumission du formulaire --}}
    {{-- <script>
  document.getElementById('formBulletin').addEventListener('submit', async function(event) {
      event.preventDefault(); // Empêche le rafraîchissement de la page
  
      const form = event.target;
      const formData = new FormData(form);
  
      try {
          const response = await fetch(form.action, {
              method: form.method,
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}' // Envoie le token CSRF
              },
              body: formData
          });
  
          if (response.ok) {
              const result = await response.json(); // Remplace par response.text() si tu attends une réponse texte
              document.getElementById('resultMessage').innerHTML = '<div class="alert alert-success">Le bulletin a été soumis avec succès !</div>';
          } else {
              document.getElementById('resultMessage').innerHTML = '<div class="alert alert-danger">Une erreur est survenue lors de la soumission.</div>';
          }
      } catch (error) {
          document.getElementById('resultMessage').innerHTML = '<div class="alert alert-danger">Erreur réseau : ' + error.message + '</div>';
      }
  });
</script> --}}

    <script>
        document.getElementById('imprimerClasses').addEventListener('click', function(event) {
            // Empêcher la soumission du formulaire si nécessaire
            event.preventDefault();
            // document.getElementById('echeancesData').value = JSON.stringify(echeances);

            // Soumettre le formulaire si la validation est réussie
            document.getElementById('formBulletin').submit();
        });
        // });
    </script>


    <script>
        function fetchClasses(libelleGroupe) {
            if (!libelleGroupe) {
                document.getElementById('classTableBody').innerHTML =
                    '<tr><td colspan="3" class="text-center">Sélectionnez un type pour afficher les classes</td></tr>';
                return;
            }

            $.ajax({
                url: "{{ url('/get-classes-by-group') }}",
                type: "GET",
                data: {
                    libelleGroupe: libelleGroupe
                },
                success: function(response) {
                    let rows = "";
                    if (response.length === 0) {
                        rows = "<tr><td colspan='3' class='text-center'>Aucune classe trouvée</td></tr>";
                    } else {
                        response.forEach(classe => {
                            rows += `
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" 
                                           name="selected_classes[]" 
                                           value="${classe.CODECLAS}"
                                           style="margin-left: 10px;">
                                </td>
                                <td class="text-center">${classe.CODECLAS}</td>
                                <td class="text-center">${classe.EFFECTIF}</td>
                            </tr>
                        `;
                        });
                    }
                    $("#classTableBody").html(rows);
                },
                error: function() {
                    $("#classTableBody").html(
                        "<tr><td colspan='3' class='text-center text-danger'>Erreur lors du chargement</td></tr>"
                    );
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restaurer la valeur de l'interligne depuis le localStorage
            var savedInterligne = localStorage.getItem('bulletin_interligne');
            if (savedInterligne) {
                document.getElementById('interligne').value = savedInterligne;
            }

            // Sauvegarder la valeur de l'interligne quand elle change
            document.getElementById('interligne').addEventListener('change', function(e) {
                localStorage.setItem('bulletin_interligne', e.target.value);
            });
        });
    </script>
    <style>
        .form-check {
            margin-left: 45px !important;
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
