@extends('layouts.master')  
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="container-fluid">
        <!-- En-tête en dehors du cadre -->
        <h5 style="color: orange;">Diriger vers</h5>
        
        <div class="row">
            <!-- Colonne gauche pour les options radio et le tableau -->
            <div class="col-lg-4 col-md-6">
                <!-- Cadre aligné à gauche avec une taille flexible -->
                <div class="card mt-2">
                    <div class="card-body">
                        <!-- Options radio -->
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="outputOption" id="ecranOption" value="ecran" checked>
                            <label class="form-check-label" for="ecranOption">
                                Écran
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="outputOption" id="imprimanteOption" value="imprimante">
                            <label class="form-check-label" for="imprimanteOption">
                                Imprimante
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Tableau avec barre de défilement -->
                <div class="table-responsive mt-4" style="max-height: 300px; overflow-y: auto; border: 1px solid #dddddd;">
                    <table id="classTable" class="table table-striped table-bordered mb-0">
                        <thead class="table-warning sticky-header">
                            <tr>
                                <th style="width: 20px;"></th> <!-- Colonne pour cases à cocher -->
                                <th>Classes</th> <!-- Colonne pour les classes -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notes as $note)
                            <tr>
                                <td style="width: 20px;"><input type="checkbox"></td>
                                <td name="nomclasse">{{ $note->CODECLAS }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Colonne droite pour le champ interligne et le texte enrichi -->
            <div class="col-lg-8 col-md-6">
                <div class="form-group">
                    <label for="interligne">Interligne (mm)</label>
                    <input type="number" class="form-control">
                </div>

                <!-- Barre d'outils pour le texte enrichi -->
                <div class="form-group mt-4">
                    <label for="editor">Texte enrichi</label><br>
                    <!-- Zone de texte enrichi -->
                    <div id="editor" contenteditable="true" style="border: 1px solid #ccc; padding: 10px; min-height: 150px; width: 100%;"></div>
                    
                    <!-- Barre d'outils stylisée -->
                    <div class="d-flex mt-2">
                        <button onclick="document.execCommand('bold', false, '');" class="btn btn-light btn-sm"><b>G</b></button>
                        <button onclick="document.execCommand('italic', false, '');" class="btn btn-light btn-sm"><i>I</i></button>
                        <button onclick="document.execCommand('underline', false, '');" class="btn btn-light btn-sm"><u>S</u></button>
                        <select id="fontSelect" onchange="document.execCommand('fontName', false, this.value);" class="form-control ml-2 btn-sm" style="width: auto;">
                            <option value="Arial">Arial</option>
                            <option value="Verdana">Verdana</option>
                            <option value="Times New Roman">Times New Roman</option>
                        </select>
                        <button class="btn btn-warning btn-sm mt-1 ml-5">Sauver</button>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-5">
                    <button class="btn btn-warning btn-sm">Sélectionner</button>
                    <button class="btn btn-warning btn-sm ml-2" id="selectAllBtn">Sélectionner tout</button>
                    <button class="btn btn-warning btn-sm ml-2">Imprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ajoute un écouteur d'événement pour le bouton "Sélectionner tout"
        document.getElementById('selectAllBtn').addEventListener('click', function() {
            // Récupère toutes les cases à cocher du tableau
            let checkboxes = document.querySelectorAll('#classTable input[type="checkbox"]');
            
            // Coche toutes les cases à cocher
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });
    });
</script>
@endsection
