@extends('layouts.master')

@section('content')
<div class="container">
    <h4 class="card-title mb-5">Annuler une facture</h4>

    <!-- SELECT pour choisir le type de factures -->
    <div class="mb-5 d-flex align-items-center">
      <label for="invoiceType" class="form-label me-2 mb-0">
        Type de facture :
      </label>
      <select id="invoiceType" class="form-select w-auto">
        <option value="paiement">Factures de paiement</option>
        <option value="inscription">Factures d'inscription</option>
      </select>
    </div>
    

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
                @foreach ($facturesPaiement as $facture)
                  <tr>
                    <td>{{ $facture->nom }}</td>
                    <td>{{ $facture->codemecef }}</td>
                    <td>{{ $facture->montant_total }}</td>
                    <td>{{ $facture->datepaiementcontrat }}</td>
                    <td>
                      @if($facture->typefac === 1)
                        {{-- Si typefac == 1, on n'affiche que “Supprimer” --}}
                        <a class="btn btn-danger"
                           href="{{ url('avoirfacturepaie/'.$facture->codemecef) }}">
                          Supprimer
                        </a>
                      @else
                        {{-- Sinon, on affiche “Modifier” ET “Supprimer” --}}
                        <a class="btn btn-primary"
                           href="{{ url('avoirfacturepaiemodif/'.$facture->codemecef) }}">
                          Modifier
                        </a>
                        <a class="btn btn-danger"
                           href="{{ url('avoirfacturepaie/'.$facture->codemecef) }}">
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

    <!-- Tableau Inscription -->
    <div id="inscriptionTable" style="display: none;">
        <h5>Factures d'inscription</h5>
        <div class="table-responsive mb-4">
            <table class="table table-striped" id="myTable1">
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
                @foreach ($facturesInscription as $facture)
                  <tr>
                    <td>{{ $facture->nom }}</td>
                    <td>{{ $facture->codemecef }}</td>
                    <td>{{ $facture->montant_total }}</td>
                    <td>{{ $facture->datepaiementcontrat }}</td>
                    <td>
                      <a class="btn btn-primary"
                         href="{{ url('avoirfactureinscri/'.$facture->codemecef) }}">
                         Modifier 
                      </a>
                      <a class="btn btn-danger"
                         href="{{ url('avoirfactureinscri/'.$facture->codemecef) }}">
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

<!-- Script JS pour basculer entre les deux tableaux -->
{{-- @push('scripts') --}}
<script>
    document.getElementById('invoiceType').addEventListener('change', function() {
        var choix = this.value;
        document.getElementById('paiementTable').style.display    = (choix === 'paiement') ? '' : 'none';
        document.getElementById('inscriptionTable').style.display = (choix === 'inscription') ? '' : 'none';
    });
</script>
{{-- @endpush --}}

@endsection
