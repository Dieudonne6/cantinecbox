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
                  <button type="button" class="btn btn-primary">Nouveau</button>
                  <button type="button" class="btn btn-secondary">Imprimer</button>  
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
                          <font></font>
                          <i class="typcn typcn-eye btn-icon-append"></i>                          
                        </button>
                        <button class="btn btn-danger p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="typcn typcn-th-list btn-icon-append"></i>  
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3" style="">
                          <li><a class="dropdown-item" href="#">Supprimer</a></li>
                          <li><a class="dropdown-item" href="#">Modifier</a></li>
                          <li><a class="dropdown-item" href="#">Paiement</a></li>
                          <li><a class="dropdown-item" href="#">Profil</a></li>
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
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

       <div class="col-12">
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-Infor-tab" data-bs-toggle="tab" data-bs-target="#nav-Infor" type="button" role="tab" aria-controls="nav-Infor" aria-selected="true">Informations généraless</button>
            <button class="nav-link" id="nav-Detail-tab" data-bs-toggle="tab" data-bs-target="#nav-Detail" type="button" role="tab" aria-controls="nav-Detail" aria-selected="false">Détail des notes</button>
            <button class="nav-link" id="nav-Deta-tab" data-bs-toggle="tab" data-bs-target="#nav-Deta" type="button" role="tab" aria-controls="nav-Deta" aria-selected="false">Détails des paiements</button>
            <button class="nav-link" id="nav-finan-tab" data-bs-toggle="tab" data-bs-target="#nav-finan" type="button" role="tab" aria-controls="nav-finan" aria-selected="false">Informations financières</button>
            <button class="nav-link" id="nav-Emploi-tab" data-bs-toggle="tab" data-bs-target="#nav-Emploi" type="button" role="tab" aria-controls="nav-Emploi" aria-selected="Emploi du temps">Emploi du temps</button>
            <button class="nav-link" id="nav-Position-tab" data-bs-toggle="tab" data-bs-target="#nav-Position" type="button" role="tab" aria-controls="nav-Position" aria-selected="false">Position Enseignants</button>
          </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
          <!--  -->
          <div class="tab-pane fade show active" id="nav-Infor" role="tabpanel" aria-labelledby="nav-Infor-tab" tabindex="0">
            <form class="accordion-body">                    
              <div class="mb-2">
                <input type="" class="form-control" id="date" placeholder="Date de Naissance">
              </div>
              <div class="mb-2">
                <input type="" class="form-control" id="lieu" placeholder="Lieu de Naissance">
              </div>
              <div class="mb-2">
                <label for="exampleDropdownFormDate" class="form-label p-2">Sexe</label>
                <select class="form-auto">
                  <option _msttexthash="42861" _msthash="226">Masculin</option>
                  <option _msttexthash="73437" _msthash="227">Féminin</option>
                </select>
              </div>
              <div class="mb-2">
                <label for="exampleDropdownFormPassword2" class="form-label">Types élèves</label>
                <select class="form-auto">
                  <option _msttexthash="42861" _msthash="226">Ancien</option>
                  <option _msttexthash="73437" _msthash="227">Nouveau</option>
                </select>
              </div>
              <div class="mb-2">
                <input type="" class="form-control" id="date" placeholder="Date d'inscription">
              </div>
              <div class="mb-2 p-2">
                <label for="exampleDropdownFormPassword2" class="form-label">Apte</label>
                <select class="form-auto">
                  <option >Oui</option>
                  <option >Non</option>
                </select>
              </div>
              <div class="mb-2">
                <div class="form-check-center mx-9">
                  <input type="checkbox" class="form-check-input mx-9" id="dropdownCheck2">
                  <label class="form-check-label" for="dropdownCheck2">
                    Statut Redoublant
                  </label>
                </div>
              </div>                           
          </form> 
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-Detail" role="tabpanel" aria-labelledby="nav-Detail-tab" tabindex="0">
            <form class="accordion-body">
              <div class="table-responsive" style="height: 100px; overflow: auto;">
                <table class="table table-bordered table-striped" style="min-width: 800px;">
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
                      <td>1</td>
                      <td>Mark</td>
                      <td>Otto</td>
                      <td>@mdo</td>
                      <td>Jacob</td>
                      <td>Thornton</td>
                      <td>@fat</td>
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
               
              <div class="row">
                <div class="mb-2">
                  <label for="exampleDropdownForm">Moy.1</label>
                  <input type="" class="form-control" id="moy1" >
                </div>
                <div class="mb-2">
                  <label for="exampleDropdownForm">Moy.2</label>
                  <input type="" class="form-control" id="moy2">
                </div>
                <div class="mb-2">
                  <label for="exampleDropdownForm">Moy.3</label>
                  <input type="" class="form-control" id="moy3">
                </div>
              </div>

            </form>
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-Deta" role="tabpanel" aria-labelledby="nav-Deta-tab" tabindex="0">
            <div class="accordion-body">  
              <div class="form-check-center mx-9">
                  <input type="checkbox" class="form-check-input mx-9" id="dropdownCheck2">
                  <label class="form-check-label" for="dropdownCheck2">
                    Détail des composantes
                  </label>
              </div> 

              <div class="table-responsive mt-2" style="height: 100px; overflow: auto;">
                  <table class="table table-bordered " style="min-width: 10px;">
                    <thead>
                      <tr>
                        <th>n°Recu</th>
                        <th>Date</th>
                        <th>Montant</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>130</td>
                        <td>05/06/2024</td>
                        <td>190 000</td>
                      </tr>
                      <tr>
                        <td> </td>
                        <td> </td>
                        <td> </td> 
                      </tr>
                    </tbody>
                  </table>
              </div>

              <div class="input-group p-2">
                <div class="input-group-prepend">
                  <span class="input-group-text">Somme</span>
                </div>
                <input type="text" class="form-control" aria-label="Montant (au dollar près)" _mstaria-label="669448" _msthash="174">
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Reste à payer</span>
                </div>
                <input type="text" class="form-control" aria-label="Montant (au dollar près)" _mstaria-label="669448" _msthash="174">
              </div>

              <button type="button" class="btn btn-outline-danger btn-icon-text p-2">
                <i class="typcn typcn-upload btn-icon-prepend"></i><font> Imprimer récapitulatif des paiements </font>
              </button>
              
              <div class="card-body">
                <h4 class="card-title" _msttexthash="323960" _msthash="106">Réduction Montants dus</h4>
                <h8><form class="forms-sample">
                  <div class="form-group row">
                    <label for="exampleInputUsername2" class="col-sm-6 p-2 col-form-label">Scolarité</label>
                    <div class="col-sm-6">
                      <input type="num" class="form-control" id="exampleInputUsername2" >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputEmail2" class="col-sm-6 col-form-label">Arrièré</label>
                    <div class="col-sm-6">
                      <input type="num" class="form-control" id="exampleInputEmail2" >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputMobile" class="col-sm-6 col-form-label">Frais 1</label>
                    <div class="col-sm-6">
                      <input type="num" class="form-control" id="exampleInputMobile">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputPassword2" class="col-sm-6 col-form-label">Frais 2</label>
                    <div class="col-sm-6">
                      <input type="num" class="form-control" id="exampleInputPassword2">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleInputConfirmPassword2" class="col-sm-6 col-form-label">Frais 3</label>
                    <div class="col-sm-6">
                      <input type="num" class="form-control" id="exampleInputConfirmPassword2">
                    </div>
                  </div>
                </form></h8>
              </div>

            </div>
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-finan" role="tabpanel" aria-labelledby="nav-finan-tab" tabindex="0">
            <div class="accordion-body">
              <table class="table table-responsive text-center p-2">
                <thead>
                  <tr>
                    <th scope="col">Scolarités perçus <br> le 23/05/24</th>
                    <th scope="col">0</th>
                  </tr>
                  <tr>
                    <th scope="col">Arrièrés perçus <br> le 23/05/24</th>
                    <th scope="col">0</th>
                  </tr>
                  <tr>
                    <th scope="col">Total</th>
                    <th scope="col">0</th>
                  </tr>
                  <tr>
                    <th scope="col">Total recettes <br> à ce jour</th>
                    <th scope="col">57 575 500</th>
                  </tr>
                  <tr>
                    <th scope="col">Versé à la banque</th>
                    <th scope="col">0</th>
                  </tr>
                  <tr>
                    <th scope="col">Recettes attendues <br> ce jour</th>
                    <th scope="col">0</th>
                  </tr>
                  <tr>
                    <th scope="col">Recettes attendues <br> cette semaine</th>
                    <th scope="col">0</th>
                  </tr>
                  <tr>
                    <th scope="col">Recettes attendues <br> ce mois</th>
                    <th scope="col">0</th>
                  </tr>
                </thead>
               
              </table>
            </div>
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-Emploi" role="tabpanel" aria-labelledby="nav-Emploi-tab" tabindex="0">
            <div class="accordion-body">
              <table class="table table-responsive">
                <thead>
                  <tr>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                  </tr>
                  <tr>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                  </tr>
                  <tr>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!--  -->
          <div class="tab-pane fade" id="nav-Position" role="tabpanel" aria-labelledby="nav-Position-tab" tabindex="0">
            <div class="accordion-body">
              <table class="table table-responsive">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
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

@endsection
