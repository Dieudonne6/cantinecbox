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
              <td class="dt-hit">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newModalmodif" onclick="populateModal({{ json_encode($mat) }})">
                    Modifier
                </button>
            </td>
                  <!-- Modal -->
                  <!-- Modal de modification -->
                  <div class="modal fade" id="newModalmodif" tabindex="-1" aria-labelledby="newModalLabelmodif" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabelmodif">Fiche d'une matière</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <form action="{{ route('updatetabledesmatieres') }}" method="POST">
                            @csrf
                            @method('PUT')  
                            <div class="d-flex">
                              <div>
                                <label>Code matière</label>
                                <input type="text" class="form-control" readonly style="width: 100px; height: 50px;" id="codeMatiereModif" name="codemat">
                              </div>
                              <div style="margin-left:70px !important">
                                <label>Mat_ligne</label>
                                <input type="text" id="matligneModif" name="matligneModif" value="{{ old('matligneModif', $mat->CODEMAT_LIGNE ?? '') }}" style="width: 100px; height: 50px;" required>
                              </div>
                              <div class="me-3" style="margin-left:100px !important">
                                <label>Libelle matière</label>
                                <input type="text" id="libelleModif" name="libelleModif" value="{{ old('libelleModif', $mat->LIBELMAT ?? '') }}" style="width: 100px; height: 50px;" required>
                              </div>
                              <div class="me-3 w-5" style="margin-left:90px !important">
                                <label> Nom court</label>
                                <input type="text" id="nomcourtModif" name="nomcourtModif" value="{{ old('nomcourtModif', $mat->NOMCOURT ?? '') }}" style="width: 100px; height: 50px;" required>
                              </div>
                              <div>
                                <label>Choisir la couleur</label>
                                <input type="color" id="colorPickerModif" name="couleurModif" value="{{ old('couleurModif', $mat->COULEUR ?? '#FFFFFF') }}" required>
                              </div>
                            </div>
                            <br>
                            <div class="d-flex justify-content-between">
                              <div>
                                <label>Type matière</label>
                                <select name="typematiereModif" id="typematiere" class="form-control js-example-basic-multiple custom-select-width" style="width: 180px; height: 50px;">
                                  <option value="1" {{ (old('typematiereModif', $mat->TYPEMAT ?? 0) == 1) ? 'selected' : '' }}>Littéraires</option>
                                  <option value="2" {{ (old('typematiereModif', $mat->TYPEMAT ?? 0) == 2) ? 'selected' : '' }}>Scientifiques</option>
                                  <option value="3" {{ (old('typematiereModif', $mat->TYPEMAT ?? 0) == 3) ? 'selected' : '' }}>Autres</option>
                              </select>
                              </div>
                              <div>
                              <div style="background-color: rgb(255, 189, 65); padding: 10px;">
                                <div class="form-check mb-3 d-flex align-items-center">
                                  <input type="checkbox" id="flexCheckConduitemodif" name="typematiere" value="1" >
                                  <label class="form-check-label" for="flexCheckConduitemodif">
                                    Cocher si c'est la matière CONDUITE (DISCIPLINE)
                                  </label>
                                </div>
                                <div class="form-check mb-3 d-flex align-items-center">
                                  <input type="checkbox" id="flexCheckEPSmodif" name="typematiere" value="2">
                                  <label class="form-check-label" for="flexCheckEPSmodif">
                                    Cocher si c'est la matière EPS (SPORT)
                                  </label>
                                </div>
                              </div>
                              <div class="form-check">
                                  <input type="checkbox" id="couleurecritmodif" name="ecritModif" value="1" {{ (old('ecritModif', $mat->COULEURECRIT ?? 1) == 1) ? 'checked' : '' }}>
                                <label class="form-check-l" for="couleurecritmodif">
                                  Couleur de l'écrit en noir
                                </label>
                              </div>
                            </div>
                            <br>
            
                              <div style="display: none;">
                                <label>Code couleur sélectionné</label>
                                <input type="text" id="colorCodemodif" class="form-control" readonly name="couleurcodemodif">
                              </div>
                              <script>
                                document.getElementById('colorPickerModif').addEventListener('input', function() {
                                  document.getElementById('colorCodemodif').value = this.value;
                                });
                              </script>
                              <div class="form-check mb-3 d-flex align-items-center">
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
              <form action="{{route('tabledesmatieres')}}" method="POST">
                @csrf
                <div class="d-flex">
                  <div>
                    <label>Code matière</label>
                    <input type="text" value="{{ $nextCodeMat }}" class="form-control" readonly style="width: 100px; height: 50px;">
                  </div>
                  <div style="margin-left:70px !important">
                    <label>Mat_ligne</label>
                    <input type="text" placeholder="0" class="form-control" name="matligne" style="width: 150px; height: 50px;">
                  </div>
                  <div class="me-3" style="margin-left:100px !important">
                    <label>Libelle matière</label>
                    <input type="text" placeholder="Libelle matiere" class="form-control" name="libelle" required>
                  </div>
                  <div class="me-3 w-5" style="margin-left:90px !important">
                    <label> Nom court</label>
                    <input type="text" placeholder="Nom court" class="form-control" name="nomcourt" maxlength="4" required style="width: 120px; height: 50px;">
                  </div>
                  <div style="margin-left:100px !important">
                    <label>Choisir la couleur</label>
                    <input type="color" id="colorPicker" class="form-control" name="couleur">
                  </div>
                </div>
                <br>
                <div class="d-flex justify-content-between">
                  <div>
                    <label>Type matière</label>
                    <select name="typematiere" id="typematiere" class="form-control js-example-basic-multiple custom-select-width" style="width: 180px; height: 50px;">
                      <option value="1">Littéraires</option>
                      <option value="2">Scientifiques</option>
                      <option value="3">Autres</option>
                    </select>
                  </div>
                  <div>
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
                  <div class="form-check">
                    <input class="form-check-inp" type="checkbox" value="" id="couleurecrit" name="ecrit" style="margin-left:110px !iimportant">
                    <label class="form-check-l" for="couleurecrit">
                      Couleur de l'écrit en noir
                    </label>
                  </div>
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
              min-width: 400px !important;
              margin: auto !important;
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
    function populateModal(mat) {
      document.getElementById('codeMatiereModif').value = mat.CODEMAT;
      document.getElementById('matligneModif').value = mat.CODEMAT_LIGNE; // Assurez-vous que l'ID est correct
      document.getElementById('libelleModif').value = mat.LIBELMAT; // Assurez-vous que l'ID est correct
      document.getElementById('nomcourtModif').value = mat.NOMCOURT; // Assurez-vous que l'ID est correct
      document.getElementById('colorPickerModif').value = mat.COULEUR; // Assurez-vous que l'ID est correct
      document.getElementById('typematiere').value = mat.TYPEMAT === 1 ? 1 : mat.TYPEMAT === 2 ? 2 : mat.TYPEMAT === 3 ? 3 : '';
      document.getElementById('couleurecritmodif').checked = mat.COULEURECRIT === 0; // Vérifie si la couleur de l'écrit est noir
  }
  </script>
  @endsection