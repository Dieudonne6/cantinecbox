@extends('layouts.master')
@section('content')
<style>
  .table-container {
    width: 100%;
    overflow-x: auto;
    margin: 20px auto;
  }
  .btn-arrow {
    position: absolute;
    top: 0px;
    left: 0px;
    background-color: transparent !important;
    border: 1px !important;
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

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
        <i class="fas fa-arrow-left"></i> Retour
    </button>   
    <br><br>                                   
    <div class="card-body">
      <h4 class="card-title">Liste sélective des élèves</h4>

      @if(Session::has('status'))
        <div class="alert alert-success btn-primary">
          {{ Session::get('status') }}
        </div>
      @endif
      @if(Session::has('erreur'))
        <div class="alert alert-danger btn-primary">
          {{ Session::get('erreur') }}
        </div>
      @endif

      <!-- Formulaire de filtrage -->
      <form method="GET" action="{{ route('filterlisteselectiveeleve') }}">
        <div class="form-group row">
          <div class="col-2">
            <select class="js-example-basic-multiple w-100" multiple="multiple" name="classes[]" data-placeholder="Toutes les classes">         
              <option value="">Toutes les classes</option>
              @foreach ($allClasse as $classes)
                <option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
              @endforeach
            </select>
          </div>

          <div class="col-3">
            <select class="js-example-basic-multiple w-100" name="sexe" id="sexeSelect">
              <option value="">Sélectionner le Sexe</option>    
              <option value="2" {{ request('sexe') == 2 ? 'selected' : '' }}>Fille</option>
              <option value="1" {{ request('sexe') == 1 ? 'selected' : '' }}>Garçon</option>
            </select>
          </div>

          <div class="col-4">
            <div class="d-flex mb-2 align-items-center">
              <label>Âge compris entre</label>
              <input type="text" class="mx-2 text-right" 
                     placeholder="{{ $minAgeFromDB }}" 
                     id="minAge" name="minAge" 
                     value="{{ request('minAge') }}" 
                     style="width: 50px !important;"> 
              et 
              <input type="text" class="text-right ml-2" 
                     id="maxAge" name="maxAge" 
                     placeholder="{{ $maxAgeFromDB }}" 
                     value="{{ request('maxAge') }}" 
                     style="width: 50px !important;">
            </div>

            <div class="ml-3">
              <input type="checkbox" id="ignoreYear" name="ignoreYear" value="1" 
                     onchange="toggleAgeInputs(this)" 
                     {{ request('ignoreYear') ? 'checked' : '' }}>
              <label for="ignoreYear">Ne pas considérer l'année</label>
            </div>
          </div>

          <div class="col-1">
            <button class="btn btn-primary" type="submit">Filtrer</button>
          </div>

          <div class="col-2">
            <button onclick="imprimerPage()" type="button" class="btn btn-primary">
              Imprimer Liste
            </button>
          </div>
        </div>
      </form>

      <!-- Tableau des élèves filtrés -->
      <div class="table-responsive mb-4">
        <table id="myTable" class="table table-bordered">
          <thead>
            <tr>
              <th>N</th>
              <th>NOM</th>
              <th>PRENOM</th>
              <th>SEXE</th>
              <th>CLASSE</th>
              <th>DATE DE NAISSANCE</th>
            </tr>
          </thead>
          <tbody>

        </table>
      </div>

      <!-- Script JS pour désactiver les inputs âge si ignoreYear coché -->
      <script>
        function toggleAgeInputs(checkbox) {
            document.getElementById('minAge').disabled = checkbox.checked;
            document.getElementById('maxAge').disabled = checkbox.checked;
        }

        // Initialiser l'état des inputs selon la case cochée au chargement
        window.onload = function() {
            const ignoreYearCheckbox = document.getElementById('ignoreYear');
            toggleAgeInputs(ignoreYearCheckbox);
        };
      </script>

      <!-- Script pour l'impression -->
      <script>
        function imprimerPage() {
          let originalTitle = document.title;
          document.title = `Liste des élèves`;
          window.print();
          document.title = originalTitle;
        }
      </script>

    </div>
  </div>
</div>
@endsection
