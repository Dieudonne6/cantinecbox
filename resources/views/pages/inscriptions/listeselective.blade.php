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
        <div class="col-3">
            <select class="form-control w-100" name="classe">
                <option value="">Sélectionnez une classe</option>
                @foreach(['CE1A', 'CE1B', 'CE1C', 'CE1S', 'CE2A', 'CE2B', 'CE2C', 'CE2S', 'CIA', 'CIB', 'CIC', 'CIS', 'CM1A', 'CM1B', 'CM1C', 'CM1S', 'CM2A', 'CM2B', 'CM2C', 'CM2S', 'CPA', 'CPB', 'CPC', 'CPS', 'DELETE', 'MAT1', 'MAT2', 'MAT2II', 'MAT3', 'MAT3II', 'NON', 'PREMATER'] as $classe)
                    <option value="{{ $classe }}">{{ $classe }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <form>
                <label for="sexe">Sexe :</label>
                <select id="sexe" name="sexe">
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Féminin</option>
                    <option value="tous">Tous</option>
                </select>
            </form>
        </div>
        <div class="col-3">
            
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
</div>

@endsection
