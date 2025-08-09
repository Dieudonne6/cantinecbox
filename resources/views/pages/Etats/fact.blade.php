@extends('layouts.master')
@section('content')
<div class="container">
    <h4 class="card-title">Liste des factures</h4>

    <div class="table-responsive mb-4">
        <table id="myTable" class="table">
          <thead>
            <tr>
              <th>
                Nom
              </th>
              <th>
                Codemecef
              </th>
              <th>
                Counters
              </th>
              <th>
                Montant total
              </th>
             
              {{-- <th>Elève </th> --}}

              <th>
                Action
              </th>
            </tr>
          </thead>
          <tbody id="eleve-details">
            <!-- Boucle sur chaque groupe d'élèves (chaque collection de factures) -->
            @foreach($factures as $eleve)
            <tr>
                <td>
                    {{ $eleve->nom }}
                </td>
                <td>
                    {{ $eleve->codemecef }}
                </td>
                <td>
                    {{ $eleve->counters }}
                </td>
                <td>
                    {{ $eleve->montant_total }}
                </td>
                <td>
                    <a class="btn btn-primary" href="{{ route('factures.show', ['id' => $eleve->id]) }}" target="_blank">Imprimer</a>
                </td>
            </tr>
            {{-- <div>
                Élève : {{ $eleve->nom }}
                <a class="btn btn-success" href="{{ route('factures.show', ['nom' => $eleve->nom], ['logoUrl' => $logoUrl]) }}" target="_blank">Imprimer</a>
            </div> --}}
            @endforeach
        </tbody>
    </table>
  </div>
</div>

<!-- Button trigger modal -->


{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> --}}





@endsection
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}


