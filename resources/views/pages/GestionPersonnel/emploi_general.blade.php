@extends('layouts.master')

@section('content')
<style>
    .emploi-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .table td {
        transition: all 0.2s ease;
    }
    .table td:hover {
        filter: brightness(0.95);
    }
    
    /* Styles pour l'impression */
    @media print {
        /* Cacher tout sauf le titre et le tableau */
        .no-print {
            display: none !important;
        }
        
        /* Cacher les alertes et messages */
        .alert {
            display: none !important;
        }
        
        body {
            margin: 0;
            padding: 10px;
        }
        
        .container-fluid {
            max-width: 100%;
            padding: 0;
        }
        
        /* Afficher uniquement le titre */
        .text-center.mb-4 {
            margin-bottom: 20px !important;
        }
        
        .emploi-header {
            color: #000 !important;
            -webkit-text-fill-color: #000 !important;
            font-size: 18px !important;
        }
        
        /* Optimiser le tableau pour l'impression */
        .table-responsive {
            overflow: visible !important;
        }
        
        .table {
            page-break-inside: avoid;
            width: 100% !important;
        }
        
        .table td, .table th {
            padding: 6px !important;
            font-size: 9px !important;
            border: 1px solid #000 !important;
        }
        
        .table th {
            background-color: #e9ecef !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Garder les couleurs des cellules */
        .table td {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<div class="container-fluid">
    <div class="text-center mb-4">
        <h3 class="emploi-header fw-bold mb-2">
            📅 Emploi du Temps Général
            @if($classeSelectionnee && $classeSelectionnee !== 'toutes')
                - {{ $classes->where('CODECLAS', $classeSelectionnee)->first()->LIBELCLAS ?? $classeSelectionnee }}
            @endif
        </h3>
    </div>

    <!-- Sélecteur de classe et boutons d'action -->
    <div class="row justify-content-center mb-4 align-items-center no-print">
        <div class="col-md-4">
            <form method="GET" action="{{ route('emploidutempsgeneral') }}" class="d-flex align-items-center">
                <label for="classe" class="form-label me-2 mb-0 fw-bold text-nowrap">
                    <i class="fas fa-filter me-1"></i>Filtrer par classe :
                </label>
                <select name="classe" id="classe" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="toutes" {{ (!$classeSelectionnee || $classeSelectionnee == 'toutes') ? 'selected' : '' }}>
                        Toutes les classes
                    </option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->CODECLAS }}" {{ $classeSelectionnee == $classe->CODECLAS ? 'selected' : '' }}>
                            {{ $classe->LIBELCLAS }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        
        <!-- Boutons d'action -->
        @if($classeSelectionnee && $classeSelectionnee !== 'toutes')
        <div class="col-md-4 text-center no-print">
            <button onclick="imprimerEmploi()" class="btn btn-primary btn-sm me-2">
                <i class="fas fa-print me-1"></i>Imprimer
            </button>
            <a href="{{ route('emploidutempsgeneral.excel', ['classe' => $classeSelectionnee]) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i>Exporter Excel
            </a>
        </div>
        @endif
    </div>

    @if(!$classeSelectionnee || $classeSelectionnee === 'toutes')
        <div class="alert alert-warning text-center mb-3">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Veuillez sélectionner une classe</strong> pour afficher son emploi du temps.
        </div>
    @endif
    
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erreur :</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if($classeSelectionnee && $classeSelectionnee !== 'toutes')
        @if($emplois->count() == 0)
            <div class="alert alert-info text-center">
                <h5>Aucun cours programmé</h5>
                <p>Cette classe n'a pas encore de cours dans l'emploi du temps. Vous pouvez en ajouter via la 
                <a href="{{ route('saisiremploitemps') }}" class="alert-link">page de saisie</a>.</p>
            </div>
        @endif

        <div class="table-responsive">
        <table class="table table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-center align-middle" style="background: #e9ecef; color: #2c3e50; width: 100px; font-size: 0.85rem; font-weight: bold;">
                        Horaires
                    </th>
                    @foreach($jours as $jourNum => $jourNom)
                        <th class="text-center align-middle" style="background: #e9ecef; color: #2c3e50; min-width: 140px; font-size: 0.85rem; font-weight: bold;">
                            {{ $jourNom }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    // Tableau pour tracker les cellules à skip (déjà fusionnées)
                    $skipCells = [];
                    
                    // Fonction pour obtenir une couleur par matière
                    function getCouleurMatiere($libelMat) {
                        $couleurs = [
                            // 'Mathématiques' => '#ffb3ba',
                            // 'Français' => '#bae1ff',
                            // 'Lecture' => '#baffc9',
                            // 'Anglais' => '#ffffba',
                            // 'Calculs' => '#ffdfba',
                            // 'Phonologie' => '#e0bfff',
                            // 'Récréation' => '#d4f1f4',
                            // 'Écriture' => '#c9e4de',
                            // 'Pause' => '#f5f5f5',
                        ];
                        
                        // Recherche par correspondance partielle
                        foreach ($couleurs as $mot => $couleur) {
                            if (stripos($libelMat, $mot) !== false) {
                                return $couleur;
                            }
                        }
                        
                        // Couleur par défaut basée sur le hash du nom
                        $hash = crc32($libelMat);
                        $hue = abs($hash % 360);
                        return "hsl($hue, 60%, 85%)";
                    }
                    
                    // Calculer le nombre de créneaux couverts par un cours
                    function calculerRowspan($cours, $heures) {
                        if (!isset($cours->HEURE) || strpos($cours->HEURE, '-') === false) {
                            return 1;
                        }
                        
                        list($coursDebut, $coursFin) = explode('-', trim($cours->HEURE));
                        $coursDebutMin = (int)substr($coursDebut, 0, 2) * 60 + (int)substr($coursDebut, 3, 2);
                        $coursFinMin = (int)substr($coursFin, 0, 2) * 60 + (int)substr($coursFin, 3, 2);
                        
                        $rowspan = 0;
                        foreach ($heures as $heure) {
                            list($creneauDebut, $creneauFin) = explode('-', trim($heure));
                            $creneauDebutMin = (int)substr($creneauDebut, 0, 2) * 60 + (int)substr($creneauDebut, 3, 2);
                            $creneauFinMin = (int)substr($creneauFin, 0, 2) * 60 + (int)substr($creneauFin, 3, 2);
                            
                            if ($creneauDebutMin < $coursFinMin && $creneauFinMin > $coursDebutMin) {
                                $rowspan++;
                            }
                        }
                        
                        return max(1, $rowspan);
                    }
                @endphp
                
                @foreach($heures as $heureIndex => $heure)
                    <tr>
                        <td class="text-center align-middle fw-bold" style="background: #f8f9fa; color: #2c3e50; font-size: 0.75rem; padding: 8px; border: 1px solid #dee2e6;">
                            {{ $heure }}
                        </td>
                        @foreach($jours as $jourNum => $jourNom)
                            @php
                                $skipKey = $heureIndex . '_' . $jourNum;
                                
                                // Si cette cellule doit être skipée, on la saute
                                if (isset($skipCells[$skipKey])) {
                                    continue;
                                }
                                
                                // Recherche du cours pour ce jour et cette heure
                                $coursAAfficher = null;
                                $estPremierCreneau = false;
                                
                                foreach ($emplois as $cours) {
                                    if ((int)$cours->JOUR == (int)$jourNum) {
                                        $coursHeure = trim($cours->HEURE);
                                        $creneauHeure = trim($heure);
                                        
                                        if (strpos($coursHeure, '-') !== false && strpos($creneauHeure, '-') !== false) {
                                            list($coursDebut, $coursFin) = explode('-', $coursHeure);
                                            list($creneauDebut, $creneauFin) = explode('-', $creneauHeure);
                                            
                                            $coursDebutMin = (int)substr($coursDebut, 0, 2) * 60 + (int)substr($coursDebut, 3, 2);
                                            $coursFinMin = (int)substr($coursFin, 0, 2) * 60 + (int)substr($coursFin, 3, 2);
                                            $creneauDebutMin = (int)substr($creneauDebut, 0, 2) * 60 + (int)substr($creneauDebut, 3, 2);
                                            $creneauFinMin = (int)substr($creneauFin, 0, 2) * 60 + (int)substr($creneauFin, 3, 2);
                                            
                                            // Vérifier le chevauchement
                                            if ($creneauDebutMin < $coursFinMin && $creneauFinMin > $coursDebutMin) {
                                                // Vérifier si c'est le premier créneau du cours
                                                if ($coursDebutMin >= $creneauDebutMin && $coursDebutMin < $creneauFinMin) {
                                                    $coursAAfficher = $cours;
                                                    $estPremierCreneau = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                // Calculer le rowspan si on a trouvé un cours
                                $rowspan = 1;
                                if ($coursAAfficher && $estPremierCreneau) {
                                    $rowspan = calculerRowspan($coursAAfficher, $heures);
                                    
                                    // Marquer les cellules suivantes à skip
                                    for ($i = 1; $i < $rowspan; $i++) {
                                        $nextSkipKey = ($heureIndex + $i) . '_' . $jourNum;
                                        $skipCells[$nextSkipKey] = true;
                                    }
                                }
                            @endphp
                            
                            @if($coursAAfficher && $estPremierCreneau)
                                <td class="align-middle text-center p-2" 
                                    rowspan="{{ $rowspan }}" 
                                    style="background: {{ getCouleurMatiere($coursAAfficher->LIBELMAT ?? 'Matière') }}; border: 1px solid #dee2e6; vertical-align: middle;">
                                    <div class="fw-bold" style="font-size: 0.9rem; color: #2c3e50;">
                                        {{ $coursAAfficher->LIBELMAT ?? 'Matière' }}
                                    </div>
                                    @if($rowspan > 1)
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                            {{ trim(($coursAAfficher->NOM ?? '') . ' ' . ($coursAAfficher->PRENOM ?? '')) }}
                                        </div>
                                        <div class="small text-muted" style="font-size: 0.7rem;">
                                            <i class="fas fa-door-open me-1"></i>{{ $coursAAfficher->CODESALLE ?? 'N/A' }}
                                        </div>
                                    @endif
                                </td>
                            @elseif(!$coursAAfficher)
                                <td class="align-middle text-center" style="background: #f8f9fa; border: 1px solid #dee2e6;">
                                    <span class="text-muted" style="font-size: 0.7rem; opacity: 0.4;">—</span>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

<script>
function imprimerEmploi() {
    // Récupérer le titre
    const titre = document.querySelector('.emploi-header').innerText;
    
    // Récupérer le tableau
    const tableau = document.querySelector('.table-responsive').innerHTML;
    
    // Créer une nouvelle fenêtre
    const printWindow = window.open('', '', 'height=600,width=800');
    
    // Écrire le contenu HTML
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Emploi du Temps</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                h3 {
                    text-align: center;
                    color: #2c3e50;
                    margin-bottom: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                table td, table th {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: center;
                    font-size: 11px;
                }
                table th {
                    background-color: #e9ecef;
                    font-weight: bold;
                }
                @media print {
                    body {
                        margin: 10px;
                    }
                    table td, table th {
                        padding: 6px;
                        font-size: 9px;
                    }
                }
            </style>
        </head>
        <body>
            <h3>${titre}</h3>
            ${tableau}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    
    // Attendre le chargement puis lancer l'impression
    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
}
</script>

@endsection
