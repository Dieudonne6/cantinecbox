@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Informations générales</button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Informations complémentaires</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label>Classe</label>
                                                    <select class="form-select">
                                                        <option>CE1</option>
                                                        <option>Italy</option>
                                                        <option>Russia</option>
                                                        <option>Britain</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                  <label>Classe d'entrée collège</label>
                                                  <select class="form-select">
                                                      <option>CM2</option>
                                                      <option>Italy</option>
                                                      <option>Russia</option>
                                                      <option>Britain</option>
                                                  </select>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-4">
                                            <div class="form-group row">
                                                <div class="col">
                                                    <label>Numéro d'ordre</label>
                                                    <div id="bloodhound">
                                                        <input class="form-control" type="text" placeholder="1082" name="prenom" id="prenom">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        </div>
                                        <div class="col">
                                          <div class="div">
                                              <button type="button" class="btn btn-primary">Données financières (Factures)</button>
                                              <button type="button" class="btn btn-primary">Classe précédente</button>
                                          </div>
                                      </div>
                                      <div class="col" style="margin-top: -150px;">
                                        <form class="custom-mt-form">
                                          <div class="form-group">
                                            <label>Image de l'élève</label>
                                            <div class="custom-file-container" id="customFileContainer">
                                              <input type="file" name="pic" accept="image/*" class="form-control-file d-none" id="imageInput">
                                              <div class="file-upload-label" onclick="document.getElementById('imageInput').click();">
                                                <span>Select or Drop Image</span>
                                              </div>
                                            </div>
                                          </div>
                                        </form>
                                      </div>
                                      </div>
                                    <hr>
                                    <h4 class="card-title" style="margin-top: 15px">Informations personnelles</h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <div class="col-sm-9">
                                                    <label>Matricule</label>
                                                    <input type="text" placeholder="Auto" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="margin-top: 23px">
                                            <div class="col">
                                                <button type="button" class="btn btn-primary">Vérifier archives</button>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Profil de réduction</label>
                                            <select class="form-select">
                                                <option>Plein Tarif</option>
                                                <option>Fils d'enseignant</option>
                                            </select>
                                        </div>
                                        <div class="col" style="margin-left:35px; margin-top:15px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1">Cocher si c'est un redoublant</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Nom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom" id="nom" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label>Prénom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="prenom" id="prenom" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label>Date de naissance</label>
                                                <div id="the-basics">
                                                    <input class="form-control" type="date" id="date" name="date" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Date d'inscription</label>
                                                <div id="the-basics">
                                                    <input class="form-control" type="date" id="date" name="date" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label>Lieu de naissance</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom" id="nom" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label>Département</label>
                                                <select class="form-select">
                                                    <option>Littoral</option>
                                                    <option>Italy</option>
                                                    <option>Russia</option>
                                                    <option>Britain</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Sexe</label>
                                                <select class="form-select">
                                                    <option>Masculin</option>
                                                    <option>Feminin</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label>Type d'élève</label>
                                                <select class="form-select">
                                                    <option>Nouveau</option>
                                                    <option>Ancien</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label>Aptitude Sport</label>
                                                <select class="form-select">
                                                    <option>Apte</option>
                                                    <option>Inapte</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Adresse personnelle</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="adresse_personnelle" id="adresse_personnelle" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label>Etablissement d'origine</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="etablissement_origine" id="etablissement_origine" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label>Nationalité</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nationalite" id="nationalite" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h4 class="card-title" style="margin-top: 15px">Filiation</h4>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="form-group row">
                                                    <div class="col">
                                                        <label>Nom du père</label>
                                                        <div id="bloodhound">
                                                            <input class="form-control" type="text" name="nom_pere" id="nom_pere" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <label>Nom de la mère</label>
                                                        <div id="bloodhound">
                                                            <input class="form-control" type="text" name="nom_mere" id="nom_mere" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-group row">
                                                    <div class="col">
                                                        <label>Adresses parents</label>
                                                        <div id="bloodhound">
                                                            <input class="form-control" type="text" name="adresses_parents" id="adresses_parents" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <label>Autres renseignements</label>
                                                        <div id="bloodhound">
                                                            <input class="form-control" type="text" name="autres_renseignements" id="autres_renseignements" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-group row">
                                                    <div class="col-sm-2">
                                                        <label>Contacts parents</label>
                                                        <select class="form-select">
                                                            <option>+229</option>
                                                            <option>+229</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <label>Téléphone 1</label>
                                                        <div id="bloodhound">
                                                            <input class="form-control" type="text" name="telephone_1" id="telephone_1" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <label>Téléphone 2</label>
                                                        <div id="bloodhound">
                                                            <input class="form-control" type="text" name="telephone_2" id="telephone_2" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                    <button type="button" class="btn btn-danger">Annuler</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                      
                      <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-group row">
                                    <div class="col">
                                        <label>Maladies chroniques et allergies connues</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="nom" id="nom" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>Interdit alimentaires</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="prenom" id="prenom" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>Groupe sanguin</label>
                                        <select class="form-select">
                                          <option>A+</option>
                                          <option>O+</option>
                                          <option>B+</option>
                                          <option>B-</option>
                                      </select>
                                    </div>
                                    <div class="col">
                                      <label>Type d'hémoglobine</label>
                                      <select class="form-select">
                                        <option>A</option>
                                        <option>O</option>
                                        <option>B</option>
                                        <option>B</option>
                                    </select>
                                  </div>
                                </div>
                            </div>
                            <hr>
                            <h4 class="card-title" style="margin-top: 15px">Mère</h4>
                            <div class="form-group">
                                <div class="form-group row">
                                    <div class="col">
                                        <label>Nom</label>
                                        <div id="bloodhound">
                                          <input class="form-control" type="text" name="nom" id="nom" value="">
                                      </div>
                                    </div>
                                    <div class="col">
                                        <label>Prénom</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="nom" id="nom" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                      <label>Numéro de téléphone</label>
                                      <div id="bloodhound">
                                        <input class="form-control" type="text" name="numero_de_telephone" id="numero_de_telephone" value="">
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group row">
                                    <div class="col">
                                        <label>Adresse e-mail</label>
                                        <div id="bloodhound">
                                          <input class="form-control" type="text" name="numero_de_telephone" id="numero_de_telephone" value="">
                                      </div>
                                    </div>
                                    <div class="col">
                                        <label>Profession</label>
                                        <div id="bloodhound">
                                          <input class="form-control" type="text" name="adresse_email" id="adresse_email" value="">
                                      </div>
                                    </div>
                                    <div class="col">
                                        <label>Adresse employeur</label>
                                        <div id="bloodhound">
                                          <input class="form-control" type="text" name="adresse_email" id="adresse_email" value="">
                                      </div>
                                    </div>
                                    <div class="col">
                                      <label>Adresse personnelle</label>
                                      <div id="bloodhound">
                                          <input class="form-control" type="textarea" name="adresse_personnelle" id="adresse_personnelle" value="">
                                      </div>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                                <h4 class="card-title" style="margin-top: 15px">Père</h4>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Nom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom_pere" id="nom_pere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label>Prénom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom_mere" id="nom_mere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                              <label>Numéro de téléphone</label>
                                              <div id="bloodhound">
                                                  <input class="form-control" type="text" name="nom_mere" id="nom_mere" value="">
                                              </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                          <div class="col">
                                            <label>Adresse e-mail</label>
                                            <div id="bloodhound">
                                              <input class="form-control" type="text" name="numero_de_telephone" id="numero_de_telephone" value="">
                                          </div>
                                        </div>
                                        <div class="col">
                                            <label>Profession</label>
                                            <div id="bloodhound">
                                              <input class="form-control" type="text" name="adresse_email" id="adresse_email" value="">
                                          </div>
                                        </div>
                                        <div class="col">
                                            <label>Adresse employeur</label>
                                            <div id="bloodhound">
                                              <input class="form-control" type="text" name="adresse_email" id="adresse_email" value="">
                                          </div>
                                        </div>
                                        <div class="col">
                                          <label>Adresse personnelle</label>
                                          <div id="bloodhound">
                                              <input class="form-control" type="textarea" name="adresse_personnelle" id="adresse_personnelle" value="">
                                          </div>
                                      </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <hr>
                                <h4 class="card-title" style="margin-top: 15px">Tuteur</h4>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Nom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom_pere" id="nom_pere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label>Prénom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom_mere" id="nom_mere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                              <label>Numéro de téléphone</label>
                                              <div id="bloodhound">
                                                  <input class="form-control" type="text" name="nom_mere" id="nom_mere" value="">
                                              </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                          <div class="col">
                                            <label>Adresse e-mail</label>
                                            <div id="bloodhound">
                                              <input class="form-control" type="text" name="numero_de_telephone" id="numero_de_telephone" value="">
                                          </div>
                                        </div>
                                        <div class="col">
                                            <label>Profession</label>
                                            <div id="bloodhound">
                                              <input class="form-control" type="text" name="adresse_email" id="adresse_email" value="">
                                          </div>
                                        </div>
                                        <div class="col">
                                            <label>Adresse employeur</label>
                                            <div id="bloodhound">
                                              <input class="form-control" type="text" name="adresse_email" id="adresse_email" value="">
                                          </div>
                                        </div>
                                        <div class="col">
                                          <label>Adresse personnelle</label>
                                          <div id="bloodhound">
                                              <input class="form-control" type="textarea" name="adresse_personnelle" id="adresse_personnelle" value="">
                                          </div>
                                      </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h4 class="card-title" style="margin-top: 15px">Personne à contacter en cas d'urgence</h4>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Nom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom_pere" id="nom_pere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label>Prénom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom_mere" id="nom_mere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                              <label>Numéro de téléphone</label>
                                              <div id="bloodhound">
                                                  <input class="form-control" type="text" name="nom_mere" id="nom_mere" value="">
                                              </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                          <div class="col">
                                            <label>Adresse e-mail</label>
                                            <div id="bloodhound">
                                              <input class="form-control" type="text" name="numero_de_telephone" id="numero_de_telephone" value="">
                                          </div>
                                        </div>
                                        <div class="col">
                                          <label>Adresse personnelle</label>
                                          <div id="bloodhound">
                                              <input class="form-control" type="textarea" name="adresse_personnelle" id="adresse_personnelle" value="">
                                          </div>
                                      </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                          Autorisation d'utiliser les vidéos à des fins publicitaires</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                          <label class="form-check-label" for="flexCheckChecked">
                                            Autorisation d'utiliser les images à des fins publicitaires</label>
                                          </div>
                                      </div>
                                    



                                    <div class="form-group">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            <button type="button" class="btn btn-danger">Annuler</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>

                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">...</div>
                    <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
  .custom-file-container {
    position: relative;
    width: 100px;
    height: 100px; /* Ajuster la hauteur pour rendre le carré */
    border: 2px dashed #007bff;
    border-radius: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    overflow: hidden;
    background-color: #f8f9fa; /* Fond gris clair pour correspondre à Bootstrap */
  }
  
  .file-upload-label {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    color: #007bff;
    font-size: 18px;
    text-align: center;
  }
  
  .file-upload-label:hover {
    background-color: rgba(0, 123, 255, 0.1);
  }
  
  .custom-file-container.dragover {
    border-color: #0056b3;
  }
  
  .custom-file-container.dragover .file-upload-label {
    background-color: rgba(0, 123, 255, 0.1);
  }
  </style>
  
  <!-- Custom JavaScript -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    function handleDragOver(container, input) {
      container.addEventListener('dragover', function(event) {
        event.preventDefault();
        container.classList.add('dragover');
      });
  
      container.addEventListener('dragleave', function() {
        container.classList.remove('dragover');
      });
  
      container.addEventListener('drop', function(event) {
        event.preventDefault();
        container.classList.remove('dragover');
        input.files = event.dataTransfer.files;
      });
  
      container.addEventListener('click', function() {
        input.click();
      });
    }
  
    var imageInput = document.getElementById('imageInput');
    var customFileContainer = document.getElementById('customFileContainer');
    handleDragOver(customFileContainer, imageInput);
  });
  </script>
@endsection
