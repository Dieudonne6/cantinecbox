@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-center align-items-start">
    <div class="card col-md-10">
        <div class="card-body">
            <h4 class="card-title">Répartition des classes par opérateur</h4>
            
            <!-- Sélecteur d'opérateur -->
            
            <form action="{{ route('repartitionclassesoperateur') }}" method="post">
                @csrf
                <div class="row">
                    <!-- Premier tableau -->
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Classe</th>
                                    <th>Opérateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classes as $classe)
                                    <tr>
                                        <td>{{ $classe->CODECLAS }}</td>
                                        <td>
                                            <select name="login[{{ $classe->CODECLAS }}]" class="custom-select-width">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->login }}" {{ $user->login == $classe->SIGNATURE ? 'selected' : '' }}>
                                                        {{ $user->login }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Liste des classes affectées -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="operator-select">Sélectionner un opérateur</label>
                            <select id="operator-select" class="form-control">
                                <option value="">-- Choisir un opérateur --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->login }}">{{ $user->login }}</option>
                                @endforeach
                            </select>
                        </div>
                        <h5>Liste des classes affectées à cet opérateur</h5>
                        <ul id="class-list">
                            <!-- Les classes affectées seront affichées ici -->
                        </ul>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-secondary mr-2" onclick="window.location.reload();">Annuler</button>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('operator-select').addEventListener('change', function() {
        const operator = this.value;
        const classList = document.getElementById('class-list');
        classList.innerHTML = '';

        // Simuler une requête pour obtenir les classes affectées
        const classesAffectees = @json($classes_operateur);

        classesAffectees.forEach(classe => {
            if (classe.SIGNATURE === operator) {
                const li = document.createElement('li');
                li.textContent = classe.CODECLAS;
                classList.appendChild(li);
            }
        });
    });
</script>

<style>
    .footer {
        position: relative !important; /* Utiliser relative pour éviter de cacher le tableau */
        width: 100% !important;
        z-index: 10 !important; /* Assurer que le footer soit au-dessus des autres éléments */
    }
    th, td {
        border: 1px solid gray !important;
    }
    .custom-select-width {
        width: 100% !important;
    }
    .table td, .table th {
        width: 50%;
    }
    .card-body {
        padding: 20px;
    }
    .btn {
        min-width: 100px;
    }
</style>
@endsection
