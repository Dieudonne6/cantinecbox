@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
        <h4 class="card-title">Gestion des Coefficients</h4>
        <div class="row mb-3">
            <div class="col-12 mt-4">
                <button type="button" class="btn-sm btn-primary" id="saveButton">Sauvegarder</button>
                <button id="btnCopierValeur" type="button" class="btn-sm btn-success">Copier valeur</button>
                <button id="btnCollerValeur" type="button" class="btn-sm btn-secondary">Coller valeur</button>
                <button id="btnMarquerFondamentale" type="button" class="btn-sm btn-danger">Marquer Fondamentale</button>
                {{-- <button type="button" class="btn-sm btn-secondary">Imprimer</button> --}}
            </div>
        </div>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Liste des Matieres</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            @foreach($listeMatieres as $matiere)
                <p><strong>{{ $matiere->CODEMAT }}</strong> - {{ $matiere->LIBELMAT }}</p>
            @endforeach
          </div>
        </div> 

        {{-- @csrf --}}
        
        <div class="row mb-3">
            @if(Session::has('status'))
            <div id="statusAlert" class="alert alert-succes btn-primary">
            {{ Session::get('status')}}
            </div>
            @endif
            <div class="table-responsive">
                <form id="coefficientForm" method="POST" action="{{url('/enregistrerCoefficient')}}">
                    @csrf
                    @method('PUT')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Classe / Matière</th>
                            @foreach($listeMatieres as $matiere)
                                <th>{{ $matiere->LIBELMAT }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listeClasses as $classe)
                            <tr>
                                <td>{{ $classe->CODECLAS }}</td>
                                @foreach($listeMatieres as $matiere)
                                    @php
                                        $key = $classe->CODECLAS . '-' . $matiere->CODEMAT;
                                        $coefficient = $coefficients->get($key);
                                        $value = $coefficient ? $coefficient->COEF : '';
                                        $isFondamentale = $coefficient && $coefficient->FONDAMENTALE ? 'fondamentale' : '';
                                    @endphp
                                    <td>
                                        <input type="number" name="coefficients[{{ $classe->CODECLAS }}][{{ $matiere->CODEMAT }}]" 
                                            class="form-control form-control-sm coefficient-input {{ $isFondamentale }}" 
                                            min="0" 
                                            style="width: 90px; text-align: center;" step="1" value="{{ $value }}">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
            </div>
        </div>
    </div>
  </div>
</div>

