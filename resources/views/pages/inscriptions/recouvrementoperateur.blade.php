@extends('layouts.master')
@section('content')
<div class="d-flex justify-content-center align-items-center">
    <div class="card col-md-12">
        <div class="card-body">
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
            <div class="col-12 mt-2 d-flex justify-content-end">
                <button class="btn btn-primary noprint" onclick="imprimerliste()">Imprimer</button>
            </div>
            <h4 class="card-title">Situation des recouvrements des opérateurs</h4>
            <h4>Période du {{ $dateDebut }} au {{ $dateFin }}</h4>
            <br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Opérateur</th>
                        <th>Scolarité</th>
                        <th>Arriéré</th>
                        <th>{{ $params->first()->LIBELF1}}</th>
                        <th>{{ $params->first()->LIBELF2}}</th>
                        <th>{{ $params->first()->LIBELF3}}</th>
                        <th>{{ $params->first()->LIBELF4}}</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scolarite as $scol)
                        <tr>
                            <td>{{ $scol->SIGNATURE }}</td>
                            <td>{{ $scol->Scolarite }}</td>
                            <td>{{ $scol->Arriere }}</td>
                            <td>{{ $scol->Insc }}</td>
                            <td>{{ $scol->Frais2 }}</td>
                            <td>{{ $scol->Frais3 }}</td>
                            <td>{{ $scol->Frais4 }}</td>
                            <td>{{ $scol->Total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
        </div>
    </div>
</div>

<style>
     th, td {
        border: 1px solid #000 !important;
     }
     @media print {
        .sidebar, .navbar, .footer, .noprint {
            display: none !important; /* Masquer les éléments non désirés à l'impression */
        }
        .card-title {
            text-align: center;
        }

     }
</style>

<script>
    function imprimerliste() {
            var content = document.querySelector('.main-panel').innerHTML; // Sélectionne le contenu à imprimer
            var originalContent = document.body.innerHTML; // Sauvegarde le contenu complet de la page

            document.body.innerHTML = content; // Remplace tout le contenu par la section sélectionnée
            window.print(); // Lance l'impression

            document.body.innerHTML = originalContent; // Restaure le contenu original après impression
        }
</script>
@endsection
