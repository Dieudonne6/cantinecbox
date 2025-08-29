@extends('layouts.master')
@section('content')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f2f2f2;
            text-transform: uppercase;
        }

        th,
        td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: center;
        }


                /* --- Styles d'impression à ajouter (copier/coller) --- */
/* <style> */
/* --- CSS à coller: forcer les page-break tout en gardant le HTML/JS tel quel --- */
@media print {

  /* Assurer que la zone d'impression et ses ancêtres ne bloquent pas les sauts */
  #printNot, #printNot * {
    /* rendre les éléments "normaux" pour l'impression afin de permettre les sauts */
    display: revert !important;   /* laisse le navigateur appliquer un display par défaut */
    overflow: visible !important;
    -webkit-print-color-adjust: exact !important;
  }

  /* Protéger la mise en page des tables (elles restent des tables) */
  #printNot table, #printNot thead, #printNot tbody, #printNot tr, #printNot td, #printNot th {
    display: table !important;
    page-break-inside: auto !important;
    break-inside: auto !important;
  }

  /* Répéter l'entête de table sur chaque page */
  #printNot thead { display: table-header-group !important; }
  #printNot tfoot { display: table-footer-group !important; }

  /* Eviter de couper les lignes / corps de table en deux (si possible) */
  #printNot tr, #printNot tbody { page-break-inside: avoid !important; break-inside: avoid !important; }

  /* Règle principale: forcer le saut de page pour ta div .page-break */
  .page-break {
    display: block !important;
    height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    /* compatibilité ancienne + moderne */
    page-break-after: always !important;
    break-after: page !important;
  }

  /* Empêcher la création d'une page blanche finale — masquer la dernière .page-break si elle se trouve dans #printNot */
  #printNot .page-break:last-of-type {
    display: none !important;
  }
}

/* petit style écran pour que .page-break n'affiche pas d'espace visible */
.page-break { line-height: 0; font-size: 0; }

    </style>


<div class="col-lg-12 grid-margin stretch-card">
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
            <div class="container" >
                <div class="d-flex justify-content-between">
                    <button class="btn btn-primary" onclick="imprimerliste()">Imprimer</button>
                    <button class="btn btn-primary btn-sm fw-bold " type="button" onclick="exportToExcel()" >Exporter vers Excel</button>
                </div>
                <div class="col-lg-12 mx-auto " id="printNot">

                @foreach ($matieres as $matiere)
                    @php
                        $hasData = false;
                        foreach ($classes as $classe) {
                            $stat = $stats[$classe->CODECLAS][$matiere->CODEMAT] ?? null;
                            if ($stat && $stat['notesStats']->nombre_eleves > 0) {
                                $hasData = true;
                                break;
                            }
                        }
                    @endphp

                    @if ($hasData) <!-- Vérifier si des données existent avant d'afficher quoi que ce soit pour cette matière -->
                        <h3 class="card-title" style="text-align: center">Statistiques par Matière</h3>
                        {{-- <h5 class="text-center">Trimestre {{ $trimestres->first()->timestreencours }}</h5> --}}
                        <h5>{{ $nomEtab }}</h5><br>
                        <p> {{ $typean == 2 ? 'Trimestre' : 'Semestre' }}: {{ $periodeParDefaut }} </p><br>
                        <p>Annee-scolaire: {{ $annescolaire }}</p><br>
                        <div>
                        <h5>Matière : {{ $matiere->LIBELMAT }}</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Classe</th>
                                    <th>Effectif</th>
                                    <th>+ Forte moyenne</th>
                                    <th>+ Faible moyenne</th>
                                    <th>M < 10</th>
                                    <th>M >= 10</th>
                                    <th>% M >= 10</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classes as $classe)
                                    @php
                                        $stat = $stats[$classe->CODECLAS][$matiere->CODEMAT] ?? null;
                                    @endphp
                                    @if ($stat && $stat['notesStats']->nombre_eleves > 0)
                                        <tr>
                                            <td>{{ $classe->CODECLAS }}</td>
                                            <td>{{ $stat['notesStats']->nombre_eleves }}</td>
                                            <td>{{ $stat['notesStats']->moyenne_max }}</td>
                                            <td>{{ $stat['notesStats']->moyenne_min }}</td>
                                            <td>{{ $stat['notesStats']->nombre_moyenne_inf_10 }}</td>
                                            <td>{{ $stat['notesStats']->nombre_moyenne_sup_10 }}</td>
                                            <td>{{ number_format($stat['pourcentage_moyenne_sup_10'], 2) }}%</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    </div>
                    <div class="page-break"></div>
                    @endif
                @endforeach
            </div>
            </div>
        </div>
    </div>
