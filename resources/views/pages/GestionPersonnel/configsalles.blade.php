@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Mise à jour de la table des salles disponibles</h5>
        </div>

        <div class="card-body">
            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="row g-3">
                        <!-- Classes -->
                        <div class="col-lg-5 col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">Classes</h6>
                                </div>
                                <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>CODECLAS</th>
                                                    <th>CODESALLE</th>
                                                    <th>VOLANTE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($classes as $classe)
                                                    <tr @if(empty($classe->CODESALLE)) style="background-color: #ffe6e6;" @endif>
                                                        <td>{{ $classe->CODECLAS }}</td>
                                                        <td>{{ $classe->CODESALLE ?? '—' }}</td>
                                                        <td>
                                                            <input type="checkbox" class="volante-checkbox"
                                                                   data-codeclas="{{ $classe->CODECLAS }}"
                                                                   @if($classe->VOLANTE) checked @endif>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Salles Disponibles -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">Salles disponibles</h6>
                                </div>
                                <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle" id="salles-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Codesalle</th>
                                                    <th>Localisation</th>
                                                    <th style="width: 120px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($salles as $salle)
                                                    <tr data-codesalle="{{ $salle->CODESALLE }}">
                                                        <td>{{ $salle->CODESALLE }}</td>
                                                        <td>{{ $salle->LOCALISATION ?? '—' }}</td>
                                                        <td>
                                                            <!-- Bouton de modification -->
                                                            <button type="button" class="btn btn-outline-primary btn-sm me-1" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editSalleModal-{{ $salle->CODESALLE }}"
                                                                    title="Modifier">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            
                                                            <!-- Formulaire de suppression -->
                                                            <form method="POST" action="{{ route('gestion.salles.delete') }}" style="display: inline-block;" 
                                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la salle {{ $salle->CODESALLE }} ?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="codesalle" value="{{ $salle->CODESALLE }}">
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">Aucune salle définie</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-lg-3 col-md-12">
                            <div class="card shadow-sm h-100">
                                <div class="card-header py-2 text-center">
                                    <h6 class="mb-0">Actions</h6>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-start align-items-stretch gap-2">
                                    <button id="btn-new" class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#salleModal">Nouvelle salle</button>

                                    <form action="{{ route('gestion.salles.assign') }}" method="POST" class="mt-2">
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

                                    <div class="text-end mt-2">
                                        <label class="form-label mb-0">Nb salles :</label>
                                        <input type="text" class="form-control form-control-sm d-inline-block w-auto"
                                               value="{{ $salles->count() }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-danger mt-3 mb-0">
                L'emploi du temps des classes en rouge ne sera pas créé car leurs salles n'existent pas.<br>
                Même si la classe est volante, lui associer une salle existante.
            </div>
        </div>
    </div>
</div>

<!-- Modal pour Nouvelle Salle -->
<div class="modal fade" id="salleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="salleForm" method="POST" action="{{ route('gestion.salles.create') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle salle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Code salle <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="new-codesalle" name="codesalle" required maxlength="50" placeholder="Ex: S101">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Localisation (optionnel)</label>
                        <input type="text" class="form-control" id="new-localisation" name="localisation" maxlength="255" placeholder="Ex: Bâtiment A, 1er étage">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modales de modification pour chaque salle -->
@foreach ($salles as $salle)
<div class="modal fade" id="editSalleModal-{{ $salle->CODESALLE }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('gestion.salles.update') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Modifier salle {{ $salle->CODESALLE }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Code salle <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="new_codesalle" value="{{ $salle->CODESALLE }}" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Localisation (optionnel)</label>
                        <input type="text" class="form-control" name="localisation" value="{{ $salle->LOCALISATION }}" maxlength="255">
                    </div>
                    <!-- Ancien code (pour l'update) -->
                    <input type="hidden" name="codesalle" value="{{ $salle->CODESALLE }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


<script>
    // Script pour les checkboxes volantes - séparé
    document.addEventListener('DOMContentLoaded', function () {
        const token = '{{ csrf_token() }}';
    
        document.querySelectorAll('input.volante-checkbox').forEach(cb => {
            cb.addEventListener('change', async function (e) {
                const checkbox = e.target;
                const code = checkbox.dataset.codeclas;
                const value = checkbox.checked ? 1 : 0;
                const row = checkbox.closest('tr');
    
                // Optionnel : indicateur visuel pendant requête
                checkbox.disabled = true;
    
                try {
                    const res = await fetch("{{ route('gestion.salles.toggleVolante') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ codeclas: code, value: value })
                    });
    
                    const json = await res.json();
    
                    if (!res.ok || !json.success) {
                        // Revenir en arrière si erreur
                        checkbox.checked = !checkbox.checked;
                        alert(json.message || 'Erreur lors de la mise à jour. Vérifie le serveur.');
                    } else {
                        // Mise à jour visuelle facultative
                        if (checkbox.checked) {
                            row.style.backgroundColor = '#fff8e1'; // ex. mise en surbrillance
                        } else {
                            row.style.backgroundColor = '';
                        }
                    }
                } catch (err) {
                    console.error(err);
                    checkbox.checked = !checkbox.checked;
                    alert('Erreur réseau. Réessaie.');
                } finally {
                    checkbox.disabled = false;
                }
            });
        });
    });
</script>


@endsection