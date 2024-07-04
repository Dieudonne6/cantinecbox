@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Enregistrement des classes</h4>

                    
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nom classe</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="exampleInputUsername2"
                                placeholder="Nom classe">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Libelle</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Libelle">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exampleSelectGender" class="col-sm-3 col-form-label">Type Classe</label>
                        <div class="col-sm-9">
                        <select class="form-control" id="exampleSelectGender">
                            <option>Normal</option>
                            <option>Temporaire</option>
                            <option>Cous du soir</option>
                        </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exampleSelectGender" class="col-sm-3 col-form-label">Enseignement</label>
                        <div class="col-sm-9">
                        <select class="form-control" id="exampleSelectGender">
                            <option>Prescolaire</option>
                            <option>Primaire</option>
                            <option>General</option>
                            <option>Technique</option>
                            <option>Profess</option>
                            <option>Superieur</option>
                        </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exampleSelectGender" class="col-sm-3 col-form-label">Promotion</label>
                        <div class="col-sm-9">
                        <select class="form-control" id="exampleSelectGender">
                            <option>Undefined</option>
                        </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exampleSelectGender" class="col-sm-3 col-form-label">No d'ordre</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="exampleInputUsername2" placeholder="No d'ordre">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exampleSelectGender" class="col-sm-3 col-form-label">Cycle</label>
                        <div class="col-sm-9">
                        <select class="form-control" id="exampleSelectGender">
                            <option>0</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                        </select>
                        </div> 
                    </div>

                    <div class="form-group row">
                        <label for="exampleSelectGender" class="col-sm-3 col-form-label">Serie</label>
                        <div class="col-sm-9">
                        <select class="form-control" id="exampleSelectGender">
                            <option>Serie</option>
                        </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exampleSelectGender" class="col-sm-3 col-form-label">Cours Jour/Soir</label>
                        <div class="col-sm-9">
                        <select class="form-control" id="exampleSelectGender">
                            <option>Jour</option>
                            <option>Soir</option>
                        </select>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-danger">Annuler</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#donnefinanciere">Donne financieres(Factures)</button>

                </div>
            </div>
        </div>

            <!-- Modal -->
            <div class="modal fade" id="donnefinanciere" tabindex="-1" aria-labelledby="donnefinanciereLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="donnefinanciereLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
    
                            <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nom classe</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="exampleInputUsername2"
                                        placeholder="Nom classe">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Libelle</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Libelle">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nom classe</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="exampleInputUsername2"
                                        placeholder="Nom classe">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Libelle</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Libelle">
                                </div>
                            </div>
    
    
    
    
    
                        </div>
                        <div class="modal-footer">
                            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button> --}}
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
