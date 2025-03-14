@extends('layouts.master')
@section('content')
<style>
    .footer {
        position:fixed !important; /* Changer de relative à absolute pour le placer en bas de la carte */
        bottom: 0 !important; /* Assurer que le footer soit en bas */
        width: 100% !important;
        z-index: 10 !important; /* Assurer que le footer soit au-dessus des autres éléments */
    }
</style>

<div class="col-lg-12 grid-margin stretch-card" style="padding-bottom: 3rem !important;">
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
      <div class="row mb-3">
        @if(Session::has('status'))
        <div id="statusAlert" class="alert alert-succes btn-primary">
        {{ Session::get('status')}}
        </div>
      @endif
      @if($errors->any())
      <div id="statusAlert" class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
      </div>
      @endif
  
      @if(Session::has('error'))
        <div id="statusAlert" class="alert alert-danger">
          {{ Session::get('error')}}
        </div>
        @endif
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
              <th class="dt-hit acti">Nom court</th>
              <th class="dt-hit acti">Type matière</th>
              <th class="dt-hit acti">Mat_ligne</th>
              <th class="dt-hit acti">Action</th>
            </tr>
          </thead>
          <tbody>
            
            @foreach ($matiere as $mat)
            <tr>
              <td>  {{$mat->CODEMAT}}</td>
              <td>  {{$mat->LIBELMAT}}</td>
              <td class="dt-hit acti" style="background-color: {{$mat->COULEUR}}; color: {{ $mat->COULEURECRIT == 16777215 ? '#fff' : '#000' }};">
                {{$mat->NOMCOURT}}
              </td>
              <td class="dt-hit acti">  
                <?php 
                if($mat->TYPEMAT == 1){
                  echo 'Littéraires';
                } else if($mat->TYPEMAT == 2){
                  echo 'Scientifiques';
                } else {
                  echo 'Autres';
                }
                ?>
                {{-- @php
                dd($mat->TYPEMAT);
                @endphp --}}
              </td>
              
              <td class="dt-hit acti"> {{$mat->CODEMAT_LIGNE}} </td>
              <td class="dt-hit acti">
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
                                <input type="text" class="form-control" readonly style="width: 100px; height: 35px;" id="codeMatiereModif" name="codemat">
                              </div>
                              <div style="margin-left:70px !important">
                                <label>Mat_ligne</label>
                                <input type="text" id="matligneModif" name="matligneModif" value="{{ old('matligneModif', $mat->CODEMAT_LIGNE ?? '') }}" style="width: 100px; height: 38px;">
                              </div>
                              <div class="me-3" style="margin-left:100px !important">
                                <label>Libelle matière</label>
                                <input type="text" id="libelleModif" name="libelleModif" value="{{ old('libelleModif', $mat->LIBELMAT ?? '') }}" style="width: 100px; height: 35px;" required>
                              </div>
                              <div class="me-3 w-5" style="margin-left:90px !important">
                                <label> Nom court</label>
                                <input type="text" id="nomcourtModif" name="nomcourtModif" value="{{ old('nomcourtModif', $mat->NOMCOURT ?? '') }}" style="width: 100px; height: 35px;" required>
                              </div>
                              <div>
                                <label>Choisir la couleur</label>
                                <input type="color" class="form-control" id="colorPickerModif" name="couleurModif" value="#FFFFFF" required>
                              </div>
                            </div>
                            <br>
                            <div class="d-flex justify-content-between">
                              <div>
                                <label>Type matière</label>
                                <select name="typematiereModif" id="typematiereModif" class="form-control js-example-basic-multiple custom-select-width" style="width: 180px; height: 35px;">
                                  {{-- <option value="{{ old('typematiereModif', $mat->TYPEMAT ?? '') }}" selected>{{ old('typematiereModif', $mat->TYPEMAT ?? '') }}</option> --}}
                                  <option value="1">Littéraires</option>
                                  <option value="2">Scientifiques</option>
                                  <option value="3">Autres</option>
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
                    <input type="text" value="{{ $nextCodeMat }}" class="form-control" readonly style="width: 100px; height: 35px;">
                  </div>
                  <div style="margin-left:70px !important">
                    <label>Mat_ligne</label>
                    <input type="text" placeholder="0" class="form-control" name="matligne" style="width: 150px; height: 35px;">
                  </div>
                  <div class="me-3" style="margin-left:100px !important">
                    <label>Libelle matière</label>
                    <input type="text" placeholder="Libelle matiere" class="form-control" name="libelle" required>
                  </div>
                  <div class="me-3 w-5" style="margin-left:90px !important">
                    <label> Nom court</label>
                    <input type="text" placeholder="Nom court" class="form-control" name="nomcourt" maxlength="4" required style="width: 120px; height: 35px;">
                  </div>
                  <div style="margin-left:100px !important">
                    <label>Choisir la couleur</label>
                    <input type="color" id="colorPicker" class="form-control" name="couleur" >
                  </div>
                </div>
                <br>
                <div class="d-flex justify-content-between">
                  <div>
                    <label>Type matière</label>
                    <select name="typematiere" id="typematiere" class="form-control js-example-basic-multiple custom-select-width" style="width: 180px; height: 35px;">
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
    function imprimerArriereConstat() {
    var originalContent = document.body.innerHTML; // Contenu original de la page
    var printDiv = document.createElement('div');
    let table = $('#myTable').DataTable();
    let currentPage = table.page();  
    table.destroy();

    // Récupérer le contenu à imprimer en utilisant l'ID
    var contenuAImprimer = document.getElementById('ArriereConstat').innerHTML; // Remplacez 'ArriereConstat' par l'ID approprié

    printDiv.innerHTML = `
        <h1 style="font-size: 20px; text-align: center; text-transform: uppercase;">
            Liste des matières
        </h1>
                ${contenuAImprimer}
        </table>
    `;

    // Appliquer des styles pour l'impression
    let style = document.createElement('style');
    style.innerHTML = `
        @page { size: landscape; }
        @media print {
          .acti {
            display: none;
          }
          th, td {
            border: 1px solid gray !important;
          }
          #myTable {
              width: 50%;
              margin: auto; /* Centre le tableau */
          }
          body * { visibility: hidden; }
          #printDiv, #printDiv * { visibility: visible; }
          #printDiv { position: absolute; top: 0; left: 0; width: 100%; }
        }
    `;

    // Ajouter les styles et le contenu à imprimer au document
    document.head.appendChild(style);
    document.body.appendChild(printDiv);
    printDiv.setAttribute("id", "printDiv");

    // Lancer l'impression
    window.print();
    document.body.removeChild(printDiv);
    document.head.removeChild(style);
}
    window.populateModal = function(mat) {
      const elements = {
        codeMatiereModif: 'CODEMAT',
        matligneModif: 'CODEMAT_LIGNE',
        libelleModif: 'LIBELMAT',
        nomcourtModif: 'NOMCOURT',
        colorPickerModif: 'COULEUR',
        typematiereModif: 'TYPEMAT',
        couleurecritmodif: 'COULEURECRIT'
      };

      for (const [key, value] of Object.entries(elements)) {
        const element = document.getElementById(key);
        if (!element) {
          console.error(`L'élément avec l'ID ${key} n'existe pas.`);
          continue;
        }

        switch (key) {
          case 'typematiereModif':
          element.value = mat[value];
            break;
          case 'couleurecritmodif':
            element.checked = mat[value] === 0;
            break;
          case 'colorPickerModif':
            element.value = mat[value] ?? '#FFFFFF';
            break;
          default:
            element.value = mat[value];
            break;
        }
      }

    }
    </script>
  @endsection