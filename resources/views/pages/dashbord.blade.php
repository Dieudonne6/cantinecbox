@extends('layouts.master')
@section('content')

  <div class="main-panel-10">
    <div class="content-wrapper">

      <div class="row">
        <div class="col-xl-4 grid-margin stretch-card">
          <div class="card">
            <div class="card-body border-bottom">
              <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Effectifs</h6>
                <div class="dropdown">
                  <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Effectifs
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3">
                    <a class="dropdown-item" href="javascript:;">Evolution de l'éffectif sur 5 ans</a>
                    <a class="dropdown-item" href="javascript:;">Evolution de l'éffectif par promotion sur 5 ans</a>
                  </div>
                </div>
              </div>
            </div>    
          </div>
        </div>
        
        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
          <div class="card">
            <div class="card-body border-bottom">
              <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Recouvrements</h6>
                <div class="dropdown">
                  <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Recouvrements
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
        </div>

        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Recouvrements</h6>
                  <div class="dropdown">
                    <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Académique
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
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Recouvrements</h6>
                  <div class="dropdown">
                    <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Rentabilité
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Recouvrements</h6>
                  <div class="dropdown">
                    <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Last 7 days
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
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Recouvrements</h6>
                  <div class="dropdown">
                    <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Last 7 days
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
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Recouvrements</h6>
                  <div class="dropdown">
                    <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Last 7 days
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
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Recouvrements</h6>
                  <div class="dropdown">
                    <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Last 7 days
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
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Recouvrements</h6>
                  <div class="dropdown">
                    <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Last 7 days
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
          </div>

      </div>

    </div>

@endsection