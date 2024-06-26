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
                  <a  class="btn btn-primary" href="{{url('/inscrireeleve')}}">Nouveau</a>
                  {{-- <button type="button" class="btn btn-primary">Nouveau</button> --}}
                  <button type="button" class="btn btn-secondary">Imprimer</button>  
                  <a  class="btn btn-primary typcn-camera btn-icon-append" href="{{url('/photos')}}">Photos</a>
                  <style>
                    table {
                      float: right;
                      width: 10%;
                      border-collapse: collapse;
                      margin: 5px auto;
                    }
                    th, td {
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
                      <td class="bouton">Star</td>
                      <td>10,942</td>
                      
                        
                      <td class="bouton">Star</td>
                      <td>10,942</td>
                      </tr>
                      <td class="bouton">Star</td>
                      <td>10,942</td>
                      
                        
                      <td class="bouton">Star</td>
                      <td>10,942</td>
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
                        <button type="button" class="btn btn-info p-2 btn-sm btn-icon-text mr-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="typcn typcn-eye btn-icon-append"></i>                          
                        </button>
                        <button class="btn btn-danger p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="typcn typcn-th-list btn-icon-append"></i>  
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3" style="">
                          <li><a class="dropdown-item" href="#">Supprimer</a></li>
                          <li><a class="dropdown-item" href="#">Modifier</a></li>
                          <li><a class="dropdown-item" href="{{url('/paiementeleve')}}">Paiement</a></li>
                          <li><a class="dropdown-item" href="{{url('/majpaiementeleve')}}">Maj Paie</a></li>
                          <li><a class="dropdown-item" href="{{url('/profil')}}">Profil</a></li>
                          <li><a class="dropdown-item" href="#">Echéance</a></li>
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
  <div class="modal-dialog modal-lg" style=" max-width: 600px">
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
            <form class="accordion-body col-md-10 mx-auto" style="background-color: #f0eff3">  
              
                <div class="form-group row mt-2">
                    <label for="dateN" class="col-sm-4 col-form-label">Date de Naissance</label>
                    <div class="col-sm-5">
                        <input type="date" class="form-control mt-2" id="dateN" placeholder="jj/mm/dd">
                    </div>
                </div>
                
                <div class="form-group row mt-1">
                    <label for="lieu" class="col-sm-4 col-form-label">Lieu</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="lieu" placeholder="Lieu">
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
            <form class="accordion-body col-md-12 mx-auto" style="background-color: #f0eff3;">
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
            <div class="accordion-body col-md-10 mx-auto bg-light-gray">
              <div class="col">
                <div class="d-flex justify-content-center">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="checkDetails">
                    <label class="form-check-label" for="checkDetails">Détail des composantes</label>
                  </div>
                </div>
                
                <div class="table-responsive" style="height: 100px; overflow: auto;">
                  <table class="table table-dark">
                    <thead style="position: sticky; top: 0; z-index: 1; background: #343a40;">
                      <tr>
                        <th>n°Recu</th>
                        <th>Date</th>
                        <th>Montant</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="table-active">
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
                    <tfoot style="position: sticky; bottom: 0; z-index: 1; background: #343a40; ">
                      <tr>
                        <td colspan="2" class="table-active">Somme</td>
                        <td>190 000</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="form-group row mt-2 p-10" style="background-color: #5f42c7;">
                  <label for="dateN" class="col-sm-6 col-form-label">Reste à Payer</label>
                  <div class="col-sm-6">
                      <input type="montant" class="form-control mt-2" id="montant" placeholder="fcfa" style="margin-left;">
                  </div>
                </div>
              </div>

              <div style="display: flex; justify-content: center; align-items: center; margin: auto;">
                <a href="votre-lien-ici" style="text-decoration: none;">
                  <button type="button" class="btn btn-outline-danger btn-icon-text-center">
                    <i class="typcn typcn-upload btn-icon-prepend"></i>Imprimer récapitulatif des paiements
                  </button>
                </a>
              </div>
              
            
              <div class="card-body">               
                <form class="forms-sample">
                  <h4 class="card-title text-center">Réduction Montants dus</h4>
                  <div class="form-group row p-2">
                    <label for="scolarite" class="col-sm-2 col-form-label">[ 3,3% ] Scolarité</label>
                    <div class="col-sm-4 mt-2">
                      <input type="number" class="form-control" id="scolarite">
                    </div>
                    <label for="arriere" class="col-sm-2 col-form-label">[ 0,0% ] Arrièré</label>
                    <div class="col-sm-4 mt-2">
                      <input type="number" class="form-control" id="arriere">
                    </div>
                  </div>

                  <div class="form-group row p-2">
                    <label for="frais1" class="col-sm-2 col-form-label">Frais 1</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="frais1">
                    </div>
                    <label for="frais2" class="col-sm-2 col-form-label">Frais 2</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="frais2">
                    </div>
                  </div>

                  <div class="form-group row p-2">
                    <label for="frais3" class="col-sm-2 col-form-label">Frais 3</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="frais3">
                    </div>
                    <label for="frais4" class="col-sm-2 col-form-label">Frais 4</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="frais4">
                    </div>
                  </div>
                </form>
              </div>
            
            </div>
            
            
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-finan" role="tabpanel" aria-labelledby="nav-finan-tab" tabindex="0">
            <div class="accordion-body col-md-10 mx-auto" style="background-color: #f0eff3"> 

              <table class="table table-responsive table-striped table-hover mt-p-2">
                <tbody class="table">
                  <tr>
                    <th scope="col" class="text-start">Scolarités perçus le 23/05/24</th>
                    <td class="text-end">0</td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-start">Arrièrés perçus le 23/05/24</th>
                    <td class="text-end">0</td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-start">Total</th>
                    <td class="text-end">0</td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-start">Total recettes à ce jour</th>
                    <td class="text-end">57 575 500</td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-start">Versé à la banque</th>
                    <td class="text-end">0</td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-start">Recettes attendues ce jour</th>
                    <td class="text-end">0</td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-start">Recettes attendues cette semaine</th>
                    <td class="text-end">0</td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-start">Recettes attendues ce mois</th>
                    <td class="text-end">0</td>
                  </tr>
                </tbody>
              </table>
              
            </div>
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-Emploi" role="tabpanel" aria-labelledby="nav-Emploi-tab" tabindex="0">
            <div class="accordion-body col-md-10 mx-auto" style="background-color: #f0eff3"> 
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
