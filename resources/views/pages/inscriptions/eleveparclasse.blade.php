@extends('layouts.master')
@section('content')

<div class="main-panel">  
    <div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">
  <h4 class="card-title">Etats des droits constatés</h4>
  <form action="http://127.0.0.1:8000/filteretat" method="POST">
    <input type="hidden" name="_token" value="rBqGG8bHdDisR0H7vlTJfcX9M1Ft0hcjMa0hezBB" autocomplete="off">
    <div class="row">
      <div class="col-3">
        <select class="form-control w-100" name="annee">
          <option value="">Sélectionnez une année</option>
                          <option value="2022">2022</option>
                      </select>
      </div>
      
      <div class="col-3">
        <select class="form-control w-100" name="classe">
          <option value="">Sélectionnez une classe</option>
                              <option value="CE1A">CE1A</option>
                              <option value="CE1B">CE1B</option>
                              <option value="CE1C">CE1C</option>
                              <option value="CE1S">CE1S</option>
                              <option value="CE2A">CE2A</option>
                              <option value="CE2B">CE2B</option>
                              <option value="CE2C">CE2C</option>
                              <option value="CE2S">CE2S</option>
                              <option value="CIA">CIA</option>
                              <option value="CIB">CIB</option>
                              <option value="CIC">CIC</option>
                              <option value="CIS">CIS</option>
                              <option value="CM1A">CM1A</option>
                              <option value="CM1B">CM1B</option>
                              <option value="CM1C">CM1C</option>
                              <option value="CM1S">CM1S</option>
                              <option value="CM2A">CM2A</option>
                              <option value="CM2B">CM2B</option>
                              <option value="CM2C">CM2C</option>
                              <option value="CM2S">CM2S</option>
                              <option value="CPA">CPA</option>
                              <option value="CPB">CPB</option>
                              <option value="CPC">CPC</option>
                              <option value="CPS">CPS</option>
                              <option value="DELETE">DELETE</option>
                              <option value="MAT1">MAT1</option>
                              <option value="MAT2">MAT2</option>
                              <option value="MAT2II">MAT2II</option>
                              <option value="MAT3">MAT3</option>
                              <option value="MAT3II">MAT3II</option>
                              <option value="NON">NON</option>
                              <option value="PREMATER">PREMATER</option>
                        </select>
      </div>
      <div class="col-3">
        <button type="submit" class="btn btn-primary w-100">
        Afficher
        </button>
      </div>
      <div class="col-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Imprimer la relance
        </button>
      </div>
    </div>
  </form>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="http://127.0.0.1:8000/relance" method="POST">
        <input type="hidden" name="_token" value="rBqGG8bHdDisR0H7vlTJfcX9M1Ft0hcjMa0hezBB" autocomplete="off">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Date Buttoir</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="date" class="form-control" name="daterelance">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Imprimer</button>
                </div>
            </div>
        </div>
    </form>
</div>
  <div class="table-responsive pt-3">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>
            N
          </th>
          <th>Elève</th>
          <th>Classe</th>
          <th>Inscription</th>
          <th>Janvier</th>
          <th>Fevrier</th>
          <th>Mars</th>
          <th>Avril</th>
          <th>Mai</th>
          <th>Juin</th>
          <th>Septembre</th>
          <th>Octobre</th>
          <th>Novembre</th>
          <th>Décembre</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
</div>
</div>
</div>
    <footer class="footer">
<div class="card mt-4">
  <div class="card-body">
      <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024 <a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">C box sarl</a>. Tous droit reserve.</span>
          <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center text-muted">Scodelux <a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">gestion de cantine.</a></span>
      </div>
  </div>    
</div>        
</footer>      </div>

@endsection