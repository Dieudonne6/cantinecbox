@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
 <div class="card">
    <div>
        <style>
            .btn-arrow {
                position: absolute;
                top: 0px;
                left: 0px;
                background-color: transparent !important;
                border: 1px !important;
                text-transform: uppercase !important;
                font-weight: bold !important;
                cursor: pointer !important;
                font-size: 17px !important;
                color: #b51818 !important;
            }

            .btn-arrow:hover {
                color: #b700ff !important;
            }
           
        </style>
        <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
        <br><br>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-9">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6 d-flex align-items-center">
                        <label for="tableSelect1" class="me-2 mb-0" style="min-width: 150px;">Classe :</label>
                        <select class="form-control js-example-basic-multiple" id="tableSelect1" onchange="displayTable()">
                            <option value="">Choisis une classe</option>
                        </select>
                    </div>

                    <div class="col-md-6 d-flex align-items-center">
                        <label for="tableSelect2" class="me-2 mb-0" style="min-width: 170px;">Classes déjà créées :</label>
                        <select class="form-control js-example-basic-multiple" id="tableSelect2" readonly>
                            <option value=""> Classes créées</option>
                        </select>
                    </div>
                </div>

                <div class="p-3 rounded" style="background-color: #f5f5dc; border: 1px solid #ccc;">
                    <div class="row align-items-center g-2">
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="seuil" class="me-2 mb-0" style="min-width: 100px;">Seuil :</label>
                            <input type="number" id="seuil" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="classeSup" class="me-2 mb-0" style="min-width: 100px;">Classe sup. :</label>
                            <input type="text" id="classeSup" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="minCycle1" class="me-2 mb-0" style="min-width: 100px;">Min. Cycle 1 :</label>
                            <input type="number" id="minCycle1" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="minCycle2" class="me-2 mb-0" style="min-width: 100px;">Min. Cycle 2 :</label>
                            <input type="number" id="minCycle2" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border p-1 mb-2 rounded" >
                    <div class="mb-3 text-center fw-bold">Statistiques élèves</div>

                        <div class="d-flex align-items-center mb-2">
                            <label class="fw-bold me-2" style="min-width: 100px;">Effectif :</label>
                            <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                            <span class="mx-1">dont</span>
                            <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                            <span>filles</span>
                        </div>

                        <div class="d-flex align-items-center mb-2">
                            <label class="fw-bold me-2" style="min-width: 100px;">Passage :</label>
                            <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                            <span class="mx-1">dont</span>
                            <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                            <span>filles</span>
                        </div>

                        <div class="d-flex align-items-center mb-2">
                            <label class="fw-bold me-2" style="min-width: 100px;">Redoubl. :</label>
                            <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                            <span class="mx-1">dont</span>
                            <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                            <span>filles</span>
                        </div>

                        <div class="d-flex align-items-center mb-2">
                            <label class="fw-bold me-2" style="min-width: 100px;">Exclusion :</label>
                            <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                            <span class="mx-1">dont</span>
                            <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                            <span>filles</span>
                        </div>

                        <div class="d-flex align-items-center">
                            <label class="fw-bold me-2" style="min-width: 100px;">Abandon :</label>
                            <input type="number" class="form-control form-control-sm me-1" value="" readonly style="width: 10px;">
                            <span class="mx-1">dont</span>
                            <input type="number" class="form-control form-control-sm mx-1" value="" readonly style="width: 10px;">
                            <span>filles</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          
        <div class="row" style="margin-left: 0.5rem" >
        
            <div class="col-md-8" style="margin-top: -4rem">
                <div class="d-flex justify-content-between mb-2">
                    <button class="btn btn-link text-primary fw-bold">1. Configurer les classes supérieures</button>
                    <button class="btn btn-link text-danger fw-bold">2. Configurer décisions</button>
                </div>
                <!-- Tableau principal -->
                <table class="table table-bordered table-light table-sm text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Rang</th>
                            <th>Nom et prénoms</th>
                            <th>Redou</th>
                            <th>Moy1</th>
                            <th>Moy2</th>
                            <th>Moy An</th>
                            <th>Observation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-muted">Aucune donnée</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-3 d-grid gap-2" style="margin-top: 1rem; margin-left: 4rem">
                <button class="btn btn-outline-primary btn-sm">Créer rapport</button>
                <button class="btn btn-outline-primary btn-sm">Imprimer rapport</button>
                <button class="btn btn-outline-secondary btn-sm">Afficher/ignorer</button>
                <button class="btn btn-success btn-sm">Enregistrer</button>
                <br> <br><br>
            </div>
        </div> 
  
         <div class="row">
        
            <div class="col-md-12 mt-n5">
                <div class="card shadow-sm p-3 rounded" style="background-color: #f8f9fa;">
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <button class="btn btn-outline-primary btn-sm" style="font-weight: bold">Liste générale passage</button>
                        <button class="btn btn-outline-warning btn-sm" style="font-weight: bold">Liste générale redoublement</button>
                        <button class="btn btn-outline-danger btn-sm" style="font-weight: bold">Liste générale exclusion</button>
                        <button class="btn btn-outline-secondary btn-sm" style="font-weight: bold">Liste générale abandon</button>
                        <button class="btn btn-outline-success btn-sm" style="font-weight: bold">Exporter liste</button>
                    </div>
                    <br><br> <br>
                </div>
            </div>

</div>
       
       
    </div>
  
</div>  
@endsection



