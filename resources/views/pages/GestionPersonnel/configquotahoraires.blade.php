@extends('layouts.master')

@section('content')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .bg-warning-subtle {
            background-color: #fff3cd !important;
        }
        .table td, .table th {
            padding: 0.25rem;
            vertical-align: middle;
            height: 30px;
        }
        .list-group-item.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .btn-toolbar .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .modal-dialog.modal-xl {
            max-width: 95%;
        }
    </style>

    <div class="container-fluid p-3">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Mémorisation des quota horaires par matière et par classe</h4>
            </div>
        </div>

        <!-- Barre d'outils -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="btn-toolbar bg-light p-2 rounded shadow-sm">
                    <div class="btn-group me-2" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-folder"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-save"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-person"></i></button>
                    </div>
                    <div class="btn-group ms-auto" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Aide</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grille principale + Liste matières -->
        <div class="row">
            <div class="col-9">
                <div class="table-responsive border rounded shadow-sm">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center" style="width: 120px;">Clas</th>
                                @for($i = 1; $i <= 17; $i++)
                                    <th class="text-center" style="width: 40px;">{{ $i }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $classes = [
                                    '1EREA1', '1EREA2', '1EREA2-1', '1EREA2-2', '1EREA2-3',
                                    '1EREB', '1EREC', '1ERED1', '1ERED2',
                                    '2NDEA2', '2NDEA2-1', '2NDEA2-2', '2NDEA2-3',
                                    '2NDEB', '2NDEC', '2NDED1', '2NDED2', '2NDED3',
                                    '3EMEM1', '3EMEM2', '3EMEM3', '3EMEM4', '3EMEM5'
                                ];
                            @endphp

                            @foreach($classes as $classe)
                            <tr>
                                <td>{{ $classe }}</td>
                                @for($i = 1; $i <= 17; $i++)
                                    <td class="bg-warning-subtle"></td>
                                @endfor
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Liste des matières -->
            <div class="col-3">
                <div class="border rounded shadow-sm h-100">
                    <div class="list-group list-group-flush">
                        @php
                            $matieres = [
                                'Communication écrite',
                                'Lecture',
                                'Composition Française',
                                'Anglais',
                                'Espagnol',
                                'Allemand',
                                'LV1',
                                'LV2',
                                'Philosophie',
                                'Histoire Géographie',
                                'Mathématique',
                                'SPCT',
                                'SVT',
                                'EPS',
                                'Economie',
                                'Informatique',
                                'Conduite'
                            ];
                        @endphp

                        @foreach($matieres as $index => $matiere)
                        <a href="#" class="list-group-item list-group-item-action {{ $loop->first ? 'active' : '' }}">
                            {{ $index + 1 }} {{ $matiere }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton pour ouvrir le modal -->
        <div class="mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#config2Modal">
                Ouvrir Définition des tranches
            </button>
        </div>

    </div>

    <!-- MODAL CONFIG2 - Définition des tranches -->
    <div class="modal fade" id="config2Modal" tabindex="-1" aria-labelledby="config2ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="config2ModalLabel">Définition des tranches</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Barre d'outils dans le modal -->
                    <div class="btn-toolbar bg-light p-2 rounded shadow-sm mb-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-folder"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-save"></i></button>
                        </div>
                    </div>

                    <!-- Grille dans le modal -->
                    <div class="table-responsive border rounded shadow-sm">
                        <div style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered">
                                <thead class="bg-primary text-white sticky-top">
                                    <tr>
                                        <th class="text-center" style="width: 120px;">Clas</th>
                                        @for($i = 1; $i <= 26; $i++)
                                            <th class="text-center" style="width: 40px;">{{ $i }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classes as $classe)
                                    <tr>
                                        <td>{{ $classe }}</td>
                                        @for($i = 1; $i <= 26; $i++)
                                            <td class="bg-warning-subtle"></td>
                                        @endfor
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var myModal = new bootstrap.Modal(document.getElementById('config2Modal'));
            myModal.show();
        });
    </script>
    
@endsection