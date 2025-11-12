@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-between align-items-center w-100" style="min-height: 100vh; margin-left: 0; padding: 20px;">
    <form id="recouvrementForm" method="GET" action="">
        <div class="card col-md-12">
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

                    /* === Séparateur uniforme === */
                    .separator {
                        display: block;
                        border: none;
                        height: 2px;
                        background: linear-gradient(to right, #007bff, #6c757d, #007bff);
                        margin: 25px auto; /* même espace avant et après + centré horizontalement */
                        width: 80%; /* largeur centrée à 80% du conteneur */
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        align-self: center; /* pour flexbox si besoin */
                    }

                    .footer {
                        position: relative !important;
                        width: 100% !important;
                        z-index: 10 !important;
                    }
                </style>

                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
                <br><br>
            </div>

            <div class="card-body">
                <h4 class="card-title">Etat des recouvrements</h4>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Succès !</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="debut">Date de début</label>
                        <input name="debut" id="debut" type="date" class="typeaheads" required value="{{ request('debut') ?? old('debut') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="fin">Date de fin</label>
                        <input name="fin" id="fin" type="date" class="typeaheads" required value="{{ request('fin') ?? old('fin') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <h4 class="text-center">Sélectionner l'état que vous souhaitez imprimer</h4>
                <br>
                @csrf
                <div class="justify-content-center">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group col-md-12 row mt-1">
                                    <div class="col-md-6">
                                        <label for="groupe">Choisir un groupe</label>
                                        <select id="groupe" name="groupe" aria-label="Small select example">
                                            @foreach ($groupeclasse as $groupe)
                                                <option value="{{ $groupe->LibelleGroupe }}" {{ request('groupe') == $groupe->LibelleGroupe ? 'selected' : '' }}>{{ $groupe->LibelleGroupe }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="typeclasse">Types de classe</label>
                                        <select id="typeclasse" name="typeclasse" aria-label="Small select example">
                                            @foreach ($typeclasse as $type)
                                                <option value="{{ $type->TYPECLASSE }}" {{ (request('typeclasse') == $type->TYPECLASSE) || (!request('typeclasse') && $type->LibelleType == 'Normal') ? 'selected' : '' }}>{{ $type->LibelleType }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mt-1 justify-content-center">
                                    <div class="radio-inline">
                                        <input type="radio" id="option1" name="choixPlage" value="option1">
                                        <label for="option1" style="font-size: 14px; font-weight: bold">Recouvrement général</label>
                                        <h6 style="font-size: 14px; margin-left: 20px">Tableau récapitulatif des recouvrements par composant</h6>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option2" name="choixPlage" value="option2">
                                        <label for="option2" style="font-size: 14px; font-weight: bold">Recouvrement général par enseignement</label>
                                        <h6 style="font-size: 14px; margin-left: 20px">Tableau type "recouvrement général" par type d'enseignement</h6>
                                    </div>
                                </div>
                            </div>

                            <!-- Séparateur uniforme -->
                            <hr class="separator">

                            <div class="card-body col-md-12 row">
                                <div class="row col-md-8">
                                    <div class="radio-inline">
                                        <input type="radio" id="option4" name="choixPlage" value="option4">
                                        <label for="option4" style="font-size: 14px; font-weight: bold">Journal détaillé des recouvrements avec précision des composantes</label>
                                        <h6 style="font-size: 14px; margin-left: 20px">Liste des élèves ayant payé par jour et par composante</h6>
                                    </div>
                                    <div class="radio-inline">
                                        <input type="radio" id="option5" name="choixPlage" value="option5">
                                        <label for="option5" style="font-size: 14px; font-weight: bold">Journal détaillé des recouvrements sans précision des composantes</label>
                                        <h6 style="font-size: 14px; margin-left: 20px">Liste des élèves ayant payé par jour sans précision des composantes</h6>
                                    </div>
                                </div>

                                <div class="radio-inline col-md-4">
                                    <select class="form-select mb-2" name="typeenseign" id="typeenseign">
                                        @foreach ($groupeclasse as $groupe)
                                            <option value="{{ $groupe->LibelleGroupe }}" {{ request('typeenseign') == $groupe->LibelleGroupe ? 'selected' : '' }}>{{ $groupe->LibelleGroupe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                            </div>

                            <!-- Séparateur uniforme -->
                            <hr class="separator">

                            <div class="card-body">
                                <div class="radio-inline">
                                    <input type="radio" id="option6" name="choixPlage" value="option6">
                                    <label for="option6" style="font-size: 14px; font-weight: bold">Recouvrement par opérateur</label>
                                    <h6 style="font-size: 14px; margin-left: 20px">Point de recouvrement par agent/par jour</h6>
                                </div>
                                <div class="radio-inline">
                                    <input type="radio" id="option7" name="choixPlage" value="option7">
                                    <label for="option7" style="font-size: 14px; font-weight: bold">Journal détaillé par opérateur</label>
                                    <h6 style="font-size: 14px; margin-left: 20px">Liste détaillée des recouvrements par agent</h6>
                                </div>
                                <div class="radio-inline">
                                    <input type="radio" id="option8" name="choixPlage" value="option8">
                                    <label for="option8" style="font-size: 14px; font-weight: bold">Journal résumé des recouvrements</label>
                                    <h6 style="font-size: 14px; margin-left: 20px">Total recouvré par jour et par composante</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="button" id="apply" class="btn btn-primary" onclick="submitForm()">
                        Appliquer la sélection
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function submitForm() {
    const form = document.getElementById('recouvrementForm');
    const selectedOption = document.querySelector('input[name="choixPlage"]:checked');
    const debut = document.getElementById('debut').value;
    const fin = document.getElementById('fin').value;
    const typeClasse = document.getElementById('typeclasse').value;
    const groupe = document.getElementById('groupe').value;
    const typeEnseign = document.getElementById('typeenseign') ? document.getElementById('typeenseign').value : '';

    if (!debut || !fin) return alert('Veuillez renseigner les dates de début et de fin.');
    if (!typeClasse || !groupe) return alert('Veuillez sélectionner un type de classe et un groupe.');
    if (!selectedOption) return alert('Veuillez sélectionner une option.');

    let actionUrl = '';
    switch (selectedOption.value) {
        case 'option1': actionUrl = `{{ route('recouvrementgeneral') }}?debut=${encodeURIComponent(debut)}&fin=${encodeURIComponent(fin)}&typeclasse=${encodeURIComponent(typeClasse)}&groupe=${encodeURIComponent(groupe)}`; break;
        case 'option2': actionUrl = `{{ route('recouvrementgeneralenseignement') }}?debut=${encodeURIComponent(debut)}&fin=${encodeURIComponent(fin)}&typeclasse=${encodeURIComponent(typeClasse)}&groupe=${encodeURIComponent(groupe)}`; break;
        case 'option4': actionUrl = `{{ route('journaldetailleaveccomposante') }}?debut=${encodeURIComponent(debut)}&fin=${encodeURIComponent(fin)}&typeclasse=${encodeURIComponent(typeClasse)}&groupe=${encodeURIComponent(groupe)}&typeenseign=${encodeURIComponent(typeEnseign)}`; break;
        case 'option5': actionUrl = `{{ route('journaldetaillesanscomposante') }}?debut=${encodeURIComponent(debut)}&fin=${encodeURIComponent(fin)}&typeclasse=${encodeURIComponent(typeClasse)}&groupe=${encodeURIComponent(groupe)}&typeenseign=${encodeURIComponent(typeEnseign)}`; break;
        case 'option6': actionUrl = `{{ route('recouvrementoperateur') }}?debut=${encodeURIComponent(debut)}&fin=${encodeURIComponent(fin)}&typeclasse=${encodeURIComponent(typeClasse)}&groupe=${encodeURIComponent(groupe)}`; break;
        case 'option7': actionUrl = `{{ route('journaloperateur') }}?debut=${encodeURIComponent(debut)}&fin=${encodeURIComponent(fin)}&typeclasse=${encodeURIComponent(typeClasse)}&groupe=${encodeURIComponent(groupe)}`; break;
        case 'option8': actionUrl = `{{ route('journalresumerecouvrement') }}?debut=${encodeURIComponent(debut)}&fin=${encodeURIComponent(fin)}&typeclasse=${encodeURIComponent(typeClasse)}&groupe=${encodeURIComponent(groupe)}`; break;
        default: alert('Veuillez sélectionner une option valide.'); return;
    }

    form.action = actionUrl;
    form.submit();
}
</script>

@endsection
