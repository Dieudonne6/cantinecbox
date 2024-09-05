@extends('layouts.master')
@section('content')
    <link href="{{ asset('css/custom-styles.css') }}" rel="stylesheet">

    <div class="container card mt-3">
        @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
        <div class="row">

            <div class="card-body">
                <h4 class="card-title">Dicipline</h4>
                <div class="row mb-3">
                    <div class="col">
                        <select class="js-example-basic-multiple w-100" id="tableSelect" onchange="displayTable()">
                            <option value="1">Fautes et sanctions</option>
                            <option value="2">Absences</option>
                        </select>
                    </div>
                    <div class="col">
                        <form method="GET" action="{{ route('discipline') }}" id="groupeForm">
                            <div class="col">
                                <select class="js-example-basic-multiple w-100" name="groupe" id="groupe"
                                    onchange="document.getElementById('groupeForm').submit();">
                                    <option value="">Sélectionner un groupe</option>
                                    @foreach ($classesGroupeclasse->unique('LibelleGroupe') as $item)
                                        <option value="{{ $item->CODECLAS }}"
                                            {{ request('groupe') == $item->CODECLAS ? 'selected' : '' }}>
                                            {{ $item->LibelleGroupe }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- <div class="row">
                    <!-- Plage Horaire Section -->
                    <div class="col-md-6" style=" padding: 10px;">
                        <div class="card">
                            <div class="card-body">
                                <h5>Plage</h5>
                                <form id="formPlage">
                                    <div style="display: inline-block;">
                                        <input type="radio" id="option1" name="choixPlage" value="matinee"
                                            onchange="updateHeures('matinee')" checked>
                                        <label for="option1">Matinée</label>
                                    </div>
                                    <div style="display: inline-block; margin-left: 10px;">
                                        <input type="radio" id="option2" name="choixPlage" value="soiree"
                                            onchange="updateHeures('soiree')">
                                        <label for="option2">Soirée</label>
                                    </div>
                                    <div style="display: inline-block; margin-left: 10px;">
                                        <input type="radio" id="option3" name="choixPlage" value="journee"
                                            onchange="updateHeures('journee')">
                                        <label for="option3">Journée</label>
                                    </div>
                                </form>
                                <form id="formPlageHoraire">
                                    <div class="form-group row mt-1 align-items-center">
                                        <label for="heureDebut" class="col-sm-3 col-form-label">Plage Horaire</label>
                                        <div class="col-sm-4">
                                            <input type="time" class="form-control" id="heureDebut">
                                        </div>
                                        <label for="heureFin" class="col-sm-1 col-form-label text-center">à</label>
                                        <div class="col-sm-4">
                                            <input type="time" class="form-control" id="heureFin">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Période Section -->
                    <div class="row col-md-6" style=" padding: 10px;">
                        <div class="card">
                            <div class="card-body">
                                <form id="formPeriode">
                                    <div style="display: inline-block;">
                                        <input type="radio" id="option4" name="choixPeriode" value="definirPeriode"
                                            onchange="togglePeriodeFields('periode')">
                                        <label for="option4">Définir une période</label>
                                    </div>
                                    <div style="display: inline-block; margin-left: 10px;">
                                        <input type="radio" id="option5" name="choixPeriode" value="trimestre"
                                            onchange="togglePeriodeFields('trimestre')">
                                        <label for="option5">Trimestre/Semestre</label>
                                    </div>
                                </form>
                                <div id="periodeFields" class="form-group row mt-1 align-items-center"
                                    style="display:none;">
                                    <label for="periodeDebut" class="col-sm-2 col-form-label">Période du</label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" id="periodeDebut">
                                    </div>
                                    <label for="periodeFin" class="col-sm-1 col-form-label text-center">au</label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" id="periodeFin">
                                    </div>
                                </div>
                                <div id="trimestreFields" class="form-group row" style="display:none;">
                                    <label for="trimestre" class="col-sm-4 col-form-label">Trimestre</label>
                                    <div class="col-sm-4">
                                        <select class="js-example-basic-multiple w-100" id="trimestre">
                                            <option value="1">1er Trimestre</option>
                                            <option value="2">2ème Trimestre</option>
                                            <option value="3">3ème Trimestre</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> --}}

                <!-- JavaScript pour gérer la dynamique -->
                <script>
                    function updateHeures(selectedPlage) {
                        let heureDebut = document.getElementById('heureDebut');
                        let heureFin = document.getElementById('heureFin');

                        switch (selectedPlage) {
                            case 'matinee':
                                heureDebut.value = "07:00";
                                heureFin.value = "12:00";
                                break;
                            case 'soiree':
                                heureDebut.value = "15:00";
                                heureFin.value = "19:00";
                                break;
                            case 'journee':
                                heureDebut.value = "07:00";
                                heureFin.value = "19:00";
                                break;
                        }
                    }

                    function togglePeriodeFields(selectedOption) {
                        if (selectedOption === 'periode') {
                            document.getElementById('periodeFields').style.display = 'flex';
                            document.getElementById('trimestreFields').style.display = 'none';
                        } else if (selectedOption === 'trimestre') {
                            document.getElementById('periodeFields').style.display = 'none';
                            document.getElementById('trimestreFields').style.display = 'flex';
                        }
                    }

                    // Activer les champs de la plage horaire par défaut lors du chargement de la page
                    document.addEventListener("DOMContentLoaded", function() {
                        updateHeures('matinee');
                    });

                    function displayTable() {
                        var selectValue = document.getElementById("tableSelect").value;

                        var fautesTable = document.getElementById("fautesTable");
                        var absencesTable = document.getElementById("absencesTable");

                        if (selectValue === "1") {
                            fautesTable.style.display = "block";
                            absencesTable.style.display = "none";
                        } else if (selectValue === "2") {
                            fautesTable.style.display = "none";
                            absencesTable.style.display = "block";
                        }
                    }

                    // Appel de la fonction lors du chargement de la page pour afficher la table correcte
                    document.addEventListener("DOMContentLoaded", function() {
                        displayTable();
                    });
                </script>

                <div class="col-md-12" style="padding: 10px; align-items: center; justify-content: space-between;">
                    <div class="card">
                        <div class="card-body">
                            <title>Disciplines</title>
                            @if (!empty($eleves))
                                <div class="table-container">
                                    <h4 class="my-4">Liste des élèves</h4>
                                    <table class="table table-striped" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>Classe</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($eleves as $eleve)
                                                <tr>
                                                    <td>{{ $eleve->NOM }}</td>
                                                    <td>{{ $eleve->PRENOM }}</td>
                                                    <td>{{ $eleve->CODECLAS }}</td>
                                                    <td>
                                                        <!-- Modal pour voir les fautes -->
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalVoirFautes-{{ $eleve->MATRICULE }}">
                                                            Voir Plus
                                                        </button>

                                                        <!-- Modal pour ajouter une faute -->
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalAjouterFaute-{{ $eleve->MATRICULE }}">
                                                            Ajouter faute
                                                        </button>

                                                        <a href="{{ route('pages.etat.imprimer_fautes', $eleve->MATRICULE) }}" class="btn btn-primary">Imprimer les fautes</a>
                                                        <a href="{{ route('pages.etat.imprimer_absences', $eleve->MATRICULE) }}" class="btn btn-primary">Imprimer les absences</a>


                                                        <!-- Original Modal Voir les fautes -->
                                                        <div class="modal fade"
                                                            id="modalVoirFautes-{{ $eleve->MATRICULE }}" tabindex="-1"
                                                            aria-labelledby="modalVoirFautesLabel-{{ $eleve->MATRICULE }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="modalVoirFautesLabel-{{ $eleve->MATRICULE }}">
                                                                            Fautes et Sanctions
                                                                        </h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Date</th>
                                                                                    <th>Faute</th>
                                                                                    <th>Sanction</th>
                                                                                    <th>Heure</th>
                                                                                    <th>Collective</th>
                                                                                    <th>Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($eleve->fautes as $faute)
                                                                                    <tr>
                                                                                        <td>{{ $faute->DATEOP }}</td>
                                                                                        <td>{{ $faute->FAUTE }}</td>
                                                                                        <td>{{ $faute->SANCTION }}</td>
                                                                                        <td>{{ $faute->NBHEURE }}</td>
                                                                                        <td>{{ $faute->COLLECTIVE ? 'Oui' : 'Non' }}
                                                                                        </td>
                                                                                        <td>
                                                                                            <!-- Button to trigger the modification modal -->
                                                                                            <button type="button"
                                                                                                class="btn btn-warning"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#modalModifierFaute-{{ $faute->MATRICULE }}">
                                                                                                Modifier
                                                                                            </button>

                                                                                            <!-- Formulaire pour la suppression -->
                                                                                            <form
                                                                                                action="{{ route('fautes.destroy', $faute->MATRICULE) }}"
                                                                                                method="POST"
                                                                                                style="display:inline;">
                                                                                                @csrf
                                                                                                @method('DELETE')
                                                                                                <button type="submit"
                                                                                                    class="btn btn-danger">Supprimer</button>
                                                                                            </form>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Modal for Modifying a Fault -->
                                                        @foreach ($eleve->fautes as $faute)
                                                            <div class="modal fade"
                                                                id="modalModifierFaute-{{ $faute->MATRICULE }}"
                                                                tabindex="-1"
                                                                aria-labelledby="modalModifierFauteLabel-{{ $faute->MATRICULE }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="modalModifierFauteLabel-{{ $faute->MATRICULE }}">
                                                                                Modifier la Faute
                                                                            </h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form
                                                                                action="{{ route('fautes.update', $faute->MATRICULE ) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <div class="row">
                                                                                    <div class="mb-3">
                                                                                        <label for="faute"
                                                                                            class="form-label">Faute</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="faute" name="FAUTE"
                                                                                            value="{{ $faute->FAUTE }}">
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="sanction"
                                                                                            class="form-label">Sanction</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="sanction" name="SANCTION"
                                                                                            value="{{ $faute->SANCTION }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="mb-3">
                                                                                        <label for="nbheure"
                                                                                            class="form-label">Heure</label>
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            id="nbheure" name="NBHEURE"
                                                                                            value="{{ $faute->NBHEURE }}">
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="collective"
                                                                                            class="form-label">Collective</label>
                                                                                        <select class="form-control"
                                                                                            id="collective"
                                                                                            name="COLLECTIVE">
                                                                                            <option value="1"
                                                                                                {{ $faute->COLLECTIVE ? 'selected' : '' }}>
                                                                                                Oui</option>
                                                                                            <option value="0"
                                                                                                {{ !$faute->COLLECTIVE ? 'selected' : '' }}>
                                                                                                Non</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <button type="submit"
                                                                                    class="btn btn-primary">Enregistrer les
                                                                                    modifications</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach


                                                        <!-- Modal Ajouter une faute -->
                                                        <div class="modal fade"
                                                            id="modalAjouterFaute-{{ $eleve->MATRICULE }}" tabindex="-1"
                                                            aria-labelledby="modalAjouterFauteLabel-{{ $eleve->MATRICULE }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="modalAjouterFauteLabel-{{ $eleve->MATRICULE }}">
                                                                            Ajouter une faute
                                                                            et sanction</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{ route('fautes.store') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="mb-3">
                                                                                        <label for="nouvelleFaute"
                                                                                            class="form-label">Nouvelle
                                                                                            faute</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="nouvelleFaute"
                                                                                            name="faute" required>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="mb-3">
                                                                                        <label for="sanctionPrev"
                                                                                            class="form-label">Sanction
                                                                                            prévue</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="sanctionPrev"
                                                                                            name="sanction" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="mb-3">
                                                                                        <label for="heure"
                                                                                            class="form-label">En
                                                                                            heure</label>
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            id="heure" name="nbheure"
                                                                                            required>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="mb-3">
                                                                                        <label for="collective"
                                                                                            class="form-label">Collective</label>
                                                                                        <select class="form-control"
                                                                                            id="collective"
                                                                                            name="collective" required>
                                                                                            <option value="1">Oui
                                                                                            </option>
                                                                                            <option value="0">Non
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <input type="hidden" name="eleve_id"
                                                                                value="{{ $eleve->MATRICULE }}">
                                                                            <button type="submit"
                                                                                class="btn btn-success">Ajouter</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>Aucun élève trouvé pour ce groupe.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style=" padding: 10px;">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-container">

                                <div id="fautesTable" style="display: none;">
                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; margin: 10px;">
                                        <h4 style="margin: 0;">Liste des fautes</h4>
                                        <a href="{{ route('pages.etat.imprimerfaute') }}" class="btn btn-primary">Imprimer les Fautes</a>

                                    </div>

                                    <table class="table table-striped" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Faute</th>
                                                <th>Sanction</th>
                                                <th>Heure</th>
                                                <th>Col</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fautes as $faute)
                                                <tr>
                                                    <td>{{ $faute->DATEOP }}</td>
                                                    <td>{{ $faute->FAUTE }}</td>
                                                    <td>{{ $faute->SANCTION }}</td>
                                                    <td>{{ $faute->NBHEURE }}</td>
                                                    <td>{{ $faute->COLLECTIVE }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div id="absencesTable" style="display: none;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin: 10px;">
                                        <h4 style="margin: 0;">Liste des absences</h4>
                                        <a href="{{ route('pages.etat.imprimerabsence') }}" class="btn btn-primary">Imprimer les Absences</a>
                                    </div>
                                    <table class="table table-striped" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Matière</th>
                                                <th>Motif</th>
                                                <th>Heure</th>
                                                <th>Absent</th>
                                                <th>Retard</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($absences as $absence)
                                                <tr>
                                                    <td>{{ $absence->DATEOP }}</td>
                                                    <td>{{ $absence->CODEMAT }}</td>
                                                    <td>{{ $absence->MOTIF }}</td>
                                                    <td>{{ $absence->HEURES }}</td>
                                                    <td>{{ $absence->ABSENT }}</td>
                                                    <td>{{ $absence->RETARD }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12" style=" padding: 10px;">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link" id="v-pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-home" type="button" role="tab"
                                aria-controls="v-pills-home" aria-selected="true">Table des
                                fautes/Sanction
                            </button>
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade" id="v-pills-home" role="tabpanel"
                                aria-labelledby="v-pills-home-tab">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Libellé faute</th>
                                            <th>Sanction prévue</th>
                                            <th>En heure</th>
                                            <th>En point</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tfautes as $tfaute)
                                            <tr>
                                                <td>{{ $tfaute->idTFautes }}</td>
                                                <td>{{ $tfaute->LibelFaute }}</td>
                                                <td>{{ $tfaute->Sanction_Indicative }}</td>
                                                <td>{{ $tfaute->Sanction_en_heure }}</td>
                                                <td>{{ $tfaute->Sanction_en_points }}</td>
                                                <td>
                                                    <!-- Bouton Modifier -->
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#editFauteModal"
                                                        data-id="{{ $tfaute->idTFautes }}"
                                                        data-libelfaute="{{ $tfaute->LibelFaute }}"
                                                        data-sanctionindicative="{{ $tfaute->Sanction_Indicative }}"
                                                        data-sanctionheure="{{ $tfaute->Sanction_en_heure }}"
                                                        data-sanctionpoints="{{ $tfaute->Sanction_en_points }}">
                                                        <i class="bi bi-pencil-fill"></i> Modifier
                                                    </button>

                                                    <!-- Bouton Supprimer -->
                                                    <form method="POST"
                                                        action="{{ route('faute.destroy', $tfaute->idTFautes) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash-fill"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </td>


                                            </tr>
                                        @endforeach
                                        <!-- Ajoutez d'autres lignes selon vos besoins -->
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3 mb-3">
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#addFauteModal">
                                        Ajouter une faute et sanction
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <br>
    <br>


    <!-- Modal pour Ajouter une Faute -->
    <div class="modal fade" id="addFauteModal" tabindex="-1" aria-labelledby="addFauteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFauteModalLabel">Ajouter une Faute et Sanction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('faute.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="LibelFaute" class="form-label">Libellé faute</label>
                            <input type="text" class="form-control" id="LibelFaute" name="LibelFaute" required>
                        </div>
                        <div class="mb-3">
                            <label for="Sanction_Indicative" class="form-label">Sanction prévue</label>
                            <input type="text" class="form-control" id="Sanction_Indicative"
                                name="Sanction_Indicative" required>
                        </div>
                        <div class="mb-3">
                            <label for="Sanction_en_heure" class="form-label">En heure</label>
                            <input type="number" class="form-control" id="Sanction_en_heure" name="Sanction_en_heure"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="Sanction_en_points" class="form-label">En point</label>
                            <input type="number" class="form-control" id="Sanction_en_points" name="Sanction_en_points"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modale de Modification -->
    <div class="modal fade" id="editFauteModal" tabindex="-1" aria-labelledby="editFauteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFauteModalLabel">Modifier Faute et Sanction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editFauteForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="idTFautes" id="edit_idTFautes">

                        <div class="mb-3">
                            <label for="edit_LibelFaute" class="form-label">Libellé faute</label>
                            <input type="text" class="form-control" id="edit_LibelFaute" name="LibelFaute">
                        </div>
                        <div class="mb-3">
                            <label for="edit_Sanction_Indicative" class="form-label">Sanction prévue</label>
                            <input type="text" class="form-control" id="edit_Sanction_Indicative"
                                name="Sanction_Indicative">
                        </div>
                        <div class="mb-3">
                            <label for="edit_Sanction_en_heure" class="form-label">En heure</label>
                            <input type="number" class="form-control" id="edit_Sanction_en_heure"
                                name="Sanction_en_heure">
                        </div>
                        <div class="mb-3">
                            <label for="edit_Sanction_en_points" class="form-label">En point</label>
                            <input type="number" class="form-control" id="edit_Sanction_en_points"
                                name="Sanction_en_points">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour Supprimer une Faute -->
    <div class="modal fade" id="deleteFauteModal" tabindex="-1" aria-labelledby="deleteFauteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFauteModalLabel">Supprimer la Faute et Sanction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="delete_idTFautes" name="idTFautes">
                    <p>Êtes-vous sûr de vouloir supprimer cette faute et sanction ?</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editFauteModal = document.getElementById('editFauteModal');
            editFauteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var idTFautes = button.getAttribute('data-id');
                var LibelFaute = button.getAttribute('data-libelfaute');
                var Sanction_Indicative = button.getAttribute('data-sanctionindicative');
                var Sanction_en_heure = button.getAttribute('data-sanctionheure');
                var Sanction_en_points = button.getAttribute('data-sanctionpoints');

                var form = document.getElementById('editFauteForm');
                form.action = "{{ route('faute.update', '') }}/" + idTFautes;

                document.getElementById('edit_idTFautes').value = idTFautes;
                document.getElementById('edit_LibelFaute').value = LibelFaute;
                document.getElementById('edit_Sanction_Indicative').value = Sanction_Indicative;
                document.getElementById('edit_Sanction_en_heure').value = Sanction_en_heure;
                document.getElementById('edit_Sanction_en_points').value = Sanction_en_points;
            });
        });
        // Remplir la modale de suppression avec l'ID de la faute
        var deleteFauteModal = document.getElementById('deleteFauteModal');
        deleteFauteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var idTFautes = button.getAttribute('data-id');

            var form = document.getElementById('deleteFauteForm');
            form.action = form.action.replace(/\/\d+$/, '/' + idTFautes);

            document.getElementById('delete_idTFautes').value = idTFautes;
        });
    </script>

@endsection