<style>
    .fondamentale {
        color: red !important; /* Change la couleur du texte en rouge */
    }
    .selected {
        border: 2px solid blue; /* Optionnel : ajouter un contour à la cellule sélectionnée */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let selectedInput = null; // Stocke l'input sélectionné
        let copiedValues = []; // Stocke les valeurs copiées
        let copiedClasses = []; // Stocke les classes copiées (pour savoir si elles sont fondamentales)

        // Sélectionne tous les inputs avec la classe 'coefficient-input'
        const coefficientInputs = document.querySelectorAll('.coefficient-input');

        coefficientInputs.forEach(input => {
            // Ajoute un écouteur d'événement pour formater la valeur quand l'utilisateur quitte le champ
            input.addEventListener('blur', function () {
                let value = parseFloat(input.value);

                // Vérifie si la valeur entrée est valide
                if (!isNaN(value)) {
                    // Formate la valeur à deux décimales
                    input.value = value.toFixed(2);
                } else {
                    input.value = ''; // Si la valeur n'est pas valide, on efface
                }
            });

            // Ajoute un écouteur pour sélectionner l'input lors du clic
            input.addEventListener('click', function () {
                // Désélectionne l'input précédent
                if (selectedInput) {
                    selectedInput.classList.remove('selected'); // Retire le style de sélection
                }

                // Sélectionne l'input actuel
                selectedInput = input; // Récupère l'input sélectionné
                selectedInput.classList.add('selected'); // Ajoute le style de sélection
            });
        });

        // Écouteur pour le bouton "Marquer Fondamentale"
        document.getElementById('btnMarquerFondamentale').addEventListener('click', function () {
            if (selectedInput) {
                // Vérifie si l'input a déjà la classe 'fondamentale'
                if (selectedInput.classList.contains('fondamentale')) {
                    selectedInput.classList.remove('fondamentale'); // Retire la classe pour revenir à la couleur d'origine
                } else {
                    selectedInput.classList.add('fondamentale'); // Ajoute la classe rouge à l'input
                }
            } else {
                alert('Veuillez sélectionner une cellule avant de marquer fondamentale.'); // Alerte si aucune cellule n'est sélectionnée
            }
        });

        // Écouteur pour le bouton "Copier valeur"
        document.getElementById('btnCopierValeur').addEventListener('click', function () {
            if (selectedInput) {
                const rowInputs = selectedInput.closest('tr').querySelectorAll('input'); // Récupère tous les inputs de la ligne
                copiedValues = []; // Réinitialise les valeurs copiées
                copiedClasses = []; // Réinitialise les classes copiées

                // Copie les valeurs à partir de l'input sélectionné
                for (let i = Array.from(rowInputs).indexOf(selectedInput); i < rowInputs.length; i++) {
                    copiedValues.push(rowInputs[i].value); // Ajoute la valeur à la liste des valeurs copiées
                    copiedClasses.push(rowInputs[i].classList.contains('fondamentale')); // Vérifie si l'input a la classe 'fondamentale'
                }

                // alert('Valeurs copiées : ' + copiedValues.join(', ')); // Affiche les valeurs copiées
            } else {
                alert('Veuillez sélectionner une cellule avant de copier les valeurs.'); // Alerte si aucune cellule n'est sélectionnée
            }
        });

        // Écouteur pour le bouton "Coller valeur"
        document.getElementById('btnCollerValeur').addEventListener('click', function () {
            if (selectedInput && copiedValues.length > 0) {
                const rowInputs = selectedInput.closest('tr').querySelectorAll('input'); // Récupère tous les inputs de la ligne
                const startIndex = Array.from(rowInputs).indexOf(selectedInput); // Trouve l'index de l'input sélectionné

                // Colle les valeurs à partir de l'input sélectionné
                for (let i = 0; i < copiedValues.length && (startIndex + i) < rowInputs.length; i++) {
                    rowInputs[startIndex + i].value = copiedValues[i]; // Colle la valeur dans l'input correspondant
                    
                    // Si la classe 'fondamentale' était présente lors de la copie, ajoute-la à l'input collé
                    if (copiedClasses[i]) {
                        rowInputs[startIndex + i].classList.add('fondamentale'); // Colle la classe 'fondamentale'
                    } else {
                        rowInputs[startIndex + i].classList.remove('fondamentale'); // Sinon, retire la classe
                    }
                }

                // alert('Valeurs collées : ' + copiedValues.join(', ')); // Affiche les valeurs collées
            } else {
                alert('Veuillez sélectionner une cellule et copier des valeurs avant de coller.'); // Alerte si aucune cellule n'est sélectionnée ou si aucune valeur n'est copiée
            }
        });

        // Écouteur pour le bouton "Sauvegarder"
        document.getElementById('saveButton').addEventListener('click', function () {
    const coefficients = {};
    const inputs = document.querySelectorAll('.coefficient-input');

    console.log("Nombre d'inputs trouvés:", inputs.length);
    
    inputs.forEach(input => {
        console.log("Input name:", input.name);
        console.log("Input value:", input.value);

        const classMatch = input.name.match(/coefficients\[(\w+)\]/);
        const matiereMatch = input.name.match(/coefficients\[\w+\]\[(\d+)\]/);

        if (classMatch && matiereMatch) {
            const classId = classMatch[1];
            const matiereId = matiereMatch[1];
            let value = input.value.trim() !== "" ? input.value : ""; // Valeur par défaut si vide
            
            // Vérifiez si l'input a la classe 'fondamentale' pour récupérer la couleur
            const color = input.classList.contains('fondamentale') ? 'red' : 'default';

            console.log("Valeur à sauvegarder pour l'input:", value); // Log pour débogage

            // Stockez les coefficients
            if (!coefficients[classId]) {
                coefficients[classId] = {};
            }
            coefficients[classId][matiereId] = { value, color }; // Stocke la valeur et la couleur
        }
    });

    console.log("Coefficients à sauvegarder:", coefficients);

    // Vérifiez si l'objet coefficients n'est pas vide avant la soumission
    if (Object.keys(coefficients).length === 0) {
        alert("Aucun coefficient à sauvegarder !");
        return; // Sortir si aucun coefficient n'est trouvé
    }

    // Ajoutez les coefficients au formulaire avant la soumission
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'coefficients'; // Le nom doit correspondre à ce que votre contrôleur attend
    hiddenInput.value = JSON.stringify(coefficients); // Sérialisez l'objet en JSON

    document.getElementById('coefficientForm').appendChild(hiddenInput);
    document.getElementById('coefficientForm').submit(); // Soumettez le formulaire
});


    });
</script>
@endsection


