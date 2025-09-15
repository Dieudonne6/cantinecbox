@extends('layouts.master')
@section('content')

    <div class="main-panel-10">
        <div class="content-wrapper">

            @if (Session::has('status'))
                <div id="statusAlert" class="alert alert-success btn-primary">
                    {{ Session::get('status') }}
                </div>
            @endif

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
                        <div class="card-body">
                            <h4 class="card-title">Gestion des séries</h4>
                            <div class="row gy-3">
                                <div class="demo-inline-spacing">
                                    <!-- Button nouveau trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalNouveau"> Nouveau</button>
                                    <style>
                                        table {
                                            float: right;
                                            width: 10%;
                                            border-collapse: collapse;
                                            margin: 5px auto;
                                        }

                                        th,
                                        td {
                                            /* border: 1px solid #aaa1a1; */
                                            padding: 4px;
                                            text-align: center;
                                        }

                                        th {
                                            /* background-color: #f2f2f2; */
                                            /* align-items: center;
                                  text-align: center; */
                                        }

                                        td.bouton {
                                            /* background-color: #ffcccb; */
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

                    <div class="card p-3">
                        <div class="table-responsive" style="overflow: auto;">
                            <table class="table table-striped" style="min-width: 600px; font-size: 10px;" id="myTable">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; !impoortant">Série</th>
                                        <th style="text-align: center; !impoortant">Libellé série</th>
                                        <th>Cycle</th>
                                        <th style="text-align: center; !impoortant">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp

                                    @foreach ($series as $cycle => $items)
                                        {{-- <h4>Cycle : {{ $cycle }}</h4> --}}

                                        @foreach ($items as $serie)
                                            @php $i++; @endphp
                                            <tr>
                                                <td>{{ $serie->SERIE }}</td>
                                                <td>{{ $serie->LIBELSERIE }}</td>
                                                <td data-order="{{ (int) $serie->CYCLE }}">{{ $serie->CYCLE }}</td>
                                                <td>
                                                    {{-- n'affiche les boutons (et modals) que si ce n'est pas l'un des 5 premiers éléments --}}
                                                    {{-- n'affiche les boutons que si ce n'est pas parmi les 13 premiers éléments (exemple) --}}
                                                    @if (empty($serie->initial) || !$serie->initial)
                                                        <div class="">
                                                            <!-- Button modifier trigger modal -->
                                                            <button type="button" class="btn btn-primary p-2 btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalModifier{{ $serie->SERIE }}">
                                                                Modifier</button>

                                                            <!-- Button supprimer trigger modal -->
                                                            <button type="button" class="btn btn-danger p-2 btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalDelete{{ $serie->SERIE }}">Supprimer</button>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <!-- Modal bouton Modifier -->
                                            <div class="modal fade" id="exampleModalModifier{{ $serie->SERIE }}"
                                                tabindex="-2" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel2">Modifier
                                                                fiche d'une série</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ url('/modifierserie') }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="SERIE" id="edit-serie"
                                                                    value="{{ $serie->SERIE }}">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <div class="col">
                                                                            <div>
                                                                                <label><strong>Série</strong> (Donner un
                                                                                    code pour la série à créer [2
                                                                                    caractères]. Ex: C)</label>
                                                                                <input type="text" name="SERIE"
                                                                                    value="{{ $serie->SERIE }}"
                                                                                    placeholder="" id="edit-serie"
                                                                                    class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <div class="col">
                                                                            <div>
                                                                                <label><strong>Libellé série</strong>
                                                                                    (Donner le libellé de la série à créer.
                                                                                    Ex: Série C)
                                                                                </label>
                                                                                <input type="text" name="LIBELSERIE"
                                                                                    value="{{ $serie->LIBELSERIE }}"
                                                                                    placeholder="" id="edit-libelserie"
                                                                                    class="form-control" required>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <div class="col">
                                                                            <label><strong>Préciser le
                                                                                    Cycle</strong></label>
                                                                            <select name="CYCLE"
                                                                                class="js-example-basic-multiple w-100"
                                                                                required>
                                                                                <option value="1"
                                                                                    {{ $serie->CYCLE == 1 ? 'selected' : '' }}>
                                                                                    1er Cycle</option>
                                                                                <option value="2"
                                                                                    {{ $serie->CYCLE == 2 ? 'selected' : '' }}>
                                                                                    2eme Cycle</option>
                                                                                {{-- <option value="3" {{ $serie->CYCLE == 3 ? 'selected' : '' }}>3eme Cycle</option> --}}
                                                                                <option value="0"
                                                                                    {{ $serie->CYCLE == 0 ? 'selected' : '' }}>
                                                                                    Aucun</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit"
                                                                class="btn btn-primary">Enregistrer</button>
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Annuler</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Modal bouton supprimer -->
                                            <div class="modal fade" id="exampleModalDelete{{ $serie->SERIE }}"
                                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation
                                                                de suppression</h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Êtes-vous sûr de vouloir supprimer cette série ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Annuler</button>
                                                            <form action="{{ url('/supprimerserie') }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="SERIE"
                                                                    value="{{ $serie->SERIE }}">
                                                                <input type="submit" class="btn btn-danger"
                                                                    value="Confirmer">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                        </div>
                        @endforeach
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal bouton Nouveau-->
    <div class="modal fade" id="exampleModalNouveau" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Enregistrement d'une série</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div id="statusAlert" class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <?php $error = Session::get('error'); ?>

                    @if (Session::has('error'))
                        <div id="statusAlert" class="alert alert-danger">
                            {{ Session::get('error') }}
                        </div>
                    @endif
                    <form id="myformclas" action="{{ url('/saveserie') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="form-group">
                                <div class="col">
                                    <div>
                                        <label><strong>Série</strong> (Donner un code pour la série à créer [2 caractères].
                                            Ex: C)</label>
                                        <input type="text" name="SERIE" placeholder="" class="form-control"
                                            value="{{ old('SERIE') }}" required minlength="1" maxlength="2"
                                            pattern="^[A-Z]([0-9]?)$"
                                            title="La série doit commencer par une lettre majuscule et comporter entre 1 et 2 caractères, uniquement des lettres majuscules ou des chiffres.">
                                    </div>
                                </div>
                                <br>
                                <div class="col">
                                    <div>
                                        <label><strong>Libellé série</strong> (Donner le libellé de la série à créer. Ex:
                                            Série C)</label>
                                        <input type="text" name="LIBELSERIE" placeholder="" class="form-control"
                                            value="{{ old('LIBELSERIE') }}">
                                    </div>
                                </div>
                                <br>
                                <div class="col">
                                    <label><strong>Préciser le Cycle</strong></label>
                                    <select name="CYCLE" class="js-example-basic-multiple w-100" required>
                                        <option value="1" {{ old('CYCLE') == 1 ? 'selected' : '' }}>1er Cycle
                                        </option>
                                        <option value="2" {{ old('CYCLE') == 2 ? 'selected' : '' }}>2eme Cycle
                                        </option>
                                        {{-- <option value="3" {{ old('CYCLE') == 3 ? 'selected' : '' }}>3eme Cycle</option> --}}
                                        <option value="0" {{ old('CYCLE') == 0 ? 'selected' : '' }}>Aucun</option>
                                    </select>
                                </div>
                            </div>
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

@endsection



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModalNouveau'));

        @if ($error || $errors->any())
            myModal.show();
        @endif

        // Réinitialiser les champs du formulaire à la fermeture du modal
        // document.getElementById('exampleModalNuveau').addEventListener('hidden.bs.modal', function () {
        //     document.getElementById('myformclas').reset();
        //     document.querySelectorAll('#myformclas .form-control').forEach(input => input.value = '');
        // });


        $(document).ready(function() {
            if (!$.fn.dataTable.isDataTable('#myTable')) {
                $('#myTable').DataTable({
                    order: [
                        [2, 'asc'],
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        targets: 2,
                        type: 'num'
                    }],
                    pageLength: 15
                });
            } else {
                // si déjà initialisé : on peut juste forcer l'ordre et redraw
                var table = $('#myTable').DataTable();
                table.order([
                    [2, 'asc'],
                    [0, 'asc']
                ]).draw();
            }
        });
    });
</script>

{{-- <script>
$(document).ready(function() {
  $('#myTable').DataTable({
    // trier d'abord par la colonne "Cycle" (index 2), puis par "Série" (index 0)
    order: [[2, 'asc'], [0, 'asc']],
    columnDefs: [
      // assurer un tri numérique sur la colonne Cycle
      { targets: 2, type: 'num' }
    ],
    pageLength: 15
  });
});
</script> --}}
