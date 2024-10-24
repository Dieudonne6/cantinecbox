@extends('layouts.master')

@section('content')

<div class="container">
    <div class="row">
        <!-- Colonne principale pour le select -->
        <div class="col-lg-10 mx-auto">
            <div class="form-group mt-5">
                <h5>Matières</h5>
                <select name="matiere" id="matiere" class="form-control bg-primary select-custom text-white">
                    <!-- Quatre premières options spécifiques -->
                    <option value="all">< Toutes les matières ></option>
                    <option value="litteraires">< Matières Littéraires (Enseign. Général ) ></option>
                    <option value="scientifiques">< Matières Scientifiques (Enseign. Général ) ></option>
                    <option value="technique">< Matières Fondamentales (Enseign. Technique) ></option>
                    <!-- Options dynamiques -->
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->CODEMAT }}">{{ $matiere->LIBELMAT }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Fieldset aligné en bas à gauche prenant 3 colonnes -->
        <div class="col-lg-3 ml-5 mt-5" style="border: 2px solid #b700ff; padding: 10px;">
            <fieldset>
                <legend class="w-auto px-2">Type Stat.</legend>
                <div class="form-check">
                    <input class="form-check-input ml-1" type="checkbox" id="general" name="stat_type" value="general">
                    <label class="form-check-label ml-4" for="general">
                        Enseign. Général
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input ml-1" type="checkbox" id="technique" name="stat_type" value="technique">
                    <label class="form-check-label ml-4" for="technique">
                        Enseign. Technique
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input ml-1" type="checkbox" id="tous" name="stat_type" value="tous">
                    <label class="form-check-label ml-4" for="tous">
                        Tous
                    </label>
                </div>
            </fieldset>
        </div>
    </div>
    <button class="btn btn-primary" style="width: auto; margin-top: 20px; margin-left: 70%">Lancer</button>
</div>

<script>
    // Fonction pour désactiver les autres checkboxes lorsqu'une est cochée
    const checkboxes = document.querySelectorAll('input[name="stat_type"]');
    
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                checkboxes.forEach((box) => {
                    if (box !== this) {
                        box.checked = false;
                    }
                });
            }
        });
    });
</script>

<style>
    /* Pour s'assurer que la flèche de déroulement soit visible sans changer la couleur de fond */
    .select-custom {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: transparent;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3E%3Cpath fill='%23ffffff' d='M0 0l2 2 2-2z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 12px 12px;
        padding-right: 2rem; /* pour donner de l'espace à la flèche */
    }
</style>

@endsection
