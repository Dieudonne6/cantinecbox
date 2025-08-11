@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
  .btn-arrow {
      position: absolute;
      top: 0px; /* Ajustez la position verticale */
      left: 0px; /* Positionnez à gauche */
      background-color: transparent !important;
      border:1px !important;
      text-transform: uppercase !important;
      font-weight: bold !important;
      cursor: pointer!important;
      font-size: 17px!important; /* Taille de l'icône */
      color: #b51818!important; /* Couleur de l'icône */
  }
  
  .btn-arrow:hover {
      color: #b700ff !important; /* Couleur au survol */
  }
  .offcanvas-body p {
    font-size: 1rem;
  }
  </style>
    <div class="card">
        <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <button class="btn btn-arrow" onclick="window.history.back();">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
            </div>
           
            <div>
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Aide</button>
        
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                  <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Duplicata facture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <p>Pour télécharger une facture qui exite déja <br>il faut:</p>
                    <p> 1- Sélectionner l’élève </p>
                    <p> 2- Sélectionner le type de facture </p>
                    <p> 3- Cliquer sur Afficher </p>
                    <p> 4- Cliquer "Imprimer" si vous voulez imprimer</p>

                  </div>
                </div>             
            </div>
        </div>
        <br></br>
        <div class="tab-content col-md-12" id="nav-tabContent">
            <!-- Onglet principal -->
            <div class="tab-pane fade show active" id="nav-cantine" role="tabpanel" aria-labelledby="nav-cantine-tab">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab1" role="tablist">
                        <button class="nav-link active" id="nav-etatdroitconstate-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-etatdroitconstate" type="button" role="tab"
                            aria-controls="nav-etatdroitconstate" aria-selected="true">Duplicata Facture</button>
                    </div>
                </nav>
                <br><br>
                    <!-- Affichage des messages d'erreur -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (isset($message))
                        <div class="alert alert-warning">
                            {{ $message }}
                        </div>
                    @endif

                    <!-- Formulaire de filtrage -->
                    <form method="POST" action="{{ route('filterduplicata') }}">
                        @csrf
                        <div class="row">
                            <!-- Sélection de l'élève -->
                            <div class="col-3">
                                <select class="js-example-basic-multiple w-100" id="eleve-select" name="eleve_id">
                                    <option value="">Sélectionnez un élève</option>
                                    @foreach ($eleves as $eleve)
                                        <option value="{{ $eleve->MATRICULE }}">
                                            {{ $eleve->NOM }} {{ $eleve->PRENOM }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

        <!-- Sélection du type de facture -->
        <div class="col-4"> <!-- Augmentation de la largeur -->
            <select class="js-example-basic-multiple w-100" id="facture-type-select"
                name="facture_type">
                <option value="">Sélectionnez un type de facture</option>
                <option value="facturenormalises">Facture de paiement</option>
                <option value="contrat">Facture d'inscription</option>
            </select>
        </div>

                            <!-- Bouton pour afficher les résultats -->
                            <div class="col-3">
                                <button type="submit" id="afficher-btn" class="btn btn-primary w-100">Afficher</button>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau pour afficher les factures -->
                    @if (isset($factures) && $factures->count() > 0)
                        <div class="table-responsive pt-3">
                            <div id="mytable">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>N° de facture</th>
                                            <th>Date de facture</th>
                                            <th>Montant Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($factures as $facture)
                                            @if ($facture->montant_total > 0)
                                                <tr>
                                                    <td>{{ $facture->nim }}/{{ $facture->counters }}</td>
                                                    <td>
                                                        {{
                                                            \Carbon\Carbon::hasFormat($facture->dateHeure, 'Y-m-d H:i:s') 
                                                            ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $facture->dateHeure)->format('d/m/Y') 
                                                            : (\Carbon\Carbon::hasFormat($facture->dateHeure, 'd/m/Y H:i:s') 
                                                            ? \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $facture->dateHeure)->format('d/m/Y') 
                                                            : 'Format de date non supporté')
                                                          }}
                                                    </td>

                                                    @php
                                                        $typefa = $facture->counters;
                                
                                                        // echo($typefa)
                                                    @endphp

                                                    @if (substr($typefa, -2) === 'FA')
                                                        <td>- {{ $facture->montant_total }}</td>
                                                    @else
                                                        <td>{{ $facture->montant_total }}</td>
                                                    @endif
                                                    <td>
                                                        <a href="{{ url('pdfduplicatapaie/' . str_replace(' ', '', preg_replace('/\//', '_', trim($facture->counters), 1))) }}"
                                                            class="btn btn-primary">
                                                            Imprimer
                                                        </a>
                                                    </td>
                                                </tr>
                                            @else
                                                <div class="alert alert-warning">
                                                    Aucun contrat trouvé pour l'élève sélectionné.
                                                </div>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif


                    @if (isset($contrats) && $contrats->count() > 0)
                        <div class="table-responsive pt-3">
                            <div id="mytable">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>N° de facture</th>
                                            <th>Date de facture</th>
                                            <th>Montant Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contrats as $contrat)
                                            @if ($contrat->montant_total > 0)
                                                <tr>
                                                    <td>{{ $contrat->nim }}/{{ $contrat->counters }}</td>

                                                    {{-- <td>{{ $contrat->eleve_contrat }}</td> --}}
                                                    <td>
                                                        {{
                                                            \Carbon\Carbon::hasFormat($contrat->dateHeure, 'Y-m-d H:i:s') 
                                                            ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $contrat->dateHeure)->format('d/m/Y ') 
                                                            : (\Carbon\Carbon::hasFormat($contrat->dateHeure, 'd/m/Y H:i:s') 
                                                            ? \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $contrat->dateHeure)->format('d/m/Y ') 
                                                            : 'Format de date non supporté')
                                                          }}
                                                    </td>
                                                    @php
                                                    $typefa = $contrat->counters;
                            
                                                    // echo($typefa)
                                                @endphp

                                                @if (substr($typefa, -2) === 'FA')
                                                    <td>- {{ $contrat->montant_total }}</td>
                                                @else
                                                    <td>{{ $contrat->montant_total }}</td>
                                                @endif
                                                    {{-- <td>{{ $contrat->montant_total }}</td> --}}
                                                    <td>
                                                        {{-- <a href="{{ url('pdfduplicatacontrat/' . $contrat->id_contrat) }}"
                                                            class="btn btn-primary">
                                                            Imprimer
                                                        </a> --}}

                                                        <a href="{{ url('pdfduplicatainscription/' . str_replace(' ', '', preg_replace('/\//', '_', trim($contrat->counters), 1))) }}"
                                                            class="btn btn-primary">
                                                            Imprimer
                                                        </a>
                                                    </td>
                                                </tr>
                                            @else
                                                <div class="alert alert-warning">
                                                    Aucun contrat trouvé pour l'élève sélectionné.
                                                </div>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
