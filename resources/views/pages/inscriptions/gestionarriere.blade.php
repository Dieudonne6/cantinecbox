@extends('layouts.master')
@section('content')
<div class="container">
  <div>
    <style>
        .btn-arrow {
            position: absolute;
            top: 0px;
            /* Ajustez la position verticale */
            left: 0px;
            /* Positionnez à gauche */
            background-color: transparent !important;
            border: 1px !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
            cursor: pointer !important;
            font-size: 17px !important;
            /* Taille de l'icône */
            color: #b51818 !important;
            /* Couleur de l'icône */
        }

        .btn-arrow:hover {
            color: #b700ff !important;
            /* Couleur au survol */
        }
    </style>
    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
        <i class="fas fa-arrow-left"></i> Retour
    </button>  
    <br>
    <br>                                    
</div>
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