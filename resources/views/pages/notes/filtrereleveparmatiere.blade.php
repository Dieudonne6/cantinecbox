
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
    </style>
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
                <h4 class="card-title">Editions des releves des notes</h4>
                <div class="form-group row">
                    <div class="col-3">
                        <button class="btn btn-primary" onclick="printNote()">Imprimer</button>
                         <button class="btn-sm btn-primary" type="button" onclick="exportToExcel()">Exporter</button>
                    </div>
                </div>
                <div class="row grid-margin stretch-card">
                    <div class="col-lg-12 mx-auto " id="printNot">
                        <div class="titles">
                            <h3 style="text-transform: uppercase; margin-bottom: 1rem; text-align: center">Releves de notes
                            </h3>
                           {{-- <h5>{{ $nomEtab }} </h5>--}}
                            <p>Classe : {{ $classe }} </p>
                            <p>Matiere : {{ $matiere }} </p>
                            <p> {{ $typean == 2 ? 'Trimestre' : 'Semestre' }}: {{ $periode }} </p>
                            <p>Annee-scolaire : {{ $annescolaire }} </p>
                        </div>

                        {{-- Définition de la fonction d'aide pour filtrer les notes --}}
                        @php
                            function filtrerNote($valeur)
                            {
                                // Vérifie si la valeur est vide, égale à 21 ou à -1
                                if (empty($valeur) || $valeur == 21 || $valeur == -1) {
                                    return '****';
                                }
                                return $valeur;
                            }
                        @endphp

                        <div class="table-responsive mb-4">
                            <table>
                                <thead>
                                    <tr>
                                        {{-- <th rowspan="2">N</th> --}}
                                        <th rowspan="2">Matricule</th>
                                        {{-- <th rowspan="2">MatriculeX</th> --}}
                                        <th rowspan="2">Nom</th>
                                        <th rowspan="2">Prenom</th>
                                        <th colspan="5">Interrogations</th>
                                        <th colspan="3">Devoirs</th>
                                        @if ($typean == 2)
                                            <th rowspan="2">TEST</th>
                                        @endif
                                        <th rowspan="2">MS</th>
                                    </tr>
                                    <tr>
                                        <th>Int1</th>
                                        <th>Int2</th>
                                        <th>Int3</th>
                                        <th>Int4</th>
                                        <th>MI</th>
                                        <th>Dev1</th>
                                        <th>Dev2</th>
                                        <th>Dev3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count = 1;
                                    @endphp
                                    @foreach ($notes as $note)
                                        <tr>
                                            {{-- <td>{{ $count }}</td> --}}
                                            <td class="mat">{{ $note->MATRICULEX ?? '****' }}</td>
                                            <td style="text-align: left;">{{ $note->nom ?? '****' }}</td>
                                            <td style="text-align: left;">{{ $note->prenom ?? '****' }}</td>
                                            <td>{{ filtrerNote($note->INT1) }}</td>
                                            <td>{{ filtrerNote($note->INT2) }}</td>
                                            <td>{{ filtrerNote($note->INT3) }}</td>
                                            <td>{{ filtrerNote($note->INT4) }}</td>
                                            <td>{{ filtrerNote($note->MI) }}</td>
                                            <td>{{ filtrerNote($note->DEV1) }}</td>
                                            <td>{{ filtrerNote($note->DEV2) }}</td>
                                            <td>{{ filtrerNote($note->DEV3) }}</td>
                                            @if ($typean == 2)
                                                <td>{{ filtrerNote($note->TEST) }}</td>
                                            @endif
                                            <td>{{ filtrerNote($note->MS1) }}</td>
                                        </tr>
                                        @php
                                            $count++;
                                        @endphp
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
        function injectTableStyles() {
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

        function printNote() {
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
        const contentElement = document.getElementById('printNot');
        
        if (!contentElement) {
            alert('Aucun relevé à exporter. Veuillez d\'abord le créer.');
            return;
        }

        // Cloner le contenu pour ne pas modifier l'original
        const clone = contentElement.cloneNode(true);

        // style Excel plus propre
        const style = `
            <style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                }
                th, td {
                    border: 1px solid black;
                    padding: 5px;
                    text-align: center;
                    font-size: 20px;
                    line-height: 1.5rem;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
                td.mat {
                    mso-number-format:"0";
                }
            </style>
        `;

        // Construire le HTML complet pour Excel
        const html = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:x="urn:schemas-microsoft-com:office:excel"
                xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta charset="UTF-8">
                ${style}
            </head>
            <body>
                ${clone.innerHTML}
            </body>
            </html>
        `;

        // 🔹 Construire le nom du fichier dynamiquement depuis Blade
        let classe = "{{ $classe }}";
        let matiere = "{{ $matiere }}";
        let periodeType = "{{ $typean == 2 ? 'Trimestre' : 'Semestre' }}";
        let periode = "{{ $periode }}";

        // Nettoyer pour éviter espaces/accents non supportés
        let filename = `Releve_${classe}_${matiere}_${periodeType}_${periode}.xls`
            .replace(/\s+/g, "_")      // remplace espaces par _
            .replace(/[^\w\-]+/g, ""); // supprime caractères spéciaux

        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

   
   </script>
@endsection
