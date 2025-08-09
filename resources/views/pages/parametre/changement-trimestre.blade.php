@extends('layouts.master')
@section('content')

<div class="col-100 grid-margin">
  <div class="card">
    <div>
      <style>/* ton CSS inchangé */</style>

      <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
        <i class="fas fa-arrow-left"></i> Retour
      </button>
      <br><br>
    </div>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
      </div>
    @endif

    <div class="card-body">
      <form class="form-inline" action="{{ route('changement-periode') }}" method="POST">
        @csrf

        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Changement de période</h4>
              <p class="card-description">
                Le <code>chargement de période</code> par ce menu s'impose à toute nouvelle connexion d'un poste quelconque du réseau
              </p>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-check mx-sm-2">Période</label>
                    <div class="col-md-9">
                      <select name="periode" class="form-control" required>
                        @for ($i = 1; $i <= 7; $i++)
                          <option value="{{ $i }}" {{ (old('periode', $current) == (string)$i) ? 'selected' : '' }}>
                            Période {{ $i }}
                          </option>
                        @endfor
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-success mb-2">Enregistrer</button>
              <button type="submit" name="action" value="delete" class="btn btn-danger mb-2"
                      onclick="return confirm('Confirmer la suppression de la période actuelle ?')">Supprimer</button>

            </div>
          </div>
        </div>
      </form> <!-- seul form -->
    </div>
  </div>
</div>

@endsection
