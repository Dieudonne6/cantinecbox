@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Configuration des taux horaires</h4>

    <div class="row">
        <!-- Colonnes pour les cycles -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">Cycle I JOUR</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Taux</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cycle1Jour as $classe)
                            <tr>
                                <td>{{ $classe['nom'] }}</td>
                                <td><input type="number" class="form-control form-control-sm" value="{{ $classe['taux'] }}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-danger text-white">Cycle I SOIR</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Taux</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cycle1Soir as $classe)
                            <tr>
                                <td>{{ $classe['nom'] }}</td>
                                <td><input type="number" class="form-control form-control-sm" value="{{ $classe['taux'] }}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">Cycle II JOUR</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Taux</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cycle2Jour as $classe)
                            <tr>
                                <td>{{ $classe['nom'] }}</td>
                                <td><input type="number" class="form-control form-control-sm" value="{{ $classe['taux'] }}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-danger text-white">Cycle II SOIR</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Taux</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cycle2Soir as $classe)
                            <tr>
                                <td>{{ $classe['nom'] }}</td>
                                <td><input type="number" class="form-control form-control-sm" value="{{ $classe['taux'] }}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Zone des paramètres -->
    <div class="card mt-3">
        <div class="card-body">
            <div class="mb-3">
                <label for="salaire_base" class="form-label">Salaire de base</label>
                <input type="number" id="salaire_base" class="form-control" value="10000">
            </div>

            <h6>Saisie des taux horaires</h6>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="mode_creation" value="classe" checked>
                <label class="form-check-label">Créer par classe</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="mode_creation" value="promotion">
                <label class="form-check-label">Créer par promotion</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="mode_creation" value="cycle">
                <label class="form-check-label">Créer par cycle</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="mode_creation" value="unique">
                <label class="form-check-label">Créer unique</label>
            </div>

            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Taux horaire cycle 1 (Jour)</label>
                    <input type="number" class="form-control" value="0">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Taux horaire cycle 2 (Jour)</label>
                    <input type="number" class="form-control" value="0">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Taux horaire cycle 1 (Soir)</label>
                    <input type="number" class="form-control" value="0">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Taux horaire cycle 2 (Soir)</label>
                    <input type="number" class="form-control" value="0">
                </div>
            </div>

            <button class="btn btn-primary mt-3">Lancer la mise à jour</button>
        </div>
    </div>
</div>
@endsection
