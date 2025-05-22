{{-- @extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
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
      <h4 class="card-title">Extraire des notes</h4>
      <form action="{{ url('/extractnote') }}" method="POST">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-3">
            <label for="periode">Période</label>
            <select class="js-example-basic-multiple custom-select-width w-auto" name="periode">
              <option value="">Sélectionnez une période</option>
                <option value="1">1ère période</option>
                <option value="2">2ème période</option>
                <option value="3">3ème période</option>
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
          

          
          <div class="col-3 mt-3">
            <button type="submit" class="btn btn-primary w-100">
              Afficher
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>



</div>


@endsection --}}

 @extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
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

          select.form-control {
        color: #727070 !important; /* Texte en noir pour une meilleure lisibilité */
        background-color: #fff !important; /* Fond blanc pour contraste */
    }

    select.form-control:invalid {
        color: #6c757d; /* Texte grisé pour les options par défaut */
    }
      </style>
      <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
          <i class="fas fa-arrow-left"></i> Retour
      </button>   
      <br>
      <br>                                   
  </div>
    <div class="card-body">
      <h4 class="card-title">Extraire des notes</h4>
    <form action="{{ route('notes.exportMulti') }}" method="GET" id="formExportNotes">
        @csrf
        <div class="row mb-4">
            <!-- Choix de la classe (déjà sélectionnée en lecture seule ou choisie précédemment) -->
            <div class="col-md-4">
                <label for="classe">Classe</label>
                <select class="form-control" id="classe" name="classe" required>
                  <option value="">Sélectionnez une classe</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->CODECLAS }}"
                        {{ (isset($selectedClasse) && $selectedClasse == $classe->CODECLAS) ? 'selected' : '' }}>
                            {{ $classe->CODECLAS }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Choix de la période -->
            <div class="col-md-4">
                <label for="periode">Période</label>
                <select class="form-control" id="periode" name="periode" required>
                    <option value="">Sélectionnez une période</option>
                    <option value="1" {{ (isset($periode) && $periode == '1') ? 'selected' : '' }}>1ère période</option>
                    <option value="2" {{ (isset($periode) && $periode == '2') ? 'selected' : '' }}>2ème période</option>
                    <option value="3" {{ (isset($periode) && $periode == '3') ? 'selected' : '' }}>3ème période</option>
                </select>
            </div>
        </div>

        <!-- Dix menus déroulants pour choisir des matières, ici uniquement les matières filtrées pour la classe -->
        <div class="row" id="matieresRow">
            @for($i = 1; $i <= 15; $i++)
            <div class="col-md-4 mb-2">
                <label for="matiere_{{ $i }}">Matière {{ $i }}</label>
                <select class="form-control matiereSelect" id="matiere_{{ $i }}" name="matieres[]" data-index="{{ $i }}">
                    <option value="">-- Sélectionnez une matière --</option>
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->CODEMAT }}">{{ $matiere->LIBELMAT }}</option>
                    @endforeach
                </select>
            </div>
            @endfor
        </div>

        <button type="submit" class="btn btn-primary mb-4">Afficher</button>
    </form>
  </div>
</div>



</div>
<!-- Script JavaScript pour filtrer les matières déjà sélectionnées -->

{{-- <script>
document.addEventListener("DOMContentLoaded", function() {
    const selects = document.querySelectorAll('.matiereSelect');

    selects.forEach(select => {
        select.addEventListener('change', function() {
            // On récupère les valeurs actuellement sélectionnées parmi tous les selects
            let selectedValues = Array.from(selects).map(s => s.value).filter(val => val !== "");

            // Pour chaque select, masquer les options déjà sélectionnées ailleurs (sauf celles sélectionnées dans ce select)
            selects.forEach(s => {
                s.querySelectorAll('option').forEach(option => {
                    if (option.value !== "") {
                        if (selectedValues.includes(option.value) && s.value !== option.value) {
                            option.style.display = 'none';
                        } else {
                            option.style.display = 'block';
                        }
                    }
                });
            });
        });
    });
});
</script> --}}

<script>
  document.addEventListener("DOMContentLoaded", function() {
      const selects = document.querySelectorAll('.matiereSelect');
      const classeSelect = document.getElementById('classe');
  
      // Gérer le changement de classe pour recharger les matières
      classeSelect.addEventListener('change', function() {
          const codeclasse = this.value;
  
          if (codeclasse) {
              fetch(`/getmatieres/${codeclasse}`)
                  .then(response => response.json())
                  .then(data => {
                      // Pour chaque select de matière, on vide et on recharge les nouvelles options
                      selects.forEach(select => {
                          // Réinitialise les options
                          select.innerHTML = '<option value="">-- Sélectionnez une matière --</option>';
  
                          data.forEach(matiere => {
                              const option = document.createElement('option');
                              option.value = matiere.CODEMAT;
                              option.text = matiere.LIBELMAT;
                              select.appendChild(option);
                          });
                      });
                  });
          }
      });
  
      // Gérer la désactivation des matières déjà sélectionnées
      selects.forEach(select => {
          select.addEventListener('change', function() {
              let selectedValues = Array.from(selects).map(s => s.value).filter(val => val !== "");
  
              selects.forEach(s => {
                  s.querySelectorAll('option').forEach(option => {
                      if (option.value !== "") {
                          if (selectedValues.includes(option.value) && s.value !== option.value) {
                              option.style.display = 'none';
                          } else {
                              option.style.display = 'block';
                          }
                      }
                  });
              });
          });
      });
  });
  </script>
  
@endsection
