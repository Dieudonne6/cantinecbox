@extends('layouts.master')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users-cog me-2"></i> Gestion des Rôles et Permissions
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Onglets --}}
                    <ul class="nav nav-tabs" id="rolesTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button">
                                <i class="fas fa-shield-alt"></i> Matrice des Permissions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="groupes-tab" data-bs-toggle="tab" data-bs-target="#groupes" type="button">
                                <i class="fas fa-users"></i> Gestion des Groupes
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="utilisateurs-tab" data-bs-toggle="tab" data-bs-target="#utilisateurs" type="button">
                                <i class="fas fa-user-friends"></i> Utilisateurs
                            </button>
                        </li>
                    </ul>
                    {{-- Contenu des onglets --}}
                    <div class="tab-content" id="rolesTabContent">
                        {{-- Onglet Matrice des Permissions --}}
                        <div class="tab-pane fade show active" id="permissions" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h4 class="mb-2">
                                        <i class="fas fa-shield-alt text-primary me-2"></i>
                                        Matrice des Permissions
                                    </h4>
                                    <p class="text-muted">
                                        Cochez les cases pour attribuer les permissions aux groupes
                                        <span class="badge bg-secondary ms-2" id="changesCounter">0 modifications</span>
                                    </p>
                                    
                                    {{-- Légende des permissions --}}
                                    <div class="alert alert-info py-2 mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small><i class="fas fa-eye text-primary me-1"></i> <strong>View</strong> = Accès en lecture seule</small>
                                            </div>
                                            <div class="col-md-6">
                                                <small><i class="fas fa-cogs text-danger me-1"></i> <strong>Manage</strong> = Accès complet (inclut automatiquement View)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="btn-group me-2" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllBtn">
                                            <i class="fas fa-check-double"></i> Tout sélectionner
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearAllBtn">
                                            <i class="fas fa-times"></i> Tout désélectionner
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-success" onclick="saveAllPermissions()" id="saveBtn">
                                        <i class="fas fa-save"></i> 
                                        <span class="save-text">Sauvegarder</span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    </button>
                                </div>
                            </div>
                            <!-- Filtre par module -->
                            <div class="row mb-3" id="moduleFilterRow">
                                <div class="col-md-4 ms-auto">
                                    <label for="moduleFilter" class="form-label">Filtrer par module :</label>
                                    <select class="form-select" id="moduleFilter">
                                        <option value="">Tous les modules</option>
                                        @foreach($fonctionnalites as $module => $items)
                                            <option value="{{ \Illuminate\Support\Str::slug($module, '_') }}">{{ $module }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Statistiques en temps réel -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card bg-light border-0">
                                        <div class="card-body py-2">
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <small class="text-muted">Total éléments</small>
                                                    <div class="fw-bold text-primary" id="statsTotal">0</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Permissions actives</small>
                                                    <div class="fw-bold text-success" id="statsActive">0</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Éléments visibles</small>
                                                    <div class="fw-bold text-info" id="statsVisible">0</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle" id="permissionsTable">
                                    <thead>
                                        <tr class="table-dark">
                                            <th style="width: 400px; text-align: center; font-weight: bold; vertical-align: middle;">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span>Fonctionnalité</span>
                                                </div>
                                            </th>                                            
                                            @foreach($groupes->where('nomgroupe', '!=', 'ADMINISTRATEUR') as $groupe)
                                                <th class="text-center" colspan="2">
                                                    <div class="fw-bold">{{ $groupe->nomgroupe }}</div>
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr class="table-secondary">
                                            <th></th>
                                            @foreach($groupes->where('nomgroupe', '!=', 'ADMINISTRATEUR') as $groupe)
                                                <th class="text-center" style="min-width: 90px;">
                                                    <i class="fas fa-eye text-info"></i> Voir
                                                </th>
                                                <th class="text-center" style="min-width: 100px;">
                                                    <i class="fas fa-cog text-warning"></i> Gérer
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="permissionsBody">
                                        @foreach($fonctionnalites as $module => $items)
                                            @php
                                                $moduleId = \Illuminate\Support\Str::slug($module, '_');
                                            @endphp
                                            <tr class="module-header table-primary" data-module="{{ $moduleId }}">
                                                <td colspan="{{ (count($groupes) * 2) + 1 }}" class="fw-bold">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-folder-open text-primary me-2"></i>
                                                        <span class="text-primary">{{ $module }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @foreach($items as $itemIndex => $item)
                                                @php
                                                    $itemId = $moduleId . '_' . $itemIndex;
                                                    $level = $item['level'] ?? 0;
                                                    $isFolder = !empty($item['is_folder']);
                                                    $hasChildren = false;
                                                    for($i = $itemIndex + 1; $i < count($items); $i++) {
                                                        if(($items[$i]['level'] ?? 0) > $level) {
                                                            $hasChildren = true;
                                                            break;
                                                        } elseif(($items[$i]['level'] ?? 0) <= $level) {
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <tr class="perm-row" 
                                                    data-module="{{ $moduleId }}" 
                                                    data-item="{{ $itemId }}" 
                                                    data-level="{{ $level }}" 
                                                    data-parent="{{ $level > 0 ? 'true' : 'false' }}"
                                                    data-has-children="{{ $hasChildren ? 'true' : 'false' }}">
                                                    <td>
                                                        <div class="d-flex align-items-center" style="padding-left: {{ $level * 25 + 10 }}px;">
                                                            @if($hasChildren)
                                                                <button type="button" class="btn btn-link btn-sm toggle-item me-2 p-0" data-item="{{ $itemId }}" data-level="{{ $level }}">
                                                                    <i class="fas fa-chevron-right text-secondary"></i>
                                                                </button>
                                                            @else
                                                                <span class="me-4"></span>
                                                            @endif
                                                            @if($isFolder)
                                                                <i class="fas fa-folder text-warning me-2"></i>
                                                                <span class="fw-semibold">{{ $item['label'] }}</span>
                                                            @else
                                                                <i class="fas fa-file-alt text-info me-2"></i>
                                                                <div>
                                                                    <span class="fw-medium">{{ $item['label'] ?? ($item['description'] ?? '') }}</span>
                                                                    @if(!empty($item['uri']))
                                                                        <br><small class="text-muted"><code>/{{ $item['uri'] }}</code></small>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    @foreach($groupes->where('nomgroupe', '!=', 'ADMINISTRATEUR') as $groupe)
                                                        <td class="text-center">
                                                            @if(!empty($item['permission_view']))
                                                                <div class="form-check d-flex justify-content-center">
                                                                    <input
                                                                        class="form-check-input permission-checkbox view-checkbox"
                                                                        type="checkbox"
                                                                        data-groupe="{{ $groupe->nomgroupe }}"
                                                                        data-type="view"
                                                                        data-item="{{ $itemId }}"
                                                                        data-level="{{ $level }}"
                                                                        data-module="{{ $module }}"
                                                                        data-label="{{ $item['label'] ?? '' }}"
                                                                        data-permission="{{ $item['permission_view'] }}"
                                                                        id="view_{{ str_replace(' ', '_', $groupe->nomgroupe) }}_{{ $itemId }}"
                                                                        {{ in_array($item['permission_view'], $existingPermissions[$groupe->nomgroupe] ?? []) ? 'checked' : '' }}
                                                                    >
                                                                </div>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if(!empty($item['permission_manage']))
                                                                <div class="form-check d-flex justify-content-center">
                                                                    <input
                                                                        class="form-check-input permission-checkbox manage-checkbox"
                                                                        type="checkbox"
                                                                        data-groupe="{{ $groupe->nomgroupe }}"
                                                                        data-type="manage"
                                                                        data-item="{{ $itemId }}"
                                                                        data-level="{{ $level }}"
                                                                        data-module="{{ $module }}"
                                                                        data-label="{{ $item['label'] ?? '' }}"
                                                                        data-permission="{{ $item['permission_manage'] }}"
                                                                        id="manage_{{ str_replace(' ', '_', $groupe->nomgroupe) }}_{{ $itemId }}"
                                                                        {{ in_array($item['permission_manage'], $existingPermissions[$groupe->nomgroupe] ?? []) ? 'checked' : '' }}
                                                                    >
                                                                </div>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Onglet Gestion des Groupes --}}
                        <div class="tab-pane fade" id="groupes" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-plus-circle"></i> Créer un Nouveau Groupe</h6>
                                        </div>
                                        <div class="card-body">
                                            <form id="createGroupeForm">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="nomgroupe" class="form-label">Nom du Groupe</label>
                                                    <input type="text" class="form-control" id="nomgroupe" name="nomgroupe" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-save"></i> Créer le Groupe
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-list"></i> Groupes Existants</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Groupe</th>
                                                            <th>Utilisateurs</th>
                                                            <th>Permissions</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($groupes as $groupe)
                                                        <tr>
                                                            <td>
                                                                <span class="badge bg-primary fs-6">{{ $groupe->nomgroupe }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-info">{{ $groupe->users->count() }} utilisateurs</span>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $permissionsCount = 0;
                                                                    if (isset($existingPermissions[$groupe->nomgroupe])) {
                                                                        $permissionsCount = count($existingPermissions[$groupe->nomgroupe]);
                                                                    }
                                                                @endphp
                                                                <span class="badge bg-success">{{ $permissionsCount }} permissions</span>
                                                            </td>
                                                            <td>
                                                                {{-- <button class="btn btn-sm btn-outline-info me-1" onclick="viewGroupeDetails('{{ $groupe->nomgroupe }}')" 
                                                                    title="Voir détails" data-bs-toggle="tooltip">
                                                                    <i class="fas fa-eye"></i>
                                                                </button> --}}
                                                                @if($groupe->nomgroupe !== 'ADMINISTRATEUR')
                                                                <button class="btn btn-sm btn-outline-warning me-1" onclick="editGroupe('{{ $groupe->nomgroupe }}')" 
                                                                    title="Modifier" data-bs-toggle="tooltip">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteGroupe('{{ $groupe->nomgroupe }}')" 
                                                                    title="Supprimer" data-bs-toggle="tooltip">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                                @else
                                                                <span class="badge bg-success">Protégé</span>
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
                            </div>
                        </div>
                        {{-- Onglet Utilisateurs --}}
                        <div class="tab-pane fade" id="utilisateurs" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h4 class="mb-2">
                                        <i class="fas fa-user-friends text-primary me-2"></i>
                                        Gestion des Utilisateurs
                                        <span class="badge bg-info ms-2" id="totalUsers">{{ count($utilisateurs) }}</span>
                                    </h4>
                                    <p class="text-muted">Assignez les utilisateurs aux groupes et gérez leurs accès</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card bg-light border-0">
                                        <div class="card-body py-2">
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <small class="text-muted">Total utilisateurs</small>
                                                    <div class="fw-bold text-primary" id="statsUsersTotal">{{ count($utilisateurs) }}</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Utilisateurs actifs</small>
                                                    <div class="fw-bold text-success" id="statsUsersActive">{{ $utilisateurs->where('user_actif', 1)->count() }}</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Sans groupe</small>
                                                    <div class="fw-bold text-warning" id="statsUsersNoGroup">{{ count($utilisateurs)-($utilisateurs->where('user_actif', 1)->count()) }}</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Dernière connexion</small>
                                                    <div class="fw-bold text-info" id="statsLastLogin">Aujourd'hui</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="fas fa-table me-2"></i>Liste des Utilisateurs</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="me-3 text-muted" id="usersCount">{{ count($utilisateurs) }} utilisateurs</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="utilisateursTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Utilisateur</th>
                                                    <th>Login</th>
                                                    <th>Groupe Actuel</th>
                                                    <th>Statut</th>
                                                    <th style="width: 200px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="usersTableBody">
                                                @foreach($utilisateurs as $user)
                                                <tr class="user-row" 
                                                    data-user-id="{{ $user->id }}"
                                                    data-groupe="{{ $user->nomgroupe ?? 'null' }}"
                                                    data-status="{{ $user->user_actif }}"
                                                    data-search="{{ strtolower($user->prenomuser . ' ' . $user->nomuser . ' ' . $user->login) }}">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                                 style="width: 35px; height: 35px; font-size: 14px;">
                                                                {{ strtoupper(substr($user->prenomuser ?? 'U', 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ $user->prenomuser }} {{ $user->nomuser }}</div>
                                                                <small class="text-muted">ID: {{ $user->id }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <code class="bg-light px-2 py-1 rounded">{{ $user->login }}</code>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $userGroupe = $groupes->firstWhere('nomgroupe', $user->nomgroupe);
                                                        @endphp
                                                        <div class="d-flex align-items-center">
                                                            @if($userGroupe)
                                                                <span class="badge bg-secondary me-2">{{ $userGroupe->nomgroupe }}</span>
                                                            @else
                                                                <span class="badge bg-warning me-2">Aucun groupe</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($user->user_actif)
                                                                <span class="badge bg-success me-2">Actif</span>
                                                            @else
                                                                <span class="badge bg-danger me-2">Inactif</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select class="form-select form-select-sm" onchange="changeUserGroupe({{ $user->id }}, this.value, this)">
                                                            <option value="">Changer de groupe</option>
                                                            @foreach($groupes as $groupe)
                                                                <option value="{{ $groupe->nomgroupe }}" {{ $user->nomgroupe == $groupe->nomgroupe ? 'selected' : '' }}>
                                                                    {{ $groupe->nomgroupe }}
                                                                </option>
                                                            @endforeach
                                                        </select>
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
            </div>
        </div>
    </div>
    <br><br><br>  
</div>

<!-- MODALE DE MODIFICATION GROUPE -->
<div class="modal fade" id="editGroupeModal" tabindex="-1" aria-labelledby="editGroupeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title" id="editGroupeModalLabel">
            <i class="fas fa-edit"></i> Modifier le groupe
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
  
        <form id="editGroupeForm">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="edit_nomgroupe" class="form-label">Nouveau nom du groupe</label>
              <input type="text" id="edit_nomgroupe" name="nomgroupe" class="form-control" required>
            </div>
            <input type="hidden" id="edit_old_nomgroupe">
          </div>
  
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times"></i> Annuler
            </button>
            <button type="submit" class="btn btn-warning">
              <i class="fas fa-save"></i> Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
</div>
  

{{-- Styles CSS --}}
<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    .permission-checkbox {
        transform: scale(1.2);
    }
    
    /* Style pour indiquer la hiérarchie des permissions */
    .permission-group {
        position: relative;
    }
    
    /* Indication visuelle que Manage inclut View */
    th:contains("Manage")::after {
        content: " (inclut View)";
        font-size: 0.8em;
        color: #6c757d;
        font-weight: normal;
    }
    
    /* Style pour les checkboxes de permissions */
    .permission-checkbox[data-type="manage"] {
        accent-color: #dc3545; /* Rouge pour manage */
    }
    
    .permission-checkbox[data-type="view"] {
        accent-color: #0d6efd; /* Bleu pour view */
    }
    
    /* Indication visuelle dans les en-têtes */
    .table th.text-center:nth-child(odd) {
        background-color: rgba(13, 110, 253, 0.1); /* Fond bleu léger pour View */
    }
    
    .table th.text-center:nth-child(even) {
        background-color: rgba(220, 53, 69, 0.1); /* Fond rouge léger pour Manage */
    }
    .table th {
        vertical-align: middle;
    }
    .nav-tabs .nav-link {
        border-radius: 0.5rem 0.5rem 0 0;
    }
    .nav-tabs .nav-link.active {
        background-color: #0d6efd;
        color: white !important;
        border-color: #0d6efd;
    }
    .perm-row {
        transition: all 0.3s ease;
        opacity: 1;
    }
    .perm-row.hiding {
        opacity: 0;
        transform: translateX(-10px);
    }
    .perm-row.showing {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .permission-checkbox {
        transition: all 0.2s ease;
        transform: scale(1);
    }
    .permission-checkbox:checked {
        transform: scale(1.1);
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.5);
    }
    .permission-checkbox:hover {
        transform: scale(1.05);
    }
    .toggle-item, .toggle-module {
        transition: all 0.2s ease;
    }
    .toggle-item:hover, .toggle-module:hover {
        transform: scale(1.1);
    }
    .toggle-item i, .toggle-module i {
        transition: transform 0.3s ease;
    }
    .btn {
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .progress-bar {
        transition: width 0.5s ease;
    }
    .badge {
        transition: all 0.3s ease;
    }
    .highlight-row {
        background-color: rgba(255, 193, 7, 0.2) !important;
        animation: pulse 1s ease-in-out;
    }
    @keyframes pulse {
        0% { background-color: rgba(255, 193, 7, 0.2); }
        50% { background-color: rgba(255, 193, 7, 0.4); }
        100% { background-color: rgba(255, 193, 7, 0.2); }
    }
    .search-highlight {
        background-color: rgba(255, 255, 0, 0.3);
        padding: 2px 4px;
        border-radius: 3px;
    }
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .stats-counter {
        animation: countUp 0.5s ease-out;
    }
    @keyframes countUp {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

{{-- JavaScript principal --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
    
        // ========== VARIABLES GLOBALES ==========
        let changesCount = 0;
        const originalStates = new Map();
    
        // ========== INITIALISATION ==========
        initializeTableState();
        trackOriginalStates();
        enforcePermissionHierarchy(); // Appliquer la hiérarchie des permissions au chargement
        initializeStats();
        initializeEventListeners();
    
        // ========== DÉPLIAGE / REPLIAGE ==========
        document.querySelectorAll('.toggle-item').forEach(btn => {
            btn.addEventListener('click', function () {
                const itemId = this.dataset.item;
                const level = parseInt(this.dataset.level || '0');
                toggleItem(itemId, level);
            });
        });
    
        function initializeTableState() {
            document.querySelectorAll('.perm-row').forEach(row => {
                const level = parseInt(row.dataset.level || '0');
                if (level > 0) row.style.display = 'none';
            });
        }
    
        function trackOriginalStates() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => {
                originalStates.set(cb.id, cb.checked);
            });
        }
        
        function enforcePermissionHierarchy() {
            // Parcourir tous les éléments pour appliquer la hiérarchie des permissions
            const processedItems = new Set();
            
            document.querySelectorAll('.permission-checkbox').forEach(cb => {
                const { groupe: groupeId, item: itemId, type } = cb.dataset;
                const itemKey = `${groupeId}_${itemId}`;
                
                if (processedItems.has(itemKey)) return;
                processedItems.add(itemKey);
                
                // Trouver les deux checkboxes (view et manage) pour cet item
                const viewCb = document.querySelector(`input[data-item="${itemId}"][data-type="view"][data-groupe="${groupeId}"]`);
                const manageCb = document.querySelector(`input[data-item="${itemId}"][data-type="manage"][data-groupe="${groupeId}"]`);
                
                // Si manage est coché, view doit aussi être coché (manage inclut view)
                if (manageCb && manageCb.checked && viewCb && !viewCb.checked) {
                    console.log(`Correction: ${itemId} avait manage sans view, on coche view automatiquement`);
                    viewCb.checked = true;
                }
                
                // Si view n'est pas coché, manage ne peut pas l'être non plus
                if (viewCb && !viewCb.checked && manageCb && manageCb.checked) {
                    console.log(`Correction: ${itemId} avait manage sans view, on décoche manage`);
                    manageCb.checked = false;
                }
            });
        }
    
        function initializeStats() {
            updateStats();
        }
    
        function initializeEventListeners() {
            const moduleFilter = document.getElementById('moduleFilter');
            moduleFilter?.addEventListener('change', applyFilters);
    
            document.getElementById('selectAllBtn')?.addEventListener('click', selectAll);
            document.getElementById('clearAllBtn')?.addEventListener('click', clearAll);
        }
    
        // ========== FILTRAGE ==========
        function applyFilters() {
            const selectedModule = document.getElementById('moduleFilter')?.value || '';
            document.querySelectorAll('.perm-row').forEach(row => {
                row.style.display = !selectedModule || row.dataset.module === selectedModule ? '' : 'none';
            });
            updateStats();
        }
    
        function selectAll() {
            document.querySelectorAll('.permission-checkbox:not([style*="display: none"])')
                .forEach(cb => cb.checked = true);
            updateStats();
            updateChangesCounter();
        }
    
        function clearAll() {
            document.querySelectorAll('.permission-checkbox:not([style*="display: none"])')
                .forEach(cb => cb.checked = false);
            updateStats();
            updateChangesCounter();
        }
    
        // ========== TOGGLE DES NIVEAUX ==========
        function toggleItem(itemId, level) {
            const toggleBtn = document.querySelector(`.toggle-item[data-item="${itemId}"] i`);
            const expanded = toggleBtn.classList.contains('fa-chevron-down');
            const moduleId = document.querySelector(`tr[data-item="${itemId}"]`).dataset.module;
            expanded ? hideChildren(moduleId, itemId, level) : showDirectChildren(moduleId, itemId, level);
            toggleBtn.className = expanded ? 'fas fa-chevron-right text-secondary' : 'fas fa-chevron-down text-secondary';
        }
    
        function hideChildren(moduleId, parentItemId, parentLevel) {
            let found = false;
            document.querySelectorAll(`tr.perm-row[data-module="${moduleId}"]`).forEach(row => {
                const itemId = row.dataset.item;
                const level = parseInt(row.dataset.level || '0');
                if (itemId === parentItemId) { found = true; return; }
                if (found && level > parentLevel) {
                    row.style.display = 'none';
                    const icon = row.querySelector('.toggle-item i');
                    if (icon) icon.className = 'fas fa-chevron-right text-secondary';
                } else if (found && level <= parentLevel) {
                    found = false;
                }
            });
        }
    
        function showDirectChildren(moduleId, parentItemId, parentLevel) {
            let found = false;
            document.querySelectorAll(`tr.perm-row[data-module="${moduleId}"]`).forEach(row => {
                const itemId = row.dataset.item;
                const level = parseInt(row.dataset.level || '0');
                if (itemId === parentItemId) { found = true; return; }
                if (found && level === parentLevel + 1) row.style.display = '';
                else if (found && level <= parentLevel) found = false;
            });
        }
    
        // ========== CHECKBOX HIÉRARCHIQUES ==========
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const { groupe: groupeId, type, item: itemId } = this.dataset;
                const moduleId = this.closest('tr').dataset.module;
                const level = parseInt(this.dataset.level || '0');
                const checked = this.checked;
    
                propagateToChildren(moduleId, itemId, level, type, groupeId, checked);
                updateParents(moduleId, itemId, level, type, groupeId);
    
                // Règle hiérarchique: Manage inclut View, mais pas l'inverse
                if (type === 'manage' && checked) {
                    // Si on coche manage, on coche automatiquement view (manage inclut view)
                    const viewCb = document.querySelector(`input[data-item="${itemId}"][data-type="view"][data-groupe="${groupeId}"]`);
                    if (viewCb && !viewCb.checked) {
                        viewCb.checked = true;
                        propagateToChildren(moduleId, itemId, level, 'view', groupeId, true);
                        updateParents(moduleId, itemId, level, 'view', groupeId);
                    }
                } else if (type === 'view' && !checked) {
                    // Si on décoche view, on décoche automatiquement manage (pas de manage sans view)
                    const manageCb = document.querySelector(`input[data-item="${itemId}"][data-type="manage"][data-groupe="${groupeId}"]`);
                    if (manageCb && manageCb.checked) {
                        manageCb.checked = false;
                        propagateToChildren(moduleId, itemId, level, 'manage', groupeId, false);
                        updateParents(moduleId, itemId, level, 'manage', groupeId);
                    }
                }
    
                updateStats();
                updateChangesCounter();
            });
        });
    
        function propagateToChildren(moduleId, parentItemId, parentLevel, type, groupeId, checked) {
            let found = false;
            document.querySelectorAll(`tr.perm-row[data-module="${moduleId}"]`).forEach(row => {
                const itemId = row.dataset.item;
                const level = parseInt(row.dataset.level || '0');
                if (itemId === parentItemId) { found = true; return; }
                if (found && level > parentLevel) {
                    const checkbox = row.querySelector(`input[data-type="${type}"][data-groupe="${groupeId}"]`);
                    if (checkbox) {
                        checkbox.checked = checked;
                    }
                } else if (found && level <= parentLevel) {
                    found = false;
                }
            });
        }
    
        function updateParents(moduleId, childItemId, childLevel, type, groupeId) {
            if (childLevel === 0) return;
            const rows = Array.from(document.querySelectorAll(`tr.perm-row[data-module="${moduleId}"]`));
            const idx = rows.findIndex(r => r.dataset.item === childItemId);
            if (idx === -1) return;
    
            for (let i = idx - 1; i >= 0; i--) {
                const level = parseInt(rows[i].dataset.level || '0');
                if (level === childLevel - 1) {
                    const parentItem = rows[i].dataset.item;
                    const parentCb = rows[i].querySelector(`input[data-type="${type}"][data-groupe="${groupeId}"]`);
                    if (parentCb) {
                        parentCb.checked = hasAnyCheckedChild(moduleId, parentItem, childLevel - 1, type, groupeId);
                        updateParents(moduleId, parentItem, childLevel - 1, type, groupeId);
                    }
                    break;
                }
            }
        }
    
        function hasAnyCheckedChild(moduleId, parentItemId, parentLevel, type, groupeId) {
            let found = false;
            for (let row of document.querySelectorAll(`tr.perm-row[data-module="${moduleId}"]`)) {
                const itemId = row.dataset.item;
                const level = parseInt(row.dataset.level || '0');
                if (itemId === parentItemId) { found = true; continue; }
                if (found && level > parentLevel) {
                    const cb = row.querySelector(`input[data-type="${type}"][data-groupe="${groupeId}"]`);
                    if (cb && cb.checked) return true;
                } else if (found && level <= parentLevel) break;
            }
            return false;
        }
    
        // ========== STATISTIQUES ==========
        function updateStats() {
            const total = document.querySelectorAll('.perm-row').length;
            const active = document.querySelectorAll('.permission-checkbox:checked').length;
            const visible = document.querySelectorAll('.perm-row:not([style*="display: none"])').length;
            animateCounter('statsTotal', total);
            animateCounter('statsActive', active);
            animateCounter('statsVisible', visible);
        }
    
        function animateCounter(id, val) {
            const el = document.getElementById(id);
            if (!el) return;
            const old = parseInt(el.textContent) || 0;
            if (old !== val) {
                el.classList.add('stats-counter');
                el.textContent = val;
                setTimeout(() => el.classList.remove('stats-counter'), 500);
            }
        }
    
        function updateChangesCounter() {
            let changes = 0;
            document.querySelectorAll('.permission-checkbox').forEach(cb => {
                if (originalStates.get(cb.id) !== cb.checked) changes++;
            });
            changesCount = changes;
    
            const counter = document.getElementById('changesCounter');
            const saveBtn = document.getElementById('saveBtn');
            if (counter) counter.textContent = `${changes} modification${changes !== 1 ? 's' : ''}`;
    
            if (saveBtn) {
                const txt = saveBtn.querySelector('.save-text');
                saveBtn.classList.toggle('btn-warning', changes > 0);
                saveBtn.classList.toggle('btn-success', changes === 0);
                txt.textContent = changes > 0 ? `Sauvegarder (${changes})` : 'Sauvegarder';
            }
        }
    
        // ========== NOTIFICATION ==========
        function showNotification(msg, type = 'info') {
            const n = document.createElement('div');
            n.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            n.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:300px;';
            n.innerHTML = `${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            document.body.appendChild(n);
            setTimeout(() => n.remove(), 5000);
        }
    
        // Expose save function globally
        window.saveAllPermissions = saveAllPermissions;
        window.showNotification = showNotification;
    
        // ========== TOOLTIP INIT ==========
        [...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach(el => new bootstrap.Tooltip(el));
    
        // ========== SAUVEGARDE ==========
        function saveAllPermissions() {
            console.log('saveAllPermissions appelée');
            const saveBtn = document.getElementById('saveBtn');
            const spinner = saveBtn.querySelector('.spinner-border');
            const text = saveBtn.querySelector('.save-text');
            spinner.classList.remove('d-none');
            text.textContent = 'Sauvegarde...';
            saveBtn.disabled = true;
    
            const permissionsData = {};
            const groupedByGroupe = {};
    
            document.querySelectorAll('.permission-checkbox:checked').forEach(cb => {
                const { groupe: gid, permission: fullPerm } = cb.dataset;
                if (!gid || !fullPerm) return;
    
                if (!permissionsData[gid]) permissionsData[gid] = [];
                if (!groupedByGroupe[gid]) groupedByGroupe[gid] = {};
    
                const lastDot = fullPerm.lastIndexOf('.');
                if (lastDot === -1) return permissionsData[gid].push(fullPerm);
    
                const base = fullPerm.substring(0, lastDot);
                const type = fullPerm.substring(lastDot + 1);
                groupedByGroupe[gid][base] ??= { view: false, manage: false };
                groupedByGroupe[gid][base][type] = true;
            });
    
            for (const [gid, bases] of Object.entries(groupedByGroupe)) {
                for (const [base, types] of Object.entries(bases)) {
                    // Logique hiérarchique : manage inclut view
                    if (types.manage) {
                        // Si manage est coché, on sauvegarde les deux permissions
                        permissionsData[gid].push(base + '.view');
                        permissionsData[gid].push(base + '.manage');
                    } else if (types.view) {
                        // Si seulement view est coché, on sauvegarde seulement view
                        permissionsData[gid].push(base + '.view');
                    }
                }
                permissionsData[gid] = [...new Set(permissionsData[gid])];
            }
    
            const promises = Object.entries(permissionsData).map(([gid, perms]) =>
                fetch('{{ route("admin.roles.update-permissions") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nomgroupe: gid, permissions: perms })
                }).then(r => r.json())
            );
    
            Promise.all(promises)
                .then(() => {
                    document.querySelectorAll('.permission-checkbox').forEach(cb => {
                        originalStates.set(cb.id, cb.checked);
                    });
                    updateChangesCounter();
                    spinner.classList.add('d-none');
                    text.textContent = 'Sauvegardé !';
                    saveBtn.classList.replace('btn-warning', 'btn-success');
                    Swal.fire({ icon: 'success', title: 'Succès!', text: 'Permissions sauvegardées', timer: 2000, showConfirmButton: false });
                    setTimeout(() => { text.textContent = 'Sauvegarder'; saveBtn.disabled = false; }, 2000);
                })
                .catch(e => {
                    spinner.classList.add('d-none');
                    text.textContent = 'Erreur';
                    saveBtn.disabled = false;
                    Swal.fire({ icon: 'error', title: 'Erreur!', text: 'Erreur lors de la sauvegarde: ' + e.message });
                });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    
        // === FONCTION POUR OUVRIR LA MODALE DE MODIFICATION ===
        window.editGroupe = function(nomgroupe) {
            $('#edit_old_nomgroupe').val(nomgroupe);
            $('#edit_nomgroupe').val(nomgroupe);
            $('#editGroupeModal').modal('show');
        };
    
        // === SOUMISSION DU FORMULAIRE DE MODIFICATION ===
        $('#editGroupeForm').on('submit', function(e) {
            e.preventDefault();
    
            const oldNom = $('#edit_old_nomgroupe').val();
            const newNom = $('#edit_nomgroupe').val().trim();
    
            if (!newNom) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: 'Le nom du groupe ne peut pas être vide.'
                });
                return;
            }
    
            $.ajax({
                url: `/groupes/update/${encodeURIComponent(oldNom)}`,
                method: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    nomgroupe: newNom
                },
                success: function(response) {
                    if (response.success) {
                        $('#editGroupeModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Erreur lors de la modification du groupe';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur!',
                        text: errorMessage
                    });
                }
            });
        });
        
        // === FONCTION POUR SUPPRIMER UN GROUPE ===
        window.deleteGroupe = function(nomgroupe) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: `Voulez-vous vraiment supprimer le groupe "${nomgroupe}" ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/groupes/delete/${encodeURIComponent(nomgroupe)}`,
                        method: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supprimé!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Erreur lors de la suppression du groupe';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur!',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        };
        
        // === FONCTION POUR VOIR LES DÉTAILS D'UN GROUPE ===
        window.viewGroupeDetails = function(nomgroupe) {
            // Afficher les détails du groupe dans une modale ou rediriger
            Swal.fire({
                title: `Détails du groupe: ${nomgroupe}`,
                text: 'Fonctionnalité à implémenter selon vos besoins',
                icon: 'info'
            });
        };
        
        // === FONCTION POUR CHANGER LE GROUPE D'UN UTILISATEUR ===
        window.changeUserGroupe = function(userId, groupeId, selectElement) {
            fetch('{{ route("admin.roles.change-user-groupe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ user_id: userId, nomgroupe: groupeId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
                    if (userRow) {
                        const badge = userRow.querySelector('.badge');
                        if (badge) {
                            if (groupeId && groupeId !== '') {
                                badge.textContent = groupeId;
                                badge.className = 'badge bg-secondary me-2';
                            } else {
                                badge.textContent = 'Aucun groupe';
                                badge.className = 'badge bg-warning me-2';
                            }
                        }
                    }
                    if (selectElement) selectElement.selectedIndex = 0;
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || 'Erreur', 'error');
                }
            })
            .catch(() => showNotification('Erreur changement groupe', 'error'));
        };
        
        // === GESTION DU FORMULAIRE DE CRÉATION DE GROUPE ===
        const createGroupeForm = document.getElementById('createGroupeForm');
        if (createGroupeForm) {
            createGroupeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const nomgroupe = formData.get('nomgroupe').trim();
                
                if (!nomgroupe) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur!',
                        text: 'Le nom du groupe ne peut pas être vide.'
                    });
                    return;
                }
                
                fetch('{{ route("admin.roles.create-groupe") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur!',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur!',
                        text: 'Erreur lors de la création du groupe'
                    });
                });
            });
        }
    
    });
</script>
    
    
    
@endsection