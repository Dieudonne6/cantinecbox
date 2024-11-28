@extends('layouts.master')
@section('content')
<div class="main-panel-10">
  
  <div class="container">
    @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
    @endif
    
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <form action="{{ route('enregistrer_notes') }}" method="POST">
      @csrf
      <div class="row">
        <div class="col-6 md-3 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                <div class="row w-100">
                  <!-- Select pour le groupe -->
                  <div class="col-md-8 mb-3">
                    <select class="form-select select2 w-100" id="tableSelect1"
                    aria-label="Choisir un groupe">
                    <option value="" selected>Choisir un groupe</option>
                    @foreach ($gclasses as $gclasse)
                    <option value="{{ $gclasse->LibelleGroupe }}">
                      {{ $gclasse->LibelleGroupe }}
                    </option>
                    @endforeach
                  </select>
                </div>
                
                <!-- Select pour la période -->
                <div class="col-md-8 mb-3">
                  <select class="form-select select2 w-100 mt-2" id="periodSelect"
                  onchange="updateCheckbox()" aria-label="Choisir une période">
                  <option value="" selected>Période</option>
                  <option value="1">1ère Période</option>
                  <option value="2">2ème Période</option>
                  <option value="3">3ème Période</option>
                  <option value="4">4ème Période</option>
                  <option value="5">5ème Période</option>
                  <option value="6">6ème Période</option>
                  <option value="7">7ème Période</option>
                  <option value="8">8ème Période</option>
                  <option value="9">9ème Période</option>
                </select>
              </div>
              
              <!-- Champ de nombre -->
              <div class="col-md-4">
                <input type="number" id="champ1" name="champ1" class="form-control"
                placeholder="Valeur" value="" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-6 md-3 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
            <div class="col">
              <div class="col-md-8 mb-3">
                <select class="form-select select2 w-100" id="tableSelect4" name="CODECLAS"
                onchange="redirectWithSelection()">
                <option value="">Classe</option>
                @foreach ($classes as $classeOption)
                <option value="{{ $classeOption->CODECLAS }}"
                  {{ $classeOption->CODECLAS == $classe ? 'selected' : '' }}>
                  {{ $classeOption->CODECLAS }}
                </option>
                @endforeach
              </select>
            </div>
            
            <div class="col-md-8 mb-3">
              <select class="form-select select2 w-100 mt-2" id="tableSelect5" name="CODEMAT"
              onchange="redirectWithSelection()">
              <option value="">Matières</option>
              @foreach ($matieres as $matiereOption)
              <option value="{{ $matiereOption->CODEMAT }}"
                {{ $matiereOption->CODEMAT == $matiere ? 'selected' : '' }}>
                {{ $matiereOption->LIBELMAT }}
              </option>
              @endforeach
            </select>
          </div>
        </div>
        <!-- Champ de nombre -->
        <div class="col-md-4 mb-3">
          <input type="number" id="champ2" name="champ2" class="form-control"
          value="{{ $getClasmat ? $getClasmat->COEF : 'Valeur non trouvée' }}" placeholder="Valeur" readonly>
        </div>
      
      </div>
    </div>
  </div>
</div>
</div>

<div class="col-12 md-3 grid-margin">
  <!-- Card pour afficher les cases à cocher -->
  <div class="card">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-6 d-flex flex-wrap" id="intCheckboxes">
          @for ($i = 5; $i <= 10; $i++)
          <label class="checkbox-label interro-checkbox me-2"
          for="optionINT{{ $i }}" data-interro="{{ $i }}">
          <input type="checkbox" id="optionINT{{ $i }}" name="optionGroup1[]"
          value="INT{{ $i }}"
          onchange="toggleColumn({{ $i }})">
          INT{{ $i }}
        </label>
        @endfor
      </div>
      <div class="col-md-6 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-rounded">
          <i class="typcn typcn-home-outline"></i> Enregistrer
        </button>
        
        <button type="button" class="btn btn-danger btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <i class="typcn typcn-delete-outline"></i>
          Supprimer
        </button>
        
        
      </div>
    </div>
    
  </div>
</div>
</div>

