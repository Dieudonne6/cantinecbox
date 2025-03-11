@extends('layouts.master')
@section('content')

<div class="main-panel-10">
    <div class="container d-flex justify-content-center">
        <div class="card w-100">
            <div>
                <style>
                    .btn-arrow {
                        position: absolute;
                        top: 0px;
                        /* Ajustez la position verticale */
                        left: 0px;
                        /* Positionnez à gauche */
                        background-color: transparent !important;
                        border: 1px !important;
                        text-transform: uppercase !important;
                        font-weight: bold !important;
                        cursor: pointer !important;
                        font-size: 17px !important;
                        /* Taille de l'icône */
                        color: #b51818 !important;
                        /* Couleur de l'icône */
                    }
            
                    .btn-arrow:hover {
                        color: #b700ff !important;
                        /* Couleur au survol */
                    }
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>  
                <br>
                <br>                                    
            </div>
            <style>
                .card {
                    margin-top: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: left;
                }
                th {
                    background-color: #f4f4f4;
                }
                tr:hover {
                    background-color: #f1f1f1;
                }
                .modal-dialog {
                    max-width: 600px;
                }
                .modal-body {
                    padding: 20px;
                }
                .form-group {
                    margin-bottom: 15px;
                }
                .btn-close {
                    margin-left: auto;
                }
            </style>
      <div class="card-body">
            @if (session('success'))
                <div id="statusAlert" class="alert alert-success btn-primary">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div id="statusAlert" class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        <h4 class="card-title">Gestion des groupes</h4>
        <table id="classTable">
            <thead>
                <tr>
                    <th>Groupes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listegroupe as $listegroupe)
                <tr>
                    <td>{{ $listegroupe->LibelleGroupe }}</td>
                    <td>
                    <a type="button" class="btn btn-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modifgroup"
                        data-libelle="{{ $listegroupe->LibelleGroupe }}"
                        onclick="openModal(this)">
                         Modifier
                    </a>

                     <form action="/supprimergroupe/{{ $listegroupe->id }}" method="POST"   style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal1" onclick="setDeleteFormAction('{{ $listegroupe->id }}')">Supprimer</button>
                    </form>
                     {{-- <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModaldelete<?php echo $listegroupe->id; ?>">Supprimer</button>  --}}
              
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        </div>
    </div>
</div>


<!-- Modal modification groupes-->
<div class="modal fade" id="modifgroup" tabindex="-1" aria-labelledby="modifgroupLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifgroupTitle">Modifier le groupe</h5>
                <button type="button" class="btn-close p-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="row mt-3">
                        <div class="form-group w-50 mb-6">
                            <input type="hidden" id="groupeLibelle" name="groupeLibelle" value="">
                            <select class="js-example-basic-multiple w-100 select2-hidden-accessible" id="classSelect" name="classes" tabindex="-1" aria-hidden="true">
                                <option value="">Sélectionner la classe</option>
                            </select>
                        </div>
                        <div class="form-group w-50 mb-6">
                            <button type="button" id="ajouterClasse" class="btn btn-primary" onclick="ajouterClasse()">Ajouter une classe</button>
                            {{-- <button type="button" id="ajouterClasse" class="btn btn-primary">Ajouter une classe</button> --}}
                        </div>  
                    </div>
                    <table id="classTable">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="classTableBody">
                            <!-- Les lignes de la table seront ajoutées ici -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- modal de confirmation de suppression de classe --}}

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">
            Êtes-vous sûr de vouloir supprimer cette classe ?
          </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteButton">Supprimer</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong id="alertModalMessage"></strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal de confirmation de suppression de groupe -->
<div class="modal fade" id="confirmDeleteModal1" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteLabel">Confirmer la suppression</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir supprimer ce groupe ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
        </div>
      </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('modifgroup');
    var myModal = new bootstrap.Modal(modal);

    modal.addEventListener('hidden.bs.modal', function() {
        // Supprimer le backdrop si nécessaire
        var backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }

        // Supprimer la classe modal-open du body
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
    });


});
    function setDeleteFormAction(groupeId) {
        var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn.onclick = function() {
            var form = document.querySelector('form[action="/supprimergroupe/' + groupeId + '"]');
            if (form) {
                form.submit();
            }
        };
    }

    function showAlert(message, callback) {
    var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
    document.getElementById('alertModalMessage').textContent = message;
    alertModal.show();

    // Assurez-vous que le callback est appelé lorsque le modal d'alerte est caché
    alertModal._element.addEventListener('hidden.bs.modal', function () {
        if (callback) {
            callback();
        }
    }, { once: true });
}

