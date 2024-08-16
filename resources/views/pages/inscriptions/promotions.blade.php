@extends('layouts.master')
@section('content')

<div class="main-panel-10">
    <div class="content-wrapper">

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        
        <div class="row">
            <div class="col-12">
                <div class="card mb-6">
                    <div class="card-body">
                        <h4 class="card-title">Mise à jour des promotions</h4>
                        <div class="row gy-3">
                            <div class="col text-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                                    Nouveau
                                </button>
                                <!-- Add Promotion Modal -->
                                <div class="modal fade" id="addPromotionModal" tabindex="-1" aria-labelledby="addPromotionModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="addPromotionModalLabel">Fiche d'une promotion</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('promotions.store') }}">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <div class="col-sm-6 mb-1">
                                                            <label for="codePromotion"><strong>Code promotion </strong></label>
                                                            <p>(4 caractères max. Ex: TLE)</p>
                                                            <input type="text" id="codePromotion" name="codePromotion" class="form-control" required>
                                                            @error('codePromotion')
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-6 mb-1">
                                                            <label for="libellePromotion"><strong>Libellé promotion</strong></label>
                                                            <p>(Ex: Terminale)</p>
                                                            <input type="text" id="libellePromotion" name="libellePromotion" class="form-control" required>
                                                            @error('libellePromotion')
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6 mb-1">
                                                            <label for="Niveau"><strong>Niveau dans la hiérarchie </strong></label>
                                                            <p>(Ex: 1 pour 6eme, 7 pour Tle)</p>
                                                            <input type="number" id="Niveau" name="Niveau" class="form-control" required min="1" max="7">
                                                            @error('Niveau')
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-6 mb-1">
                                                            <label for="enseignement"><strong>Pour quel Enseignement</strong></label>
                                                            <p>(Choisir type d'enseignement)</p>
                                                            <select id="enseignement" name="enseignement" class="form-control" required>
                                                                <option value="0">Préscolaire</option>
                                                                <option value="1">Primaire</option>
                                                                <option value="2">Général</option>
                                                                <option value="3">Technique</option>
                                                                <option value="4">Professionnel</option>
                                                                <option value="5">Supérieur</option>
                                                            </select>
                                                            @error('enseignement')
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                               
                                <style>
                                    table {
                                        float: right;
                                        width: 100%;
                                        border-collapse: collapse;
                                        margin: 5px auto;
                                    }
                                    th, td {
                                        padding: 8px;
                                        text-align: center;
                                    }
                                </style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="table-responsive" style="overflow: auto;">
                        <table class="table table-striped" style="min-width: 600px; font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Code promotion</th>
                                    <th>Libellé promotion</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promotions as $promotion)
                                <tr>
                                    <td>{{ $promotion->CODEPROMO }}</td>
                                    <td>{{ $promotion->LIBELPROMO }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary p-2 btn-sm" data-bs-toggle="modal" data-bs-target="#editPromotionModal{{ $promotion->CODEPROMO }}">
                                            Modifier
                                        </button>
                        
                                        <!-- Edit Promotion Modal -->
                                        <div class="modal fade" id="editPromotionModal{{ $promotion->CODEPROMO }}" tabindex="-1" aria-labelledby="editPromotionModalLabel{{ $promotion->CODEPROMO }}" aria-hidden="true">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="editPromotionModalLabel{{ $promotion->CODEPROMO }}">Modifier fiche d'une promotion</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card-body">
                                                            <form method="POST" action="{{ route('promotions.update', $promotion->CODEPROMO) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="form-group row">
                                                                    <div class="col-sm-6">
                                                                        <label for="editCodePromotion{{ $promotion->CODEPROMO }}"><strong>Code promotion</strong></label>
                                                                        <p>(4 caractères max. Ex: TLE)</p>
                                                                        <input type="text" id="editCodePromotion{{ $promotion->CODEPROMO }}" name="codePromotion" class="form-control" value="{{ $promotion->CODEPROMO }}" required>
                                                                        @error('codePromotion')
                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label for="editLibellePromotion{{ $promotion->CODEPROMO }}"><strong>Libellé promotion</strong></label>
                                                                        <p>(Ex: Terminale)</p>
                                                                        <input type="text" id="editLibellePromotion{{ $promotion->CODEPROMO }}" name="libellePromotion" class="form-control" value="{{ $promotion->LIBELPROMO }}" required>
                                                                        @error('libellePromotion')
                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-6">
                                                                        <label for="editNiveauHierarchie{{ $promotion->CODEPROMO }}"><strong>Niveau dans la hiérarchie</strong></label>
                                                                        <p>(Ex: 1 pour 6eme, 7 pour Tle)</p>
                                                                        <input type="number" id="editNiveauHierarchie{{ $promotion->CODEPROMO }}" name="Niveau" class="form-control" value="{{ $promotion->Niveau }}" required min="1" max="7">
                                                                        @error('Niveau')
                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label for="editEnseignement{{ $promotion->CODEPROMO }}"><strong>Pour quel Enseignement</strong></label>
                                                                        <p>(Choisir type d'enseignement)</p>
                                                                        <select id="editEnseignement{{ $promotion->CODEPROMO }}" name="enseignement" class="form-control" required>
                                                                            <option value="0" {{ $promotion->TYPEENSEIG == 0 ? 'selected' : '' }}>Préscolaire</option>
                                                                            <option value="1" {{ $promotion->TYPEENSEIG == 1 ? 'selected' : '' }}>Primaire</option>
                                                                            <option value="2" {{ $promotion->TYPEENSEIG == 2 ? 'selected' : '' }}>Général</option>
                                                                            <option value="3" {{ $promotion->TYPEENSEIG == 3 ? 'selected' : '' }}>Technique</option>
                                                                            <option value="4" {{ $promotion->TYPEENSEIG == 4 ? 'selected' : '' }}>Professionnel</option>
                                                                            <option value="5" {{ $promotion->TYPEENSEIG == 5 ? 'selected' : '' }}>Supérieur</option>
                                                                        </select>
                                                                        @error('enseignement')
                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                        
                                        <button type="button" class="btn btn-danger p-2 btn-sm" data-bs-toggle="modal" data-bs-target="#deletePromotionModal{{ $promotion->CODEPROMO }}">
                                            Supprimer
                                        </button>
                        
                                        <!-- Delete Promotion Modal -->
                                        <div class="modal fade" id="deletePromotionModal{{ $promotion->CODEPROMO }}" tabindex="-1" aria-labelledby="deletePromotionModalLabel{{ $promotion->CODEPROMO }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deletePromotionModalLabel{{ $promotion->CODEPROMO }}">Supprimer la promotion</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Voulez-vous vraiment supprimer cette promotion?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('promotions.destroy', $promotion->CODEPROMO) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
