@extends('layouts.master')
@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<div class="main-content">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-between" style="margin-top: 20px; margin-bottom: 40px;"> <!-- Ajout d'un margin-bottom pour espacer le bouton du footer -->
                    <h2>Etat des arriérés</h2>
                    <button type="button" class="btn btn-primary btn-lg btn-print" onclick="imprimerliste()"> <!-- Ajout de la classe btn-lg pour agrandir le bouton -->
                        Imprimer
                    </button>
                </div>
            <div class="container-fluid d-flex align-items-center justify-content-center">
                <div id="contenu">
                    <table id="arrearsTable" class="display" {{-- style="width:50%; margin: 0 auto; border-collapse: collapse;" --}}>
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Classe</th>
                                <th>Montant du</th>
                                <th>PAYE</th>
                                <th>RESTE</th>
                                <th>Inscrit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $num = 1;
                            @endphp
                            @foreach ($archive as $eleve)
                                @foreach ($delevea->where('MATRICULE', $eleve->MATRICULE) as $arrears)
                                @php
                                    $reste = $arrears->MONTANTARRIERE - $arrears->MONTANTENAVANCE;
                                    $isInscrit = $eleve->pluck('MATRICULE')->contains($eleve->MATRICULE);
                                @endphp
                                    <tr data-classe="{{ $arrears->CODECLAS }}" class="classe-table">
                                        <td>{{ $num++ }}</td>
                                        <td>{{ $eleve->NOM }}</td>
                                        <td>{{ $eleve->PRENOM }}</td>
                                        <td>{{ $arrears->CODECLAS }}</td>
                                        <td>{{ number_format($arrears->MONTANTARRIERE, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($arrears->MONTANTENAVANCE, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($reste, 0, ',', ' ') }}</td>
                                        <td>
                                            <input type="checkbox" disabled {{ $isInscrit ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#arrearsTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
            }
        });
    });

    function imprimerliste() {
        // Désactiver DataTables pour afficher toutes les lignes
        var table = $('#arrearsTable').DataTable();
        table.destroy();

        // Obtenir le contenu du tableau complet
        var originalTable = document.getElementById('arrearsTable');
        var printContent = originalTable.cloneNode(true);

        // Créer une nouvelle fenêtre pour l'impression
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Impression</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('td, th { border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h3 style="text-align: center;">Etat des arriérés</h3>');
        printWindow.document.write(printContent.outerHTML);
        printWindow.document.write('</body></html>');

        // Fermer le document pour déclencher l'impression
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();

        // Réinitialiser DataTables après l'impression
        $(document).ready(function() {
            $('#arrearsTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
                }
            });
        });
    }
</script>

<style>
    .footer {
        position: relative !important; /* Utiliser relative pour éviter de cacher le tableau */
        width: 100% !important;
        z-index: 10 !important; /* Assurer que le footer soit au-dessus des autres éléments */
    }

    @media print {
        .sidebar, .navbar, .footer, .noprint, button {
            display: none !important; /* Masquer la barre de titre et autres éléments */
        }
        body {
            overflow: hidden; /* Masquer les barres de défilement */
        }

        .card {
            margin-top: 0px !important;
        }

        table {
            margin: 0 auto;
            width: 100%; 
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            white-space: nowrap;
        }
    }
</style>
    @endsection