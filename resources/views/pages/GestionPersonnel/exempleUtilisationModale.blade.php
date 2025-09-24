@extends('layouts.master')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Exemple d'utilisation de la modale Configuration Taux Horaires</h4>
                </div>
                <div class="card-body">
                    <p class="mb-4">Cette page démontre comment utiliser la modale de configuration des taux horaires depuis n'importe quelle autre page de votre application.</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Méthodes d'ouverture de la modale :</h5>
                            <div class="d-grid gap-2">
                                <!-- Bouton simple -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confTauxHModal">
                                    <i class="fas fa-cog"></i> Ouvrir Configuration Taux Horaires
                                </button>
                                
                                <!-- Bouton avec icône différente -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confTauxHModal">
                                    <i class="fas fa-money-bill-wave"></i> Gérer les Taux
                                </button>
                                
                                <!-- Bouton avec style personnalisé -->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confTauxHModal">
                                    <i class="fas fa-calculator"></i> Configuration Avancée
                                </button>
                                
                                <!-- Lien stylé comme bouton -->
                                <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#confTauxHModal">
                                    <i class="fas fa-edit"></i> Modifier Taux Horaires
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Ouverture programmatique :</h5>
                            <button type="button" class="btn btn-secondary" onclick="ouvrirModaleJS()">
                                <i class="fas fa-code"></i> Ouvrir via JavaScript
                            </button>
                            
                            <div class="mt-3">
                                <h6>Code JavaScript :</h6>
                                <pre class="bg-light p-2 rounded"><code>function ouvrirModaleJS() {
    var modal = new bootstrap.Modal(document.getElementById('confTauxHModal'));
    modal.show();
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row">
                        <div class="col-12">
                            <h5>Instructions d'intégration :</h5>
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Pour utiliser cette modale dans vos pages :</h6>
                                <ol>
                                    <li>Ajoutez l'inclusion de la modale à la fin de votre fichier Blade :
                                        <pre class="mt-2"><code>@include('modals.confTauxHModal')</code></pre>
                                    </li>
                                    <li>Ajoutez un bouton ou lien avec les attributs suivants :
                                        <pre class="mt-2"><code>data-bs-toggle="modal" data-bs-target="#confTauxHModal"</code></pre>
                                    </li>
                                    <li>Ou utilisez JavaScript pour ouvrir la modale programmatiquement</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Exemple d'intégration dans un tableau :</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Agent</th>
                                            <th>Type</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Jean Dupont</td>
                                            <td>Enseignant</td>
                                            <td><span class="badge bg-success">Actif</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#confTauxHModal">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Marie Martin</td>
                                            <td>Administrateur</td>
                                            <td><span class="badge bg-success">Actif</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#confTauxHModal">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
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

<script>
function ouvrirModaleJS() {
    var modal = new bootstrap.Modal(document.getElementById('confTauxHModal'));
    modal.show();
}

// Exemple d'écoute d'événements de la modale
document.addEventListener('DOMContentLoaded', function() {
    var modalElement = document.getElementById('confTauxHModal');
    
    if (modalElement) {
        modalElement.addEventListener('shown.bs.modal', function () {
            console.log('Modale Configuration Taux Horaires ouverte');
        });
        
        modalElement.addEventListener('hidden.bs.modal', function () {
            console.log('Modale Configuration Taux Horaires fermée');
        });
    }
});
</script>

@endsection

<!-- Inclusion de la modale Configuration Taux Horaires -->
@include('modals.confTauxHModal')
