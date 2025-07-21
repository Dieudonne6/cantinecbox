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
        <br><br>
      </div>
  <div>
    <div class="card-body">
      <h4 class="card-title">Configuration Imprimante</h4>

        <div class="content-wrapper">
            <div class="row">

            <!-- Formulaire -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Configuration Imprimante</h4>

                    {{-- Affichage des messages --}}
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- <form method="POST" action="{{ route('imprimante.store') }}"> --}}
                    @csrf

                    <div class="form-group">
                        <label>Nom de l'imprimante</label>
                        <input type="text" name="nom" class="form-control" placeholder="Nom ex: EPSON-TX300" required>
                    </div>

                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control" required>
                        <option value="Thermique">Thermique</option>
                        <option value="Laser">Laser</option>
                        <option value="Jet d'encre">Jet d'encre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Port</label>
                        <select name="port" class="form-control" required>
                        <option value="USB">USB</option>
                        <option value="Wi-Fi">Wi-Fi</option>
                        <option value="Ethernet">Ethernet</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Définir comme imprimante par défaut ?</label><br>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="defaut" value="1">
                        <label class="form-check-label">Oui</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="defaut" value="0" checked>
                        <label class="form-check-label">Non</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    {{-- </form> --}}
                </div>
                </div>
            </div>

            <!-- Liste des imprimantes -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Imprimantes configurées</h4>

                    {{-- @if($imprimantes->isEmpty()) --}}
                    <p>Aucune imprimante configurée.</p>
                    {{-- @else --}}
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Port</th>
                            <th>Défaut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{-- @foreach ($imprimantes as $imp)
                            <tr>
                            <td>{{ $imp->nom }}</td>
                            <td>{{ $imp->type }}</td>
                            <td>{{ $imp->port }}</td>
                            <td>
                                @if($imp->defaut)
                                <span class="badge bg-success">Oui</span>
                                @else
                                Non
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('imprimante.destroy', $imp->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                </form>
                            </td>
                            </tr>
                        @endforeach --}}
                        </tbody>
                    </table>
                    {{-- @endif --}}
                </div>
                </div>
            </div>

            </div>
        </div>

    </div>
</div>
@endsection
