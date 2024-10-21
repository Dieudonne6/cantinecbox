@extends('layouts.master')
@section('content')
<div class="d-flex justify-content-center align-items-center">
    <div class="card col-md-10">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Journal des opérateurs</h4>
                <button class="btn btn-primary" onclick="imprimerliste()">Imprimer</button>
            </div>
            @foreach ($scolarite as $operateur => $dates)
                <h4 style="text-align: center;">Opérateur: {{ $operateur }}</h4>
                @foreach ($dates as $date => $items)
                    @php
                        $formattedDate = \Carbon\Carbon::parse($date)->format('d/m/Y');
                    @endphp
                    <br>
                    <h6>Date de l'opération: {{ $formattedDate }}</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Num reçu</th>
                                <th>Nom</th>
                                <th>Prénoms</th>
                                <th>Classes</th>
                                <th>Montants</th>
                                <th>Signatures</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->NUMRECU }}</td>
                                <td>{{ $item->NOM }}</td>
                                <td>{{ $item->PRENOM }}</td>
                                <td>{{ $item->CODECLAS }}</td>
                                <td>{{ $item->MONTANT }}</td>
                                <td>{{ $item->SIGNATURE }}</td>
                            </tr>
                            @php $total += $item->MONTANT; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">Total perçu du: {{ $formattedDate }}</td>
                                <td>{{ $total }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                @endforeach
            @endforeach
        </div>
    </div>
</div>

<style>
    th, td {
        border: 1px solid #000 !important;
    }
    @media print {
        .sidebar, .navbar, .footer, .noprint, button{
            display: none !important; /* Masquer les éléments non désirés à l'impression */
        }
        .card-title {
            text-align: center;
        }
        th, td {
            border: 1px solid #000 !important;
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
