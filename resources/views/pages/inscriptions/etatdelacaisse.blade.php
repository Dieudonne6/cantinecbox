@extends('layouts.master')

@section('content')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Bordereau des paiements/Recettes</h4>
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col">
                            <label>Type</label>
                            <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                <option>Bordereaux des paiements</option>
                                <option>Relevé mensuel des ordres de recettes</option>
                                <option>Bordereau de caisse</option>
                            </select>
                        </div>
                        <div class="col">
                          <label>Chapitre</label>
                          <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                              <option>ACTIVITE GENERALES</option>
                              <option>MATERNELLE CAMP-GUEZO</option>
                              <option>MATERNELLE CADJEHOUN</option>
                              <option>PRIMAIRE CADJEHOUN</option>
                              <option>PRIMAIRE CAMP-GUEZO</option>
                          </select>
                      </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label>Date début</label>
                            <div id="the-basics">
                                <input class="form-control" type="date" id="date" name="date" value="">
                            </div>
                        </div>
                        <div class="col">
                            <label>Date fin</label>
                            <div id="the-basics">
                                <input class="form-control" type="date" id="date" name="date" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">      
                        <div class="col">
                            <label>Périodicité</label>
                            <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                <option>Mensuel</option>
                                <option>Annuel</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Date d'arrêt</label>
                            <div id="the-basics">
                                <input class="form-control" type="date" id="date" name="date" value="">
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="text-center">
                <button type="button" class="btn btn-primary">Imprimer</button>
              </div>
        </div>
    </div>
</div>

@endsection