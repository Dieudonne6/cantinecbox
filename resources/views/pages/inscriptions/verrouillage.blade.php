@extends('layouts.master')

@section('content')

<div class="container">
    <div class="col-12 grid-margin">
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
                <h4 class="card-title">verrouillage de la scolarité</h4>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group row">
                            <div class="col">
                                <label>Sélectionner</label>
                                    <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                        <option>Verrouiller l'écriture comptable de l'élève sélectionné</option>
                                        <option>Verrouiller les écritures comptables de tous les élèves</option>
                                    </select>
                            </div>
                            <div class="col">
                                <label>Seul le superviseur pourra déverouiller</label>
                                    <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                        <option>Oui</option>
                                        <option>Non</option>
                                    </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="text-center">
                                <h6>Sur quelle période effectuer les verrouillages</h6>
                            </div>
                            <br>
                            <br>
                            <div class="col">
                                <label>Date début période</label>
                                <div id="the-basics">
                                    <input class="form-control" type="date" id="date" name="date" value="">
                                </div>
                            </div>
                            <div class="col">
                              <label>Date fin période</label>
                              <div id="the-basics">
                                <input class="form-control" type="date" id="date" name="date" value="">
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">      
                            <div class="col">
                                <label>Bloquer également toute nouvelle saisie antérieures à cette date</label>
                                    <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                                        <option>Oui</option>
                                        <option>Non</option>
                                    </select>
                            </div>
                    <div class="col">
                        <label>Date de verrouillage</label>
                        <div id="the-basics">
                            <input class="form-control" type="date" id="date" name="date" value="">
                        </div>
                    </div>
                        </div>
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Lancer verrouillage</button>
                            </div>
                        </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>

@endsection