<div class="col-12 md-3 grid-margin">
  <!-- Card pour afficher le tableau -->
  <div class="card">
    <div class="card-body">
      <div class="table-responsive mb-4">
          <table id="myTab" class="table table-bordered">
              <thead>
                  <tr>
                      <th>MATRICULE</th>
                      <th>Nom et Prénoms</th>
                      <th class="interro-column" data-interro="1">Int1</th>
                      <th class="interro-column" data-interro="2">Int2</th>
                      <th class="interro-column" data-interro="3">Int3</th>
                      <th class="interro-column" data-interro="4">Int4</th>
                      <th class="interro-column" data-interro="5">Int5</th>
                      <th class="interro-column" data-interro="6">Int6</th>
                      <th class="interro-column" data-interro="7">Int7</th>
                      <th class="interro-column" data-interro="8">Int8</th>
                      <th class="interro-column" data-interro="9">Int9</th>
                      <th class="interro-column" data-interro="10">Int10</th>
                      <th>M.int</th>
                      <th>Dev1</th>
                      <th>Dev2</th>
                      <th>Dev3</th>
                      <th>Moy</th>
                      <th>Test</th>
                      <th>Ms</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($eleves as $eleve)
                      <tr>
                          <td>{{ $eleve->MATRICULE ?? '' }}</td>
                          <td>{{ $eleve->NOM ?? '' }}<br>{{ $eleve->PRENOM ?? '' }}</td>

                          @for ($i = 1; $i <= 10; $i++)
                              <td class="interro-column" data-interro="{{ $i }}">
                                  <input type="text"
                                      name="notes[{{ $eleve->MATRICULE }}][INT{{ $i }}]"
                                      value="{{ $eleve['INT' . $i] ?? '' }}"
                                      class="form-control form-control-sm interro-input"
                                      oninput="calculateMIAndMoy(this)">
                              </td>
                          @endfor

                          <td>
                              <input type="text" name="notes[{{ $eleve->MATRICULE }}][MI]"
                                  value="{{ $eleve->MI ?? '' }}"
                                  class="form-control form-control-sm mi-input" readonly>
                          </td>
                          <td><input type="text" name="notes[{{ $eleve->MATRICULE }}][DEV1]"
                                  value="{{ $eleve->DEV1 ?? '' }}"
                                  class="form-control form-control-sm dev-input"
                                  oninput="calculateMIAndMoy(this)"></td>
                          <td><input type="text" name="notes[{{ $eleve->MATRICULE }}][DEV2]"
                                  value="{{ $eleve->DEV2 ?? '' }}"
                                  class="form-control form-control-sm dev-input"
                                  oninput="calculateMIAndMoy(this)"></td>
                          <td><input type="text" name="notes[{{ $eleve->MATRICULE }}][DEV3]"
                                  value="{{ $eleve->DEV3 ?? '' }}"
                                  class="form-control form-control-sm dev-input"
                                  oninput="calculateMIAndMoy(this)"></td>
                          <td>
                              <input type="text" name="notes[{{ $eleve->MATRICULE }}][MS]"
                                  value="{{ $eleve->MS ?? '' }}"
                                  class="form-control form-control-sm moy-input" readonly>
                          </td>
                          <td><input type="text" name="notes[{{ $eleve->MATRICULE }}][TEST]"
                                  value="{{ $eleve->TEST ?? '' }}"
                                  class="form-control form-control-sm test-input"
                                  oninput="calculateMIAndMoy(this)"></td>
                          <td><input type="text" name="notes[{{ $eleve->MATRICULE }}][MS1]"
                                  value="{{ $eleve->MS1 ?? '' }}"
                                  class="form-control form-control-sm moy1-input"readonly>
                          </td>
                      </tr>
                  @endforeach
              </tbody>
          </table>
      </div>
      <script>
        const selectElement = document.getElementById('tableSelect1');
      
        // Sauvegarder la valeur sélectionnée dans le localStorage
        selectElement.addEventListener('change', () => {
          localStorage.setItem('selectedGroup', selectElement.value);
        });
      
        // Restaurer la valeur sauvegardée lors du chargement de la page
        document.addEventListener('DOMContentLoaded', () => {
          const savedValue = localStorage.getItem('selectedGroup');
          if (savedValue) {
            selectElement.value = savedValue;
          }
        });
     
          function calculateMIAndMoy(input) {
              const row = input.closest('tr');
      
              // Calcul de MI (moyenne des notes d'interrogation)
              const interroInputs = row.querySelectorAll('.interro-input');
              let interroSum = 0;
              let interroCount = 0;
      
              interroInputs.forEach(interro => {
                  const value = parseFloat(interro.value);
                  if (!isNaN(value)) {
                      interroSum += value;
                      interroCount++;
                  }
              });
      
              const miField = row.querySelector('.mi-input');
              const mi = interroCount > 0 ? (interroSum / interroCount).toFixed(2) : '';
              miField.value = mi;
      
              // Calcul de Moy (moyenne de MI et des scores DEV)
              const devInputs = row.querySelectorAll('.dev-input');
              let devSum = 0;
              let devCount = 0;
      
              devInputs.forEach(dev => {
                  const value = parseFloat(dev.value);
                  if (!isNaN(value)) {
                      devSum += value;
                      devCount++;
                  }
              });
      
              const moyField = row.querySelector('.moy-input');
              const moy = devCount > 0 ? ((parseFloat(mi) + devSum) / (devCount + 1)).toFixed(2) : '';
              moyField.value = moy;
      
              // Calcul de MS1 (moyenne de Moy et TEST)
              const testInput = row.querySelector('[name*="TEST"]');
              const testValue = parseFloat(testInput.value) || 0;
      
              const ms1Field = row.querySelector('[name*="MS1"]');
              if (devCount === 0) {
                  // Si aucun score DEV n'est présent, MS1 = MI
                  ms1Field.value = mi;
              } else {
                  // Sinon, MS1 est basé sur Moy et Test
                  const ms1 = (parseFloat(moyField.value) + testValue) / (testValue > 0 ? 2 : 1);
                  ms1Field.value = ms1.toFixed(2);
              }
      
              // Calcul de MS (moyenne de MS1 et TEST)
              const msField = row.querySelector('[name*="MS"]');
              const ms = (parseFloat(ms1Field.value) + testValue) / (testValue > 0 ? 2 : 1);
              msField.value = ms.toFixed(2);
          }
      </script>
      

  </div>
  </div>
  <br><br><br>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Voulez vous vriament supprimer les notes?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('delete-notes') }}" method="POST">
          @csrf
          <div class="modal-body">
            <input type="hidden" value="CE1A" id="tableSelec4" name="classe">
            <input type="hidden" value="1" id="tableSelec5" name="matiere">
            
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Supprimer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    function updateCodeMat() {
      // Récupère la valeur sélectionnée dans le select de matières
      var selectedMatiere = document.getElementById("tableSelect2").value;
      // Met à jour le champ d'input avec le CODEMAT sélectionné
      document.getElementById("champ2").value = selectedMatiere;
    }
    
    function updateInterroVisibility() {
      const value = parseInt(document.getElementById('champ2').value);
      document.querySelectorAll('.interro-checkbox').forEach(checkbox => {
        const interroNumber = parseInt(checkbox.dataset.interro);
        checkbox.style.display = interroNumber <= value ? 'inline-block' : 'none';
      });
      
      document.querySelectorAll('.interro-column').forEach(column => {
        const interroNumber = parseInt(column.dataset.interro);
        column.style.display = interroNumber <= value ? '' : 'none';
      });
    }
    
    function updateCheckbox() {
  const periodSelect = document.getElementById('periodSelect');
  const champ1 = document.getElementById('champ1');

  // Met à jour la valeur de champ1 avec la valeur sélectionnée dans periodSelect
  if (periodSelect.value) {
    champ1.value = periodSelect.value;

    // Sauvegarde de la sélection dans localStorage
    localStorage.setItem('selectedPeriod', periodSelect.value);
  } else {
    champ1.value = '';
    localStorage.removeItem('selectedPeriod');
  }
}

