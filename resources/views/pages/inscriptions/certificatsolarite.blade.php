@extends('layouts.master')
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <div class="d-flex align-items-center">
        <button class="btn btn-arrow me-2" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
        <h4 class="card-title mb-0 text-center">Certificat de scolarité</h4>
    </div>
    
      @if(Session::has('status'))
        <div id="statusAlert" class="alert alert-success">
          {{ Session::get('status') }}
        </div>
      @endif
      @if(Session::has('erreur'))
        <div id="statusAlert" class="alert alert-danger">
          {{ Session::get('erreur') }}
        </div>
      @endif

      <div class="form-group row d-flex align-items-center">
        <div class="col-4">
          <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
            <option value="{{ url('certificatsolarite') }}">Sélectionnez une classe</option>
            @foreach ($classe as $classes)
              <option value="{{ url('certificatsolarite/'.$classes->CODECLAS) }}" {{ Request::segment(2) == $classes->CODECLAS ? 'selected' : '' }}>
                {{ $classes->CODECLAS }}
              </option>
            @endforeach
          </select>
        </div>
      
        <div class="col-6 d-flex align-items-center">
          <label for="observation" class="me-4 mb-2">Observation</label>
          <textarea id="observation" name="observation" class="form-control" rows="3" placeholder="Saisir une observation"></textarea>
        </div>        
      
        <div class="col-auto">
          <form action="{{ url('certificatsolarite/impression') }}" method="POST" id="printForm" class="d-inline-block">
            @csrf
            <input type="hidden" name="classe" value="{{ Request::segment(2) }}">
            <input type="hidden" name="matricules" id="matricules">
      
            <button type="submit" class="btn btn-primary btn-sm">Imprimer</button>
          </form>
        </div>
      </div>
      

      <div class="table-responsive mb-4">
        <table class="table table-bordered" id="myTable">
          <thead>
            <tr>
              <th><input type="checkbox" id="selectAll"></th>
              <th> Matricule </th>
              <th> Nom </th>
              <th> Prénom </th>
              <th> Date de naissance </th>
              <th> Assiduité </th>
              <th> Conduite </th>
              <th> Travail </th>
            </tr>
          </thead>
          <tbody id="eleve-details">
            @foreach ($eleves as $eleve)
            <tr class="clickable-row" data-matricule="{{ $eleve->MATRICULE }}">
              <td><input type="checkbox" name="eleves[]" value="{{ $eleve->MATRICULE }}" required></td>
              <td>{{ $eleve->MATRICULE }}</td>
              <td>{{ $eleve->NOM }}</td>
              <td>{{ $eleve->PRENOM }}</td>
              <td>{{ \Carbon\Carbon::parse($eleve->DATENAIS)->format('d/m/Y') }}</td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  // Fonction pour sélectionner ou désélectionner toutes les cases
  document.getElementById('selectAll').addEventListener('click', function() {
    const checkboxes = document.querySelectorAll('input[name="eleves[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
  });

  // Fonction pour ajouter les matricules sélectionnés au champ caché
  document.getElementById('printForm').addEventListener('submit', function() {
    const selectedCheckboxes = document.querySelectorAll('input[name="eleves[]"]:checked');
    const matricules = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
    document.getElementById('matricules').value = matricules.join(',');
  });

  // Ajouter un événement de clic à chaque ligne pour sélectionner la case correspondante
  document.querySelectorAll('.clickable-row').forEach(row => {
    row.addEventListener('click', function(event) {
      // Empêcher le clic de lier la case à cocher
      if (event.target.type !== 'checkbox') {
        const checkbox = this.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
      }
    });
  });
</script>

@endsection
