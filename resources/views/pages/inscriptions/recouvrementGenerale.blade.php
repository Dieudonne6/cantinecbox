@extends('layouts.master')

@section('content')
 
<div class="main-panel-10">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
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
                <h4 class="card-title">Recouvrement generale</h4>
                <div class="row mb-3">
                    <div class="col-6">
                        <form action="{{ url('/recouvrementGenParPeriode') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <div class="col">
                                    <label for="debut">Du</label>
                                    <input name="debut" id="debut" type="date"
                                        value="{{ old('date', now()->subMonths(3)->format('Y-m-d')) }}"
                                        class="typeaheads" required>
                                </div>
                                <div class="col">
                                    <label for="fin">Au</label>
                                    <input name="fin" id="fin" type="date"
                                        value="{{ old('date', now()->format('Y-m-d')) }}" class="typeaheads" required>
                                </div>

 
                            </div>
                        </form>
                    </div>
                    <div class="col-3 " style="margin-top: 1.8rem">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
