@extends('layouts.master')
@section('content')
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
            </style>
            <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <br>
            <br>
        </div>
        <div class="card-body">
            <!-- Formulaire pour le filtrage et le calcul -->
            <form action="{{ route('tableauanalytique') }}" method="POST">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Moyenne de référence</label>
                            <input type="number" name="moyenne_ref" class="form-control" value="10.00" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Période</label>
                            <select name="periode" class="form-control">
                                <option value="">Sélectionner une période</option>
                                <option value="1">1ère Période</option>
                                <option value="2">2ème Période</option>
                                <option value="3">3ème Période</option>
                                <option value="4">Annuel</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Type d'états</label>
                            <select name="typeEtat" class="form-control">
                                <option value="">Sélectionner un état</option>
                                <option value="tableau_analytique">Tableau analytique des résultats</option>
                                <option value="tableau_synoptique">Tableau synoptique des résultats</option>
                                <option value="effectifs">Tableau synoptique des effectifs</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tableau des intervalles -->
                <div class="table-responsive">
                    <table class="table table-bordered" style="max-width: 300px; margin: 0 auto;">
                        <thead>
                            <tr>
                                <th style="width: 100px; text-align: center;">Intervalle</th>
                                <th style="width: 150px; text-align: center;">Min</th>
                                <th style="width: 150px; text-align: center;">Max</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(range(1, 7) as $i)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">I{{ $i }}</td>
                                <td style="text-align: center;">
                                    <input type="number" name="intervales[I{{ $i }}][min]" class="form-control mx-auto" 
                                        value="{{ $i == 1 ? '0.00' : ($i == 2 ? '06.50' : ($i == 3 ? '07.50' : ($i == 4 ? '10.00' : ($i == 5 ? '12.00' : ($i == 6 ? '14.00' : '16.00'))))) }}">
                                </td>
                                <td style="text-align: center;">
                                    <input type="number" name="intervales[I{{ $i }}][max]" class="form-control mx-auto" 
                                        value="{{ $i == 1 ? '06.50' : ($i == 2 ? '07.50' : ($i == 3 ? '10.00' : ($i == 4 ? '12.00' : ($i == 5 ? '14.00' : ($i == 6 ? '16.00' : '20.00'))))) }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calculator"></i> Calculer
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="window.print();">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                </div>
            </form>

            <!-- Affichage du tableau des résultats si disponibles -->
            @if(isset($resultats))
            <div class="table-responsive mt-5">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 25px;">GPE</th>
                            <th style="text-align: center;">MFOG</th>
                            <th style="text-align: center;">MFOF</th>
                            <th style="text-align: center;">MFAG</th>
                            <th style="text-align: center;">MFAF</th>
                            @foreach(range(1,7) as $i)
                                <th style="text-align: center;">I{{ $i }}G</th>
                                <th style="text-align: center;">I{{ $i }}F</th>
                                <th style="text-align: center;">I{{ $i }}T</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultats as $codePromo => $stats)
                        <tr>
                            <td>{{ $codePromo }}</td>
                            <td>{{ number_format($stats['max_moyenne_garcons'], 2) }}</td>
                            <td>{{ number_format($stats['max_moyenne_filles'], 2) }}</td>
                            <td>{{ number_format($stats['min_moyenne_garcons'], 2) }}</td>
                            <td>{{ number_format($stats['min_moyenne_filles'], 2) }}</td>
                            @foreach($stats['intervales'] as $intervalle => $data)
                                <td>{{ $data['garcons'] }}</td>
                                <td>{{ $data['filles'] }}</td>
                                <td>{{ $data['total'] }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>
</div>
    
<style>
    .form-control {
        border: 1px solid #ddd;
    }
    .table input.form-control {
        width: 100%;
        max-width: 120px;
        text-align: center;
        margin: 0 auto;
    }
    .btn {
        margin-left: 10px;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        margin: 0 auto;
    }
    .table td, .table th {
        vertical-align: middle !important;
    }
    .table input.form-control {
        padding: 2px;
        font-size: 0.9em;
    }
    .table-responsive {
        overflow-x: auto;
        margin-bottom: 1rem;
    }
    .table-responsive table {
        min-width: 100%;
        white-space: nowrap;
    }
</style>
@endsection
