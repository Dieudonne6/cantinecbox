@extends('layouts.master')
@section('content')
<style>
.my-select2-style ,
.my-select2-style  {
  background-color: #6b2c80 !important;
  color: #fff !important;
  border-color: #5a235f !important;
}
.my-select2-style .select2-selection__placeholder { color: rgba(255,255,255,0.8); }
.my-select2-style .select2-selection__rendered { color: #fff; }
</style>
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
                .form-check-input {
                    width: 1.25em;
                    height: 1.25em;
                    margin-top: 0 !important;
                    margin-left: 0 !important;
                    margin-right: 0.5rem !important;
                    flex-shrink: 0;
                    position: relative;
                    z-index: 1;
                    background-color: #fff;
                    border: 2px solid #dee2e6;
                }
                .form-check {
                    padding-left: 0 !important;
                    margin-bottom: 0.5rem;
                }
                #listeClasses .form-check {
                    min-height: 1.5rem;
                    display: flex !important;
                    align-items: center !important;
                    padding: 0.25rem;
                }
                #listeClasses .col-md-4 {
                    padding-left: 0.5rem;
                    padding-right: 0.5rem;
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
                        @if(empty($pagePermission['isReadOnly']) || $pagePermission['canManage'])
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
                        @endif
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


<!-- Modal modification groupes -->
<div class="modal fade" id="modifgroup" tabindex="-1" aria-labelledby="modifgroupLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modifgroupTitle">Modifier le groupe</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" id="groupeLibelle" name="groupeLibelle" value="">
              
              <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6>Liste des classes</h6>
                  <div>
                      <input type="checkbox" id="checkAllClasses" class="form-check-input me-2">
                      <label for="checkAllClasses" class="form-check-label">Tout cocher / décocher</label>
                  </div>
              </div>

              <div id="listeClasses" class="row g-2" style="max-height: 350px; overflow-y: auto;">
                  <!-- Les cases à cocher seront générées ici -->
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
              <button type="button" class="btn btn-primary" id="btnValiderAjout">Synchroniser</button>
          </div>
      </div>
  </div>
</div>

