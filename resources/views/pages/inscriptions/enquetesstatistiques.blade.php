@extends('layouts.master')

@section('content')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
          <h4 class="card-title">Statistiques</h4>
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col">
                            <label>Choisir type d'enseignement</label>
                            <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                <option>Enseignement Général</option>
                                <option>Enseignement Technique</option>
                            </select>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary" style="margin-top: 20px">Créer les statistiques</button>
                        </div>
                        <div class="col">
                          <label>Cliquer pour choisir</label>
                          <div class="dropdown">
                            <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                              Imprimer
                            </a>
                          
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#">Groupes pédagogiques</a></li>
                              <li><a class="dropdown-item" href="#">Effectif par sexe et année d'étude</a></li>
                              <li><a class="dropdown-item" href="#">Effectif par âge, sexe et année d'étude</a></li>
                            </ul>
                          </div>
                      </div>
                    </div>
                </div>
              </div>
        </div>
    </div>
</div>

@endsection