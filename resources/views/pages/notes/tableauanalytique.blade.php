@extends('layouts.master')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card" id="original">
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

                    @media print {
                        .no-print {
                            display: none !important;
                        }
                        .print-only {
                            display: block !important;
                        }
                        .table-print {
                            width: 100% !important;
                            margin: 0 !important;
                            padding: 0 !important;
                        }
                        .table-print table {
                            width: 100% !important;
                            border-collapse: collapse !important;
                        }
                        .table-print th,
                        .table-print td {
                            border: 1px solid #000 !important;
                            padding: 8px !important;
                            text-align: center !important;
                            font-size: 12px !important;
                        }
                        .table-print thead {
                            background-color: #f2f2f2 !important;
                        }
                        .table-print .font-weight-bold {
                            font-weight: bold !important;
                        }
                        .table-print .text-center {
                            text-align: center !important;
                        }
                        .table-print h4 {
                            text-align: center !important;
                            margin-bottom: 20px !important;
                            font-weight: bold !important;
                        }
                    }
                </style>
                <button type="button" class="btn btn-arrow no-print" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br><br>
            </div>

            <div class="card-body">
                <!-- Formulaire pour le filtrage et le calcul -->
                <form action="{{ route('tableauanalytique') }}" method="POST" id="etatForm" class="no-print">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Moyenne de référence</label>
                                <input type="number" name="moyenne_ref" class="form-control"
                                    value="{{ old('moyenne_ref', '10.00') }}" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Période</label>
                                <select name="periode" class="form-control" required>
                                    <option value="">Sélectionner une période</option>
                                    <option value="1" {{ old('periode') == '1' ? 'selected' : '' }}>1ère Période</option>
                                    <option value="2" {{ old('periode') == '2' ? 'selected' : '' }}>2ème Période</option>
                                    <option value="3" {{ old('periode') == '3' ? 'selected' : '' }}>3ème Période</option>
                                    <option value="4" {{ old('periode') == '4' ? 'selected' : '' }}>Annuel</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type d'états</label>
                                <select name="typeEtat" class="form-control" id="typeEtat" required>
                                    <option value="">Sélectionner un état</option>
                                    <option value="tableau_analytique" {{ old('typeEtat') == 'tableau_analytique' ? 'selected' : '' }}>Tableau analytique des résultats</option>
                                    <option value="tableau_synoptique" {{ old('typeEtat') == 'tableau_synoptique' ? 'selected' : '' }}>Tableau synoptique des résultats</option>
                                    <option value="effectifs" {{ old('typeEtat') == 'effectifs' ? 'selected' : '' }}>Tableau synoptique des effectifs</option>
                                    <option value="statistique" {{ old('typeEtat') == 'statistique' ? 'selected' : '' }}>Statistique des résultats</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des intervalles -->
                    <div class="table-responsive">
                        <table class="table table-bordered" style="max-width: 400px; margin: 0 auto;">
                            <thead>
                                <tr>
                                    <th style="width: 100px; text-align: center;">Intervalle</th>
                                    <th style="width: 150px; text-align: center;">Min</th>
                                    <th style="width: 150px; text-align: center;">Max</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (range(1, 7) as $i)
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

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calculator"></i> Calculer
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="printTable()">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                    </div>
                </form>

                <!-- Affichage conditionnel des tableaux -->
                @if (isset($resultats) && isset($typeEtat))
                    <div id="printableContent">
                        @if ($typeEtat == 'tableau_analytique')
                            <div class="table-responsive mt-5">
                                <h4 class="text-center mb-4">Tableau Analytique des Résultats</h4>
                                <table class="table table-bordered table-screen">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="text-align: center; width: 25px;">GPE</th>
                                            <th style="text-align: center;">MFOG</th>
                                            <th style="text-align: center;">MFOF</th>
                                            <th style="text-align: center;">MFAG</th>
                                            <th style="text-align: center;">MFAF</th>
                                            @foreach (range(1, 7) as $i)
                                                <th style="text-align: center;">I{{ $i }}G</th>
                                                <th style="text-align: center;">I{{ $i }}F</th>
                                                <th style="text-align: center;">I{{ $i }}T</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $ordreDesClasses = [
                                                '6è',
                                                '5è',
                                                '4è',
                                                '3è',
                                                'CYCLE I',
                                                '2ndA1',
                                                '2ndA2',
                                                '2ndB',
                                                '2ndC',
                                                '2ndD',
                                                'SECONDES',
                                                '1èreA1',
                                                '1èreA2',
                                                '1èreB',
                                                '1èreC',
                                                '1èreD',
                                                'PREMIÈRE',
                                                'TleA1',
                                                'TleA2',
                                                'TleB',
                                                'TleC',
                                                'TleD',
                                                'TERMINALE',
                                                'CYCLE II',
                                                'ETABLISSEMENT',
                                            ];
                                        @endphp
                                        @foreach ($ordreDesClasses as $classe)
                                            @if (isset($resultats[$classe]))
                                                <tr>
                                                    <td class="font-weight-bold">{{ $classe }}</td>
                                                    <td>{{ number_format($resultats[$classe]['max_moyenne_garcons'], 2) }}</td>
                                                    <td>{{ number_format($resultats[$classe]['max_moyenne_filles'], 2) }}</td>
                                                    <td>{{ number_format($resultats[$classe]['min_moyenne_garcons'], 2) }}</td>
                                                    <td>{{ number_format($resultats[$classe]['min_moyenne_filles'], 2) }}</td>
                                                    @foreach ($resultats[$classe]['intervales'] as $intervalle => $data)
                                                        <td>{{ $data['garcons'] }}</td>
                                                        <td>{{ $data['filles'] }}</td>
                                                        <td class="font-weight-bold">{{ $data['total'] }}</td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif($typeEtat == 'tableau_synoptique')
                            <div class="table-responsive mt-5">
                                <h4 class="text-center mb-4">Tableau Synoptique des Résultats</h4>
                                <style>
                                    .ligne-bilan {
                                        background-color: #f2f2f2;
                                        font-weight: bold;
                                    }

                                    .table-synoptique {
                                        border-collapse: collapse;
                                        width: 100%;
                                        margin-bottom: 1rem;
                                    }

                                    .table-synoptique th {
                                        background-color: #343a40;
                                        color: white;
                                        text-align: center;
                                        vertical-align: middle;
                                        padding: 12px 8px;
                                        border: 1px solid #dee2e6;
                                        font-weight: 600;
                                    }

                                    .table-synoptique td {
                                        text-align: center;
                                        vertical-align: middle;
                                        padding: 10px 8px;
                                        border: 1px solid #dee2e6;
                                    }

                                    .table-synoptique .font-weight-bold {
                                        font-weight: bold;
                                    }

                                    .table-synoptique .text-center {
                                        text-align: center;
                                    }

                                    .table-synoptique .col-gpe {
                                        text-align: left !important;
                                        padding-left: 15px;
                                        min-width: 100px;
                                    }

                                    .table-synoptique tr:hover {
                                        background-color: rgba(0, 0, 0, .03);
                                    }

                                    .table-synoptique .ligne-bilan:hover {
                                        background-color: #e9ecef;
                                    }

                                    .table-synoptique .total-cell {
                                        background-color: #f8f9fa;
                                        font-weight: bold;
                                    }
                                </style>
                                <table class="table table-bordered table-synoptique">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;">GPE</th>
                                            <th>NBGPE</th>
                                            @foreach (range(1, 4) as $i)
                                                <th>I{{ $i }}G</th>
                                                <th>I{{ $i }}F</th>
                                                <th>I{{ $i }}T</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $ordreDesGroupes = [
                                                '6è',
                                                '5è',
                                                '4è',
                                                '3è',
                                                'CYCLE I',
                                                '2ndA1',
                                                '2ndA2',
                                                '2ndB',
                                                '2ndC',
                                                '2ndD',
                                                'SECONDES',
                                                '1èreA1',
                                                '1èreA2',
                                                '1èreB',
                                                '1èreC',
                                                '1èreD',
                                                'PREMIÈRE',
                                                'TleA1',
                                                'TleA2',
                                                'TleB',
                                                'TleC',
                                                'TleD',
                                                'TERMINALE',
                                                'CYCLE II',
                                                'ETABLISSEMENT',
                                            ];
                                        @endphp
                                        @foreach ($ordreDesGroupes as $codePromo)
                                            @if (isset($resultats[$codePromo]))
                                                <tr @if (in_array($codePromo, ['CYCLE I', 'SECONDES', 'PREMIÈRE', 'TERMINALE', 'CYCLE II', 'ETABLISSEMENT'])) class="ligne-bilan" @endif>
                                                    <td class="font-weight-bold col-gpe">{{ $codePromo }}</td>
                                                    <td>{{ $resultats[$codePromo]['nbClasses'] }}</td>
                                                    @foreach (range(1, 4) as $i)
                                                        <td>{{ $resultats[$codePromo]['intervales']['I' . $i]['garcons'] ?? 0 }}
                                                        </td>
                                                        <td>{{ $resultats[$codePromo]['intervales']['I' . $i]['filles'] ?? 0 }}
                                                        </td>
                                                        <td class="total-cell">
                                                            {{ $resultats[$codePromo]['intervales']['I' . $i]['total'] ?? 0 }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif($typeEtat == 'effectifs')
                            <div class="table-responsive mt-5">
                                <h4 class="text-center mb-4">Tableau Synoptique des Effectifs</h4>
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="text-align: center; width: 25px;">GPE</th>
                                            <th style="text-align: center;">MFOG</th>
                                            <th style="text-align: center;">MFOF</th>
                                            <th style="text-align: center;">MFAG</th>
                                            <th style="text-align: center;">MFAF</th>
                                            <th style="text-align: center;">Total Élèves</th>
                                            <th style="text-align: center;">Total Garçons</th>
                                            <th style="text-align: center;">Total Filles</th>
                                            <th style="text-align: center;">I1G</th>
                                            <th style="text-align: center;">I1F</th>
                                            <th style="text-align: center;">I1T</th>
                                            <th style="text-align: center;">I2G</th>
                                            <th style="text-align: center;">I2F</th>
                                            <th style="text-align: center;">I2T</th>
                                            <th style="text-align: center;">I3G</th>
                                            <th style="text-align: center;">I3F</th>
                                            <th style="text-align: center;">I3T</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resultats as $codePromo => $stats)
                                            <tr>
                                                <td class="font-weight-bold">{{ $codePromo }}</td>
                                                <td class="text-center">
                                                    {{ number_format($stats['max_moyenne_garcons'] ?? 0, 2) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($stats['max_moyenne_filles'] ?? 0, 2) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($stats['min_moyenne_garcons'] ?? 0, 2) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($stats['min_moyenne_filles'] ?? 0, 2) }}</td>
                                                <td class="text-center">{{ $stats['total_eleves'] ?? 0 }}</td>
                                                <td class="text-center">{{ $stats['total_garcons'] ?? 0 }}</td>
                                                <td class="text-center">{{ $stats['total_filles'] ?? 0 }}</td>
                                                @foreach (['I1', 'I2', 'I3'] as $interval)
                                                    <td class="text-center">
                                                        {{ $stats['effectifs_statut'][$interval]['garcons'] ?? 0 }}</td>
                                                    <td class="text-center">
                                                        {{ $stats['effectifs_statut'][$interval]['filles'] ?? 0 }}</td>
                                                    <td class="text-center font-weight-bold">
                                                        {{ ($stats['effectifs_statut'][$interval]['garcons'] ?? 0) + ($stats['effectifs_statut'][$interval]['filles'] ?? 0) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif($typeEtat == 'statistique')
                            <div class="table-responsive mt-5">
                                <h4 class="text-center mb-4">Statistique des Résultats</h4>
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th rowspan="2" style="text-align: center; vertical-align: middle;">Classe</th>
                                            <th rowspan="2" style="text-align: center; vertical-align: middle;">Effectif
                                            </th>
                                            <th colspan="7" style="text-align: center;">Répartition des moyennes</th>
                                            <th colspan="2" style="text-align: center;">Meilleur élève</th>
                                            <th colspan="2" style="text-align: center;">Plus faible élève</th>
                                            <th rowspan="2" style="text-align: center; vertical-align: middle;">Abandons
                                            </th>
                                            <th rowspan="2" style="text-align: center; vertical-align: middle;">Taux de
                                                réussite</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;">[0-6.5[</th>
                                            <th style="text-align: center;">[6.5-10[</th>
                                            <th style="text-align: center;">[10-12[</th>
                                            <th style="text-align: center;">[12-14[</th>
                                            <th style="text-align: center;">[14-16[</th>
                                            <th style="text-align: center;">[16-18[</th>
                                            <th style="text-align: center;">[18-20]</th>
                                            <th style="text-align: center;">Nom</th>
                                            <th style="text-align: center;">Moyenne</th>
                                            <th style="text-align: center;">Nom</th>
                                            <th style="text-align: center;">Moyenne</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Récupérer les classes triées par CODEPROMO, SERIE et CYCLE
                                            $classes = \App\Models\Classes::join('promo', 'classes.CODEPROMO', '=', 'promo.CODEPROMO')
                                                ->join('series', 'classes.SERIE', '=', 'series.SERIE')
                                                ->orderBy('promo.Niveau', 'asc')
                                                ->orderBy('series.CYCLE', 'asc')
                                                ->orderBy('classes.SERIE', 'asc')
                                                ->orderBy('classes.LIBELCLAS', 'asc')
                                                ->select('classes.*', 'promo.LIBELPROMO', 'series.LIBELSERIE', 'series.CYCLE')
                                                ->get();
                                            
                                            $seuilReussite = 10; // Seuil de réussite
                                            
                                            // Récupérer les intervalles du formulaire
                                            $intervalles = [];
                                            foreach (range(1, 7) as $i) {
                                                $min = request("intervales.I{$i}.min", 0);
                                                $max = request("intervales.I{$i}.max", 20);
                                                $intervalles["I{$i}"] = [
                                                    'min' => $min,
                                                    'max' => $max,
                                                    'count' => 0
                                                ];
                                            }
                                            
                                            // Initialiser les totaux pour l'établissement
                                            $totalEtablissement = [
                                                'effectif' => 0,
                                                'intervalles' => array_fill_keys(array_keys($intervalles), 0),
                                                'abandons' => 0,
                                                'reussite' => 0,
                                                'meilleur_eleve' => null,
                                                'plus_faible_eleve' => null
                                            ];
                                            
                                            // Variables pour suivre les changements de niveau/série/cycle
                                            $currentPromo = null;
                                            $currentCycle = null;
                                            
                                            // Tableaux pour stocker les totaux par niveau/série/cycle
                                            $totauxPromo = [];
                                            $totauxCycle = [];
                                        @endphp
                                        
                                        @foreach ($classes as $classe)
                                            @php
                                                // Afficher le bilan du niveau si changement de niveau
                                                if ($currentPromo !== null && $currentPromo !== $classe->CODEPROMO) {
                                                    $promoTotal = $totauxPromo[$currentPromo];
                                                    $effectifActifPromo = $promoTotal['effectif'] - $promoTotal['abandons'];
                                                    $tauxReussitePromo = $effectifActifPromo > 0 ? ($promoTotal['reussite'] / $effectifActifPromo) * 100 : 0;
                                            @endphp
                                                    <tr class="table-success">
                                                        <td class="font-weight-bold">{{ $promoTotal['libelle'] }}</td>
                                                        <td class="text-center">{{ $promoTotal['effectif'] }}</td>
                                                        @foreach ($promoTotal['intervalles'] as $intervalle => $nombre)
                                                            <td class="text-center">
                                                                {{ $nombre }}
                                                            </td>
                                                        @endforeach
                                                        <td>{{ $promoTotal['meilleur_eleve']['nom'] ?? '-' }}</td>
                                                        <td class="text-center">
                                                            {{ $promoTotal['meilleur_eleve'] ? number_format($promoTotal['meilleur_eleve']['moyenne'], 2) : '-' }}
                                                        </td>
                                                        <td>{{ $promoTotal['plus_faible_eleve']['nom'] ?? '-' }}</td>
                                                        <td class="text-center">
                                                            {{ $promoTotal['plus_faible_eleve'] ? number_format($promoTotal['plus_faible_eleve']['moyenne'], 2) : '-' }}
                                                        </td>
                                                        <td class="text-center">{{ $promoTotal['abandons'] }}</td>
                                                        <td class="text-center">{{ number_format($tauxReussitePromo, 2) }}%</td>
                                                    </tr>
                                            @php
                                                }
                                                
                                                // Afficher le bilan du cycle si changement de cycle
                                                if ($currentCycle !== null && $currentCycle !== $classe->CYCLE) {
                                                    $cycleTotal = $totauxCycle[$currentCycle];
                                                    $effectifActifCycle = $cycleTotal['effectif'] - $cycleTotal['abandons'];
                                                    $tauxReussiteCycle = $effectifActifCycle > 0 ? ($cycleTotal['reussite'] / $effectifActifCycle) * 100 : 0;
                                            @endphp
                                                    <tr class="table-info">
                                                        <td class="font-weight-bold">{{ $cycleTotal['libelle'] }}</td>
                                                        <td class="text-center">{{ $cycleTotal['effectif'] }}</td>
                                                        @foreach ($cycleTotal['intervalles'] as $intervalle => $nombre)
                                                            <td class="text-center">
                                                                {{ $nombre }}
                                                            </td>
                                                        @endforeach
                                                        <td>{{ $cycleTotal['meilleur_eleve']['nom'] ?? '-' }}</td>
                                                        <td class="text-center">
                                                            {{ $cycleTotal['meilleur_eleve'] ? number_format($cycleTotal['meilleur_eleve']['moyenne'], 2) : '-' }}
                                                        </td>
                                                        <td>{{ $cycleTotal['plus_faible_eleve']['nom'] ?? '-' }}</td>
                                                        <td class="text-center">
                                                            {{ $cycleTotal['plus_faible_eleve'] ? number_format($cycleTotal['plus_faible_eleve']['moyenne'], 2) : '-' }}
                                                        </td>
                                                        <td class="text-center">{{ $cycleTotal['abandons'] }}</td>
                                                        <td class="text-center">{{ number_format($tauxReussiteCycle, 2) }}%</td>
                                                    </tr>
                                            @php
                                                }
                                                
                                                // Récupérer tous les élèves de la classe
                                                $eleves = \App\Models\Eleve::where('CODECLAS', $classe->CODECLAS)->get();
                                                $effectifTotal = $eleves->count();
                                                
                                                // Initialiser les compteurs pour chaque intervalle
                                                $compteursIntervalles = array_fill_keys(array_keys($intervalles), 0);
                                                
                                                $meilleurEleve = null;
                                                $plusFaibleEleve = null;
                                                $abandons = 0;
                                                $reussite = 0;
                                                
                                                foreach ($eleves as $eleve) {
                                                    if ($eleve->STATUT == 0) {
                                                        $abandons++;
                                                        continue;
                                                    }
                                                    
                                                    $moyenne = \App\Models\Notes::where('MATRICULE', $eleve->MATRICULE)
                                                        ->where('SEMESTRE', request('periode', 1))
                                                        ->avg('MS');
                                                    
                                                    if ($moyenne !== null && $moyenne < 21) { // Exclure les moyennes de 21
                                                        // Classer la moyenne dans l'intervalle approprié
                                                        foreach ($intervalles as $key => $intervalle) {
                                                            if ($moyenne >= $intervalle['min'] && $moyenne < $intervalle['max']) {
                                                                $compteursIntervalles[$key]++;
                                                                break;
                                                            }
                                                        }
                                                        
                                                        if ($meilleurEleve === null || $moyenne > $meilleurEleve['moyenne']) {
                                                            $meilleurEleve = [
                                                                'nom' => $eleve->NOM . ' ' . $eleve->PRENOM,
                                                                'moyenne' => $moyenne
                                                            ];
                                                        }
                                                        if ($plusFaibleEleve === null || $moyenne < $plusFaibleEleve['moyenne']) {
                                                            $plusFaibleEleve = [
                                                                'nom' => $eleve->NOM . ' ' . $eleve->PRENOM,
                                                                'moyenne' => $moyenne
                                                            ];
                                                        }
                                                        
                                                        if ($moyenne >= $seuilReussite) {
                                                            $reussite++;
                                                        }
                                                    }
                                                }
                                                
                                                $effectifActif = $effectifTotal - $abandons;
                                                $tauxReussite = $effectifActif > 0 ? ($reussite / $effectifActif) * 100 : 0;
                                                
                                                // Mettre à jour les totaux
                                                $totalEtablissement['effectif'] += $effectifTotal;
                                                $totalEtablissement['abandons'] += $abandons;
                                                $totalEtablissement['reussite'] += $reussite;
                                                foreach ($compteursIntervalles as $key => $value) {
                                                    $totalEtablissement['intervalles'][$key] += $value;
                                                }
                                                
                                                // Mettre à jour les totaux par niveau
                                                if (!isset($totauxPromo[$classe->CODEPROMO])) {
                                                    $totauxPromo[$classe->CODEPROMO] = [
                                                        'libelle' => $classe->LIBELPROMO,
                                                        'effectif' => 0,
                                                        'intervalles' => array_fill_keys(array_keys($intervalles), 0),
                                                        'abandons' => 0,
                                                        'reussite' => 0,
                                                        'meilleur_eleve' => null,
                                                        'plus_faible_eleve' => null
                                                    ];
                                                }
                                                $totauxPromo[$classe->CODEPROMO]['effectif'] += $effectifTotal;
                                                $totauxPromo[$classe->CODEPROMO]['abandons'] += $abandons;
                                                $totauxPromo[$classe->CODEPROMO]['reussite'] += $reussite;
                                                foreach ($compteursIntervalles as $key => $value) {
                                                    $totauxPromo[$classe->CODEPROMO]['intervalles'][$key] += $value;
                                                }
                                                
                                                // Mettre à jour les totaux par cycle
                                                if (!isset($totauxCycle[$classe->CYCLE])) {
                                                    $totauxCycle[$classe->CYCLE] = [
                                                        'libelle' => 'CYCLE ' . $classe->CYCLE,
                                                        'effectif' => 0,
                                                        'intervalles' => array_fill_keys(array_keys($intervalles), 0),
                                                        'abandons' => 0,
                                                        'reussite' => 0,
                                                        'meilleur_eleve' => null,
                                                        'plus_faible_eleve' => null
                                                    ];
                                                }
                                                $totauxCycle[$classe->CYCLE]['effectif'] += $effectifTotal;
                                                $totauxCycle[$classe->CYCLE]['abandons'] += $abandons;
                                                $totauxCycle[$classe->CYCLE]['reussite'] += $reussite;
                                                foreach ($compteursIntervalles as $key => $value) {
                                                    $totauxCycle[$classe->CYCLE]['intervalles'][$key] += $value;
                                                }
                                                
                                                // Mettre à jour le meilleur et plus faible élève du niveau
                                                if ($meilleurEleve !== null) {
                                                    if ($totauxPromo[$classe->CODEPROMO]['meilleur_eleve'] === null || 
                                                        $meilleurEleve['moyenne'] > $totauxPromo[$classe->CODEPROMO]['meilleur_eleve']['moyenne']) {
                                                        $totauxPromo[$classe->CODEPROMO]['meilleur_eleve'] = $meilleurEleve;
                                                    }
                                                }
                                                if ($plusFaibleEleve !== null) {
                                                    if ($totauxPromo[$classe->CODEPROMO]['plus_faible_eleve'] === null || 
                                                        $plusFaibleEleve['moyenne'] < $totauxPromo[$classe->CODEPROMO]['plus_faible_eleve']['moyenne']) {
                                                        $totauxPromo[$classe->CODEPROMO]['plus_faible_eleve'] = $plusFaibleEleve;
                                                    }
                                                }
                                                
                                                // Mettre à jour le meilleur et plus faible élève du cycle
                                                if ($meilleurEleve !== null) {
                                                    if ($totauxCycle[$classe->CYCLE]['meilleur_eleve'] === null || 
                                                        $meilleurEleve['moyenne'] > $totauxCycle[$classe->CYCLE]['meilleur_eleve']['moyenne']) {
                                                        $totauxCycle[$classe->CYCLE]['meilleur_eleve'] = $meilleurEleve;
                                                    }
                                                }
                                                if ($plusFaibleEleve !== null) {
                                                    if ($totauxCycle[$classe->CYCLE]['plus_faible_eleve'] === null || 
                                                        $plusFaibleEleve['moyenne'] < $totauxCycle[$classe->CYCLE]['plus_faible_eleve']['moyenne']) {
                                                        $totauxCycle[$classe->CYCLE]['plus_faible_eleve'] = $plusFaibleEleve;
                                                    }
                                                }
                                                
                                                // Mettre à jour le meilleur et plus faible élève de l'établissement
                                                if ($meilleurEleve !== null) {
                                                    if ($totalEtablissement['meilleur_eleve'] === null || 
                                                        $meilleurEleve['moyenne'] > $totalEtablissement['meilleur_eleve']['moyenne']) {
                                                        $totalEtablissement['meilleur_eleve'] = $meilleurEleve;
                                                    }
                                                }
                                                if ($plusFaibleEleve !== null) {
                                                    if ($totalEtablissement['plus_faible_eleve'] === null || 
                                                        $plusFaibleEleve['moyenne'] < $totalEtablissement['plus_faible_eleve']['moyenne']) {
                                                        $totalEtablissement['plus_faible_eleve'] = $plusFaibleEleve;
                                                    }
                                                }
                                                
                                                // Mettre à jour les variables de suivi
                                                $currentPromo = $classe->CODEPROMO;
                                                $currentCycle = $classe->CYCLE;
                                            @endphp
                                            
                                            <tr>
                                                <td class="font-weight-bold">{{ $classe->LIBELCLAS }}</td>
                                                <td class="text-center">{{ $effectifTotal }}</td>
                                                @foreach ($compteursIntervalles as $intervalle => $nombre)
                                                    <td class="text-center">
                                                        {{ $nombre }}
                                                    </td>
                                                @endforeach
                                                <td>{{ $meilleurEleve['nom'] ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ $meilleurEleve ? number_format($meilleurEleve['moyenne'], 2) : '-' }}
                                                </td>
                                                <td>{{ $plusFaibleEleve['nom'] ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ $plusFaibleEleve ? number_format($plusFaibleEleve['moyenne'], 2) : '-' }}
                                                </td>
                                                <td class="text-center">{{ $abandons }}</td>
                                                <td class="text-center">{{ number_format($tauxReussite, 2) }}%</td>
                                            </tr>
                                        @endforeach
                                        
                                        @php
                                            // Afficher le dernier bilan de niveau
                                            if ($currentPromo !== null) {
                                                $promoTotal = $totauxPromo[$currentPromo];
                                                $effectifActifPromo = $promoTotal['effectif'] - $promoTotal['abandons'];
                                                $tauxReussitePromo = $effectifActifPromo > 0 ? ($promoTotal['reussite'] / $effectifActifPromo) * 100 : 0;
                                        @endphp
                                                <tr class="table-success">
                                                    <td class="font-weight-bold">{{ $promoTotal['libelle'] }}</td>
                                                    <td class="text-center">{{ $promoTotal['effectif'] }}</td>
                                                    @foreach ($promoTotal['intervalles'] as $nombre)
                                                        <td class="text-center">
                                                            {{ $nombre }}
                                                        </td>
                                                    @endforeach
                                                    <td>{{ $promoTotal['meilleur_eleve']['nom'] ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ $promoTotal['meilleur_eleve'] ? number_format($promoTotal['meilleur_eleve']['moyenne'], 2) : '-' }}
                                                    </td>
                                                    <td>{{ $promoTotal['plus_faible_eleve']['nom'] ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ $promoTotal['plus_faible_eleve'] ? number_format($promoTotal['plus_faible_eleve']['moyenne'], 2) : '-' }}
                                                    </td>
                                                    <td class="text-center">{{ $promoTotal['abandons'] }}</td>
                                                    <td class="text-center">{{ number_format($tauxReussitePromo, 2) }}%</td>
                                                </tr>
                                        @php
                                            }
                                            
                                            // Afficher le dernier bilan de cycle
                                            if ($currentCycle !== null) {
                                                $cycleTotal = $totauxCycle[$currentCycle];
                                                $effectifActifCycle = $cycleTotal['effectif'] - $cycleTotal['abandons'];
                                                $tauxReussiteCycle = $effectifActifCycle > 0 ? ($cycleTotal['reussite'] / $effectifActifCycle) * 100 : 0;
                                        @endphp
                                                <tr class="table-info">
                                                    <td class="font-weight-bold">{{ $cycleTotal['libelle'] }}</td>
                                                    <td class="text-center">{{ $cycleTotal['effectif'] }}</td>
                                                    @foreach ($cycleTotal['intervalles'] as $nombre)
                                                        <td class="text-center">
                                                            {{ $nombre }}
                                                        </td>
                                                    @endforeach
                                                    <td>{{ $cycleTotal['meilleur_eleve']['nom'] ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ $cycleTotal['meilleur_eleve'] ? number_format($cycleTotal['meilleur_eleve']['moyenne'], 2) : '-' }}
                                                    </td>
                                                    <td>{{ $cycleTotal['plus_faible_eleve']['nom'] ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ $cycleTotal['plus_faible_eleve'] ? number_format($cycleTotal['plus_faible_eleve']['moyenne'], 2) : '-' }}
                                                    </td>
                                                    <td class="text-center">{{ $cycleTotal['abandons'] }}</td>
                                                    <td class="text-center">{{ number_format($tauxReussiteCycle, 2) }}%</td>
                                                </tr>
                                        @php
                                            }
                                            
                                            // Afficher le bilan total de l'établissement
                                            $effectifActifTotal = $totalEtablissement['effectif'] - $totalEtablissement['abandons'];
                                            $tauxReussiteTotal = $effectifActifTotal > 0 ? ($totalEtablissement['reussite'] / $effectifActifTotal) * 100 : 0;
                                        @endphp
                                        <tr class="table-primary">
                                            <td class="font-weight-bold">TOTAL ÉTABLISSEMENT</td>
                                            <td class="text-center">{{ $totalEtablissement['effectif'] }}</td>
                                            @foreach ($totalEtablissement['intervalles'] as $nombre)
                                                <td class="text-center">
                                                    {{ $nombre }}
                                                </td>
                                            @endforeach
                                            <td>{{ $totalEtablissement['meilleur_eleve']['nom'] ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ $totalEtablissement['meilleur_eleve'] ? number_format($totalEtablissement['meilleur_eleve']['moyenne'], 2) : '-' }}
                                            </td>
                                            <td>{{ $totalEtablissement['plus_faible_eleve']['nom'] ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ $totalEtablissement['plus_faible_eleve'] ? number_format($totalEtablissement['plus_faible_eleve']['moyenne'], 2) : '-' }}
                                            </td>
                                            <td class="text-center">{{ $totalEtablissement['abandons'] }}</td>
                                            <td class="text-center">{{ number_format($tauxReussiteTotal, 2) }}%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Fonction pour sauvegarder les sélections dans le localStorage
        function saveSelections() {
            var selections = {
                periode: document.querySelector('select[name="periode"]').value,
                typeEtat: document.getElementById('typeEtat').value,
                moyenne_ref: document.querySelector('input[name="moyenne_ref"]').value,
                intervales: {}
            };

            // Sauvegarder les intervalles
            document.querySelectorAll('input[name^="intervales"]').forEach(function(input) {
                var name = input.name;
                var value = input.value;
                selections.intervales[name] = value;
            });

            localStorage.setItem('tableauSelections', JSON.stringify(selections));
        }

        // Fonction pour restaurer les sélections
        function restoreSelections() {
            var savedSelections = localStorage.getItem('tableauSelections');
            if (savedSelections) {
                var selections = JSON.parse(savedSelections);
                
                // Restaurer les valeurs
                document.querySelector('select[name="periode"]').value = selections.periode;
                document.getElementById('typeEtat').value = selections.typeEtat;
                document.querySelector('input[name="moyenne_ref"]').value = selections.moyenne_ref;

                // Restaurer les intervalles
                for (var name in selections.intervales) {
                    var input = document.querySelector(`input[name="${name}"]`);
                    if (input) {
                        input.value = selections.intervales[name];
                    }
                }
            }
        }

        // Sauvegarder les sélections lors du changement
        document.querySelector('select[name="periode"]').addEventListener('change', saveSelections);
        document.getElementById('typeEtat').addEventListener('change', saveSelections);
        document.querySelector('input[name="moyenne_ref"]').addEventListener('change', saveSelections);
        document.querySelectorAll('input[name^="intervales"]').forEach(function(input) {
            input.addEventListener('change', saveSelections);
        });

        // Restaurer les sélections au chargement de la page
        document.addEventListener('DOMContentLoaded', restoreSelections);

        function printTable() {
            var typeEtat = document.getElementById('typeEtat').value;
            if (!typeEtat) {
                alert('Veuillez sélectionner un type de tableau à imprimer.');
                return;
            }

            var printableContent = document.getElementById('printableContent');
            if (!printableContent) {
                alert('Aucun tableau à imprimer. Veuillez d\'abord calculer les résultats.');
                return;
            }

            // Créer une nouvelle fenêtre pour l'impression
            var printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Impression - ${typeEtat}</title>
                    <style>
                        body {
                            margin: 0;
                            padding: 20px;
                        }
                        @media print {
                            @page {
                                size: landscape;
                                margin: 1cm;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                            }
                            th, td {
                                border: 1px solid #000;
                                padding: 8px;
                                text-align: center;
                                font-size: 12px;
                            }
                            th {
                                background-color: #f2f2f2 !important;
                            }
                            .font-weight-bold {
                                font-weight: bold;
                            }
                            .text-center {
                                text-align: center;
                            }
                            h4 {
                                text-align: center;
                                margin-bottom: 20px;
                                font-weight: bold;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${printableContent.innerHTML}
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();

            // Attendre que le contenu soit chargé avant d'imprimer
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }

        // Mettre à jour l'action du formulaire en fonction du type d'état sélectionné
        document.getElementById('typeEtat').addEventListener('change', function() {
            var form = document.getElementById('etatForm');
            var selectedValue = this.value;
            var routes = {
                'tableau_analytique': "{{ route('tableauanalytique') }}",
                'tableau_synoptique': "{{ route('tableausynoptique') }}",
                'effectifs': "{{ route('effectifs') }}",
                'statistique': "{{ route('statistiques') }}"
            };
            if (routes[selectedValue]) {
                form.action = routes[selectedValue];
            }
        });

        // Sauvegarder les sélections avant la soumission du formulaire
        document.getElementById('etatForm').addEventListener('submit', saveSelections);
    </script>
@endsection
