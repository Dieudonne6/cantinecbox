@extends('layouts.master')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-lg-10 card">
      <div class="card-body">
        <h4 class="card-title">Transfert de données</h4>
      <div class="row p-3">
        <div class="col-lg-3">
          <h6>Diriger vers</h6>
          <select  class="form-select">
            <option> Fichier texte </option>
            <option> Excel </option>
            <option>  XML </option>
          </select>
        </div>
        <div class="col-lg-4">
          <h6>Repertoire de destination</h6>
          <input type="file" class="form-control" placeholder="\Tempo12">
        </div>
        <div class="col-lg-3">
          <h6>Nom du fichier</h6>
          <input type="text" class="form-control" placeholder="eleve">
        </div>
        <div class="col-lg-2 mt-4">
          <input type="submit" class="btn btn-primary" value="Demarer">
        </div>
      </div>
      <div class="table-responsive">
        <table id="" class="table table-striped">
          <thead>
            <tr>
              <th>N ORDRE</th>
              <th>Matricule</th> 
              <th>Nom</th>
              <th>Prenom</th>
              <th>Classe</th>
              <th>Sexe</th>
              <th> Statut</th>
              <th>Datenais</th>
              <th>Lieunais</th>
              <th>Nevers</th>
              <th>Moy1</th>
              <th>Moy2</th>
              <th>Moy3</th>
              <th>Moyan</th>
              <th>Rang1</th>
              <th>Rang2</th>
              <th>Rang3</th>
              <th>Rangan</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>4</td>
              <td>00004</td>
              <td>KOUNOU</td>
              <td>Orane</td>
              <td>CE2B</td>
              <td>Feminin</td>
              <td><input type="checkbox" class=""></td>
              <td>04/09/2021</td>
              <td>cotonou</td>
              <td>###</td>
              <td>4</td>
              <td>6</td>
              <td>8</td>
              <td>10</td>
              <td>19</td>
              <td>89</td>
              <td>10</td>
              <td>19</td>
            </tr>    
            <tr>
              <td>4</td>
              <td>00004</td>
              <td>KOUNOU</td>
              <td>Orane</td>
              <td>CE2B</td>
              <td>Feminin</td>
              <td><input type="checkbox" class=""></td>
              <td>04/09/2021</td>
              <td>cotonou</td>
              <td>###</td>
              <td>4</td>
              <td>6</td>
              <td>8</td>
              <td>10</td>
              <td>19</td>
              <td>89</td>
              <td>10</td>
              <td>19</td>
            </tr>      
          </tbody>  
        </table>
      </div>
    </div>
    </div>
    <div class="col-lg-2 card">
      <h5>Colonnes</h5>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Matricule
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Nom
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Prenom
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
         Sexe
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Statut
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Classe
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Datenais
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Lieunais
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Nevers
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Moy1
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Moy2
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Moy3
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Moyan
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Rang1
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Rang2
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Rang3
        </label>
      </div>
      <div class="form-check m-0">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" checked>
          Rangan
        </label>
      </div>
    </div>

    
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
      // Map checkbox labels to table column indices
      const columnMapping = {
          'Matricule': 1,
          'Nom': 2,
          'Prenom': 3,
          'Classe': 4,
          'Sexe': 5,
          'Statut': 6,
          'Datenais': 7,
          'Lieunais': 8,
          'Nevers': 9,
          'Moy1': 10,
          'Moy2': 11,
          'Moy3': 12,
          'Moyan': 13,
          'Rang1': 14,
          'Rang2': 15,
          'Rang3': 16,
          'Rangan': 17
      };
  
      // Hide/show columns based on checkbox state
      $('input[type=checkbox]').change(function() {
          let columnLabel = $(this).parent().text().trim();
          let columnIndex = columnMapping[columnLabel] + 1; // Adjust for 0-based index and th
  
          if ($(this).is(':checked')) {
              $('table tr').each(function() {
                  $(this).find('td:nth-child(' + columnIndex + '), th:nth-child(' + columnIndex + ')').show();
              });
          } else {
              $('table tr').each(function() {
                  $(this).find('td:nth-child(' + columnIndex + '), th:nth-child(' + columnIndex + ')').hide();
              });
          }
      });
  
      // Trigger change event on page load to apply initial visibility
      $('input[type=checkbox]').trigger('change');
  });
  </script>
  
@endsection