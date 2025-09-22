@extends('layouts.master')

@section('content')


<div class="main-panel-10">
    <div class="content-wrapper">
        @if (Session::has('status'))
        <div id="statusAlert" class="alert alert-success btn-primary">
            {{ Session::get('status') }}
        </div>
        @endif
        {{--  --}}
        <div class="row">          
            <div class="col-12">
                <div class="card mb-6">
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
                </div>
            </div>       
        </div>
        <div class="row">       
            <div class="col">                      
                <div class="card">
                    <br>
                    <button type="button" class="btn btn-primary" style="width: 10rem;" data-bs-toggle="modal" data-bs-target="#ajoutagent">
                        Nouveau
                    </button>
                    <br>
                    <div class="table-responsive" style="height: 300px; overflow: auto;">
                        <table class="table table-striped" style="min-width: 600px; font-size: 10px;">
                            <thead>
                                <tr>
                                    <th class="">Libellé Type</th>
                                    <th>Quota </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="ajoutagent" tabindex="-1" aria-labelledby="ajoutagentLabel" aria-hidden="true" >
  <div class="modal-dialog " > <!-- modal-lg pour plus de largeur -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajoutagentLabel">Type d'agent</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <div class="modal-body">
        <form id="formAjoutAgent">
          <div class="table-responsive">
            <table class="table table-bordered" style="max-width: 600px;">
              <thead class="table-light">
                <tr>
                  <th>Libellé Type</th>
                  <th>Quota</th>
                {{--  <th>Actions</th>--}}
                </tr>
              </thead>
              <tbody id="tableBodyModal">
                <tr>
                  <td><input type="text" name="libelle[]" class="form-control" placeholder="Saisir libellé"></td>
                  <td><input type="number" name="quota[]" class="form-control" placeholder="Valeur quota"></td>
                 {{-- <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Supprimer</button>
                  </td>--}}
                </tr>
              </tbody>
            </table>
          </div>
          {{--<button type="button" class="btn btn-success btn-sm" onclick="addRow()">+ Ajouter une ligne</button>--}}
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<!-- JS pour ajouter/supprimer des lignes -->
<script>
  function addRow() {
    let tbody = document.getElementById("tableBodyModal");
    let row = document.createElement("tr");
    row.innerHTML = `
      <td><input type="text" name="libelle[]" class="form-control" placeholder="Saisir libellé"></td>
      <td><input type="number" name="quota[]" class="form-control" placeholder="Saisir quota"></td>
      <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Supprimer</button></td>
    `;
    tbody.appendChild(row);
  }

  function removeRow(btn) {
    btn.closest("tr").remove();
  }
</script>


@endsection