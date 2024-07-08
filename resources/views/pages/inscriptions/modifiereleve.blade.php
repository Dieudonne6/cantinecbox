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
                                        <div class="form-group">
                                            <label for="classe">Classe</label>
                                            <select class="form-select" id="classe">
                                                <option>CE1</option>
                                                <option>Italy</option>
                                                <option>Russia</option>
                                                <option>Britain</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="classe-entree">Classe d'entrée collège</label>
                                            <select class="form-select" id="classe-entree">
                                                <option>CM2</option>
                                                <option>Italy</option>
                                                <option>Russia</option>
                                                <option>Britain</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="numero-ordre">Numéro d'ordre</label>
                                            <input class="form-control" type="text" id="numero-ordre" placeholder="1082" name="prenom">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Image de l'élève</label>
                                            <div class="custom-file-container" id="customFileContainer">
                                                <input type="file" name="pic" accept="image/*" class="form-control-file d-none" id="imageInput">
                                                <div class="file-upload-label" onclick="document.getElementById('imageInput').click();">
                                                    <span>Sélectionner ou glisser une image</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <button type="button" class="btn btn-primary">Dente</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                Données financières (Factures)
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary">Classe précédente</button>
                                        </div>
                                    </div>

                                </div>
                                <hr>
                                <h4 class="card-title mt-3">Informations personnelles</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <label>Matricule</label>
                                                <input type="text" placeholder="Auto" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="col">
                                            <button type="button" class="mt-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                                Vérifier archives
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Profil de réduction</label>
                                        <select class="form-select">
                                            <option>Plein Tarif</option>
                                            <option>Fils d'enseignant</option>
                                        </select>
                                    </div>
                                    <div class="col mt-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">Cocher si c'est un redoublant</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <div class="row">
                                        <div class="col">
                                            <label>Nom</label>
                                            <input class="form-control" type="text" name="nom" id="nom">
                                        </div>
                                        <div class="col">
                                            <label>Prénom</label>
                                            <input class="form-control" type="text" name="prenom" id="prenom">
                                        </div>
                                        <div class="col">
                                            <label>Date de naissance</label>
                                            <input class="form-control" type="date" id="date" name="date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <div class="row">
                                        <div class="col">
                                            <label>Date d'inscription</label>
                                            <input class="form-control" type="date" id="date-inscription" name="date-inscription">
                                        </div>
                                        <div class="col">
                                            <label>Lieu de naissance</label>
                                            <input class="form-control" type="text" name="lieu-naissance" id="lieu-naissance">
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
                                <div class="form-group mt-3">
                                    <div class="row">
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
                                <div class="form-group mt-3">
                                    <div class="row">
                                        <div class="col">
                                            <label>Adresse personnelle</label>
                                            <input class="form-control" type="text" name="adresse_personnelle" id="adresse_personnelle">
                                        </div>
                                        <div class="col">
                                            <label>Etablissement d'origine</label>
                                            <input class="form-control" type="text" name="etablissement_origine" id="etablissement_origine">
                                        </div>
                                        <div class="col">
                                            <label>Nationalité</label>
                                            <input class="form-control" type="text" name="nationalite" id="nationalite">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h4 class="card-title mt-3">Filiation</h4>
                                <div class="row">
                                    <div class="form-group mt-3">
                                        <div class="row">
                                            <div class="col">
                                                <label>Nom du père</label>
                                                <input class="form-control" type="text" name="nom_pere" id="nom_pere">
                                            </div>
                                            <div class="col">
                                                <label>Nom de la mère</label>
                                                <input class="form-control" type="text" name="nom_mere" id="nom_mere">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <div class="row">
                                            <div class="col">
                                                <label>Adresses parents</label>
                                                <input class="form-control" type="text" name="adresses_parents" id="adresses_parents">
                                            </div>
                                            <div class="col">
                                                <label>Autres renseignements</label>
                                                <input class="form-control" type="text" name="autres_renseignements" id="autres_renseignements">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <label>Contacts parents</label>
                                                <select class="form-select">
                                                    <option>+229</option>
                                                    <option>+229</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-5">
                                                <label>Téléphone 1</label>
                                                <input class="form-control" type="text" name="telephone_1" id="telephone_1">
                                            </div>
                                            <div class="col-lg-5">
                                                <label>Téléphone 2</label>
                                                <input class="form-control" type="text" name="telephone_2" id="telephone_2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
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
                
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="maladies_chroniques">Maladies chroniques et allergies connues</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="maladies_chroniques" id="maladies_chroniques" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="interdit_alimentaires">Interdit alimentaires</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="interdit_alimentaires" id="interdit_alimentaires" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="groupe_sanguin">Groupe sanguin</label>
                                        <select class="form-select" id="groupe_sanguin" name="groupe_sanguin">
                                            <option>A+</option>
                                            <option>O+</option>
                                            <option>B+</option>
                                            <option>B-</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="type_hemoglobine">Type d'hémoglobine</label>
                                        <select class="form-select" id="type_hemoglobine" name="type_hemoglobine">
                                            <option>A</option>
                                            <option>O</option>
                                            <option>B</option>
                                            <option>AB</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h4 class="card-title" style="margin-top: 15px">Mère</h4>
                            <div class="form-group">
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="nom_mere">Nom</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="nom_mere" id="nom_mere" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="prenom_mere">Prénom</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="prenom_mere" id="prenom_mere" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="telephone_mere">Numéro de téléphone</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="telephone_mere" id="telephone_mere" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="email_mere">Adresse e-mail</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="email_mere" id="email_mere" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="profession_mere">Profession</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="profession_mere" id="profession_mere" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="adresse_employeur_mere">Adresse employeur</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="text" name="adresse_employeur_mere" id="adresse_employeur_mere" value="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="adresse_personnelle_mere">Adresse personnelle</label>
                                        <div id="bloodhound">
                                            <input class="form-control" type="textarea" name="adresse_personnelle_mere" id="adresse_personnelle_mere" value="">
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
                                                <label for="nom_pere">Nom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom_pere" id="nom_pere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="prenom_pere">Prénom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="prenom_pere" id="prenom_pere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="telephone_pere">Numéro de téléphone</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="telephone_pere" id="telephone_pere" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label for="email_pere">Adresse e-mail</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="email_pere" id="email_pere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="profession_pere">Profession</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="profession_pere" id="profession_pere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="adresse_employeur_pere">Adresse employeur</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="adresse_employeur_pere" id="adresse_employeur_pere" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="adresse_personnelle_pere">Adresse personnelle</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="textarea" name="adresse_personnelle_pere" id="adresse_personnelle_pere" value="">
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
                                                <label for="nom_tuteur">Nom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom_tuteur" id="nom_tuteur" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="prenom_tuteur">Prénom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="prenom_tuteur" id="prenom_tuteur" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="telephone_tuteur">Numéro de téléphone</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="telephone_tuteur" id="telephone_tuteur" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label for="email_tuteur">Adresse e-mail</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="email_tuteur" id="email_tuteur" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="profession_tuteur">Profession</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="profession_tuteur" id="profession_tuteur" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="adresse_employeur_tuteur">Adresse employeur</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="adresse_employeur_tuteur" id="adresse_employeur_tuteur" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="adresse_personnelle_tuteur">Adresse personnelle</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="textarea" name="adresse_personnelle_tuteur" id="adresse_personnelle_tuteur" value="">
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
                                                <label for="nom_urgence">Nom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="nom_urgence" id="nom_urgence" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="prenom_urgence">Prénom</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="prenom_urgence" id="prenom_urgence" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="telephone_urgence">Numéro de téléphone</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="telephone_urgence" id="telephone_urgence" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <div class="col">
                                                <label for="email_urgence">Adresse e-mail</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="text" name="email_urgence" id="email_urgence" value="">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="adresse_personnelle_urgence">Adresse personnelle</label>
                                                <div id="bloodhound">
                                                    <input class="form-control" type="textarea" name="adresse_personnelle_urgence" id="adresse_personnelle_urgence" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Autorisation d'utiliser les vidéos à des fins publicitaires
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Autorisation d'utiliser les images à des fins publicitaires
                                        </label>
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
                
                    {{-- <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">...</div>
                    <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Modal n*1 -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content calcul-modal">
                                                  <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Données financières</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body profile-content">
                                                    <div class="row">
                                                      <div class="" id="calcul-one">
                                                        <form class="forms-sample">
                                                          <div class="form-group row">
                                                            <label for="exampleInputUsername2" class="col-sm-12">Profil de réduction</label>
                                                            <div class="col-sm-12 mb-2">
                                                              <select class="form-select">
                                                                <option>Fils d'enseignant</option>
                                                                <option>Plein Tarfif</option>
                                                              </select>
                                                            </div>
                                                          </div>
                                                          <button type="submit" class="btn btn-primary col-sm-10">Créer un profil de réduction</button>
                                                          <hr>
                                                          <div class="form-group row">
                                                            <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Scolarité</label>
                                                            <div class="col-sm-4">
                                                              <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0">
                                                            </div>
                                                          </div>
                                                          <div class="form-group row">
                                                            <label for="exampleInputUsername2" class="col-sm-8 col-form-label">Arrièré</label>
                                                            <div class="col-sm-4">
                                                              <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0">
                                                            </div>
                                                          </div>
                                                          <div class="form-group row">
                                                            <label for="exampleInputUsername2" class="col-sm-8 col-form-label"></label>
                                                            <div class="col-sm-4">
                                                              <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0">
                                                            </div>
                                                          </div>
                                                          <div class="form-group row">
                                                            <label for="exampleInputUsername2" class="col-sm-8 col-form-label"></label>
                                                            <div class="col-sm-4">
                                                              <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0">
                                                            </div>
                                                          </div>
                                                          <div class="form-group row">
                                                            <label for="exampleInputUsername2" class="col-sm-8 col-form-label"></label>
                                                            <div class="col-sm-4">
                                                              <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0">
                                                            </div>
                                                          </div>
                                                          <div class="form-group row">
                                                            <label for="exampleInputUsername2" class="col-sm-8 col-form-label"></label>
                                                            <div class="col-sm-4">
                                                              <input type="text" class="form-control" id="exampleInputUsername2" placeholder="0">
                                                            </div>
                                                          </div>
                                                        </form>
                                                      </div>
                                                    </div>
                                                  </div><div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Valider</button>
                                                  </div>
                                                </div>
                                              </div>
  </div>
  <!-- Modal n*2-->
  <div class="modal fade" id="exampleModal1" tabindex="-2" aria-labelledby="exampleModalLabel1" aria-hidden="true">
                                                  <div class="modal-dialog modal-lg">
                                                      <div class="modal-content">
                                                          <div class="modal-header">
                                                              <h1 class="modal-title fs-5" id="exampleModalLabel1">Liste d'archives</h1>
                                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                          </div>
                                                          <div class="modal-body profile-content">
                                                              <div class="row">
                                                                  <div class="col">
                                                                      <div class="card">
                                                                          <div class="table-responsive" style="height: 300px; overflow: auto;">
                                                                              <table class="table table-bordered table-striped" style="min-width: 600px; font-size: 10px;">
                                                                                  <thead>
                                                                                      <tr>
                                                                                          <th>Matricule</th>
                                                                                          <th>Nom</th>
                                                                                          <th>Prénoms</th>
                                                                                          <th>Sexe</th>
                                                                                          <th>Date nai</th>
                                                                                          <th>Lieu nais</th>
                                                                                          <th>Photo</th>
                                                                                      </tr>
                                                                                  </thead>
                                                                                  <tbody>
                                                                                      <tr>
                                                                                          <td>00000704</td>
                                                                                          <td>ABDOU</td>
                                                                                          <td>Oumar</td>
                                                                                          <td>M</td>
                                                                                          <td>05/06/2022</td>
                                                                                          <td>Cotonou</td>
                                                                                          <td><img src="photo2.jpg" alt="" width="50"></td>
                                                                                          <td>
                                                                                              <button type="button" class="btn btn-primary"><i class="typcn typcn-archive btn-icon-append"></i></button>
                                                                                          </td>
                                                                                      </tr>
                                                                                  </tbody>
                                                                              </table>
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="modal-footer">
                                                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
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
