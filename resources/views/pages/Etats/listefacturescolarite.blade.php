@extends('layouts.master')

@section('content')

<div class="card">

    <div class="row ">
        <div class="col">
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
                    
                            @if(Session::has('status'))
                                <div id="statusAlert" class="alert alert-succes btn-primary">
                                {{ Session::get('status')}}
                                </div></br>
                            @endif

                            @if(Session::has('erreur'))
                                <div id="statusAlert" class="alert alert-danger btn-primary">
                                {{ Session::get('erreur')}}
                                </div></br>
                            @endif
                </div>
                <div class="card-body">
                    <h4 class="card-title">Annuler une facture</h4>

    <!-- SELECT pour choisir le type de factures -->
    {{-- <div class="mb-5 d-flex align-items-center">
      <label for="invoiceType" class="form-label me-2 mb-0">
        Type de facture :
      </label>
      <select id="invoiceType" class="form-select w-auto">
        <option value="paiement">Factures de paiement</option>
        <option value="inscription">Factures d'inscription</option>
      </select>
    </div> --}}
    

    <!-- Tableau Paiement -->
    <div id="paiementTable">
        <h5>Factures de paiement</h5>
        <div class="table-responsive mb-4">
            <table class="table table-striped" id="myTable">
                <thead>
                  <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Référence</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($facturesPaiementscolarite as $facture)
                  <tr>
                    {{-- <td>{{ $facture->nom }}</td> --}}
                    <td>{{  \Illuminate\Support\Str::before(trim($facture->nom ?? ''), ' ') }}</td>
                    <td>{{ implode(' ', array_slice(preg_split('/\s+/', trim($facture->nom ?? '')), 1)) }}</td>
                    <td>{{ $facture->codemecef }}</td>
                    <td>{{ $facture->montant_total }}</td>
                    <td>{{ $facture->dateHeure }}</td>

                    <td>
                    @if ($facture->typefac == '1')
                        <a class="btn btn-primary"
                           href="{{ url('modiffacturepaiescolaritesimple/'.$facture->id) }}">
                          Modifier
                        </a>

                        {{-- <a class="btn btn-danger"
                           href="{{ url('suppfacturescolaritenonnormalise/'.$facture->id) }}">
                          Supprimer
                        </a>     --}}
                        
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#exampleModal-{{ $facture->id }}" >Supprimer</button>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal-{{ $facture->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de
                                            suppression</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer ce paiement ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Annuler</button>
                                        <form action="{{ url('suppfacturescolaritenonnormalise/'.$facture->id) }}" method="POST">
                                            {{-- <form action="{{ url('supprimercontrat/'.$eleves->MATRICULE)}}" method="POST"> --}}
                                            @csrf
                                            {{-- @method('PUT') --}}
                                            {{-- <input type="hidden" value="{{ $eleves->MATRICULE }}"
                                                name="matricule"> --}}
                                            {{-- <a href='/admin/deletecashier/{{$eleves->MATRICULE}}' class='btn btn-danger w-50'>Suspendre</a> --}}
                                            <input type="submit" class="btn btn-danger" value="Confirmer">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
              
                    @else
                        {{-- Sinon, on affiche “Modifier” ET “Supprimer” --}}
                        <a class="btn btn-primary"
                           href="{{ url('avoirfacturepaiescolaritemodif/'.$facture->codemecef) }}">
                          Modifier
                        </a>
                        <a class="btn btn-danger"
                           href="{{ url('avoirfacturepaiescolarite/'.$facture->codemecef) }}">
                          Supprimer
                        </a>
                    @endif
                    </td>
                    
                  </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>



@endsection
