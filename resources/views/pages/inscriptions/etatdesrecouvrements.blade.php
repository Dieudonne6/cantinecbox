@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card col-md-10">
        <div class="card-body">
            <div class="form-group row">
                <div class="col">
                    <label for="debut">Du</label>
                    <input name="debut" id="debut" type="date" class="typeaheads" required>
                </div>
                <div class="col">
                    <label for="fin">Au</label>
                    <input name="fin" id="fin" type="date" class="typeaheads" required>
                </div>
            </div>
        </div>

        <div class="card-body">
            <h4 class="text-center">Sélectionner l'état que vous souhaitez imprimer</h4>
            <div class="form-group row mt-1">
                <label for="groupe" class="col-sm-3 col-form-label">Choisir un groupe</label>
                <div class="col-sm-3 mt-2">
                    <select class="form-select" id="groupe">
                        <option>Standard</option>
                        <option>Féminin</option>
                    </select>
                </div>

                <label for="typeclasse" class="col-sm-3 col-form-label">Types de classe</label>
                <div class="col-sm-3 mt-2">
                    <select class="form-select" id="typeclasse">
                        <option>Normal</option>
                        <option>Nouveau</option>
                    </select>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                                <form>
                                    <div class="radio-inline">
                                        <input type="radio" id="option1" name="choixPlage" value="option1">
                                        <label for="option1">Recouvrement général (Tableau récapitulatif des recouvrements par composant)</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option2" name="choixPlage" value="option2">
                                        <label for="option2">Recouvrement général par enseignement (Tableau type "recouvrement général" par type d'enseignement)</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option3" name="choixPlage" value="option3">
                                        <label for="option3">Recouvrement par opérateur (Point des recouvrements par agent/par jour)</label>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                                <form>
                                    <div class="radio-inline">
                                        <input type="radio" id="option4" name="choixPlage" value="option4">
                                        <label for="option4">Journal détaillé des recouvrements (avec précision des composantes)</label>
                                        <select class="form-select mb-2" aria-label="Small select example">
                                            <option selected>Sélectionner un enseignement</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option5" name="choixPlage" value="option5">
                                        <label for="option5">Journal détaillé des recouvrements (sans précision des composantes)</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option6" name="choixPlage" value="option6">
                                        <label for="option6">Journal détaillé par opérateur (Liste détaillée des recouvrements par agent)</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option7" name="choixPlage" value="option7">
                                        <label for="option7">Journal résumé des recouvrements (Total recouvré par jour et par composante)</label>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Imprimer 
            </button>
        </div>
    </div>
</div>

@endsection
