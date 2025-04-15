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
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br><br>
            </div>

            <div class="card-body">
                <!-- Formulaire pour le filtrage et le calcul -->
                <form action="{{ route('tableauanalytique') }}" method="POST" id="etatForm">
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
                                    <option value="1" {{ old('periode') == '1' ? 'selected' : '' }}>1ère Période
                                    </option>
                                    <option value="2" {{ old('periode') == '2' ? 'selected' : '' }}>2ème Période
                                    </option>
                                    <option value="3" {{ old('periode') == '3' ? 'selected' : '' }}>3ème Période
                                    </option>
                                    <option value="4" {{ old('periode') == '4' ? 'selected' : '' }}>Annuel</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type d'états</label>
                                <select name="typeEtat" class="form-control" id="typeEtat" required>
                                    <option value="">Sélectionner un état</option>
                                    <option value="tableau_analytique"
                                        {{ old('typeEtat') == 'tableau_analytique' ? 'selected' : '' }}>Tableau analytique
                                        des résultats</option>
                                    <option value="tableau_synoptique"
                                        {{ old('typeEtat') == 'tableau_synoptique' ? 'selected' : '' }}>Tableau synoptique
                                        des résultats</option>
                                    <option value="effectifs" {{ old('typeEtat') == 'effectifs' ? 'selected' : '' }}>Tableau
                                        synoptique des effectifs</option>
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
                                @foreach (range(1, 7) as $i)
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">I{{ $i }}</td>
                                        <td style="text-align: center;">
                                            <input type="number" name="intervales[I{{ $i }}][min]"
                                                class="form-control mx-auto"
                                                value="{{ $i == 1 ? '0.00' : ($i == 2 ? '06.50' : ($i == 3 ? '07.50' : ($i == 4 ? '10.00' : ($i == 5 ? '12.00' : ($i == 6 ? '14.00' : '16.00'))))) }}">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" name="intervales[I{{ $i }}][max]"
                                                class="form-control mx-auto"
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
                        <button type="button" class="btn btn-secondary" onclick="printNote()">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                    </div>
                </form>

                <!-- Affichage conditionnel des tableaux -->
                @if (isset($resultats) && isset($typeEtat))
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
                                    @foreach ($resultats as $groupeKey => $stats)
                                        <tr>
                                            <td class="font-weight-bold">{{ $groupeKey }}</td>
                                            <td>{{ number_format($stats['max_moyenne_garcons'], 2) }}</td>
                                            <td>{{ number_format($stats['max_moyenne_filles'], 2) }}</td>
                                            <td>{{ number_format($stats['min_moyenne_garcons'], 2) }}</td>
                                            <td>{{ number_format($stats['min_moyenne_filles'], 2) }}</td>
                                            @foreach ($stats['intervales'] as $intervalle => $data)
                                                <td>{{ $data['garcons'] }}</td>
                                                <td>{{ $data['filles'] }}</td>
                                                <td class="font-weight-bold">{{ $data['total'] }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($typeEtat == 'tableau_synoptique')
                        <div class="table-responsive mt-5">
                            <h4 class="text-center mb-4">Tableau Synoptique des Résultats</h4>
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="text-align: center; width: 25px;">GPE</th>
                                        <th style="text-align: center;">NBGPE</th>
                                        @foreach (range(1, 4) as $i)
                                            <th style="text-align: center;">I{{ $i }}G</th>
                                            <th style="text-align: center;">I{{ $i }}F</th>
                                            <th style="text-align: center;">I{{ $i }}T</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultats as $codePromo => $stats)
                                        <tr>
                                            <td class="font-weight-bold">{{ $codePromo }}</td>
                                            <td class="text-center">{{ $stats['nbClasses'] }}</td>
                                            @foreach (range(1, 4) as $i)
                                                <td class="text-center">
                                                    {{ $stats['intervales']['I' . $i]['garcons'] ?? 0 }}
                                                </td>
                                                <td class="text-center">{{ $stats['intervales']['I' . $i]['filles'] ?? 0 }}
                                                </td>
                                                <td class="text-center font-weight-bold">
                                                    {{ $stats['intervales']['I' . $i]['total'] ?? 0 }}</td>
                                            @endforeach
                                        </tr>
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
                    @endif
                @endif

                <!-- TABLEAU SPÉCIAL "CACHÉ" POUR L'IMPRESSION -->
                @if (isset($resultats))
                    <div class="table-print" id="mainTable" style="display: none;">
                        <h4 style="text-align:center; font-weight: bold;">TABLEAU ANALYTIQUE SPÉCIAL POUR IMPRESSION</h4>
                        <table class="table table-bordered table-rapport">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align: center;">GPE</th>
                                    <th colspan="2" style="text-align: center;">FORTE MOY</th>
                                    <th colspan="2" style="text-align: center;" class="bordleft">FAIBLE MOY</th>
                                    @php
                                        $listeIntervales = [];
                                        $anyKey = array_key_first($resultats);
                                        if ($anyKey !== null) {
                                            foreach ($resultats[$anyKey]['intervales'] as $intervalName => $val) {
                                                $listeIntervales[] = $intervalName;
                                            }
                                        }
                                    @endphp
                                    @foreach ($listeIntervales as $intervalName)
                                        <th colspan="3" style="text-align: center;">
                                            {{ $intervalName }}
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th style="text-align: center;">G</th>
                                    <th style="text-align: center;">F</th>
                                    <th style="text-align: center;">G</th>
                                    <th style="text-align: center;">F</th>
                                    @foreach ($listeIntervales as $intervalName)
                                        <th style="text-align: center;">G</th>
                                        <th style="text-align: center;">F</th>
                                        <th style="text-align: center;">T</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resultats as $groupeKey => $stats)
                                    <tr>
                                        <td>{{ $groupeKey }}</td>
                                        <td>{{ number_format($stats['max_moyenne_garcons'], 2) }}</td>
                                        <td>{{ number_format($stats['max_moyenne_filles'], 2) }}</td>
                                        <td>{{ number_format($stats['min_moyenne_garcons'], 2) }}</td>
                                        <td class="bordleft">{{ number_format($stats['min_moyenne_filles'], 2) }}</td>
                                        @foreach ($stats['intervales'] as $intervalName => $data)
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

    <script>
        function injectTableStyles() {
            var style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    .table-print, .table-print * {
                        visibility: visible;
                    }
                    .table-print {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                    }
                    @page { 
                        size: landscape;
                        margin: 1cm;
                    }
                    .table-print table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .table-print thead {
                        background-color: #f2f2f2;
                    }
                    .table-print th, 
                    .table-print td {
                        padding: 8px;
                        border: 1px solid #000;
                        text-align: center;
                        font-size: 12px;
                    }
                    .table-print .font-weight-bold {
                        font-weight: bold;
                    }
                    .table-print .text-center {
                        text-align: center;
                    }
                    .table-print h4 {
                        text-align: center;
                        margin-bottom: 20px;
                        font-weight: bold;
                    }
                }`;
            document.head.appendChild(style);
        }

        function printNote() {
     injectTableStyles(); // Injecter les styles pour l'impressionoriginal
     var originalContent = document.body.innerHTML; // Contenu original de la page
     var printContent = document.getElementById('mainTable').innerHTML; // Contenu spécifique à imprimer
     document.body.innerHTML = printContent; // Remplacer le contenu de la page par le contenu à imprimer
     
     setTimeout(function() {
        window.print(); // Ouvrir la boîte de dialogue d'impression
        document.body.innerHTML = originalContent; // Restaurer le contenu original
     }, 1000);
   }

        // function printNote() {
        //     injectTableStyles();
        //     var originalContent = document.body.innerHTML;
        //     var typeEtat = document.getElementById('typeEtat').value;
        //     var printContent = '';

        //     // Sélectionner le contenu à imprimer selon le type d'état
        //     if (typeEtat === 'tableau_analytique') {
        //         printContent = document.querySelector(
        //             '.table-responsive:has(h4:contains("Tableau Analytique des Résultats"))').innerHTML;
        //     } else if (typeEtat === 'tableau_synoptique') {
        //         printContent = document.querySelector(
        //             '.table-responsive:has(h4:contains("Tableau Synoptique des Résultats"))').innerHTML;
        //     } else if (typeEtat === 'effectifs') {
        //         printContent = document.querySelector(
        //             '.table-responsive:has(h4:contains("Tableau Synoptique des Effectifs"))').innerHTML;
        //     }

        //     // Créer le conteneur d'impression
        //     var printContainer = document.createElement('div');
        //     printContainer.className = 'table-print';
        //     printContainer.innerHTML = printContent;

        //     // Remplacer le contenu du body
        //     document.body.innerHTML = '';
        //     document.body.appendChild(printContainer);

        //     // Imprimer
        //     setTimeout(function() {
        //         window.print();
        //         // Restaurer le contenu original
        //         document.body.innerHTML = originalContent;
        //     }, 1000);
        // }
    </script>
    {{-- <script>
        document.getElementById('typeEtat').addEventListener('change', function() {
            var form = document.getElementById('etatForm');
            var selectedValue = this.value;
            var routes = {
                'tableau_analytique': "{{ route('tableauanalytique') }}",
                'tableau_synoptique': "{{ route('tableausynoptique') }}",
                'effectifs': "{{ route('effectifs') }}"
            };
            if (routes[selectedValue]) {
                form.action = routes[selectedValue];
            } else {
                form.action = '#'; // Valeur par défaut ou gestion d'erreur
            }
        });
    </script> --}}

@endsection
{{-- @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.fr.min.js">
    </script> --}}
