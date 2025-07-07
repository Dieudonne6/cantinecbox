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
                    <th>Référence</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($facturesPaiementscolarite as $facture)
                  <tr>
                    <td>{{ $facture->nom }}</td>
                    <td>{{ $facture->codemecef }}</td>
                    <td>{{ $facture->montant_total }}</td>
                    <td>{{ $facture->dateHeure }}</td>
                    <td>

                        {{-- Sinon, on affiche “Modifier” ET “Supprimer” --}}
                        {{-- <a class="btn btn-primary"
                           href="{{ url('avoirfacturepaiemodif/'.$facture->codemecef) }}">
                          Modifier
                        </a> --}}
                        <a class="btn btn-danger"
                           href="{{ url('avoirfacturepaiescolarite/'.$facture->codemecef) }}">
                          Supprimer
                        </a>
                    </td>
                    
                  </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>



@endsection