<script>
async function openModal(button) {
    const groupeLibelle = button.getAttribute('data-libelle');
    document.getElementById('modifgroupTitle').textContent = 'Modifier le groupe : ' + groupeLibelle;
    document.getElementById('groupeLibelle').value = groupeLibelle;

    const listeClasses = document.getElementById('listeClasses');
    listeClasses.innerHTML = '<p class="text-muted text-center">Chargement des classes...</p>';

    try {
        console.log('=== DÉBUT DU CHARGEMENT DES CLASSES ===');
        console.log('Groupe sélectionné:', groupeLibelle);
        
        const [allRes, groupRes] = await Promise.all([
            fetch('/afficherTouteClasse'),
            fetch(`/groupes/${encodeURIComponent(groupeLibelle)}/classes`)
        ]);

        console.log('Status allRes:', allRes.status);
        console.log('Status groupRes:', groupRes.status);

        const allClasses = await allRes.json();
        console.log('=== TOUTES LES CLASSES ===');
        console.log('Nombre total de classes:', allClasses.length);
        console.log('Structure première classe:', allClasses[0]);
        console.log('Toutes les classes:', allClasses);
        
        let groupClasses = [];
        if (groupRes.ok) {
            groupClasses = await groupRes.json();
            console.log('=== CLASSES DU GROUPE ===');
            console.log('Nombre de classes dans le groupe:', groupClasses.length);
            if (groupClasses.length > 0) {
                console.log('Structure première classe du groupe:', groupClasses[0]);
            }
            console.log('Classes du groupe (brutes):', groupClasses);
        } else {
            console.log('Aucune classe trouvée pour ce groupe ou erreur:', groupRes.status);
            const errorText = await groupRes.text();
            console.log('Erreur détaillée:', errorText);
        }
        
        // Normaliser les codes des classes du groupe (conversion en string et trim)
        const groupCodes = (Array.isArray(groupClasses) ? groupClasses : [])
            .map(c => String(c.CODECLAS || '').trim().toUpperCase())
            .filter(code => code !== ''); // Éliminer les codes vides
        
        console.log('Codes des classes du groupe (normalisés):', groupCodes);
        console.log('Types des codes:', groupCodes.map(c => typeof c));

        listeClasses.innerHTML = ''; // vider

        console.log('=== GÉNÉRATION DES CHECKBOXES ===');
        let totalChecked = 0;
        
        allClasses.forEach((classe, index) => {
            // Normaliser le code de la classe (conversion en string, trim et majuscules)
            const classeCodeOriginal = classe.CODECLAS;
            const classeCode = String(classe.CODECLAS || '').trim().toUpperCase();
            const isInGroup = groupCodes.includes(classeCode);
            const isChecked = isInGroup ? 'checked' : '';
            
            if (isInGroup) totalChecked++;
            
            console.log(`[${index + 1}] Classe originale: "${classeCodeOriginal}" | Normalisée: "${classeCode}" | Dans groupe: ${isInGroup} | Cochée: ${isChecked !== ''}`);
            
            listeClasses.insertAdjacentHTML('beforeend', `
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input classe-checkbox" type="checkbox" value="${classe.CODECLAS}" id="chk_${classe.CODECLAS}" ${isChecked}>
                        <label class="form-check-label" for="chk_${classe.CODECLAS}">
                            ${classe.CODECLAS}
                        </label>
                    </div>
                </div>
            `);
        });
        
        console.log(`=== RÉSUMÉ ===`);
        console.log(`Total classes: ${allClasses.length}`);
        console.log(`Classes dans le groupe: ${groupCodes.length}`);
        console.log(`Classes qui devraient être cochées: ${totalChecked}`);
        
        // Vérification après génération des checkboxes
        setTimeout(() => {
            const actualChecked = document.querySelectorAll('.classe-checkbox:checked');
            console.log(`=== VÉRIFICATION POST-GÉNÉRATION ===`);
            console.log(`Checkboxes réellement cochées: ${actualChecked.length}`);
            actualChecked.forEach((checkbox, index) => {
                console.log(`[${index + 1}] Checkbox cochée: ${checkbox.value}`);
            });
            
            if (actualChecked.length !== totalChecked) {
                console.error(`PROBLÈME: ${totalChecked} classes devraient être cochées mais seulement ${actualChecked.length} le sont !`);
            }
        }, 100);

        // Gestion du "Tout cocher / décocher"
        const checkAll = document.getElementById('checkAllClasses');
        const totalClasses = allClasses.length;
        const checkedClasses = groupCodes.length;
        
        // Cocher "Tout cocher" seulement si toutes les classes sont dans le groupe
        checkAll.checked = checkedClasses > 0 && checkedClasses === totalClasses;
        
        checkAll.addEventListener('change', () => {
            document.querySelectorAll('.classe-checkbox').forEach(chk => {
                chk.checked = checkAll.checked;
            });
        });

        // Auto mise à jour : gérer l'état de "Tout cocher" selon les cases individuelles
        document.querySelectorAll('.classe-checkbox').forEach(chk => {
            chk.addEventListener('change', () => {
                const allCheckboxes = document.querySelectorAll('.classe-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.classe-checkbox:checked');
                
                // Si toutes les cases sont cochées, cocher "Tout cocher"
                // Si aucune case n'est cochée ou pas toutes, décocher "Tout cocher"
                checkAll.checked = checkedCheckboxes.length === allCheckboxes.length;
            });
        });

        const myModal = new bootstrap.Modal(document.getElementById('modifgroup'));
        myModal.show();

    } catch (err) {
        console.error(err);
        alert('Erreur de chargement des classes.');
    }
}

