@extends('layouts.master')

@section('content')
    
<div>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"> Configurer les comptes</h4>
                    <div class="col">
                        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Voir la liste des comptes</button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal-size">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Selection d'un compte</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="myTable" class="table table-hover">
                      <thead>
                        <tr>
                            <th>Comptes</th>
                          <th>Libellé</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>

                        @foreach ($comptes as $compte)

                        <tr>
                          <td class="text-info">{{$compte->NCOMPTE}}</td>
                          <td> {{$compte->LIBELCPTE}} </td>
                          <td>
                            <button class="btn btn-primary p-2 btn-sm dropdown" type="button" onclick="selectCompte('{{$compte->NCOMPTE}}')">Sélectionner</button>
                          </td>
                        </tr>

                        @endforeach
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>

        </div>
      </div>
    </div>
  </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="form-group">
                        <div class="form-group row">
                            <div class="col">
                                <label>Frais scolarité</label>
                                <div id="bloodhound">
                                    <input class="form-control" type="text" placeholder=""  name="" id="compteInput" value="">
                                </div>
                            </div>
                            <div class="col">
                                <label>Libellé</label>
                                <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                  <option value="AG │ ACTIVITES GENERALES">AG │ ACTIVITES GENERALES</option>
                                  <option value="MF │ MATERNELLE CAMP-GUEZO">MF │ MATERNELLE CAMP-GUEZO</option>
                                  <option value="MM │ MATERNELLE CADJEHOUN">MM │ MATERNELLE CADJEHOUN</option>
                                  <option value="PC │ PRIMAIRE CADJEHOUN">PC │ PRIMAIRE CADJEHOUN</option>
                                  <option value="PM │ PRIMAIRE CAMP-GUEZO">PM │ PRIMAIRE CAMP-GUEZO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label>Arrièrés</label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="" id="" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                              <option value="AG │ ACTIVITES GENERALES">AG │ ACTIVITES GENERALES</option>
                              <option value="MF │ MATERNELLE CAMP-GUEZO">MF │ MATERNELLE CAMP-GUEZO</option>
                              <option value="MM │ MATERNELLE CADJEHOUN">MM │ MATERNELLE CADJEHOUN</option>
                              <option value="PC │ PRIMAIRE CADJEHOUN">PC │ PRIMAIRE CADJEHOUN</option>
                              <option value="PM │ PRIMAIRE CAMP-GUEZO">PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label></label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="" id="" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                <option value="AG │ ACTIVITES GENERALES">AG │ ACTIVITES GENERALES</option>
                                <option value="MF │ MATERNELLE CAMP-GUEZO">MF │ MATERNELLE CAMP-GUEZO</option>
                                <option value="MM │ MATERNELLE CADJEHOUN">MM │ MATERNELLE CADJEHOUN</option>
                                <option value="PC │ PRIMAIRE CADJEHOUN">PC │ PRIMAIRE CADJEHOUN</option>
                                <option value="PM │ PRIMAIRE CAMP-GUEZO">PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label></label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="" id="" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                              <option value="AG │ ACTIVITES GENERALES">AG │ ACTIVITES GENERALES</option>
                              <option value="MF │ MATERNELLE CAMP-GUEZO">MF │ MATERNELLE CAMP-GUEZO</option>
                              <option value="MM │ MATERNELLE CADJEHOUN">MM │ MATERNELLE CADJEHOUN</option>
                              <option value="PC │ PRIMAIRE CADJEHOUN">PC │ PRIMAIRE CADJEHOUN</option>
                              <option value="PM │ PRIMAIRE CAMP-GUEZO">PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label></label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="" id="" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                              <option value="AG │ ACTIVITES GENERALES">AG │ ACTIVITES GENERALES</option>
                              <option value="MF │ MATERNELLE CAMP-GUEZO">MF │ MATERNELLE CAMP-GUEZO</option>
                              <option value="MM │ MATERNELLE CADJEHOUN">MM │ MATERNELLE CADJEHOUN</option>
                              <option value="PC │ PRIMAIRE CADJEHOUN">PC │ PRIMAIRE CADJEHOUN</option>
                              <option value="PM │ PRIMAIRE CAMP-GUEZO">PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                          <div class="col">
                            <label></label>
                            <div id="bloodhound">
                              <input class="form-control" type="text" placeholder="" name="" id="" value="">
                          </div>
                        </div>
                        <div class="col">
                            <label>Libellé</label>
                            <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                              <option value="AG │ ACTIVITES GENERALES">AG │ ACTIVITES GENERALES</option>
                              <option value="MF │ MATERNELLE CAMP-GUEZO">MF │ MATERNELLE CAMP-GUEZO</option>
                              <option value="MM │ MATERNELLE CADJEHOUN">MM │ MATERNELLE CADJEHOUN</option>
                              <option value="PC │ PRIMAIRE CADJEHOUN">PC │ PRIMAIRE CADJEHOUN</option>
                              <option value="PM │ PRIMAIRE CAMP-GUEZO">PM │ PRIMAIRE CAMP-GUEZO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary">Annuler</button>
                    </div>
                </div>
        </div>
    </div>
    </div>

<style>
  .custom-modal-size {
  max-width: 40%; /* Ajustez ce pourcentage selon vos besoins */
}

</style>

<script>
function selectCompte(ncompte) {
    document.getElementById('compteInput').value = ncompte;
    var modalElement = document.getElementById('exampleModal');
    var modal = bootstrap.Modal.getInstance(modalElement); // Récupérer l'instance du modal
    modal.hide(); // Masquer le modal
  }
</script>

@endsection