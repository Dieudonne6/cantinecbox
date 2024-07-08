@extends('layouts.master')
@section('content')



  <div class="main-panel-10">
    <div class="content-wrapper">
        
      {{--  --}}
      <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="demo-inline-spacing">
                            <a class="btn btn-primary" href="{{url('/inscrireeleve')}}">
                                <i class="typcn typcn-plus btn-icon-prepend"></i> Nouveau
                            </a>
                            <button type="button" class="btn btn-secondary">
                                <i class="typcn typcn-printer btn-icon-prepend"></i> Imprimer
                            </button>
                            {{-- <a class="btn btn-primary" href="{{url('/photos')}}">
                                <i class="typcn typcn-camera btn-icon-prepend"></i> Photos
                            </a> --}}
                            <style>
                              table {
                                  float: right;
                                  width: 60%; /* Augmentez la largeur pour accommoder plus de colonnes */
                                  border-collapse: collapse;
                                  margin: 5px auto;
                              }
                          
                              th,
                              td {
                                  border: 1px solid #ddd;
                                  padding: 4px;
                                  text-align: center;
                              }
                          
                              th {
                                  background-color: #f2f2f2;
                              }
                          
                              td.bouton {
                                  background-color: #ffcccb;
                              }
                          </style>
                          <table>
                              <tbody>
                                  <tr>
                                      <td class="bouton">Eff.Total</td>
                                      <td>942</td>
                                      <td class="bouton">Filles</td>
                                      <td>60</td>
                                      <td class="bouton">Garçons</td>
                                      <td>742</td>
                                  </tr>
                                  <tr>
                                      <td class="bouton">Eff.Red</td>
                                      <td>10</td>
                                      <td class="bouton">Red.Filles</td>
                                      <td>2</td>
                                      <td class="bouton">Red.Garçons</td>
                                      <td>0</td>
                                  </tr>
                              </tbody>
                          </table>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>

      {{--  --}}
      <div class="row">
        <div class="col">
                
          <div class="card">
            <div class="table-responsive" style="height: 300px; overflow: auto;">
              <table class="table table-bordered table-striped" style="min-width: 600px; font-size: 10px;">
                <thead>
                  <tr>
                    <th class="ml-5">Matricule</th>
                    <th>Nom</th>
                    <th>Prénoms</th>
                    <th>Classe</th>
                    <th>Sexe</th>
                    <th>Red.</th>
                    <th>Date nai</th>
                    <th>Lieunais</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>#D1</td>
                    <td>Consectetur adipisicing elit</td>
                    <td>Beulah Cummings</td>
                    <td>CIA</td>
                    <td>M</td>
                    <td class="checkboxes-select" rowspan="1" colspan="1" style="width: 18px;">
                      <input type="checkbox" class="form-check-input-center">
                    </td>
                    <td>05/06/2022</td>
                    <td>Cotonou</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary p-2 btn-sm btn-icon-text mr-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="typcn typcn-eye btn-icon-append"></i>                          
                        </button>
                        <button class="btn btn-primary p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="typcn typcn-th-list btn-icon-append"></i>  
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3" style="">
                          <li><a class="dropdown-item" href="#">Supprimer</a></li>
                          <li><a class="dropdown-item" href="{{url('/modifiereleve')}}">Modifier</a></li>
                          <li><a class="dropdown-item" href="{{url('/paiementeleve')}}">Paiement</a></li>
                          <li><a class="dropdown-item" href="{{url('/majpaiementeleve')}}">Maj Paie</a></li>
                          <li><a class="dropdown-item" href="{{url('/profil')}}">Profil</a></li>
                          <li><a class="dropdown-item" href="{{ url('/echeancier')}}">Echéance</a></li>
                          <li><a class="dropdown-item" href="#">Cursus</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
  </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style=" max-width: 1100px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

       <div class="">
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist" style="font-size: 14px;">
            <button class="nav-link active" id="nav-Infor-tab" data-bs-toggle="tab" data-bs-target="#nav-Infor" type="button" role="tab" aria-controls="nav-Infor" aria-selected="true">Informations générales</button>
            <button class="nav-link" id="nav-Detail-tab" data-bs-toggle="tab" data-bs-target="#nav-Detail" type="button" role="tab" aria-controls="nav-Detail" aria-selected="false">Détail des notes</button>
            <button class="nav-link" id="nav-Deta-tab" data-bs-toggle="tab" data-bs-target="#nav-Deta" type="button" role="tab" aria-controls="nav-Deta" aria-selected="false">Détails des paiements</button>
            <button class="nav-link" id="nav-finan-tab" data-bs-toggle="tab" data-bs-target="#nav-finan" type="button" role="tab" aria-controls="nav-finan" aria-selected="false">Informations financières</button>
            <button class="nav-link" id="nav-Emploi-tab" data-bs-toggle="tab" data-bs-target="#nav-Emploi" type="button" role="tab" aria-controls="nav-Emploi" aria-selected="false">Emploi du temps</button>
            <button class="nav-link" id="nav-Position-tab" data-bs-toggle="tab" data-bs-target="#nav-Position" type="button" role="tab" aria-controls="nav-Position" aria-selected="false">Position Enseignants</button>
          </div>         
        </nav>
        <div class="tab-content" id="nav-tabContent">
          <!--  -->
          <div class="tab-pane fade show active" id="nav-Infor" role="tabpanel" aria-labelledby="nav-Infor-tab" tabindex="0">
            <form class="accordion-body col-md-12 mx-auto">  
              
                <div class="form-group row mt-2">
                    <label for="dateN" class="col-sm-2 col-form-label">Date de Naissance</label>
                    <div class="col-sm-4">
                        <input type="date" class="form-control mt-2" id="dateN" placeholder="jj/mm/dd">
                    </div>

                    <label for="lieu" class="col-sm-1 col-form-label">Lieu</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control mt-2" id="lieu" placeholder="Lieu">
                    </div>
                </div>
        
                <div class="form-group row mt-1">
                    <label for="sexe" class="col-sm-2 col-form-label">Sexe</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="sexe">
                            <option>Masculin</option>
                            <option>Féminin</option>
                        </select>
                    </div>
        
                    <label for="typeEleve" class="col-sm-3 col-form-label">Types élèves</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="typeEleve">
                            <option>Ancien</option>
                            <option>Nouveau</option>
                        </select>
                    </div>
                </div>
        
                <div class="form-group row mt-1">
                    <label for="dateIn" class="col-sm-4 col-form-label">Date d'inscription</label>
                    <div class="col-sm-5">
                        <input type="date" class="form-control" id="dateIn" placeholder="jj/mm/dd">
                    </div>
                </div>
        
                <div class="form-group row mt-1 align-items-center">
                    <label for="apte" class="col-sm-2 col-form-label">Apte</label>
                    <div class="col-sm-2">
                        <select class="form-control" id="apte">
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                    </div>
        
                    <div class="col-sm-2 form-check" style="margin-left: 100px;">
                      <input type="checkbox" class="form-check-input" id="statutRedoublant">
                      <label class="form-check-label" for="statutRedoublant">
                          Statut Redoublant
                      </label>
                  </div>
                  
                </div>
                                            
            </form> 
          </div>       
          <!--  -->
          <div class="tab-pane fade" id="nav-Detail" role="tabpanel" aria-labelledby="nav-Detail-tab" tabindex="0">
            <form class="accordion-body col-md-12 mx-auto">
                <div class="table-responsive mt-2">
                  <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Matière</th>
                                <th scope="col">Mi</th>
                                <th scope="col">Dev1</th>
                                <th scope="col">Dev2</th>
                                <th scope="col">Dev3</th>
                                <th scope="col">Test</th>
                                <th scope="col">Ms</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Français</td>
                                <td>09</td>
                                <td>15</td>
                                <td>06</td>
                                <td>10</td>
                                <td>T13</td>
                                <td>16</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                        </tbody>
                  </table>
                </div>

                <div class="table mt-2">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Moy 1</th>
                                <th scope="col">Moy 2</th>
                                <th scope="col">Moy 3</th>
                                <th scope="col">Moy Totale</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>12</td>
                                <td>14</td>
                                <td>13</td>
                                <td>11</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </form>
          </div>        
          <!--  -->
          <div class="tab-pane fade" id="nav-Deta" role="tabpanel" aria-labelledby="nav-Deta-tab" tabindex="0">
            <div class="accordion-body col-md-12 mx-auto bg-light-gray">
              <div class="col">
                <div class="d-flex justify-content-between align-items-center mt-2">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="checkDetails">
                    <label class="form-check-label" for="checkDetails">Détail des composantes</label>
                  </div>
                  <a href="votre-lien-ici" style="text-decoration: none;">
                    <button type="button" class="btn btn-primary btn-icon-text-center p-2">
                      <i class="typcn typcn-upload btn-icon-prepend"></i>Imprimer récapitulatif des paiements
                    </button>
                  </a>
                </div>
                               
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                  <table class="table table-hover">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">n°Reçu</th>
                        <th scope="col">Date</th>
                        <th scope="col">Montant</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>130</td>
                        <td>05/06/2024</td>
                        <td>190 000</td>
                      </tr>
                      <tr>
                        <td>130</td>
                        <td>05/06/2024</td>
                        <td>190 000</td>
                      </tr>
                      <tr>
                        <td>130</td>
                        <td>05/06/2024</td>
                        <td>190 000</td>
                      </tr>
                    </tbody>
                    <tfoot class="tfoot-dark">
                      <tr>
                        <td colspan="2" class="table-active">Somme</td>
                        <td>190 000</td>
                      </tr>
                      <tr>
                        <td colspan="2" class="table-active">Reste à Payer</td>
                        <td>1 900</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                
              </div>
              
              <div class="card-body">
                <h6 class="card-title text-center">Réduction Montants dus</h6>
                <table class="table">
                  <tbody style=" width: 50%;">
                    <tr>
                      <td>[ 3,3% ] Scolarité</td>
                      <td><input type="number" class="form-control" id="scolarite"></td>
                      <td>[ 0,0% ] Arriéré</td>
                      <td><input type="number" class="form-control" id="arriere"></td>
                    </tr>
                    <tr>
                      <td>Frais 1</td>
                      <td><input type="number" class="form-control" id="frais1"></td>
                      <td>Frais 2</td>
                      <td><input type="number" class="form-control" id="frais2"></td>
                    </tr>
                    <tr>
                      <td>Frais 3</td>
                      <td><input type="number" class="form-control" id="frais3"></td>
                      <td>Frais 4</td>
                      <td><input type="number" class="form-control" id="frais4"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
            </div>
            
            
            
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-finan" role="tabpanel" aria-labelledby="nav-finan-tab" tabindex="0">
            <div class="accordion-body col-md-12 mx-auto"> 
              <div class="table-responsive mt-2">
                <table class="table table-striped table-hover">
                  <tbody>
                    <tr>
                      <th scope="row" class="text-start">Scolarités perçus le 23/05/24</th>
                      <td class="text-end">0</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-start">Arrièrés perçus le 23/05/24</th>
                      <td class="text-end">0</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-start">Total</th>
                      <td class="text-end">0</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-start">Total recettes à ce jour</th>
                      <td class="text-end">57 575 500</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-start">Versé à la banque</th>
                      <td class="text-end">0</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-start">Recettes attendues ce jour</th>
                      <td class="text-end">0</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-start">Recettes attendues cette semaine</th>
                      <td class="text-end">0</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-start">Recettes attendues ce mois</th>
                      <td class="text-end">0</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-Emploi" role="tabpanel" aria-labelledby="nav-Emploi-tab" tabindex="0">
            <div class="accordion-body col-md-12 mx-auto"> 
              <div class="container">
                <div class="row">
                  <div class="col-md-4">
                    <table class="table table-striped mt-2">
                      <thead>
                        <tr>
                          <th scope="col">Colonne 1</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Contenu 1.1</td>
                        </tr>
                        <tr>
                          <td>Contenu 1.2</td>
                        </tr>
                        <tr>
                          <td>Contenu 1.3</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-4">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Colonne 2</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Contenu 2.1</td>
                        </tr>
                        <tr>
                          <td>Contenu 2.2</td>
                        </tr>
                        <tr>
                          <td>Contenu 2.3</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-4">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Colonne 3</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Contenu 3.1</td>
                        </tr>
                        <tr>
                          <td>Contenu 3.2</td>
                        </tr>
                        <tr>
                          <td>Contenu 3.3</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-Position" role="tabpanel" aria-labelledby="nav-Position-tab" tabindex="0">
            <div class="accordion-body">
              <div id="dateTime" style="text-align: justify;"></div>

              <!-- Scripts JavaScript à placer à la fin du body pour optimiser le chargement -->
              <script>
                // JavaScript pour afficher le jour de la semaine et l'heure actuelle
                function afficherDateHeure() {
                  // Récupérer la date et l'heure actuelles
                  let date = new Date();
                  
                  // Obtenir le jour de la semaine
                  let joursSemaine = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
                  let jour = joursSemaine[date.getDay()];
            
                  // Formater l'heure
                  let heures = date.getHours();
                  let minutes = date.getMinutes();
                  let secondes = date.getSeconds();
                  let heureFormatee = `${heures.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secondes.toString().padStart(2, '0')}`;
            
                  // Créer une chaîne de texte avec jour et heure
                  let texteAffichage = `Aujourd'hui, c'est ${jour}. Il est actuellement ${heureFormatee}.`;
            
                  // Afficher le texte dans l'élément HTML correspondant
                  document.getElementById("dateTime").textContent = texteAffichage;
                }
            
                // Appeler la fonction pour l'exécuter initialement
                afficherDateHeure();
            
                // Actualiser l'affichage de l'heure chaque seconde
                setInterval(afficherDateHeure, 1000);
              </script>
              

              <div class="container">
                <div class="row">
                  <div class="col-md-4">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Colonne 1</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Contenu 1.1</td>
                        </tr>
                        <tr>
                          <td>Contenu 1.2</td>
                        </tr>
                        <tr>
                          <td>Contenu 1.3</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-4">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Colonne 2</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Contenu 2.1</td>
                        </tr>
                        <tr>
                          <td>Contenu 2.2</td>
                        </tr>
                        <tr>
                          <td>Contenu 2.3</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-4">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Colonne 3</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Contenu 3.1</td>
                        </tr>
                        <tr>
                          <td>Contenu 3.2</td>
                        </tr>
                        <tr>
                          <td>Contenu 3.3</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div> 
       </div>

      </div>
    </div>
  </div>
</div>

@endsection
