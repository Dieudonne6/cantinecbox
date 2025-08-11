@extends('layouts.master')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <!-- Bouton de retour -->
        <div>
            <style>
                .btn-arrow {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    background-color: transparent !important;
                    border: 1px !important;
                    text-transform: uppercase !important;
                    font-weight: bold !important;
                    cursor: pointer !important;
                    font-size: 17px !important;
                    color: #b51818 !important;
                }
                .btn-arrow:hover {
                    color: #b700ff !important;
                }
            </style>
            <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <br><br>
        </div>
        <div class="card-body">
            <h5 class="mb-2">Exportation des élèves</h5>

            <!-- Zone d'upload et boutons sur une même ligne -->
            <div class="col-auto" style="margin-left: 15rem;">
                <div class="d-flex align-items-center" style="space-between: 10rem">
                    <form action="{{ route('exporter.eleves') }}" method="GET">
                        <button class="btn btn-primary me-2">Charger les élèves</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

