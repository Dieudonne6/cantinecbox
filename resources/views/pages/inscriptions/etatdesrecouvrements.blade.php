@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card col-md-10">
        <div class="card-body">
            <h4 class="card-title">Etat des recouvrements</h4>
            <div class="form-group row">
                <div class="col">
                    <label for="debut">Date de début</label>
                    <input name="debut" id="debut" type="date" class="typeaheads" required>
                </div>
                <div class="col">
                    <label for="fin">Date de fin</label>
                    <input name="fin" id="fin" type="date" class="typeaheads" required>
                </div>
            </div>
        </div>

        <div class="card-body">
            <h4 class="text-center">Sélectionner l'état que vous souhaitez imprimer</h4>
            <br>
            <div class="row justify-content-center">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <form required>
                            <div class="form-group row mt-1 justify-content-center">
                                <label for="groupe" class="col-sm-3 col-form-label">Choisir un groupe</label>
                                <div class="col-sm-3 mt-1">
                                    <select class="js-example-basic-multiple w-100" style="border: 1px solid #000; width: 100%; margin-right: 30px !important;" id="groupe">
                                        @foreach ($groupeclasse as $groupe)
                                            <option value="{{ $groupe->id }}">{{ $groupe->LibelleGroupe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                
                                <label for="typeclasse" class="col-sm-3 col-form-label">Types de classe</label>
                                <div class="col-sm-3 mt-2">
                                    <select class="js-example-basic-multiple w-100" id="typeclasse">
                                        @foreach ($typeclasse as $type)
                                            <option value="{{ $type->id }}">{{ $type->LibelleType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-1 justify-content-center">
                                    <div class="radio-inline">
                                        <input type="radio" id="option1" name="choixPlage" value="option1">
                                        <label for="option1" style="font-size: 14px; font-weight: bold">Recouvrement général</label>
                                        <h6 style="font-size: 14px; margin-left: 20px">Tableau récapitulatif des recouvrements par composant</h6>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option2" name="choixPlage" value="option2">
                                        <label for="option2" style="font-size: 14px; font-weight: bold">Recouvrement général par enseignement</label>
                                        <h6 style="font-size: 14px; margin-left: 20px">Tableau type "recouvrement général" par type d'enseignement</h6>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option3" name="choixPlage" value="option3">
                                        <label for="option3" style="font-size: 14px; font-weight: bold">Recouvrement par opérateur</label>
                                        <h6 style="font-size: 14px; margin-left: 20px">Point des recouvrements par agent/par jour</h6>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="radio-inline">
                                <select class="form-select mb-2" aria-label="Small select example">
                                    <option selected>Sélectionner un enseignement</option>
                                    @foreach ($typeenseign as $type)
                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div class="radio-inline">
                                <input type="radio" id="option4" name="choixPlage" value="option4">
                                <label for="option4" style="font-size: 14px; font-weight: bold">Journal détaillé des recouvrements avec précision des composantes</label>
                                <h6 style="font-size: 14px; margin-left: 20px">Liste des élèves ayant payé par jour et par composante</h6>
                            </div>
                            <div class="radio-inline">
                                <input type="radio" id="option5" name="choixPlage" value="option5">
                                <label for="option5" style="font-size: 14px; font-weight: bold">Journal détaillé des recouvrements sans précision des composantes</label>
                                <h6 style="font-size: 14px; margin-left: 20px">Liste des élèves ayant payé par jour sans précision des composantes</h6>
                            </div>
                            <br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 grid-margin stretch-card d-flex justify-content-center">
                    <div class="card">
                        <div class="card-body">
                            <div class="radio-inline">
                                <input type="radio" id="option6" name="choixPlage" value="option6">
                                <label for="option6" style="font-size: 14px; font-weight: bold">Recouvrement par opérateur</label>
                                <h6 style="font-size: 14px; margin-left: 20px">Point de recouvrement par agent/par jour</h6>
                            </div>
                            <div class="radio-inline">
                                <input type="radio" id="option7" name="choixPlage" value="option7">
                                <label for="option7" style="font-size: 14px; font-weight: bold">Journal détaillé par opérateur</label>
                                <h6 style="font-size: 14px; margin-left: 20px">Liste détaillée des recouvrements par agent</h6>
                            </div>
                            <div class="radio-inline">
                                <input type="radio" id="option8" name="choixPlage" value="option8">
                                <label for="option8" style="font-size: 14px; font-weight: bold">Journal résumé des recouvrements</label>
                                <h6 style="font-size: 14px; margin-left: 20px">Total recouvré par jour et par composante</h6>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <div class="d-flex justify-content-end">
                    <div class="col-md-2">
                        <button type="button" id="apply" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Appliquer la sélection  
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
/*     .footer{
    display: none;
    } */
    .footer {
        position: relative !important; /* Utiliser relative pour éviter de cacher le tableau */
        width: 100% !important;
        z-index: 10 !important; /* Assurer que le footer soit au-dessus des autres éléments */
    }
</style>