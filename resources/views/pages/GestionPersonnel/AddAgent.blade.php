@extends('layouts.master')

@section('content')


<div class="main-panel-10">
    <div class="content-wrapper">
        @if (Session::has('status'))
        <div id="statusAlert" class="alert alert-success btn-primary">
            {{ Session::get('status') }}
        </div>
        @endif
        {{--  --}}
        <div class="row">          
            <div class="col-12">
                <div class="card mb-6">
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
                </div>
            </div>       
        </div>
        <div class="row">       
            <div class="col">                      
                <div class="card">
                    <br>
                    <button type="button" class="btn btn-primary" style="width: 10rem;" data-bs-toggle="modal" data-bs-target="#ajoutagent">
                        Nouveau
                    </button>
                    <br>
                    <div class="table-responsive" style="height: 300px; overflow: auto;">
                        <table class="table table-striped" style="min-width: 600px; font-size: 10px;">
                            <thead>
                                <tr>
                                    <th>Libellé Type</th>
                                    <th>Quota </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="typeAgentTable">
                                @foreach($agents as $agent)
                                    <tr data-libelle="{{ $agent->LibelTypeAgent }}">
                                        <td>{{ $agent->LibelTypeAgent }}</td>
                                        <td>{{ $agent->Quota }}</td>
                                        <td>
                                            {{-- Les 4 premières lignes : non modifiables --}}
                                            @if($loop->index < 4)
                                                <span class="text-muted">Non modifiable</span>
                                            @else
                                                <button class="btn btn-sm btn-warning edit-btn"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></button>
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


<!-- Modal (inchangé visuellement) mais champs avec id -->
<div class="modal fade" id="ajoutagent" tabindex="-1" aria-labelledby="ajoutagentLabel" aria-hidden="true">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nouveau Type d'agent</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="formAjoutAgent">
          @csrf
          <div class="mb-3">
            <label>Libellé</label>
            <input id="libelleInput" type="text" name="libelle" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Quota</label>
            <input id="quotaInput" type="number" name="quota" class="form-control" required>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary" id="saveAgentBtn">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
    const saveBtn = document.getElementById('saveAgentBtn');
    const form = document.getElementById('formAjoutAgent');
    const libelleInput = document.getElementById('libelleInput');
    const quotaInput = document.getElementById('quotaInput');
    const tbody = document.getElementById('typeAgentTable');
    const baseByLibUrl = "{{ url('/typeagent/libelle') }}";
    const storeUrl = "{{ route('typeagent.store') }}";
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // mode par défaut = create
    saveBtn.dataset.mode = 'create';

    function resetModalToCreate() {
        saveBtn.dataset.mode = 'create';
        delete saveBtn.dataset.original;
        saveBtn.textContent = 'Enregistrer';
        form.reset();
    }

    // CREATE or UPDATE handler
    saveBtn.addEventListener('click', function () {
        const mode = this.dataset.mode || 'create';

        if (mode === 'create') {
            const fd = new FormData(form);
            fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: fd
            })
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(data => {
                if (!data.LibelTypeAgent) throw new Error('Réponse invalide');
                // Ajoute la ligne dans le tableau (data-libelle)
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-libelle', data.LibelTypeAgent);
                newRow.innerHTML = `
                    <td>${data.LibelTypeAgent}</td>
                    <td>${data.Quota}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(newRow);

                resetModalToCreate();
                const modalEl = document.getElementById('ajoutagent');
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.hide();
            })
            .catch(err => {
                console.error(err);
                alert('Erreur à l\'enregistrement (voir console).');
            });

        } else { // mode === 'edit'
            const original = this.dataset.original;
            if (!original) return alert('Original introuvable pour la mise à jour.');

            fetch(baseByLibUrl + '/' + encodeURIComponent(original), {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    libelle: libelleInput.value,
                    quota: quotaInput.value
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(data => {
                // trouver la ligne correspondante dans le DOM (par data-libelle)
                let targetRow = null;
                document.querySelectorAll('#typeAgentTable tr').forEach(r => {
                    if (r.dataset.libelle === original) targetRow = r;
                });

                if (targetRow) {
                    targetRow.dataset.libelle = data.LibelTypeAgent;
                    targetRow.children[0].textContent = data.LibelTypeAgent;
                    targetRow.children[1].textContent = data.Quota;
                }

                resetModalToCreate();
                const modalEl = document.getElementById('ajoutagent');
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.hide();
            })
            .catch(err => {
                console.error(err);
                alert('Erreur lors de la mise à jour (voir console).');
            });
        }
    });

    // Delegate click events for Edit and Delete
    document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const row = editBtn.closest('tr');
            const libelle = row.dataset.libelle || '';
            const quota = row.children[1] ? row.children[1].textContent.trim() : '';

            libelleInput.value = libelle;
            quotaInput.value = quota;

            saveBtn.dataset.mode = 'edit';
            saveBtn.dataset.original = libelle;
            saveBtn.textContent = 'Mettre à jour';

            const modalEl = document.getElementById('ajoutagent');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
            return;
        }

        const delBtn = e.target.closest('.delete-btn');
        if (delBtn) {
            const row = delBtn.closest('tr');
            const libelle = row.dataset.libelle;
            if (!libelle) return alert('Identifiant manquant.');

            if (!confirm(`Supprimer le type "${libelle}" ?`)) return;

            fetch(baseByLibUrl + '/' + encodeURIComponent(libelle), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) return res.json().then(j => { throw new Error(j.error || 'Erreur'); });
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    row.remove();
                } else {
                    alert(data.error || 'Impossible de supprimer.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Erreur lors de la suppression (voir console).');
            });
        }
    });
})();
</script>



@endsection