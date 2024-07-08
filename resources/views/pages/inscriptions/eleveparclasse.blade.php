@extends('layouts.master')
@section('content')

<div class="main-panel-10">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Liste des élèves par classe</h4>
                <div class="row mb-3">
                    <div class="col-3">
                        <select class="form-control w-100" name="classe">
                            <option value="">Sélectionnez une classe</option>
                            @foreach(['CE1A', 'CE1B', 'CE1C', 'CE1S', 'CE2A', 'CE2B', 'CE2C', 'CE2S', 'CIA', 'CIB', 'CIC', 'CIS', 'CM1A', 'CM1B', 'CM1C', 'CM1S', 'CM2A', 'CM2B', 'CM2C', 'CM2S', 'CPA', 'CPB', 'CPC', 'CPS', 'DELETE', 'MAT1', 'MAT2', 'MAT2II', 'MAT3', 'MAT3II', 'NON', 'PREMATER'] as $classe)
                                <option value="{{ $classe }}">{{ $classe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="titreEtat" class="col-sm-4 col-form-label">Titre de l'Etat</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="titreEtat" placeholder=" " />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-4">
                        <button class="btn btn-primary w-100" onclick="printWithPhoto()">Imprimer avec photo</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-primary w-100" onclick="printWithoutPhoto()">Imprimer sans photo</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-primary w-100" onclick="printMaquettesDroits()">Maquettes droits</button>
                    </div>
                </div>

                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f8f9fa;
                    }

                    .table-container {
                        width: 100%;
                        overflow-x: auto;
                        margin: 20px auto;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        min-width: 800px;
                        background-color: #fff;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }

                    th, td {
                        padding: 10px 15px;
                        border: 1px solid #ddd;
                        text-align: left;
                    }

                    th {
                        background-color: #f4f4f4;
                        font-weight: bold;
                    }

                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }

                    .photo {
                        width: 50px;
                        height: auto;
                        border-radius: 50%;
                    }

                    @media (max-width: 768px) {
                        th, td {
                            padding: 8px 10px;
                        }

                        .photo {
                            width: 40px;
                        }
                    }

                    @media (max-width: 480px) {
                        table {
                            display: block;
                            overflow-x: auto;
                        }

                        th, td {
                            font-size: 12px;
                            padding: 5px;
                        }

                        .photo {
                            width: 30px;
                        }
                    }
                </style>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Photo</th>
                                <th>Date de Naissance</th>
                                <th>Lieu de Naissance</th>
                                <th>Sexe</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>001</td>
                                <td>Dupont</td>
                                <td>Jean</td>
                                <td><img src="photo1.jpg" alt="Photo de Jean Dupont" class="photo"></td>
                                <td>01/01/1990</td>
                                <td>Paris</td>
                                <td>M</td>
                                <td>Actif</td>
                            </tr>
                            <tr>
                                <td>002</td>
                                <td>Durand</td>
                                <td>Marie</td>
                                <td><img src="photo2.jpg" alt="Photo de Marie Durand" class="photo"></td>
                                <td>15/05/1985</td>
                                <td>Lyon</td>
                                <td>F</td>
                                <td>Inactif</td>
                            </tr>
                            <!-- Ajouter d'autres lignes ici -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printWithPhoto() {
        // Logique pour imprimer avec photo
        console.log("Imprimer avec photo");
    }

    function printWithoutPhoto() {
        // Logique pour imprimer sans photo
        console.log("Imprimer sans photo");
    }

    function printMaquettesDroits() {
        // Logique pour imprimer les maquettes droits
        console.log("Imprimer maquettes droits");
    }
</script>

@endsection
