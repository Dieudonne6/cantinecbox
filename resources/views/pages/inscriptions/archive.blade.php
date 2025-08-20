@extends('layouts.master')

@section('content')
<style>
    .cadre-photo {
        border: 2px solid #ccc;
        padding: 10px;
        max-width: 100%;
        display: block;
        margin: 0 auto;
    }

    .conteneur {
        text-align: center;
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
        text-align: left;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #ddd;
    }

    .modal-lg {
        max-width: 90%;
    }

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
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <!-- Bouton retour -->
                <div class="mb-4">
                    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                </div>

                <!-- Titre -->
                <h4 class="card-title mb-4">Archives</h4>

                <!-- Tableau des élèves -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="myTable">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénoms</th>
                                <th>Dern. Classe</th>
                                <th>Sexe</th>
                                <th>Datenais</th>
                                <th>Lieunais</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($elevea as $eleve)
                                <tr>
                                    <td>{{ $eleve->MATRICULE }}</td>
                                    <td>{{ $eleve->NOM }}</td>
                                    <td>{{ $eleve->PRENOM }}</td>
                                    <td>{{ $eleve->CLASSESOR }}</td>
                                    <td data-sexe="{{ $eleve->SEXE }}">
                                        {{ $eleve->SEXE == 1 ? 'M' : ($eleve->SEXE == 2 ? 'F' : 'Non spécifié') }}
                                    </td>
                                    <td>
                                        {{ $eleve->DATENAIS ? \Carbon\Carbon::parse($eleve->DATENAIS)->format('d/m/Y') : '' }}
                                    </td>
                                    <td>{{ $eleve->LIEUNAIS }}</td>
                                    <td>
                                        <a href="/pagedetailarchive/{{ $eleve->MATRICULE }}"
                                           class="btn btn-primary btn-sm">Voir plus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div> <!-- .card-body -->
        </div> <!-- .card -->
    </div> <!-- .col-12 -->
</div> <!-- .row -->
</br>
</br>
@endsection