document.getElementById('btnValiderAjout').addEventListener('click', async () => {
    const groupeLibelle = document.getElementById('groupeLibelle').value;
    const selected = Array.from(document.querySelectorAll('.classe-checkbox:checked')).map(chk => chk.value);

    console.log('Groupe sélectionné:', groupeLibelle);
    console.log('Classes sélectionnées:', selected);

    // Permettre la suppression de toutes les classes (selected.length peut être 0)
    if (selected.length === 0) {
        const confirmRemoveAll = confirm('Aucune classe sélectionnée. Voulez-vous supprimer toutes les classes de ce groupe ?');
        if (!confirmRemoveAll) {
            return;
        }
    }

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    console.log('Token CSRF:', csrf);

    try {
        const res = await fetch(`/groupes/${encodeURIComponent(groupeLibelle)}/classes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ classCodes: selected })
        });

        console.log('Statut de la réponse:', res.status);
        const data = await res.json();
        console.log('Réponse du serveur:', data);

        // Gérer la réponse de synchronisation
        if (res.status === 200) {
            let message = '';
            let details = [];
            
            if (data.added && data.added.length > 0) {
                details.push(`${data.added.length} classe(s) ajoutée(s)`);
                console.log('Classes ajoutées:', data.added);
            }
            
            if (data.removed && data.removed.length > 0) {
                details.push(`${data.removed.length} classe(s) supprimée(s)`);
                console.log('Classes supprimées:', data.removed);
            }
            
            if (details.length > 0) {
                message = details.join(', ') + ' avec succès.';
            } else if (selected.length === 0) {
                // Si aucune classe sélectionnée, c'est une suppression totale
                message = 'Toutes les classes ont été supprimées du groupe avec succès.';
            } else {
                message = 'Aucune modification nécessaire.';
            }
            
            console.log(`Total classes sélectionnées: ${data.total_selected || selected.length}`);
            alert(message);
        } else {
            // Erreur
            throw new Error(data.message || 'Erreur serveur');
        }

        bootstrap.Modal.getInstance(document.getElementById('modifgroup')).hide();

        // Rechargement du tableau principal
        location.reload();

    } catch (err) {
        console.error(err);
        alert('Erreur : ' + err.message);
    }
});
</script>



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
$(document).ready(function(){
  // initialise Select2
  $('#classSelect').select2({
    placeholder: "Sélectionner la classe"
  });

  // récupère le container généré et lui ajoute une classe personnalisée
  var $container = $('#classSelect').data('select2').$container;
  $container.addClass('my-select2-style');
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('modifgroup');
    if (modal) {
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
    } else {
        console.error('Modal #modifgroup non trouvé dans le DOM');
    }
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

// L'ancienne fonction openModal a été remplacée par la nouvelle logique avec cases à cocher

// L'ancienne fonction ajouterClasse a été remplacée par la nouvelle logique dans le script du modal

// Fonction utilitaire pour afficher des alertes avec modal
function showAlert(message, callback) {
    var alertModalElement = document.getElementById('alertModal');
    var alertMessageElement = document.getElementById('alertModalMessage');
    
    if (alertModalElement && alertMessageElement) {
        alertMessageElement.textContent = message;
        var alertModal = new bootstrap.Modal(alertModalElement);
        alertModal.show();
        
        // Gérer la fermeture du modal
        alertModalElement.addEventListener('hidden.bs.modal', function() {
            if (callback) callback();
        }, { once: true });
    } else {
        console.error('Éléments du modal d\'alerte non trouvés');
        // Fallback avec alert natif
        alert(message);
        if (callback) callback();
    }
}


function supprimerClasse(button) {
    var id = button.getAttribute('data-id');
    var groupeLibelle = document.getElementById('modifgroupTitle').textContent.replace('Modifier le groupe : ', '').trim();
    
    // Ouvrir le modal de confirmation
    var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    confirmModal.show();
    
    // Gérer la confirmation
    document.getElementById('confirmDeleteButton').onclick = function() {
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
            // Fermer le modal de confirmation
            confirmModal.hide();
            
            // Afficher le message de succès
            showAlert('Classe supprimée avec succès !', function() {
                // Recharger la page pour actualiser les données
                location.reload();
            });
        })
        .catch(error => {
            console.error('Error:', error);
            confirmModal.hide();
            showAlert('Erreur lors de la suppression de la classe.', function() {});
        });
    };
}

    function confirmDelete() {
        return confirm('Êtes-vous sûr de vouloir supprimer ce groupe?');
    }




</script>



@endsection
