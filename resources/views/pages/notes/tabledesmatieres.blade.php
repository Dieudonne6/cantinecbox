@extends('layouts.master')
@section('content')

<div class="col-lg-12 grid-margin stretch-card" style="padding-bottom: 3rem !important;">
  <div class="card">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-lg-9">
          <h4 class="card-title">Mise à jour des matières</h4>
        </div>
        <div class="col-lg-3">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newModal">
            Nouveau      
          </button>
          <button id="" class="btn btn-primary" onclick="imprimerArriereConstat()"> Imprimer</button>
        </div>
        
      </div>
      
      
      <div id="ArriereConstat">
        <h3 class="text-center mb-4" style="display: none;">Mise à jour des matières</h3>
        
        <table class="table table-striped" id="myTable">
          <thead>
            <tr>
              <th>
                Code
              </th>
              <th>
                Libelle matiere
              </th>
              <th class="dt-hit">Nom court</th>
              <th class="dt-hit">Type matière</th>
              <th class="dt-hit">Mat_ligne</th>
              <th class="dt-hit">Action</th>
            </tr>
          </thead>
          <tbody>
            
            @foreach ($matiere as $mat)
            <tr>
              <td>  {{$mat->CODEMAT}}</td>
              <td>  {{$mat->LIBELMAT}}</td>
              <td class="dt-hit" style="background-color: {{$mat->COULEUR}}; color: {{ $mat->COULEURECRIT == 16777215 ? '#fff' : '#000' }};">
                {{$mat->NOMCOURT}}
              </td>
              <td class="dt-hit">  
                <?php 
                if($mat->CODEMAT == 1){
                  echo 'Littéraires';
                } else if($mat->CODEMAT == 2){
                  echo 'Scientifiques';
                } else {
                  echo 'Autres';
                }
                ?>
              </td>
              
              <td class="dt-hit"> {{$mat->CODEMAT_LIGNE}} </td>
              <td class="dt-hit"><!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  Modifier      </button>
                  <!-- Modal -->
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          ...
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
              
              
            </tbody>
          </table>
        </div>
        
      </div>
      <div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Fiche d'une matière</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="{{route('storetabledesmatieres')}}" method="POST">
                @csrf
                <div class="d-flex">
                  <div>
                    <label>Code matiere</label>
                    <input type="text" value="{{ $nextCodeMat }}" class="form-control w-100" readonly>
                  </div>
                  <div style="margin-left:120px !important">
                    <label>Mat_ligne</label>
                    <input type="text" placeholder="0" class="form-control w-100" name="matligne">
                  </div>
                </div>
                <br>
                <div class="d-flex justify-content-between">
                  <div class="me-3">
                    <label>Libelle matiere</label>
                    <input type="text" placeholder="Libelle matiere" class="form-control" name="libelle" required>
                  </div>
                  <div class="me-3">
                    <label> Nom court (Pour la gestion de l'emploi du temps)</label>
                    <input type="text" placeholder="Nom court" class="form-control" name="nomcourt" maxlength="4" required>
                  </div>
                  <div>
                    <label>Type matière</label>
                    <select name="typematiere" class="form-control js-example-basic-multiple custom-select-width">
                      <option value="1">Littéraires</option>
                      <option value="2">Scientifiques</option>
                      <option value="3">Autres</option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="d-flex justify-content-between">
                  <div class="d-flex justify-content-between">
                    <div style="background-color: rgb(255, 189, 65); padding: 10px;">
                      <div class="form-check mb-3 d-flex align-items-center">
                        <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckConduite" style="margin-left: 4px;" name="conduite">
                        <label class="form-check-label" for="flexCheckConduite">
                          Cocher si c'est la matière CONDUITE (DISCIPLINE)
                        </label>
                      </div>
                      <div class="form-check mb-3 d-flex align-items-center">
                        <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckEPS" style="margin-left: 4px;" name="eps">
                        <label class="form-check-label" for="flexCheckEPS">
                          Cocher si c'est la matière EPS (SPORT)
                        </label>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div>
                    <label>Choisir la couleur</label>
                    <input type="color" id="colorPicker" class="form-control" name="couleur">
                  </div>
                  <br>
                  <div style="display: none;">
                    <label>Code couleur sélectionné</label>
                    <input type="text" id="colorCode" class="form-control" readonly name="couleurcode">
                  </div>
                  <script>
                    document.getElementById('colorPicker').addEventListener('input', function() {
                      document.getElementById('colorCode').value = this.value;
                    });
                  </script>
                  <div class="form-check mb-3 d-flex align-items-center">
                    <input class="form-check-input me-2" type="checkbox" value="" id="couleurecrit" style="margin-left: 4px;" name="ecrit">
                    <label class="form-check-label" for="couleurecrit">
                      Couleur de l'écrit en noir
                    </label>
                  </div>
                </div>
                <div class="text-end">
                  <p>Evitez les couleurs jaune pure et gris foncé</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                  <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
    
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
    function injectTableStyles() {
      var style = document.createElement('style');
      style.innerHTML = `
      @page { size: landscape; }
      
          table {

              // width: 600px !important;
              margin: 0 auto !important;
              border-collapse: collapse;
              
          }
              h3 {
              display: block !important;
               }
          .dt-end, .dt-start, .hide-printe, .offcanvas { display: none !important; }

          thead {
              background-color: #f2f2f2;
          }
              tr {
                text-align: center;

              }
          th, td {
              padding: 6px;
              border: 1px solid #ddd;
              text-align: center !important;
          }
              .dt-hit {
              display : none;}
          .classe-row {
              background-color: #f9f9f9;
              font-weight: bold;
          }`;
      document.head.appendChild(style);
    }
    function imprimerArriereConstat() {
      injectTableStyles(); // Injecter les styles pour l'impression
      // let originalTitle = document.title;
      // document.title = `Liste des matières`;
      var originalContent = document.body.innerHTML; // Contenu original de la page
      var printContent = document.getElementById('ArriereConstat').innerHTML; // Contenu spécifique à imprimer
      document.body.innerHTML = printContent; // Remplacer le contenu de la page par le contenu à imprimer
      
      setTimeout(function() {
        window.print(); // Ouvrir la boîte de dialogue d'impression
        document.body.innerHTML = originalContent;
        window.location.reload();
        // Restaurer le contenu original
      }, 1000);
    }
  </script>
  @endsection
  