@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Extraire des notes</h4>
      <form action="{{ url('/extractnote') }}" method="POST">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-3">
            <label for="periode">Période</label>
            <select class="js-example-basic-multiple custom-select-width w-auto" name="periode">
              <option value="">Sélectionnez une période</option>
              {{-- @foreach ($annee as $annees) --}}
                <option value="1">1ère période</option>
                <option value="2">2ème période</option>
                <option value="3">3ème période</option>
              {{-- @endforeach --}}
            </select>
          </div>
          
          <div class="col-3">
            <label for="classe">Classe</label>
            <select class="js-example-basic-multiple custom-select-width w-auto" name="classe">
              <option value="">Sélectionnez une classe</option>
              @foreach ($classes as $classe)
                <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="col-3">
            <label for="matiere">Matière</label>
            <select class="js-example-basic-multiple custom-select-width w-auto" name="matiere" id="matiereSelect">
              <option value="">Sélectionnez une matière</option>
              @foreach ($matieres as $matiere)
                <option value="{{ $matiere->CODEMAT }}">{{ $matiere->LIBELMAT }}</option>
              @endforeach
            </select>
          </div>
          
          <!-- Nouveau champ pour le type d'évaluation -->
          {{-- <div class="col-3" id="evaluation-type-div" style="display: none;">
            <label for="evaluation">Évaluation</label>
            <select class="js-example-basic-multiple custom-select-width w-auto" name="evaluation">
              <option value="">Sélectionnez une évaluation</option>
              <option value="Muy interro">Muy interro</option>
              <option value="DEV1">DEV1</option>
              <option value="DEV2">DEV2</option>
              <option value="DEV3">DEV3</option>
            </select>
          </div> --}}
          
          <div class="col-3 mt-3">
            <button type="submit" class="btn btn-primary w-100">
              Afficher
            </button>
          </div>
          {{-- <div class="col-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
              Imprimer la relance
            </button>
          </div> --}}
        </div>
      </form>
    </div>
  </div>



</div>

{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
  const matiereSelect = document.getElementById('matiereSelect');
  const evaluationDiv = document.getElementById('evaluation-type-div');

  // Afficher le select "Évaluation" si une matière est sélectionnée
  matiereSelect.addEventListener('change', function () {
    if (this.value !== '') {
      evaluationDiv.style.display = 'block';
    } else {
      evaluationDiv.style.display = 'none';
    }
  });
});
</script> --}}
@endsection
