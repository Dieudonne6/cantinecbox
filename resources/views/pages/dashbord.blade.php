@extends('layouts.master')
@section('content')

  <div class="main-panel-10">
    <div class="content-wrapper">

      <div class="row">
        <div class="col-xl-4 grid-margin stretch-card">
          <div class="card">
            <div class="card-body border-bottom">
              <div class="d-flex justify-content-center align-items-center flex-wrap">
                <button class="btn btn-light dropdown-toggle" style="display: flex; justify-content: center; align-items: center; height:;"
                    type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <h6>Effectif</h6>
                </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3">
                    <a class="dropdown-item" href="javascript:;">Evolution de l'éffectif sur 5 ans</a>
                    <a class="dropdown-item" href="javascript:;">Evolution de l'éffectif par promotion sur 5 ans</a>
                  </div>
                </div>
            </div>    
          </div>
        </div>
        
        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
          <div class="card">
            <div class="card-body border-bottom">
              <div class="d-flex justify-content-center align-items-center flex-wrap">
                <button class="btn btn-light dropdown-toggle" style="display: flex; justify-content: center; align-items: center; height:;"
                type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <h6>Recouvrement</h6>
               </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton4">
                    <a class="dropdown-item" href="javascript:;">Tableau analytique des recouvrements mensuels</a>
                    <a class="dropdown-item" href="javascript:;">Comparaison taux de recouvrement</a>
                    <a class="dropdown-item" href="javascript:;">Comparaison chiffre d'affaire</a>
                  </div>
                </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="dropdown d-flex justify-content-center align-items-center flex-wrap">
                    <button class="btn btn-light dropdown-toggle" style="display: flex; justify-content: center; align-items: center; height:;"
                     type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <h6>Académique</h6>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton4">
                      <a class="dropdown-item" href="javascript:;">Taux de passage en classe supérieure</a>
                      <a class="dropdown-item" href="javascript:;">Taux de redoublement</a>
                      <a class="dropdown-item" href="javascript:;">Taux d'exclusion</a>
                      <a class="dropdown-item" href="javascript:;">Evolution Taux de réussite par type d'examen</a>
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="dropdown d-flex justify-content-center align-items-center flex-wrap">
                    <button class="btn btn-light dropdown-toggle" style="display: flex; justify-content: center; align-items: center; height:;"
                     type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <h6>Budgétaire</h6>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton4">
                      <h6 class="dropdown-header">Settings</h6>
                      <a class="dropdown-item" href="javascript:;">Action</a>
                      <a class="dropdown-item" href="javascript:;">Another action</a>
                      <a class="dropdown-item" href="javascript:;">Something else here</a>
                      <a class="dropdown-item" href="javascript:;">Separated link</a>
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div style="display: flex; justify-content: center; align-items: center; height:;">
                    <button class="btn btn-light">Rentabilité</button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap"> 
                  <a class="btn btn-light " data-toggle="button">Historique des suppressions et paiements</a>    
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <a class="btn btn-light " data-toggle="button">Historique des modifications de paiements</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <a class="btn btn-light " data-toggle="button">Historique des suppressions d'élèves</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <a class="btn btn-light" data-toggle="button">Historique des modifications de profiles</a>
                </div>
              </div>
            </div>
          </div>

      </div>

    </div>

@endsection