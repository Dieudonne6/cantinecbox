@extends('layouts.master')  
@section('content')

<div class="container-fluid">
    <!-- Début de la disposition sur 4 colonnes -->
    <div class="row">
        <!-- Réservation de 4 colonnes pour le contenu -->
        <div class="col-lg-4">
            <button class="btn btn-primary">Sélectionner</button>
            <button class="btn btn-primary">Sélectionner Tout</button>

            <!-- Tableau avec tri alphabétique et classes -->
            <h4 class="mt-4 text-center">Choix des classes</h4>
            <div class="table-responsive" style="max-height: 382px; overflow-y: auto;">
                <table class="table table-striped table-bordered" id="classes-table">
                    <thead class="table-warning">
                        <tr>
                            <th>
                                <i class="fas fa-sort" data-sort="code"></i> <!-- Icône de tri pour le code -->
                            </th>
                            <th>
                                CLASSES 
                                <i class="fas fa-sort" data-sort="classe"></i> <!-- Icône de tri pour les classes -->
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($releves as $releve)
                        <tr>
                            <td>
                                <!-- Contenu de la première colonne (vide ou à définir) -->
                            </td>
                            <td>
                                {{ $releve->CODECLAS }} <!-- code de la classe -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Réservation de 6 colonnes pour le contenu -->
        <div class="col-lg-6">
            <div class="container"><button class=" btn btn-primary">Sélectionner</button>
            <button class="btn btn-primary">Sélectionner Tout</button></div>

            <!-- Tableau avec tri alphabétique et matières -->
            <h4 class="mt-4 text-center">Choix des matières</h4>
            <div class="table-responsive" style="max-height: 382px; overflow-y: auto;">
                <table class="table table-striped table-bordered" id="matieres-table">
                    <thead class="table-warning">
                        <tr>
                            <th><i class="element fas fa-sort" data-sort="code"></i></th> <!-- Icône de tri pour les codes -->
                            <th>Code <i class="fas fa-sort" data-sort="code"></i></th> <!-- Icône de tri pour les classes -->
                            <th>Matières <i class="fas fa-sort" data-sort="libelle"></i></th> <!-- Icône de tri pour les libellés -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matieres as $matiere)
                        <tr>
                            <td>
                                <!-- Contenu de la première colonne (vide ou à définir) -->
                            </td>
                            <td>
                                {{ $matiere->CODEMAT }} <!-- Code de la matière -->
                            </td>
                            <td>
                                {{ $matiere->LIBELMAT }} <!-- Nom de la matière -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-2 ">
            <div class="d-flex align-items-start justify-content-center mt-4">
            <div class="border border-white p-3" style="width: auto; border-width: 2px; margin-top: 75px;">
                <h5>Diriger vers</h5>
                <div>
                    <input type="radio" id="ecran" name="output" value="ecran" checked>
                    <label for="ecran">Écran</label><br>
                    <input type="radio" id="imprimante" name="output" value="imprimante">
                    <label for="imprimante">Imprimante</label>
                </div>
            </div>
            </div>
            <button class="btn btn-primary ml-4" style="width: auto; margin-top: 20px;">Imprimer</button>
        </div>
        
        
    </div>
</div>

@endsection
@section('styles')
<style>
/* .fas.fa-sort {
    cursor: pointer;
    color: #333; /* Assure-toi que l'icône est visible 
    font-size: 1.2em; /* Taille de l'icône 
} */

.element:before {
    content: "\f000";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
/*--adjust as necessary--*/
    color: #000;
    font-size: 18px;
    padding-right: 0.5em;
    position: absolute;
    top: 10px;
    left: 0;
}
.table thead {
    position: sticky; /* Rend l'en-tête collant */
    top: 0; /* Positionne l'en-tête en haut du conteneur */
    z-index: 100; /* Assure que l'en-tête est au-dessus du contenu défilant */
    background-color: #ffc107; /* Couleur de fond pour l'en-tête */
}
</style>
@endsection

@section('scripts')
<!-- Assure-toi d'avoir bien chargé FontAwesome pour afficher les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dNB8nBUksL5H55Cnlbx6b7Qv4D0JRZdM+HBgLoAXG/6O2rBX/oC7SFEqrAkwbF93e/dFrYHs2MlFVj4yTtY0w==" crossorigin="anonymous" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortIcons = document.querySelectorAll('.fas.fa-sort');

        sortIcons.forEach(function(icon) {
            icon.addEventListener('click', function() {
                const sortAttribute = icon.getAttribute('data-sort'); // Obtenez l'attribut pour savoir quoi trier (code ou classe)
                const tableId = icon.closest('table').id; // Récupérez l'id du tableau correspondant
                sortTable(tableId, sortAttribute);
            });
        });

        function sortTable(tableId, sortAttribute) {
            const table = document.getElementById(tableId);
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            // Récupérez l'indice de la colonne à trier
            let columnIndex = 0;
            if (sortAttribute === 'classe') columnIndex = 1; // Si tri par nom de classe

            // Détecter l'ordre de tri actuel (ascendant ou descendant)
            let isAscending = table.getAttribute('data-sort-order') === 'asc';
            
            // Effectuer le tri des lignes du tableau
            rows.sort((rowA, rowB) => {
                const cellA = rowA.querySelectorAll('td')[columnIndex].innerText.trim();
                const cellB = rowB.querySelectorAll('td')[columnIndex].innerText.trim();

                return isAscending 
                    ? cellA.localeCompare(cellB) // Tri ascendant
                    : cellB.localeCompare(cellA); // Tri descendant
            });

            // Inverser l'ordre de tri
            table.setAttribute('data-sort-order', isAscending ? 'desc' : 'asc');

            // Réinsérer les lignes triées dans le tbody
            rows.forEach(row => tbody.appendChild(row));
        }
    });
</script>
@endsection
