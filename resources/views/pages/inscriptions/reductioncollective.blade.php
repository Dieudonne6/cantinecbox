@extends('layouts.master')
@section('content')

<div class="container card mt-3">
    <div class="card-body">
        <h4 class="card-title">Réductions de groupe</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="table-container mt-2">
                <table>
                    <thead>
                        <tr>
                            <th>Choix</th>
                            <th>Nom & Prénoms</th>
                            <th>Sexe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><input type="checkbox" onclick="toggleAll(this)"></th>
                            <th colspan="2" class="table-active">Colonne 1</th>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Données 1</td>
                            <td>Données 2</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Données 4</td>
                            <td>Données 5</td>
                        </tr>
                        <!-- Ajoutez plus de lignes ici -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-body">
                <select class="form-select mb-3" aria-label="Large select example">
                    <option selected>Sélectionner une classe</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>

                <form>
                    <p>Public cible</p>
                    <input type="radio" id="option1" name="choix" value="option1">
                    <label for="option1">Sélectionner les filles</label><br>
                    <input type="radio" id="option2" name="choix" value="option2">
                    <label for="option2">Sélectionner les garçons</label><br>
                    <input type="radio" id="option3" name="choix" value="option3">
                    <label for="option3">Sélectionner sans distinction</label><br>
                </form>

                <select class="form-select mb-3" aria-label="Large select example">
                    <option selected>Profil de réduction</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>

                <a class="btn btn-primary" href="#">Appliquer les réductions</a>
                <button type="button" class="btn btn-secondary">Fermer</button>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

<style>
    body {
        font-family: Arial, sans-serif;
    }
    .table-container {
        max-width: 100%;
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead {
        position: sticky;
        top: 0;
        background-color: #f2f2f2;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
</style>


<script>
    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (checkbox != source) checkbox.checked = source.checked;
        });
    }
</script>