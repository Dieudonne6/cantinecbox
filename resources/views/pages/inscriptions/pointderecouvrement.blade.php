@extends('layouts.master')
@section('content')

<div class="main-panel-10">  
    <div class="container">
        <div class="card">
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
            <div class="card-body">
                <h4 class="card-title">Points de recouvrements par enseignement</h4>
                <form action=" " method="POST">
                    <input type="hidden" name="_token" value="rBqGG8bHdDisR0H7vlTJfcX9M1Ft0hcjMa0hezBB" autocomplete="off">
                    <div class="form-group row">
                        <div class="col">
                            <label for="debut">Du</label>
                            <input name="debut" id="debut" type="date" class="typeaheads" required="">
                        </div>
                        <div class="col">
                            <label for="fin">Au</label>
                            <input name="fin" id="fin" type="date" class="typeaheads" required="">
                        </div>
                
                        <div class="col">
                            <!-- Bouton de soumission de formulaire -->
                            <label for="debut" style="visibility: hidden">supprimer paiement</label>
                            <button type="submit" class="btn btn-primary w-100">Afficher</button>
                        </div>
                        
                        <div class="col">
                            <label for="debut" style="visibility: hidden">Du</label>
                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Imprimer 
                            </button>
                        </div>
                        
                    </div>
                </form>
                <div class="row">
                    <div class="table-responsive col-8">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Général
                                    </th>
                                    <th>
                                        Technique
                                    </th>
                                    <th>
                                        Supérieur
                                    </th>
                                    <th>
                                        Mat/Prim
                                    </th>
                                    <th>
                                        Total
                                    </th>   
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsible col-4">
                        <table class="table border=1">
                            <thead>
                              <tr>
                                <th>Nom</th>
                                <th>Classe</th>
                                <th>Montant</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>John Doe</td>
                                <td>9A</td>
                                <td>$200</td>
                              </tr>
                              <tr>
                                <td>Jane Smith</td>
                                <td>10B</td>
                                <td>$180</td>
                              </tr>
                              <!-- Ajoutez d'autres lignes ici -->
                            </tbody>
                          </table>
                          
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection