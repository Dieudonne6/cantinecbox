@extends('layouts.master')
@section('content')

<div class="main-content">
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
                <br><br>
            </div>

            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center" style="margin-top: 20px; margin-bottom: 40px;">
                    <h2 class="mb-3 mb-md-0">Etat des arriérés</h2>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button type="button" class="btn btn-primary btn-lg btn-print" onclick="imprimerliste()">
                            Imprimer
                        </button>
                        <button type="button" class="btn btn-primary" onclick="exportToExcel()">Exporter vers Excel</button>
                    </div>
                </div>

                <div class="container-fluid d-flex align-items-center justify-content-center">
                    <div id="contenu">

                        <!-- ===================== TABLEAU CORRIGÉ ===================== -->
                        <div class="table-responsive">
                            <table id="arrearsTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Classe</th>
                                    <th>Montant dû</th>
                                    <th>Payé</th>
                                    <th>Reste</th>
                                    {{-- <th>Inscrit</th> --}}
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $num = 1;
                                    $totalArriere = 0;
                                    $totalPaye = 0;
                                    $totalReste = 0;
                                    $totalsByClass = [];
                                    $currentClass = null;
                                @endphp

                                @foreach ($listeEle as $eleve)
                                    @php
                                        $paye = \App\Models\Scolarite::where('AUTREF', 2)
                                            ->where('MATRICULE', $eleve->MATRICULE)
                                            ->sum('MONTANT');

                                        $reste = $eleve->ARRIERE - $paye;

                                        // Mettre à jour les totaux
                                        $totalArriere += $eleve->ARRIERE;
                                        $totalPaye += $paye;
                                        $totalReste += $reste;

                                        // Récupération classe (optionnel)
                                        $classe = \App\Models\Eleve::where('MATRICULE', $eleve->MATRICULE)
                                                ->value('CODECLAS');

                                        // Update class totals
                                        if (!isset($totalsByClass[$classe])) {
                                            $totalsByClass[$classe] = ['totalArriere' => 0, 'totalPaye' => 0, 'totalReste' => 0];
                                        }
                                        $totalsByClass[$classe]['totalArriere'] += $eleve->ARRIERE;
                                        $totalsByClass[$classe]['totalPaye'] += $paye;
                                        $totalsByClass[$classe]['totalReste'] += $reste;

                                        // Check if class has changed
                                        if ($currentClass !== null && $currentClass !== $classe) {
                                            // Output totals for the previous class
                                            echo "<tr><td colspan='3' style='text-align: right; font-weight: bold;'>Total $currentClass</td>";
                                            echo "<td style='font-weight: bold;'>" . number_format($totalsByClass[$currentClass]['totalArriere'], 0, ',', ' ') . "</td>";
                                            echo "<td style='font-weight: bold;'>" . number_format($totalsByClass[$currentClass]['totalPaye'], 0, ',', ' ') . "</td>";
                                            echo "<td style='font-weight: bold;'>" . number_format($totalsByClass[$currentClass]['totalReste'], 0, ',', ' ') . "</td>";
                                            echo "<td></td></tr>";
                                        }

                                        $currentClass = $classe;
                                    @endphp

                                    <tr data-classe="{{ $classe }}">
                                        <td>{{ $num++ }}</td>
                                        <td>{{ $eleve->NOM }}</td>
                                        <td>{{ $eleve->PRENOM }}</td>
                                        <td>{{ $classe }}</td>
                                        <td>{{ number_format($eleve->ARRIERE, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($paye, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($reste, 0, ',', ' ') }}</td>
                                        {{-- <td><input type="checkbox" disabled checked></td> --}}
                                    </tr>
                                @endforeach

                                @if ($currentClass !== null)
                                    <tr>
                                        <td colspan="3" style="text-align: right; font-weight: bold;">Total {{ $currentClass }}</td>
                                        <td style="font-weight: bold;">{{ number_format($totalsByClass[$currentClass]['totalArriere'], 0, ',', ' ') }}</td>
                                        <td style="font-weight: bold;">{{ number_format($totalsByClass[$currentClass]['totalPaye'], 0, ',', ' ') }}</td>
                                        <td style="font-weight: bold;">{{ number_format($totalsByClass[$currentClass]['totalReste'], 0, ',', ' ') }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold;">Total Général</td>
                                    <td style="font-weight: bold;">{{ number_format($totalArriere, 0, ',', ' ') }}</td>
                                    <td style="font-weight: bold;">{{ number_format($totalPaye, 0, ',', ' ') }}</td>
                                    <td style="font-weight: bold;">{{ number_format($totalReste, 0, ',', ' ') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                            </table>
                        </div>
                        <!-- =========================================================== -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction d'impression
    window.imprimerliste = function() {
        const originalTable = document.getElementById('arrearsTable');
        const printContent = originalTable.cloneNode(true);

        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Impression</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('td, th { border: 1px solid black; padding: 8px; text-align: left; white-space: nowrap; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h3 style="text-align: center;">Etat des arriérés</h3>');
        printWindow.document.write(printContent.outerHTML);
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }

    // Fonction d'export Excel
    window.exportToExcel = function() {
        const contentElement = document.getElementById('contenu');
        if (!contentElement) {
            alert("Aucune liste à exporter.");
            return;
        }

        const clone = contentElement.cloneNode(true);
        const buttons = clone.querySelectorAll('button, .form-group, select');
        buttons.forEach(el => el.remove());

        const style = `
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid black; padding: 5px; text-align: center; font-size: 14px; }
                th { font-weight: bold; background-color: #f2f2f2; }
                td.mat { mso-number-format:"0"; }
            </style>
        `;

        const html = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:x="urn:schemas-microsoft-com:office:excel"
                xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="UTF-8">${style}</head>
            <body>${clone.innerHTML}</body>
            </html>
        `;

        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `etat_arrieres.xls`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
</script>


<style>
    .footer {
        position: relative !important;
        width: 100% !important;
        z-index: 10 !important;
    }

    @media print {
        .sidebar, .navbar, .footer, .noprint, button {
            display: none !important;
        }
        body { overflow: hidden; }

        .card { margin-top: 0px !important; }

        table {
            margin: 0 auto;
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid black;
            padding: 8px;
            white-space: nowrap;
        }
    }
</style>
@endsection
