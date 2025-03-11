@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-between align-items-center w-100 " style="min-height: 100vh; margin-left: 0; padding: 20px;">
    <form id="recouvrementForm" method="GET" action="">
        <div class="card col-md-12">
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
                <h4 class="card-title">Etat des recouvrements</h4>
                <div class="form-group row">
                    <div class="col">
                        <label for="debut">Date de début</label>
                        <input name="debut" id="debut" type="date" class="typeaheads" required>
                    </div>
                    <div class="col">
                        <label for="fin">Date de fin</label>
                        <input name="fin" id="fin" type="date" class="typeaheads" required>
                    </div>
                </div>
            </div>
        
        <div class="card-body">
            <h4 class="text-center">Sélectionner l'état que vous souhaitez imprimer</h4>
            <br>
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row mt-1">
                                    <div class="col-12 ">
                                        <label for="groupe" class="col-form-label">Choisir un groupe</label>
                                        <label for="typeclasse" class="col-form-label" style="margin-left: 11rem">Types de classe</label>
                                        
                                    </div>
                                    <div class="col-12">
                                        <select id="typeclasse" name="typeclasse" aria-label="Small select example" >
                                            @foreach ($typeclasse as $type)
                                                <option value="{{ $type->TYPECLASSE }}">{{ $type->LibelleType }}</option>
                                            @endforeach
                                        </select>
                                        <select  id="groupe" name="groupe" aria-label="Small select example" style=" margin-left: 10rem;">
                                            @foreach ($groupeclasse as $groupe)
                                                <option value="{{ $groupe->LibelleGroupe }}">{{ $groupe->LibelleGroupe }}</option>
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
                        </div>
                    </div>

                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="radio-inline">
                                    <select class="form-select mb-2" name="typeenseign" id="typeenseign">
                                        <option selected>Sélectionner un enseignement</option>
                                        @foreach ($typeenseign as $type)
                                            <option value="{{ $type->idenseign }}">{{ $type->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
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
                                <br>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
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



        // Récupération des valeurs des champs de date, type de classe et groupe
        const debut = document.getElementById('debut').value;
        const fin = document.getElementById('fin').value;
        
        const typeClasse = document.getElementById('typeclasse').value;
        const groupe = document.getElementById('groupe').value;

        // Vérifier que les champs requis sont remplis
        // if (!debut || !fin || !typeClasse || !groupe) {
        //     alert('Veuillez remplir tous les champs obligatoires.');
        //     return;
        // }
        console.log(typeClasse);

        if (!selectedOption) {
            alert('Veuillez sélectionner une option.');
            return;
        }
        

        let actionUrl = '';

        switch (selectedOption.value) {

            case 'option1':
                actionUrl = `{{ route('recouvrementgeneral') }}?debut=${debut}&fin=${fin}&typeclasse=${typeClasse}&groupe=${groupe}`;
                break;

            case 'option2':
                actionUrl = `{{ route('recouvrementgeneralenseignement') }}?debut=${debut}&fin=${fin}&typeclasse=${typeClasse}&groupe=${groupe}`;
                break;

            case 'option4':
                actionUrl = "{{ route('journaldetailleaveccomposante') }}";
                break;

            case 'option5':
                actionUrl = "{{ route('journaldetaillesanscomposante') }}";
                break;

            case 'option6':
                actionUrl = "{{ route('recouvrementoperateur') }}";
                break;

            case 'option7':
                actionUrl = "{{ route('journaloperateur') }}";
                break;

            case 'option8':
                actionUrl = "{{ route('journalresumerecouvrement') }}";
                break;

            default:
                alert('Veuillez sélectionner une option valide.');
                return;
        }
        form.action = actionUrl;
        form.submit();
    }
</script>



<style>
/*     .footer{
    display: none;
    } */
    .footer {
        position: relative !important; /* Utiliser relative pour éviter de cacher le tableau */
        width: 100% !important;
        z-index: 10 !important; /* Assurer que le footer soit au-dessus des autres éléments */
    }
    
    
</style>

Route::get('/recouvrementGenParPeriode/{datedebut}/{datefin}/{typeclasse}/{groupe}', [PagesController::class, 'recouvrementGenParPeriode'])->name('recouvrementgeneral');

@endsection
