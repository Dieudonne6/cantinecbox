@extends('layouts.master')
@section('content')
<div class="container">
  <div class="row">
    <h4>Gestion des arrierés pour les non inscrits</h4>
    <div class="col-lg-8 card">
      <div class="table-responsive">
        <table id="myTable" class="table table-striped">
          <thead>
            <tr>
              <th>Matricule</th>
              <th>Matricule</th> 
              <th>Nom</th>
              <th>Prenom</th>
              <th>Anc Classe</th>
              <th>Reste à payer</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td></td>

              <td></td>

              <td></td>

              <td></td>

              <td></td>

            </tr>
              
                        
          </tbody>  
        </table>
        <a class="btn btn-primary">Modifier les restes a payer</a>
      </div>
    </div>
    <div class="col-lg-4">
      <h5>Détails des paiements</h5>
      <div class="table-responsive">
        <table id="myTable" class="table table-striped">
          <thead>
            <tr>
              <th>N</th>
              <th>Date</th> 
              <th>Montant</th>
            </tr>
          </thead>
          <tbody>
                     
          </tbody>  
        </table>
      </div>
    </div>
    
  </div>
</div>
@endsection