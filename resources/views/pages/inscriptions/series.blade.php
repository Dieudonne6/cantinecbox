@extends('layouts.master')
@section('content')

<div class="main-panel-10">
    <div class="content-wrapper">

      @if(Session::has('status'))
        <div id="statusAlert" class="alert alert-danger btn-primary">
          {{ Session::get('status')}}
        </div>
      @endif

      <div class="row">          
        <div class="col-12">
          <div class="card mb-6">
            <div class="card-body">
              <h4 class="card-title">Gestion des séries</h4>
              <div class="row gy-3">
                <div class="demo-inline-spacing">
                  <!-- Button nouveau trigger modal -->
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalNouveau"> Nouveau</button>
                  <style>
                    table {
                      float: right;
                      width: 10%;
                      border-collapse: collapse;
                      margin: 5px auto;
                    }
                    th, td {
                      /* border: 1px solid #aaa1a1; */
                      padding: 4px;
                      text-align: center;
                    }
                    th {
                      /* background-color: #f2f2f2; */
                    }
                    td.bouton {
                      /* background-color: #ffcccb; */
                    }
                  </style>
                </div>
              </div>
            </div>
          </div>
        </div>       
      </div>

      <div class="row">
        <div class="col">
                
          <div class="card">
            <div class="table-responsive" style="overflow: auto;" >
              <table class="table table-striped" style="min-width: 600px; font-size: 10px;" id="myTable">
                <thead>
                  <tr>
                    <th class="">Série</th>
                    <th>Libellé série</th>
                    <th>Cycle</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($series as $serie)

                  <tr>
                    <td>{{ $serie->SERIE }}</td>
                    <td>{{ $serie->LIBELSERIE }}</td>
                    <td>{{ $serie->CYCLE }}</td>
                    <td>
                        <div class="">
                            <!-- Button modifier trigger modal -->
                            <button type="button" class="btn btn-primary p-2 btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalModifier{{ $serie->SERIE }}"> Modifier</button>

                            <!-- Button supprimer trigger modal -->
                            <button type="button" class="btn btn-danger p-2 btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalDelete{{ $serie->SERIE }}">Supprimer</button>
                          </div>
                    </td>
                  </tr>
    <!-- Modal bouton Modifier -->
    <div class="modal fade" id="exampleModalModifier{{ $serie->SERIE }}" tabindex="-2" aria-labelledby="exampleModalLabel2" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel2">Modifier fiche d'une série</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{url('/modifierserie')}}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="SERIE" id="edit-serie" value="{{ $serie->SERIE }}">
              <div class="form-group">
                  <div class="form-group row">
                      <div class="col-sm-4">
                        <div>
                            <label><strong>Série</strong> (Donner un code pour la série à créer [2 caractères]. Ex: C)</label>
                            <input type="text" name="SERIE" value="{{ $serie->SERIE }}" placeholder="" id="edit-serie" class="form-control" readonly>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div>
                            <label><strong>Libellé série</strong> (Donner le libellé de la série à créer. Ex: Série C)</label>
                            <input type="text" name="LIBELSERIE" value="{{ $serie->LIBELSERIE }}" placeholder="" id="edit-libelserie" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-sm-4">
                          <label><strong>Préciser le Cycle</strong></label>
                          <select name="CYCLE" class="js-example-basic-multiple w-100" required>
                            <option value="1" {{ $serie->CYCLE == 1 ? 'selected' : '' }}>1er Cycle</option>
                            <option value="2" {{ $serie->CYCLE == 2 ? 'selected' : '' }}>2eme Cycle</option>
                            <option value="3" {{ $serie->CYCLE == 3 ? 'selected' : '' }}>3eme Cycle</option>
                            <option value="0" {{ $serie->CYCLE == 0 ? 'selected' : '' }}>Aucun</option>
                        </select>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Enregistrer</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
          </div>
        </form>
        </div>
      </div>
    </div>


        <!-- Modal bouton supprimer -->
    <div class="modal fade" id="exampleModalDelete{{ $serie->SERIE }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de suppression</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Êtes-vous sûr de vouloir supprimer cette série ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <form action="{{ url('/supprimerserie')}}" method="POST">
              @csrf
              @method('DELETE')
              <input type="hidden" name="SERIE" value="{{ $serie->SERIE }}">
              <input type="submit" class="btn btn-danger" value="Confirmer">
            </form>  
          </div>
        </div>
      </div>
    </div>
</div>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
  </div>


  <!-- Modal bouton Nouveau-->
  <div class="modal fade" id="exampleModalNouveau" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Enregistrement d'une série</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @if($errors->any())
          <div id="statusAlert" class="alert alert-danger">
              <ul>
                  @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
          @endif
          <form id="myformclas" action="{{url('/saveserie')}}" method="POST" >
            @csrf
            <div class="form-group">
                <div class="form-group">
                    <div class="col">
                      <div>
                          <label><strong>Série</strong> (Donner un code pour la série à créer [2 caractères]. Ex: C)</label>
                          <input type="text" name="SERIE" placeholder="" class="form-control" required minlength="2" maxlength="3" pattern="^[A-Z][A-Z0-9]{1,2}$" title="La série doit commencer par une lettre majuscule et comporter entre 2 et 3 caractères, uniquement des lettres majuscules ou des chiffres.">
                      </div>
                    </div>
                    <br>
                    <div class="col">
                      <div>
                          <label><strong>Libellé série</strong> (Donner le libellé de la série à créer. Ex: Série C)</label>
                          <input type="text" name="LIBELSERIE" placeholder="" class="form-control" >
                      </div>
                    </div>
                    <br>
                    <div class="col">
                        <label><strong>Préciser le Cycle</strong></label>
                        <select name="CYCLE" class="js-example-basic-multiple w-100" required>
                          <option value="1">1er Cycle</option>
                          <option value="2">2eme Cycle</option>
                          <option value="3">3eme Cycle</option>
                          <option value="0">Aucun</option>
                      </select>
                     </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
        </div>
       </form>
      </div>
    </div>
  </div>

@endsection
<script>


  document.addEventListener('DOMContentLoaded', function() {
          var myModal = new bootstrap.Modal(document.getElementById('exampleModalNouveau'));
  
          @if ($errors->any())
              myModal.show();
          @endif
  
          // Réinitialiser les champs du formulaire à la fermeture du modal
          document.getElementById('exampleModalNouveau').addEventListener('hidden.bs.modal', function () {
              document.getElementById('myformclas').reset();
              document.querySelectorAll('#myformclas .form-control').forEach(input => input.value = '');
          });
      });
    </script>
  