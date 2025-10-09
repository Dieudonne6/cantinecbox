@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Mise à jour de la table des salles disponibles</h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="window.close()">Fermer</button>
        </div>

        <div class="card-body">
            <!-- Bloc global -->
            <div class="row">
                <!-- Conteneur principal -->
                <div class="col-12">
                    <div class="row g-3">

                        <!-- Colonne 1 : Tableau Classes -->
                        <div class="col-lg-5 col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">Classes</h6>
                                </div>
                                <div class="card-body p-2" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>CODECLAS <i class="fas fa-search ms-1"></i></th>
                                                    <th>CODESALLE <i class="fas fa-search ms-1"></i></th>
                                                    <th>VOLANTE <i class="fas fa-trash-alt ms-1"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($classes as $classe)
                                                    <tr @if(empty($classe->CODESALLE)) style="background-color: #ffe6e6;" @endif>
                                                        <td>{{ $classe->CODECLAS }}</td>
                                                        <td>{{ $classe->CODESALLE ?? '—' }}</td>
                                                        <td>
                                                            @if($classe->VOLANTE)
                                                                <span class="text-success">✓</span>
                                                            @else
                                                                —
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Colonne 2 : Tableau Salles Disponibles -->
                        <div class="col-lg-5 col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">Salles disponibles</h6>
                                </div>
                                <div class="card-body p-2" style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Codesalle <i class="fas fa-search ms-1"></i></th>
                                                    <th>Localisation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($salles as $salle)
                                                    <tr>
                                                        <td>{{ $salle }}</td>
                                                        <td>—</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted">Aucune salle définie</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Colonne 3 : Boutons -->
                        <div class="col-lg-2 col-md-12">
                            <div class="card shadow-sm h-100">
                                <div class="card-header py-2 text-center">
                                    <h6 class="mb-0">Actions</h6>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-start align-items-stretch gap-2">
                                    <button class="btn btn-outline-primary btn-sm w-100" disabled>Nouvelle salle</button>
                                    <button class="btn btn-outline-primary btn-sm w-100" disabled>Modifier salle</button>
                                    <button class="btn btn-outline-primary btn-sm w-100" disabled>Supprimer salle</button>

                                    <form action="{{ route('gestion.salles.assign') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm w-100"
                                            onclick="return confirm('Associer chaque classe sans salle à son propre code classe ?')">
                                            Classes = salles
                                        </button>
                                    </form>

                                    <button class="btn btn-outline-secondary btn-sm w-100 mt-2"
                                        onclick="alert('Aide : Cliquez sur \\\"Classes=salles\\\" pour attribuer automatiquement à chaque classe sans salle le code de la classe comme salle.')">
                                        Aide >>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Compteur Nb salles -->
            <div class="text-end mt-2">
                <label class="form-label mb-0">Nb salles :</label>
                <input type="text" class="form-control form-control-sm d-inline-block w-auto"
                        value="{{ $salles->count() }}" readonly>
            </div>
            <!-- Message d'avertissement -->
            <div class="alert alert-danger mt-3 mb-0">
                L'emploi du temps des classes en rouge ne sera pas créé car leurs salles n'existent pas.<br>
                Même si la classe est volante, lui associer une salle existante.
            </div>
        </div>
    </div>
</div>
@endsection