</div>

{{-- <style>
    .footer {
        position:fixed !important; /* Changer de relative à absolute pour le placer en bas de la carte */
        bottom: 0 !important; /* Assurer que le footer soit en bas */
        width: 100% !important;
        z-index: 10 !important; /* Assurer que le footer soit au-dessus des autres éléments */
    }
    th, td {
        border: 1px solid black !important;
    }
    @media print {
        .sidebar, .navbar, .footer, .noprint, button {
            display: none !important; /* Masquer la barre de titre et autres éléments */
        }
        h3 {
            text-align: center !important;
        }
    }
</style> --}}
<script>
function injectTableStyles () {
 var style = document.createElement('style');
            style.innerHTML = `
      @page { size: landscape; }
          table {
              width: 100%;
              border-collapse: collapse;
          }
              thead {
      background-color: #f2f2f2;
      text-transform: uppercase;
    }
          th, td {
              padding: 6px;
              border: 1px solid #ddd;
              text-align: center;
          }
              .titles {
              display: block  !important;
              }
          .classe-row {
              background-color: #f9f9f9;
              font-weight: bold;
          }`;
            document.head.appendChild(style);
        }

        function imprimerliste() {
            injectTableStyles(); // Injecter les styles pour l'impression
            var originalContent = document.body.innerHTML; // Contenu original de la page
            var printContent = document.getElementById('printNot').innerHTML; // Contenu spécifique à imprimer
            document.body.innerHTML = printContent; // Remplacer le contenu de la page par le contenu à imprimer

            setTimeout(function() {
                window.print(); // Ouvrir la boîte de dialogue d'impression
                document.body.innerHTML = originalContent; // Restaurer le contenu original
            }, 1000);
        }

        function exportToExcel() {
    // ----------------------------
    // 1. Récupération des infos globales
    // ----------------------------
    const ecole = @json($nomEtab); // Nom de l'établissement
    const periode = @json($periodeParDefaut);
    const anneeScolaire = @json($annescolaire);
    const typeAn = @json($typean) == 2 ? "Trimestre" : "Semestre";
    const dateExport = new Date().toLocaleDateString('fr-FR');

    // ----------------------------
    // 2. Récupération de la zone à exporter
    // ----------------------------
    const printZone = document.getElementById('printNot');
    if (!printZone) {
        alert("Aucune donnée à exporter !");
        return;
    }

    // ----------------------------
    // 3. Cloner le contenu pour éviter de le modifier
    // ----------------------------
    const contentCopy = printZone.cloneNode(true);

    // Supprimer les boutons ou éléments inutiles
    contentCopy.querySelectorAll("button").forEach(btn => btn.remove());

    // ----------------------------
    // 4. Styles Excel
    // ----------------------------
    const style = `
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            h3, h5, p {
                text-align: center;
                margin: 5px 0;
            }
            .header {
                text-align: center;
                margin-bottom: 15px;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 25px;
            }
            th, td {
                border: 1px solid black;
                padding: 6px;
                text-align: center;
                font-size: 13px;
            }
            th {
                font-weight: bold;
                background-color: #f2f2f2;
            }
        </style>
    `;

    // ----------------------------
    // 5. Construction du HTML à exporter
    // ----------------------------
    const html = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office"
              xmlns:x="urn:schemas-microsoft-com:office:excel"
              xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="UTF-8">
            ${style}
        </head>
        <body>
            <div class="header">
                <h2>${ecole}</h2>
                <h3>Statistiques par Matière</h3>
                <p>
                    ${typeAn} : ${periode} | Année scolaire : ${anneeScolaire} <br>
                    Exporté le ${dateExport}
                </p>
            </div>
            ${contentCopy.innerHTML}
        </body>
        </html>
    `;

    // ----------------------------
    // 6. Création et téléchargement du fichier Excel
    // ----------------------------
    const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;

    // Nom du fichier dynamique
    a.download = `Statistiques_Matieres_${anneeScolaire}_${dateExport}.xls`;

    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

</script>
@endsection
