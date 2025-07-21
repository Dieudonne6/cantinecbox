@extends('layouts.master')

@section('content')
<div class="col-100 grid-margin">
    <div class="card">
      <div>
        <style>
          .btn-arrow {
            position: absolute;
            top: 0px;
            left: 0px;
            background-color: transparent !important;
            border: none !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
            cursor: pointer !important;
            font-size: 17px !important;
            color: #b51818 !important;
          }
          .btn-arrow:hover {
            color: #b700ff !important;
          }
        </style>
        <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
          <i class="fas fa-arrow-left"></i> Retour
        </button>   
        <br>
        <br>                                   
      </div>
    <h4 class="card-title mb-4">Sélection d'un exercice</h4>

    {{-- Affichage des messages --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Tableau des exercices --}}
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Année Scolaire</th>
              <th>Début</th>
              <th>Fin</th>
              <th>Clôturé</th>
              <th>Ouvert par</th>
              <th>Clôturé par</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($exercices as $ex)
              <tr>
                <td>{{ $ex->ANSCOL }}</td>
                <td>{{ $ex->DATEDEB }}</td>
                <td>{{ $ex->DATEFIN }}</td>
                <td>
                  @if($ex->CLOTURE)
                    <span class="badge bg-danger">Oui</span>
                  @else
                    <span class="badge bg-success">Non</span>
                  @endif
                </td>
                <td>{{ $ex->SIGNATUREOUVRE }}</td>
                <td>{{ $ex->SIGNATURECLOTURE ?? '-' }}</td>
                <td>
                  <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $ex->ANSCOL }}">
                    <i class="fas fa-edit"></i> Modifier
                  </button>
                </td>
              </tr>

              {{-- Modal de modification --}}
              <div class="modal fade" id="editModal{{ $ex->ANSCOL}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="POST" action="{{ route('exercice.update', $ex->ANSCOL) }}">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Modifier l'exercice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label>Année Scolaire</label>
                          <input type="text" name="ANNEESCOLAIRE" class="form-control" value="{{ $ex->ANSCOL }}">
                        </div>
                        <div class="mb-3">
                          <label>Date de Début</label>
                          <input type="date" name="DEBUT" class="form-control" value="{{ $ex->DATEDEB }}">
                        </div>
                        <div class="mb-3">
                          <label>Date de Fin</label>
                          <input type="date" name="FIN" class="form-control" value="{{ $ex->DATEFIN }}">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Valider</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="7" class="text-center">Aucun exercice trouvé</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>    
    </div>
</div>
@endsection
