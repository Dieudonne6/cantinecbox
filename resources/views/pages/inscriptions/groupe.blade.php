@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="col-12">
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
            <div class="card">
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

                    <form action="{{ url('/ajoutergroupe')}}" method="POST">
                        @csrf
                        <div class="form-group row"><br>
                            {{-- <p>Ajout d'un nouveau groupe</p> --}}
                            <label for="exampleInputUsername2" class="col-sm-2 col-form-label">Nouveau groupe</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="nomgroupe" name="nomgroupe" placeholder="Nom groupe" required>
                            </div>
                            <div class="col-sm-3">
                                {{-- <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Libelle"> --}}
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </div>
                    </form>


                    <div class="row justify-content-center">
                        <div class="col-8" style="text-align: center;">

                            <div class="table-responsive pt-3">
    
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Groupe</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                        @foreach ($allgroupes as $allgroupe)
                                            
                                        <tr>
                                            <td>{{ $allgroupe->LibelleGroupe }}</td>
                                            <td>
                                                <form action="/suppgroupe/{{ $allgroupe->id }}" method="POST"  onsubmit="return confirmDelete()" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal1" onclick="setDeleteFormAction('{{ $allgroupe->id }}')">Supprimer</button>
                                                    {{-- <button type="submit" class="btn btn-danger btn-sm">Supprimer</button> --}}
                                                </form>
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

    function setDeleteFormAction(groupeId) {
        var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn.onclick = function() {
            var form = document.querySelector('form[action="/suppgroupe/' + groupeId + '"]');
            if (form) {
                form.submit();
            }
        };
    }
        function confirmDelete()
         {
            return confirm('Êtes-vous sûr de vouloir supprimer ce groupe?');
         }
    </script>
@endsection