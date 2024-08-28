@extends('layouts.master')
@section('content')
    <div class="main-panel-10">
        <div class="content-wrapper">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card mb-6">
                        <div class="card-body">
                            <h4 class="card-title">Gestion des promotions</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addPromotionModal">
                                Nouveau
                            </button>
                            <div class="row gy-3">
                                <div class="col text-end">
                                    <!-- Add Promotion Modal -->
                                    <div class="modal fade" id="addPromotionModal" tabindex="-1"
                                        aria-labelledby="addPromotionModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="addPromotionModalLabel">Fiche d'une
                                                        promotion</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    @if ($errors->any())
                                                        <div id="statusAlert" class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    <?php $error = Session::get('error'); ?>

                                                    @if (Session::has('error'))
                                                        <div id="statusAlert" class="alert alert-danger">
                                                            {{ Session::get('error') }}
                                                        </div>
                                                    @endif
                                                    <form method="POST" action="{{ route('promotions.store') }}">
                                                        @csrf
                                                        <div class="form-group row">
                                                            <div class="col-sm-6 mb-1">
                                                                <label for="codePromotion"><strong>Code promotion</strong>
                                                                    <p>(Ex: TLE4)</p>
                                                                </label>

                                                                <input type="text" id="codePromotion"
                                                                    name="codePromotion" class="form-control"
                                                                    value="{{ old('codePromotion') }}" required
                                                                    minlength="2" maxlength="4"
                                                                    pattern="^(?=.*[A-Z])[A-Z0-9]{2,4}$"
                                                                    title="Le code promo doit comporter entre 2 et 4 caractères, avec au moins une lettre majuscule et des chiffres.">
                                                            </div>


                                                            <div class="col-sm-6 mb-1">
                                                                <label for="libellePromotion"><strong>Libellé
                                                                        promotion</strong>
                                                                    <p>(Ex: Terminale)</p>
                                                                </label>
                                                                <input type="text" id="libellePromotion"
                                                                    name="libellePromotion" class="form-control" required
                                                                    minlength="2" maxlength="14"
                                                                    pattern="^(?=.*[A-Z])[A-Za-z0-9éè]{2,14}$"
                                                                    title="Le libellé doit comporter entre 2 et 14 caractères, avec au moins une lettre majuscule et peut contenir des lettres, des chiffres et des accents.">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6 mb-1">
                                                                <label for="Niveau"><strong>Niveau dans la hiérarchie
                                                                    </strong>
                                                                    <p>(Ex: 1 pour 6eme, 7 pour Tle)</p>
                                                                </label>
                                                                <input type="number" id="Niveau" name="Niveau"
                                                                    class="form-control" required min="1"
                                                                    max="7">
                                                            </div>
                                                            <div class="col-sm-6 mb-1">
                                                                <label for="enseignement"><strong>Pour quel
                                                                        Enseignement</strong>
                                                                    <p>(Choisir type d'enseignement)</p>
                                                                </label>
                                                                <select id="enseignement" name="enseignement"
                                                                    class="form-control" required>
                                                                    <option value="0">Préscolaire</option>
                                                                    <option value="1">Primaire</option>
                                                                    <option value="2">Général</option>
                                                                    <option value="3">Technique</option>
                                                                    <option value="4">Professionnel</option>
                                                                    <option value="5">Supérieur</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit"
                                                                class="btn btn-primary">Enregistrer</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Annuler</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <style>
                                        table {
                                            float: right;
                                            width: 100%;
                                            border-collapse: collapse;
                                            margin: 5px auto;
                                        }

                                        th,
                                        td {
                                            padding: 8px;
                                            text-align: center;
                                        }

                                        .modal-body label {
                                            text-align: left;
                                            display: block;
                                            margin-bottom: 0.5rem;
                                        }
                                    </style>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card p-3">
                        <div class="table-responsive" style="overflow: auto;">
                            <table class="table table-striped" style="min-width: 600px; font-size: 12px;" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Code promotion</th>
                                        <th>Libellé promotion</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($promotions as $promotion)
                                        <tr>
                                            <td>{{ $promotion->CODEPROMO }}</td>
                                            <td>{{ $promotion->LIBELPROMO }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary p-2 btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPromotionModal{{ $promotion->CODEPROMO }}">
                                                    Modifier
                                                </button>

                                                <!-- Edit Promotion Modal -->
                                                <div class="modal fade" id="editPromotionModal{{ $promotion->CODEPROMO }}"
                                                    tabindex="-1"
                                                    aria-labelledby="editPromotionModalLabel{{ $promotion->CODEPROMO }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5"
                                                                    id="editPromotionModalLabel{{ $promotion->CODEPROMO }}">
                                                                    Modifier fiche d'une promotion</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if (session('success'))
                                                                    <div class="alert alert-success">
                                                                        {{ session('success') }}
                                                                    </div>
                                                                @endif
                                                                <div class="card-body">
                                                                    <form method="POST"
                                                                        action="{{ route('promotions.update', $promotion->CODEPROMO) }}">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-6">
                                                                                <label
                                                                                    for="editCodePromotion{{ $promotion->CODEPROMO }}"><strong>Code
                                                                                        promotion</strong>
                                                                                    <p>(4 caractères max. Ex: TLE)</p>
                                                                                </label>
                                                                                <input type="text"
                                                                                    id="editCodePromotion{{ $promotion->CODEPROMO }}"
                                                                                    name="codePromotion"
                                                                                    class="form-control" required
                                                                                    minlength="2" maxlength="4"
                                                                                    pattern="^(?=.*[A-Z])[A-Z0-9]{2,4}$"
                                                                                    value="{{ $promotion->CODEPROMO }}"
                                                                                    title="Le code promo doit commencer par une lettre majuscule ou un chifrre et comporter entre 2 et 4 caractères, uniquement des lettres majuscules ou des lettres majuscules et des chiffres .">
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <label
                                                                                    for="editLibellePromotion{{ $promotion->CODEPROMO }}"><strong>Libellé
                                                                                        promotion</strong>
                                                                                    <p>(Ex: Terminale)</p>
                                                                                </label>
                                                                                <input type="text"
                                                                                    id="editLibellePromotion{{ $promotion->CODEPROMO }}"
                                                                                    name="libellePromotion"
                                                                                    class="form-control" required
                                                                                    minlength="2" maxlength="14"
                                                                                    pattern="^(?=.*[A-Z])[A-Za-z0-9éè]{2,14}$"
                                                                                    value="{{ $promotion->LIBELPROMO }}"
                                                                                    title="Le libellé doit commencer par une lettre majuscule ou un chifrre et comporter entre 2 et 14 caractères, uniquement des lettres majuscules et minuscules ou des lettres majuscules et des chiffres .">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-6">
                                                                                <label
                                                                                    for="editNiveauHierarchie{{ $promotion->CODEPROMO }}"><strong>Niveau
                                                                                        dans la hiérarchie</strong>
                                                                                    <p>(Ex: 1 pour 6eme, 7 pour Tle)</p>
                                                                                </label>
                                                                                <input type="number"
                                                                                    id="editNiveauHierarchie{{ $promotion->CODEPROMO }}"
                                                                                    name="Niveau" class="form-control"
                                                                                    value="{{ $promotion->Niveau }}"
                                                                                    required min="1"
                                                                                    max="7">


                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <label
                                                                                    for="editEnseignement{{ $promotion->CODEPROMO }}"><strong>Pour
                                                                                        quel Enseignement</strong>
                                                                                    <p>(Choisir type d'enseignement)</p>
                                                                                </label>
                                                                                <select
                                                                                    id="editEnseignement{{ $promotion->CODEPROMO }}"
                                                                                    name="enseignement"
                                                                                    class="form-control" required>
                                                                                    <option value="0"
                                                                                        {{ $promotion->TYPEENSEIG == 0 ? 'selected' : '' }}>
                                                                                        Préscolaire</option>
                                                                                    <option value="1"
                                                                                        {{ $promotion->TYPEENSEIG == 1 ? 'selected' : '' }}>
                                                                                        Primaire</option>
                                                                                    <option value="2"
                                                                                        {{ $promotion->TYPEENSEIG == 2 ? 'selected' : '' }}>
                                                                                        Général</option>
                                                                                    <option value="3"
                                                                                        {{ $promotion->TYPEENSEIG == 3 ? 'selected' : '' }}>
                                                                                        Technique</option>
                                                                                    <option value="4"
                                                                                        {{ $promotion->TYPEENSEIG == 4 ? 'selected' : '' }}>
                                                                                        Professionnel</option>
                                                                                    <option value="5"
                                                                                        {{ $promotion->TYPEENSEIG == 5 ? 'selected' : '' }}>
                                                                                        Supérieur</option>
                                                                                </select>

                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Enregistrer</button>
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Annuler</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <button type="button" class="btn btn-danger p-2 btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deletePromotionModal{{ $promotion->CODEPROMO }}">
                                                    Supprimer
                                                </button>

                                                <!-- Delete Promotion Modal -->
                                                <div class="modal fade"
                                                    id="deletePromotionModal{{ $promotion->CODEPROMO }}" tabindex="-1"
                                                    aria-labelledby="deletePromotionModalLabel{{ $promotion->CODEPROMO }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="deletePromotionModalLabel{{ $promotion->CODEPROMO }}">
                                                                    Supprimer la promotion</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Voulez-vous vraiment supprimer cette promotion?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form method="POST"
                                                                    action="{{ route('promotions.destroy', $promotion->CODEPROMO) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Supprimer</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Annuler</button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('addPromotionModal'));

            @if ($error || $errors->any())
                myModal.show();
            @endif
        });
    </script>
@endsection
