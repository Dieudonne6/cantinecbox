@extends('layouts.master')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <h4 class="card-title mb-0 text-center">Liste par Ordre de mérite</h4>
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
  
        <div class="form-group mt-3 row d-flex align-items-center">
          <div class="col-5">
            <select class="js-example-basic-multiple custom-select-width w-auto" id="groupe" onchange="fetchClasses(this.value)">
                <option value="">Sélectionner un type d'enseignement</option>
                @foreach ($classesg as $classeg)
                    <option value="{{ $classeg->LibelleGroupe }}">
                      {{ $classeg->LibelleGroupe }}</option>
                @endforeach
            </select>
          </div>
        
          <div class="col-5 align-items-center">
                  <select class="js-example-basic-multiple custom-select-width w-auto" id="periode" name="periode" style="margin-left: 20px !important;">
                    <option value="" selected>Sélectionner une période</option>
                    <option value="1">1ère période</option>
                    <option value="2">2ème période</option>
                    <option value="3">3ème période</option>
                    <option value="4">4ème période</option>
                    <option value="5">5ème période</option>
                    <option value="6">6ème période</option>
                    <option value="7">7ème période</option>
                    <option value="8">8ème période</option>
                    <option value="9">9ème période</option>
                  </select>
          </div>        
        
          <div class="col-2">
              {{--<input type="hidden" name="classe" value="{{ Request::segment(2) }}">
              <input type="hidden" name="matricules" id="matricules"> --}}
              <button type="submit" onclick="imprimerPage()" class="btn btn-primary btn-sm">Imprimer</button>
          </div>
        </div>
        
  
        <div class="table-responsive mb-4">
          <table class="table table-bordered" id="tableClasses">
            <thead>
              <tr>
                <th class="text-center"><input type="checkbox" name="selected_classes[]" value="all" style="margin-left: 10px !important; margin-top: -7px !important;" onclick="selectAllCheckboxes(this)"></th>
                <th class="text-center"> Classe </th>
                <th class="text-center"> Effectif </th>
              </tr>
            </thead>
            <tbody id="classTableBody">
          
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <script>
        function selectAllCheckboxes(source) {
      checkboxes = document.getElementsByName('selected_classes[]');
      for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
      }
    }
  </script>

<script>
    function fetchClasses(libelleGroupe) {
        if (!libelleGroupe) {
            $("#classTableBody").html("");
            return;
        }

        $.ajax({
            url: "{{ url('/get-classes-by-group') }}",
            type: "GET",
            data: { libelleGroupe: libelleGroupe },
            success: function(response) {
                let rows = "";
                if (response.length === 0) {
                    rows = "<tr><td colspan='3' class='text-center'>Aucune classe trouvée</td></tr>";
                } else {
                    response.forEach(classe => {
                        rows += `
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" 
                                           name="selected_classes[]" 
                                           value="${classe.CODECLAS}"
                                           style="margin-left: 10px;">
                                </td>
                                <td class="text-center">${classe.CODECLAS}</td>
                                <td class="text-center">${classe.EFFECTIF}</td>
                            </tr>
                        `;
                    });
                }
                $("#classTableBody").html(rows);
            },
            error: function() {
                $("#classTableBody").html("<tr><td colspan='3' class='text-center text-danger'>Erreur lors du chargement</td></tr>");
            }
        });
    }
</script>

<script>
    function imprimerPage() {
        let selectedClasses = [];
        document.querySelectorAll('input[name="selected_classes[]"]:checked').forEach(checkbox => {
            if (checkbox.value !== 'all' && checkbox.value !== 'undefined' && checkbox.value !== '') {
                selectedClasses.push(checkbox.value);
            }
        });

        if (selectedClasses.length === 0) {
            alert("Veuillez sélectionner au moins une classe.");
            return;
        }

        let periode = document.getElementById('periode').value;
        
        if (!periode) {
            alert("Veuillez sélectionner une période.");
            return;
        }

        let url = `{{ url('/imprimer-liste-merite') }}?classes=${selectedClasses.join(',')}&periode=${periode}`;
        window.location.href = url;
    }
</script>

    @endsection