// Restaure la sélection après le rechargement
window.addEventListener('DOMContentLoaded', () => {
  const periodSelect = document.getElementById('periodSelect');
  const champ1 = document.getElementById('champ1');
  const savedPeriod = localStorage.getItem('selectedPeriod');

  if (savedPeriod) {
    periodSelect.value = savedPeriod;
    champ1.value = savedPeriod;
  }
});

    
    function redirectWithSelection() {
      const classe = document.getElementById("tableSelect4").value; // Récupère la classe sélectionnée
      const matiere = document.getElementById("tableSelect5").value; // Récupère la matière sélectionnée
      let url = '/filternotes'; // URL de redirection
      let params = [];
      
      if (classe) params.push(`classe=${classe}`); // Ajoute le paramètre de classe si sélectionné
      if (matiere) params.push(`matiere=${matiere}`); // Ajoute le paramètre de matière si sélectionné
      
      if (params.length > 0) {
        url += '?' + params.join('&'); // Crée la chaîne de requête
      }
      
      window.location.href = url; // Redirige vers l'URL construite
    }
    
    // Ajoute les écouteurs d'événements pour les deux sélecteurs
    document.getElementById("tableSelect4").addEventListener("change", redirectWithSelection);
    document.getElementById("tableSelect5").addEventListener("change", redirectWithSelection);
    document.getElementById("tableSelec4").addEventListener("change", redirectWithSelection);
    document.getElementById("tableSelec5").addEventListener("change", redirectWithSelection);
  </script>
  
  <script>
    function toggleColumn(interroNumber) {
      // Récupère l'état de la case à cocher
      const checkbox = document.getElementById("optionINT" + interroNumber);
      const isChecked = checkbox.checked;
      
      // Récupère toutes les cellules de la colonne correspondante
      const columns = document.querySelectorAll(`.interro-column[data-interro="${interroNumber}"]`);
      
      // Affiche ou masque la colonne selon l'état de la case à cocher
      columns.forEach(column => {
        column.style.display = isChecked ? '' : 'none';
      });
    }
    
    // Initialisation : Masque les colonnes Int5 à Int10 au chargement de la page
    document.addEventListener("DOMContentLoaded", () => {
      for (let i = 5; i <= 10; i++) {
        toggleColumn(i);
      }
    });
  </script>
  <style>
    /* Améliore l'affichage des champs de saisie */
    .table thead th,
    .table tbody td {
      text-align: center;
      vertical-align: middle;
    }
    
    .form-control-sm {
      width: 100%;
      padding: 0px;
      text-align: center;
      border: 1px solid #ddd;
    }
    
    /* Ajustement des marges dans les cellules */
    td {
      padding: 0px;
    }
  </style>
  @endsection
  