
@extends('layouts.master')
@section('content')
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

          select.form-control {
        color: #727070 !important; /* Texte en noir pour une meilleure lisibilité */
        background-color: #fff !important; /* Fond blanc pour contraste */
    }

    select.form-control:invalid {
        color: #6c757d; /* Texte grisé pour les options par défaut */
    }
      </style>
      <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
          <i class="fas fa-arrow-left"></i> Retour
      </button>   
      <br>
      <br>                                   
  </div>

    <div class="card-body">
      <h4 class="card-title">Export de notes pour la classe : {{ $classe }}</h4>
    <h5>{{ $periodLabel }} : {{ $periode }}</h5>

    <div class="col-auto">
        <button class="btn btn-primary ml-3" onclick="exportMultiExcel()">Exporter en Excel</button>
    </div>
    <br>
    <br>
    <!-- Onglets -->
    <ul class="nav nav-tabs" id="matiereTab" role="tablist">
        @php $first = true; @endphp
        @foreach($matieres as $matiere)
            @if(in_array($matiere->CODEMAT, array_keys($result)))
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $first ? 'active' : '' }}" id="tab-{{ $matiere->CODEMAT }}" data-bs-toggle="tab" data-bs-target="#matiere-{{ $matiere->CODEMAT }}" type="button" role="tab" aria-controls="matiere-{{ $matiere->CODEMAT }}" aria-selected="{{ $first ? 'true' : 'false' }}">
                    {{ $matiere->LIBELMAT }}
                </button>
            </li>
            @php $first = false; @endphp
            @endif
        @endforeach
    </ul>

    <div class="tab-content mt-3" id="matiereTabContent">
        @php $first = true; @endphp
        @foreach($result as $matiereCode => $notes)
            <div class="tab-pane fade {{ $first ? 'show active' : '' }}" id="matiere-{{ $matiereCode }}" role="tabpanel" aria-labelledby="tab-{{ $matiereCode }}">
                <h5>Matière : 
                    @php 
                        $mat = $matieres->firstWhere('CODEMAT', $matiereCode); 
                        echo $mat ? $mat->LIBELMAT : $matiereCode; 
                    @endphp
                </h5>

                <table class="table table-bordered table-striped mb-4">
                    <thead>
                        <tr>
                            <th>MATRICULE</th>
                            <th>Nom et Prenom</th>
                            <th>Moyenne Interro</th>
                            <th>DEV1</th>
                            <th>DEV2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notes->groupBy('MATRICULE') as $matricule => $studentNotes)
                            @php
                                $totalInterro = $studentNotes->sum('interro');
                                $countInterro = $studentNotes->count();
                                $moyenneInterro = $countInterro ? number_format($totalInterro / $countInterro, 2) : 0;
                                $firstNote = $studentNotes->first();
                            @endphp
                            <tr>
                                <td>{{ $firstNote->eleve->MATRICULEX }}</td>
                                <td>{{ $firstNote->eleve->NOM . ' ' . $firstNote->eleve->PRENOM }}</td>
                                <td>{{ ($firstNote->MI == 21 || $firstNote->MI == -1) ? '**.**' : round($firstNote->MI,2) }}</td>
                                <td>{{ ($firstNote->DEV1 == 21 || $firstNote->DEV1 == -1) ? '**.**' : $firstNote->DEV1 }}</td>
                                <td>{{ ($firstNote->DEV2 == 21 || $firstNote->DEV2 == -1) ? '**.**' : $firstNote->DEV2 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @php $first = false; @endphp
        @endforeach
    </div>
</div>
</div>
</div>



{{-- </div> --}}

<script>
    function exportMultiExcel() {
        // Récupère les paramètres actuels de l'URL
        let params = new URLSearchParams(window.location.search);
        // Construit l'URL d'export en ajoutant les mêmes paramètres
        let url = "{{ route('notes.exportMultiExcel') }}" + "?" + params.toString();
        window.location.href = url;
    }
    </script>
@endsection
