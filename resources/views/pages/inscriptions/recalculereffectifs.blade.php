@extends('layouts.master')
@section('content')

<div class="container">
    <div class="card">
        <div>
            <style>
                .btn-arrow {
                    position: absolute;
                    top: 0px;
                    /* Ajustez la position verticale */
                    left: 0px;
                    /* Positionnez à gauche */
                    background-color: transparent !important;
                    border: 1px !important;
                    text-transform: uppercase !important;
                    font-weight: bold !important;
                    cursor: pointer !important;
                    font-size: 17px !important;
                    /* Taille de l'icône */
                    color: #b51818 !important;
                    /* Couleur de l'icône */
                }
        
                .btn-arrow:hover {
                    color: #b700ff !important;
                    /* Couleur au survol */
                }
            </style>
            <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <br>
            <br>                                     
        </div>
        <div class="card-body">
            <h4 class="card-title">Statistiques généraux</h4>
            <div class="form-group row">
                <div class="col-3">
                    <label for="tableSelect">Sélectionnez un tableau :</label>
                    <select class="js-example-basic-multiple custom-select-width" id="tableSelect">
                        <option value="table1">Effectif par classe</option>
                        <option value="table2">Effectif par promotion</option>
                        <option value="table3">Effectif par série</option>
                        <option value="table4">Effectif alphabétique</option>
                    </select>
                </div>
                <div class="col-3" style="margin-top: 18px; margin-left: 500px;">
                    <div class="">
                        <button type="submit" class="btn btn-primary">Recalculer effectifs</button>
                    </div>
                </div>
            </div>
            
            <div id="table1" class="table-container">
                <h5>Effectif par classe</h5>
                <table class="table">
                    <!-- En-têtes de tableau -->
                    <thead class="table-header">
                        <tr>
                            <th>CLASSES</th>
                            <th>EFFECTIF</th>
                            <th>FILLES</th>
                            <th>GARCONS</th>
                            <th>REDOUBLE</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @php
                            $totalGarcons = 0;
                            $totalFilles = 0;
                            $totalRedoublants = 0;
                            $totalEffectif = 0;
                        @endphp
                        @foreach ($effectifsParClasse as $classe => $effectif)
                        <tr>
                            <td>{{ $classe }}</td>
                            <td>{{ $effectif['total'] }}</td>
                            <td>{{ $effectif['filles'] }}</td>
                            <td>{{ $effectif['garcons'] }}</td>
                            <td>{{ $effectif['redoublants'] }}</td>
                        </tr>
                        @php
                            $totalEffectif += $effectif['total'];
                            $totalFilles += $effectif['filles'];
                            $totalGarcons += $effectif['garcons'];
                            $totalRedoublants += $effectif['redoublants'];
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="table-footer">
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalEffectif }}</td>
                            <td>{{ $totalFilles }}</td>
                            <td>{{ $totalGarcons }}</td>
                            <td>{{ $totalRedoublants }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>

            <div id="table2" class="table-container d-none">
                <h5>Effectif par promotion</h5>
                <table class="table table-bordered">
                    <thead class="table-header">
                        <tr>
                            <th>PROMOTION</th>
                            <th>Nb CLASSES</th>
                            <th>EFFECTIF</th>
                            <th>FILLES</th>
                            <th>GARCONS</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @php
                            $totalPromotions = 0;
                            $totalEffectifPromotion = 0;
                            $totalFillesPromotion = 0;
                            $totalGarconsPromotion = 0;
                        @endphp
                        @foreach ($effectifsParPromotion as $promoName => $effpromo)
                        <tr>
                            <td>{{ $promoName }}</td> <!-- Utilisez la clé comme nom de la promotion -->
                            <td>{{ $effpromo['totalClasses'] }}</td>
                            <td>{{ $effpromo['total'] }}</td>
                            <td>{{ $effpromo['filles'] }}</td>
                            <td>{{ $effpromo['garcons'] }}</td>
                        </tr>
                        @php
                            $totalPromotions += $effpromo['totalClasses'];
                            $totalEffectifPromotion += $effpromo['total'];
                            $totalFillesPromotion += $effpromo['filles'];
                            $totalGarconsPromotion += $effpromo['garcons'];
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="table-footer">
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalPromotions }}</td>
                            <td>{{ $totalEffectifPromotion }}</td>
                            <td>{{ $totalFillesPromotion }}</td>
                            <td>{{ $totalGarconsPromotion }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div id="table3" class="table-container d-none">
                <h5>Effectif par série</h5>
                <table class="table table-bordered">
                    <thead class="table-header">
                        <tr>
                            <th>SERIE</th>
                            <th>Nb CLASSES</th>
                            <th>EFFECTIF</th>
                            <th>FILLES</th>
                            <th>GARCONS</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @php
                            $totalClassesSeries = 0;
                            $totalEffectifSeries = 0;
                            $totalFillesSeries = 0;
                            $totalGarconsSeries = 0;
                        @endphp
                        @foreach ($effectifsParSerie as $serie => $effectif)
                        <tr>
                            <td>{{ $serie }}</td>
                            <td>{{ $effectif['totalClasses'] }}</td>
                            <td>{{ $effectif['total'] }}</td>
                            <td>{{ $effectif['filles'] }}</td>
                            <td>{{ $effectif['garcons'] }}</td>
                        </tr>
                        @php
                            $totalClassesSeries += $effectif['totalClasses'];
                            $totalEffectifSeries += $effectif['total'];
                            $totalFillesSeries += $effectif['filles'];
                            $totalGarconsSeries += $effectif['garcons'];
                        @endphp
                        @endforeach 
                    </tbody>
                    <tfoot class="table-footer">
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalClassesSeries }}</td>
                            <td>{{ $totalEffectifSeries }}</td>
                            <td>{{ $totalFillesSeries }}</td>
                            <td>{{ $totalGarconsSeries }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div id="table4" class="table-container d-none">
                <h5>Effectif alphabétique</h5>
                <table class="table table-bordered">
                    <thead class="table-header">
                        <tr>
                            <th>LETTRE</th>
                            <th>EFFECTIF</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @php
                            $totalEffectifAlphabetique = 0;
                        @endphp
                        @foreach ($effectifsAlphabetiques as $lettre => $effectif)
                        <tr>
                            <td>{{ $lettre }}</td>
                            <td>{{ $effectif }}</td>
                        </tr>
                        @php
                            $totalEffectifAlphabetique += $effectif;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="table-footer">
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalEffectifAlphabetique }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
    .table-header {
        background-color: #060e1600;
        color: #333;
        font-weight: bold;
    }

    .table-header th {
        padding: 10px;
        text-align: center;
        border-bottom: 2px solid #ddd;
    }


    .table-body td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table-footer {
        background-color: #e9ecef;
        color: #333;
        font-weight: bold;
    }

    .table-footer td {
        padding: 10px;
        text-align: center;
        border-top: 2px solid #ddd;
    }

    h5 {
        text-align: center;
    }

    .custom-select-width {
        width: 100% !important;
        max-width: 400px !important;
    }
    .footer {
        position:fixed !important; /* Changer de relative à absolute pour le placer en bas de la carte */
        bottom: 0 !important; /* Assurer que le footer soit en bas */
        width: 100% !important;
        z-index: 10 !important; /* Assurer que le footer soit au-dessus des autres éléments */
    }
    .table-container {
        max-height: calc(100vh - 350px) !important; /* Ajustez 200px en fonction de la hauteur de votre header et footer */
        overflow-y: auto; /* Ajouter une barre de défilement verticale */
        padding: 20px;
    }

    .table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .table th, .table td {
        padding: 8px;
        text-align: left !important;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #f2f2f2;
        cursor: pointer;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .btn {
        background-color: #6f42c1;
        color: white;
        border: none;
        padding: 5px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }

    .btn:hover {
        background-color: #5a3791;
    }
</style>



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

<script>
    $(document).ready(function() {
        $('#tableSelect').change(function() {
            var selectedTable = $(this).val();
            $('.table-container').addClass('d-none');
            $('#' + selectedTable).removeClass('d-none');
        });
        $('.table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
            },
            "pagingType": "simple" 
        });
    });
</script>