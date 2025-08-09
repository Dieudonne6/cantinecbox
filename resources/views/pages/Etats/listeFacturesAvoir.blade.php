@extends('layouts.master')

@section('content')
<div class="container">
    
    <h4 class="card-title mb-5">Liste des factures d'avoir</h4>

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
    <div id="paiementTable" >
        <h5 class="mb-4">Factures d'avoir de paiement</h5>
        <div class="table-responsive mb-5">
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
                @foreach ($facturesAvoirPaiement as $facture)
                  <tr>
                    <td>{{ $facture->nom }}</td>
                    <td>{{ $facture->codemecef }}</td>
                    <td>- {{ $facture->montant_total }}</td>
                    <td>{{ $facture->datepaiementcontrat }}</td>
                    <td>
                        <a href="{{ url('pdfduplicatapaie/' . str_replace(' ', '', preg_replace('/\//', '_', trim($facture->counters), 1))) }}"
                            class="btn btn-primary">
                            Imprimer
                        </a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tableau Inscription -->
    <div id="inscriptionTable" style="display: none;">
        <h5 class="mb-4">Factures d'avoir d'inscription</h5>
        <div class="table-responsive mb-5">
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
                @foreach ($facturesAvoirInscription as $facture)
                  <tr>
                    <td>{{ $facture->nom }}</td>
                    <td>{{ $facture->codemecef }}</td>
                    <td>- {{ $facture->montant_total }}</td>
                    <td>{{ $facture->datepaiementcontrat }}</td>
                    <td>
                        <a href="{{ url('pdfduplicatainscription/' . str_replace(' ', '', preg_replace('/\//', '_', trim($facture->counters), 1))) }}"
                            class="btn btn-primary">
                            Imprimer
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
