@extends('layouts.master')

@section('content')
<div class="card">
    <div class="col mt-2">
        <div class="form-group row">
            <label for="titreEtat" class="col-sm-4 col-form-label">Titre de l'Etat</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="titreEtat" placeholder=" " />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <select class="form-control w-100" name="classe">
                <option value="">Sélectionnez une classe</option>
                @foreach(['CE1A', 'CE1B', 'CE1C', 'CE1S', 'CE2A', 'CE2B', 'CE2C', 'CE2S', 'CIA', 'CIB', 'CIC', 'CIS', 'CM1A', 'CM1B', 'CM1C', 'CM1S', 'CM2A', 'CM2B', 'CM2C', 'CM2S', 'CPA', 'CPB', 'CPC', 'CPS', 'DELETE', 'MAT1', 'MAT2', 'MAT2II', 'MAT3', 'MAT3II', 'NON', 'PREMATER'] as $classe)
                    <option value="{{ $classe }}">{{ $classe }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <form>
                <label for="sexe">Sexe :</label>
                <select id="sexe" name="sexe" class="form-control">
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Féminin</option>
                    <option value="tous">Tous</option>
                </select>
            </form>
        </div>
        <div class="col-md-5">
            <div class="form-group row mt-1">
                <label for="periodeDebut" class="col-sm-4 col-form-label">Age compris entre</label>
                <div class="col-sm-3">
                    <input type="age" class="form-control" id="periodeDebut">
                </div>
                <label for="periodeFin" class="col-sm-1 col-form-label">et</label>
                <div class="col-sm-3">
                    <input type="age" class="form-control" id="periodeFin">
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-check ">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                  Ignorer Age
                </label>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="table-responsive pt-3">
            <table class="table table-striped project-orders-table">
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénoms</th>
                        <th>Classe</th>
                        <th>Date de Naissance</th>
                        <th>Lieu de Naissance</th>
                        <th>Sexe</th>
                        <th>Statut</th>
                        <th>Apte</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>Dupont</td>
                        <td>Jean</td>
                        <td>CE2</td>
                        <td>01/01/1990</td>
                        <td>Paris</td>
                        <td>M</td>
                        <td>Actif</td>
                        <td>Apte</td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>Durand</td>
                        <td>Marie</td>
                        <td>CM2</td>
                        <td>15/05/1985</td>
                        <td>Lyon</td>
                        <td>F</td>
                        <td>Inactif</td>
                        <td>Apte</td>
                    </tr>
                    <!-- Ajouter d'autres lignes ici -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6 text-center">
            <button class="btn btn-primary ">Créer liste</button>
        </div>
        <div class="col-md-6">
            <button class="btn btn-secondary">Imprimer liste</button>
        </div>
    </div>
</div>
@endsection