function openModal(button) {
    var groupeLibelle = button.getAttribute('data-libelle');
    var modalTitle = document.getElementById('modifgroupTitle');
    modalTitle.textContent = 'Modifier le groupe : ' + groupeLibelle;

    // Effectuer une requête AJAX pour obtenir toutes les classes
    fetch('/afficherTouteClasse')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(allClasses => {
            var select = document.getElementById('classSelect');
            select.innerHTML = '<option value="">Sélectionner la classe</option>'; // Réinitialiser le select

            allClasses.forEach(function(classe) {
                var option = document.createElement('option');
                option.value = classe.CODECLAS;
                option.textContent = classe.CODECLAS;
                select.appendChild(option);
            });

            // Ensuite, obtenir les classes spécifiques au groupe
            return fetch(`/groupes/${encodeURIComponent(groupeLibelle)}/classes`);
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(classesByGroup => {
            var tableBody = document.getElementById('classTableBody');
            tableBody.innerHTML = ''; // Réinitialiser le tableau

            classesByGroup.forEach(function(classe) {
                var row = document.createElement('tr');
                row.innerHTML = `
                    <td>${classe.CODECLAS}</td>
                    <td><button type="button" class="btn btn-danger" data-id="${classe.id}" onclick="supprimerClasse(this)">Supprimer</button></td>
                `;
                tableBody.appendChild(row);
            });

            // Ouvrir le modal
            var myModal = new bootstrap.Modal(document.getElementById('modifgroup'));
            myModal.show();
        })
        .catch(error => console.error('Error:', error));
}

function ajouterClasse() {
    var select = document.getElementById('classSelect');
    var classCode = select ? select.value : null;
    var modalTitle = document.getElementById('modifgroupTitle');
    var groupeLibelle = modalTitle ? modalTitle.textContent.replace('Modifier le groupe : ', '').trim() : null;

    if (!classCode) {
        showAlert('Veuillez sélectionner une classe.', function() {
            var modal = document.getElementById('modifgroup');
            var myModal = bootstrap.Modal.getInstance(modal);
            if (myModal) {
                myModal.show(); // Réouvrir le modal
            }
        });
        return;
    }

    var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenMeta) {
        console.error('Le token CSRF est manquant.');
        showAlert('Le token CSRF est manquant.', function() {
            var modal = document.getElementById('modifgroup');
            var myModal = bootstrap.Modal.getInstance(modal);
            if (myModal) {
                myModal.show(); // Réouvrir le modal
            }
        });
        return;
    }

    var csrfToken = csrfTokenMeta.getAttribute('content');

    fetch(`/groupes/${encodeURIComponent(groupeLibelle)}/classes`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ classCode: classCode })
    })
    .then(response => {
        if (response.status === 409) {
            return response.json().then(data => {
                showAlert(data.error, function() {
                    var modal = document.getElementById('modifgroup');
                    var myModal = bootstrap.Modal.getInstance(modal);
                    if (myModal) {
                        myModal.show(); // Réouvrir le modal
                    }
                });
                throw new Error(data.error);
            });
        }

        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Erreur lors de l\'ajout de la classe.');
            });
        }
        return response.json();
    })
    .then(data => {
        showAlert('Classe ajoutée avec succès !', function() {
            // Fermer le modal
            var modal = document.getElementById('modifgroup');
            var myModal = bootstrap.Modal.getInstance(modal);
            if (myModal) {
                myModal.hide(); // Fermer le modal
            }

            // Assurez-vous que le modal est complètement caché avant de réouvrir
            setTimeout(function() {
                // Recharger et réouvrir le modal avec les données du groupe
                openModal(document.querySelector('a[data-libelle="' + groupeLibelle + '"]'));
            }, 500); // Ajuster le délai si nécessaire
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
// }


    function supprimerClasse(button) {
    var id = button.getAttribute('data-id');
    var groupeLibelle = document.getElementById('modifgroupTitle').textContent.replace('Modifier le groupe : ', '').trim();

        // Ouvrir la modale de confirmation
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        confirmModal.show();

        // Gestion de l'événement de confirmation
        var confirmButton = document.getElementById('confirmDeleteButton');

    confirmButton.onclick = function() {
        fetch(`/groupes/${encodeURIComponent(groupeLibelle)}/classes/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Afficher le message de succès
            showAlert(data.message);  // Assurez-vous que la réponse contient 'message'

            // Fermer le modal
            var modal = document.getElementById('modifgroup');
            var myModal = bootstrap.Modal.getInstance(modal);
            myModal.hide();

            // Retirer backdrop et modal-open au cas où
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
        // })

            // Recharger les données du groupe
            openModal(document.querySelector('button[data-libelle="' + encodeURIComponent(groupeLibelle) + '"]'));
        })
        .catch(error => console.error('Error:', error));


        // Fermer la modale de confirmation
        confirmModal.hide();
    }
    }

    function confirmDelete() {
        return confirm('Êtes-vous sûr de vouloir supprimer ce groupe?');
    }




</script>



@endsection
