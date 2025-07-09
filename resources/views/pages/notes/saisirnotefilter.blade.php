@extends('layouts.master')
@section('content')
    <div class="main-panel-10">

        <div class="container">
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

                      /* 1. On limite la hauteur visible du tableau et on autorise le scroll vertical */
                        .table-scrollable {
                            max-height: 500px;       /* Ajustez selon la hauteur souhaitée */
                            overflow-y: auto;
                        }

                        /* 2. On rend les <th> “collants” en haut du conteneur */
                        .table-scrollable thead th {
                            position: sticky;
                            top: 0;
                            background-color: #fff;  /* Couleur d'arrière-plan de l'en-tête */
                            z-index: 10;             /* Pour que l’en-tête reste au-dessus des lignes du tbody */
                        }
                </style>
                <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>   
                <br>
                <br>                                   
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('enregistrer_notes') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-6 md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                    <div class="row w-100">
                                        <!-- Select pour le groupe -->
                                        <div class="col-md-8 mb-3">
                                            <select class="form-select select2 w-100" id="tableSelect1"
                                                aria-label="Choisir un groupe">
                                                <option value="" selected>Choisir un groupe</option>
                                                @foreach ($gclasses as $gclasse)
                                                    <option value="{{ $gclasse->LibelleGroupe }}">
                                                        {{ $gclasse->LibelleGroupe }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Select pour la période -->
                                        <div class="col-md-8 mb-3">
                                            <select class="form-select select2 w-100 mt-2" 
                                                    id="periodSelect" 
                                                    name="periode"
                                                    onchange="handleChange()"
                                                    aria-label="Choisir une période">
                                                <option value="">Période</option>
                                                <option value="1" {{ request('periode')=='1' ? 'selected':'' }}>1ère Période</option>
                                                <option value="2" {{ request('periode')=='2' ? 'selected':'' }}>2ème Période</option>
                                                <option value="3" {{ request('periode')=='3' ? 'selected':'' }}>3ème Période</option>
                                            </select>
                                        </div>

{{--                                         <!-- Champ de nombre -->
                                        <div class="col-md-4">
                                            <input type="number" id="champ1" name="champ1" class="form-control"
                                                placeholder="Valeur" value="" readonly>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                    <div class="col">
                                        <div class="col-md-8 mb-3">
                                            <select class="form-select select2 w-100" id="tableSelect4" name="CODECLAS"
                                                onchange="redirectWithSelection()">
                                                <option value="">Classe</option>
                                                @foreach ($classes as $classeOption)
                                                    <option value="{{ $classeOption->CODECLAS }}"
                                                        {{ $classeOption->CODECLAS == $classe ? 'selected' : '' }}>
                                                        {{ $classeOption->CODECLAS }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-8 mb-3">
                                            <select class="form-select select2 w-100 mt-2" id="tableSelect5" name="CODEMAT"
                                                onchange="redirectWithSelection()">
                                                <option value="">Matières</option>
                                                @foreach ($matieres as $matiereOption)
                                                    <option value="{{ $matiereOption->CODEMAT }}"
                                                        {{ $matiereOption->CODEMAT == $matiere ? 'selected' : '' }}>
                                                        {{ $matiereOption->LIBELMAT }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Champ de nombre -->
                                    <div class="col-md-4 mb-3">
                                        <input type="number" id="champ2" name="champ2" class="form-control"
                                            value="{{ $getClasmat ? $getClasmat->COEF : 'Valeur non trouvée' }}"
                                            placeholder="Valeur" readonly>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 md-3 grid-margin">
                    <!-- Card pour afficher les cases à cocher -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6 d-flex flex-wrap" id="intCheckboxes">
                                    @for ($i = 3; $i <= 10; $i++)
                                        <label class="checkbox-label interro-checkbox me-2"
                                            for="optionINT{{ $i }}" data-interro="{{ $i }}">
                                            <input type="checkbox" id="optionINT{{ $i }}" name="optionGroup1[]"
                                                value="INT{{ $i }}"
                                                onchange="toggleColumn({{ $i }})">
                                            INT{{ $i }}
                                        </label>
                                    @endfor

                                    <label class="checkbox-label me-2" for="optionDEV3">
                                        <input type="checkbox" id="optionDEV3" onchange="toggleDev3()">
                                        DEV3
                                    </label>
                                    <label class="checkbox-label me-2" for="optionTEST">
                                        <input type="checkbox" id="optionTEST" onchange="toggleTest()">
                                        TEST
                                    </label>

                                </div>
                                <div class="col-md-6 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-rounded">
                                        <i class="typcn typcn-home-outline"></i> Enregistrer
                                    </button>

                                    <!-- Bouton Permuter (ouvre le modal) -->
                                    <button type="button" class="btn btn-warning btn-rounded ms-2" data-bs-toggle="modal"
                                        data-bs-target="#permuterModal">
                                        <i class="typcn typcn-refresh-outline"></i> Permuter
                                    </button>

                                    <button type="button" class="btn btn-danger btn-rounded" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        <i class="typcn typcn-delete-outline"></i>
                                        Supprimer
                                    </button>

                                    


                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 md-3 grid-margin">
                    <!-- Card pour afficher le tableau -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-scrollable table-responsive mb-4">
                                <table id="myTab" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>MATRICULE</th>
                                            <th>Nom et Prénoms</th>
                                            <th class="interro-column" data-interro="1">Int1</th>
                                            <th class="interro-column" data-interro="2">Int2</th>
                                            <th class="interro-column" data-interro="3">Int3</th>
                                            <th class="interro-column" data-interro="4">Int4</th>
                                            <th class="interro-column" data-interro="5">Int5</th>
                                            <th class="interro-column" data-interro="6">Int6</th>
                                            <th class="interro-column" data-interro="7">Int7</th>
                                            <th class="interro-column" data-interro="8">Int8</th>
                                            <th class="interro-column" data-interro="9">Int9</th>
                                            <th class="interro-column" data-interro="10">Int10</th>
                                            <th>M.int</th>
                                            <th>Dev1</th>
                                            <th>Dev2</th>
                                            <th class="dev3-column">Dev3</th>
                                            <th>Moy</th>
                                            <th class="test-column">Test</th>
                                            <th>Ms</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eleves as $eleve)
                                            <tr>
                                                <td>{{ $eleve->MATRICULEX ?? '' }}</td>
                                                <td style="text-align: left;">{{ $eleve->NOM ?? '' }} {{ $eleve->PRENOM ?? '' }}</td>

                                                @for ($i = 1; $i <= 10; $i++)
                                                    <td class="interro-column" data-interro="{{ $i }}">
                                                        <input type="text"
                                                            name="notes[{{ $eleve->MATRICULE }}][INT{{ $i }}]"
                                                            value="{{ in_array($eleve['INT' . $i] ?? '', [21, -1]) ? '' : ($eleve['INT' . $i] ?? '') }}"
                                                            class="form-control form-control-sm interro-input fixed-input"
                                                            oninput="calculateMIAndMoy(this)">
                                                    </td>
                                                @endfor

                                                <td>
                                                    <input type="text" name="notes[{{ $eleve->MATRICULE }}][MI]"
                                                    value="{{ in_array($eleve->MI ?? '', [21, -1]) ? '' : ($eleve->MI ?? '') }}"
                                                        class="form-control form-control-sm mi-input fixed-input" readonly>
                                                </td>
                                                <td><input type="text" name="notes[{{ $eleve->MATRICULE }}][DEV1]"
                                                    value="{{ in_array($eleve->DEV1 ?? '', [21, -1]) ? '' : ($eleve->DEV1 ?? '') }}"
                                                        class="form-control form-control-sm dev-input fixed-input"
                                                        oninput="calculateMIAndMoy(this)"></td>

                                                <td><input type="text" name="notes[{{ $eleve->MATRICULE }}][DEV2]"
                                                    value="{{ in_array($eleve->DEV2 ?? '', [21, -1]) ? '' : ($eleve->DEV2 ?? '') }}"
                                                        class="form-control form-control-sm dev-input fixed-input"
                                                        oninput="calculateMIAndMoy(this)"></td>

                                                <td class="dev3-column"><input type="text" name="notes[{{ $eleve->MATRICULE }}][DEV3]"
                                                    value="{{ in_array($eleve->DEV3 ?? '', [21, -1]) ? '' : ($eleve->DEV3 ?? '') }}"
                                                        class="form-control form-control-sm dev-input fixed-input"
                                                        oninput="calculateMIAndMoy(this)"></td>
                                                <td>
                                                    <input type="text" name="notes[{{ $eleve->MATRICULE }}][MS1]"
                                                    value="{{ in_array($eleve->MS1 ?? '', [21, -1]) ? '' : ($eleve->MS1 ?? '') }}"
                                                        class="form-control form-control-sm ms1-input fixed-input"
                                                        readonly>
                                                </td>

                                                <td class="test-column"><input type="text" name="notes[{{ $eleve->MATRICULE }}][TEST]"
                                                    value="{{ in_array($eleve->TEST ?? '', [21, -1]) ? '' : ($eleve->TEST ?? '') }}"
                                                        class="form-control form-control-sm test-input fixed-input"
                                                        oninput="calculateMIAndMoy(this)"></td>

                                                <td><input type="text" name="notes[{{ $eleve->MATRICULE }}][MS]"
                                                    value="{{ in_array($eleve->MS ?? '', [21, -1]) ? '' : ($eleve->MS ?? '') }}"
                                                        class="form-control form-control-sm ms-input fixed-input" readonly>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>


                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                // 1) Masquer les colonnes Int3 à Int10 au chargement
                                for (let i = 3; i <= 10; i++) {
                                    toggleColumn(i);
                                }
                                toggleDev3();
                                toggleTest();


                                // 2) Fonction utilitaire pour savoir si un élément est visible (pas en display:none)
                                function isVisible(el) {
                                    return el && el.offsetParent !== null;
                                }

                                // 3) Fonction pour passer au champ suivant (même logique que pour →/Enter)
                                function moveToNext(currentInput) {
                                    const td = currentInput.closest('td');
                                    const tr = td.closest('tr');
                                    const colIndex = td.cellIndex;

                                    // a) Chercher dans la même ligne
                                    let nextCell = td.nextElementSibling;
                                    while (nextCell) {
                                    if (isVisible(nextCell)) {
                                        const candidate = nextCell.querySelector('input.fixed-input');
                                        if (candidate && !candidate.readOnly) {
                                        candidate.focus();
                                        return;
                                        }
                                    }
                                    nextCell = nextCell.nextElementSibling;
                                    }

                                    // b) Sinon, passer à la ligne suivante
                                    let nextTr = tr.nextElementSibling;
                                    while (nextTr) {
                                    for (let inp of nextTr.querySelectorAll('input.fixed-input')) {
                                        const parentTD = inp.closest('td');
                                        if (!inp.readOnly && isVisible(parentTD)) {
                                        inp.focus();
                                        return;
                                        }
                                    }
                                    nextTr = nextTr.nextElementSibling;
                                    }
                                }

                                // 4) Gestionnaire global de keydown pour les flèches et Enter
                                document.addEventListener("keydown", function(e) {
                                    const target = e.target;
                                    if (!target.matches('input.fixed-input')) return;

                                    const td = target.closest('td');
                                    const tr = td.closest('tr');
                                    const colIndex = td.cellIndex;
                                    let nextCell, prevCell, upCell, downCell, cand, found;

                                    // --- FLÈCHE DROITE ou ENTER ---
                                    if (e.key === 'ArrowRight' || e.key === 'Enter') {
                                    e.preventDefault();

                                    // a) Essayer la cellule à droite
                                    nextCell = td.nextElementSibling;
                                    while (nextCell) {
                                        if (isVisible(nextCell)) {
                                        cand = nextCell.querySelector('input.fixed-input');
                                        if (cand && !cand.readOnly) {
                                            cand.focus();
                                            return;
                                        }
                                        }
                                        nextCell = nextCell.nextElementSibling;
                                    }

                                    // b) Si on a appuyé sur ENTER et rien à droite → ligne suivante
                                    if (e.key === 'Enter') {
                                        let nextTr = tr.nextElementSibling;
                                        while (nextTr) {
                                        found = false;
                                        for (let inp of nextTr.querySelectorAll('input.fixed-input')) {
                                            const parentTD = inp.closest('td');
                                            if (!inp.readOnly && isVisible(parentTD)) {
                                            inp.focus();
                                            found = true;
                                            break;
                                            }
                                        }
                                        if (found) return;
                                        nextTr = nextTr.nextElementSibling;
                                        }
                                    }

                                    return;
                                    }

                                    // --- FLÈCHE GAUCHE ---
                                    if (e.key === 'ArrowLeft') {
                                    e.preventDefault();
                                    prevCell = td.previousElementSibling;
                                    while (prevCell) {
                                        if (isVisible(prevCell)) {
                                        cand = prevCell.querySelector('input.fixed-input');
                                        if (cand && !cand.readOnly) {
                                            cand.focus();
                                            return;
                                        }
                                        }
                                        prevCell = prevCell.previousElementSibling;
                                    }
                                    return;
                                    }

                                    // --- FLÈCHE HAUT ---
                                    if (e.key === 'ArrowUp') {
                                    e.preventDefault();
                                    let prevTr = tr.previousElementSibling;
                                    while (prevTr) {
                                        upCell = prevTr.children[colIndex];
                                        if (upCell && isVisible(upCell)) {
                                        cand = upCell.querySelector('input.fixed-input');
                                        if (cand && !cand.readOnly) {
                                            cand.focus();
                                            return;
                                        }
                                        }
                                        prevTr = prevTr.previousElementSibling;
                                    }
                                    return;
                                    }

                                    // --- FLÈCHE BAS ---
                                    if (e.key === 'ArrowDown') {
                                    e.preventDefault();
                                    let nextTr2 = tr.nextElementSibling;
                                    while (nextTr2) {
                                        downCell = nextTr2.children[colIndex];
                                        if (downCell && isVisible(downCell)) {
                                        cand = downCell.querySelector('input.fixed-input');
                                        if (cand && !cand.readOnly) {
                                            cand.focus();
                                            return;
                                        }
                                        }
                                        nextTr2 = nextTr2.nextElementSibling;
                                    }
                                    return;
                                    }
                                });

                                // 5) Fonction pour calculer MI et MS dès qu'un champ change
                                function calculateMIAndMoy(input) {
                                    const row = input.closest('tr');

                                    // Récupérer les champs int, dev et test dans la ligne
                                    const interroInputs = row.querySelectorAll('.interro-input');
                                    const devInputs     = row.querySelectorAll('.dev-input');
                                    const testInput     = row.querySelector('[name*="TEST"]');

                                    // --- Calcul de la moyenne d'interrogations (MI) ---
                                    let interroSum   = 0;
                                    let interroCount = 0;
                                    interroInputs.forEach(interro => {
                                    // Remplacer la virgule par un point pour parseFloat
                                    const raw = interro.value.replace(',', '.');
                                    const value = parseFloat(raw);
                                    if (!isNaN(value)) {
                                        interroSum += value;
                                        interroCount++;
                                    }
                                    });
                                    const miField = row.querySelector('.mi-input');
                                    const mi = interroCount > 0 ? (interroSum / interroCount).toFixed(2) : '';
                                    miField.value = mi;

                                    // --- Calcul de la moyenne des devoirs (MS1) ---
                                    let devSum   = 0;
                                    let devCount = 0;
                                    devInputs.forEach(dev => {
                                    const rawDev = dev.value.replace(',', '.');
                                    const value  = parseFloat(rawDev);
                                    if (!isNaN(value)) {
                                        devSum += value;
                                        devCount++;
                                    }
                                    });
                                    const moyField = row.querySelector('.ms1-input');
                                    let moy = '';

                                    if (devCount > 0 && interroCount === 0) {
                                    // queue de devoirs uniquement
                                    moy = (devSum / devCount).toFixed(2);
                                    } else if (interroCount > 0) {
                                    // il y a des interros → on intègre MI
                                    moy = devCount > 0 
                                        ? ((parseFloat(mi) + devSum) / (devCount + 1)).toFixed(2)
                                        : mi;
                                    }
                                    moyField.value = moy;

                                    // --- Calcul de la note semestrielle (MS) selon votre logique ---
                                    const msField   = row.querySelector('.ms-input');
                                    const rawTest   = testInput.value.replace(',', '.');
                                    const testValue = parseFloat(rawTest) || 0;

                                    if (devCount === 0 && interroCount === 0 && testValue === 0) {
                                    // ni int, ni dev, ni test → MS = MI ou MS1
                                    msField.value = mi || moyField.value;
                                    } else if (interroCount === 0 && devCount === 0) {
                                    // seuls test → MS = test
                                    msField.value = testValue.toFixed(2);
                                    } else if (interroCount === 0) {
                                    // seuls les devoirs → MS = moyenne des devoirs
                                    msField.value = (devSum / devCount).toFixed(2);
                                    } else {
                                    // sinon → MS = moyenne(MS1, TEST) si test>0 sinon MS1 seul
                                    const ms = testValue > 0 
                                        ? (parseFloat(moyField.value) + testValue) / 2 
                                        : parseFloat(moyField.value);
                                    msField.value = ms.toFixed(2);
                                    }
                                }

                                // 6) Appliquer calculateMIAndMoy au changement de chaque champ dev ou int ou test
                                document.querySelectorAll('.interro-input, .dev-input, .test-input').forEach(elem => {
                                    elem.addEventListener('input', function() {
                                    calculateMIAndMoy(this);
                                    });
                                });

                                // 7) Écouteur 'input' pour formater en "xx,yy" et passer au suivant au 4e chiffre
                                document.querySelectorAll('input.fixed-input').forEach(inp => {
                                    inp.addEventListener('input', function() {
                                    // On retire tout ce qui n'est pas chiffre
                                    let digits = this.value.replace(/\D/g, '');

                                    // Dès que l'on a au moins 4 chiffres, on formate en "AB,CD"
                                    if (digits.length >= 4) {
                                        digits = digits.slice(0, 4); // 4 premiers chiffres
                                        this.value = digits.slice(0,2) + '.' + digits.slice(2);
                                        // Puis on recalcule MI/MS1/MS et on passe au champ suivant
                                            const numericValue = parseFloat(this.value);
                                           calculateMIAndMoy(this);
                                           if (!isNaN(numericValue) && numericValue <= 20) {
                                               moveToNext(this);
                                           } else {
                                               // Si > 20, on reste dans la même case (on ne bouge pas le focus).
                                               // Facultatif : vous pouvez remettre 'this.value = ""' ou
                                               // afficher un message d'erreur ici si besoin.
                                           }
                                    } else {
                                        // Sinon, on laisse l'utilisateur inscrire librement (sans virgule)
                                        this.value = digits;
                                        // On peut quand même recalculer MI/MS1/MS en direct si souhaité :
                                        calculateMIAndMoy(this);
                                    }
                                    });
                                });
                                });
                            </script>


{{-- 
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                // 1) Masquer les colonnes Int3 à Int10 au chargement
                                for (let i = 3; i <= 10; i++) {
                                    toggleColumn(i);
                                }

                                // 2) Fonction utilitaire pour savoir si un élément est visible (pas en display:none)
                                function isVisible(el) {
                                    return el && el.offsetParent !== null;
                                }

                                // 3) Fonction qui, à partir d'un <input>, détermine et donne le focus au champ suivant
                                function moveToNext(currentInput) {
                                    const td = currentInput.closest('td');
                                    const tr = td.closest('tr');
                                    const colIndex = td.cellIndex;

                                    // a) Chercher à droite dans la même ligne
                                    let nextCell = td.nextElementSibling;
                                    while (nextCell) {
                                    if (isVisible(nextCell)) {
                                        const candidate = nextCell.querySelector('input.fixed-input');
                                        if (candidate && !candidate.readOnly) {
                                        candidate.focus();
                                        return;
                                        }
                                    }
                                    nextCell = nextCell.nextElementSibling;
                                    }

                                    // b) Si aucun champ à droite, descendre à la ligne suivante
                                    let nextTr = tr.nextElementSibling;
                                    while (nextTr) {
                                    for (let inp of nextTr.querySelectorAll('input.fixed-input')) {
                                        const parentTD = inp.closest('td');
                                        if (!inp.readOnly && isVisible(parentTD)) {
                                        inp.focus();
                                        return;
                                        }
                                    }
                                    nextTr = nextTr.nextElementSibling;
                                    }
                                    // Si on n’a vraiment rien trouvé, on reste où l’on est
                                }

                                // 4) Gestionnaire global de keydown pour les flèches et Entrée
                                document.addEventListener("keydown", function(e) {
                                    const target = e.target;
                                    if (!target.matches('input.fixed-input')) return;

                                    const td = target.closest('td');
                                    const tr = td.closest('tr');
                                    const colIndex = td.cellIndex;
                                    let nextCell, prevCell, upCell, downCell, cand, found;

                                    // --- FLÈCHE DROITE ou Touche Entrée ---
                                    if (e.key === 'ArrowRight' || e.key === 'Enter') {
                                    e.preventDefault();

                                    // 4.a. Parcours des TD suivants sur la même ligne
                                    nextCell = td.nextElementSibling;
                                    while (nextCell) {
                                        if (isVisible(nextCell)) {
                                        cand = nextCell.querySelector('input.fixed-input');
                                        if (cand && !cand.readOnly) {
                                            cand.focus();
                                            return;
                                        }
                                        }
                                        nextCell = nextCell.nextElementSibling;
                                    }

                                    // Si on appuie sur Entrée et qu’on n’a rien trouvé à droite, on passe à la ligne suivante
                                    if (e.key === 'Enter') {
                                        let nextTr = tr.nextElementSibling;
                                        while (nextTr) {
                                        found = false;
                                        for (let inp of nextTr.querySelectorAll('input.fixed-input')) {
                                            const parentTD = inp.closest('td');
                                            if (!inp.readOnly && isVisible(parentTD)) {
                                            inp.focus();
                                            found = true;
                                            break;
                                            }
                                        }
                                        if (found) return;
                                        nextTr = nextTr.nextElementSibling;
                                        }
                                    }

                                    return;
                                    }

                                    // --- FLÈCHE GAUCHE ---
                                    if (e.key === 'ArrowLeft') {
                                    e.preventDefault();
                                    prevCell = td.previousElementSibling;
                                    while (prevCell) {
                                        if (isVisible(prevCell)) {
                                        cand = prevCell.querySelector('input.fixed-input');
                                        if (cand && !cand.readOnly) {
                                            cand.focus();
                                            return;
                                        }
                                        }
                                        prevCell = prevCell.previousElementSibling;
                                    }
                                    return;
                                    }

                                    // --- FLÈCHE HAUT ---
                                    if (e.key === 'ArrowUp') {
                                    e.preventDefault();
                                    let prevTr = tr.previousElementSibling;
                                    while (prevTr) {
                                        upCell = prevTr.children[colIndex];
                                        if (upCell && isVisible(upCell)) {
                                        cand = upCell.querySelector('input.fixed-input');
                                        if (cand && !cand.readOnly) {
                                            cand.focus();
                                            return;
                                        }
                                        }
                                        prevTr = prevTr.previousElementSibling;
                                    }
                                    return;
                                    }

                                    // --- FLÈCHE BAS ---
                                    if (e.key === 'ArrowDown') {
                                    e.preventDefault();
                                    let nextTr2 = tr.nextElementSibling;
                                    while (nextTr2) {
                                        downCell = nextTr2.children[colIndex];
                                        if (downCell && isVisible(downCell)) {
                                        cand = downCell.querySelector('input.fixed-input');
                                        if (cand && !cand.readOnly) {
                                            cand.focus();
                                            return;
                                        }
                                        }
                                        nextTr2 = nextTr2.nextElementSibling;
                                    }
                                    return;
                                    }
                                });

                                // 5) Écouteur 'input' pour formater en "xx,yy" et passer au suivant dès 4 chiffres
                                document.querySelectorAll('input.fixed-input').forEach((inp) => {
                                    inp.addEventListener('input', function() {
                                    // Garder uniquement les chiffres dans la valeur
                                    let digits = this.value.replace(/\D/g, '');

                                    // Dès qu'on a au moins 4 chiffres, on formate et on saute au champ suivant
                                    if (digits.length >= 4) {
                                        digits = digits.slice(0, 4); // garder les 4 premiers chiffres
                                        // Format "ABCD" → "AB,CD"
                                        this.value = digits.slice(0, 2) + '.' + digits.slice(2);
                                        // On déplace le focus sur le champ suivant
                                        moveToNext(this);
                                    } else {
                                        // Si on a moins de 4 chiffres, on met simplement ce qu'il y a (sans virgule)
                                        this.value = digits;
                                    }
                                    });
                                });
                                });
                            </script> --}}

{{-- 
                            <script>
                                const selectElement = document.getElementById('tableSelect1');

                                // Sauvegarder la valeur sélectionnée dans le localStorage
                                selectElement.addEventListener('change', () => {
                                    localStorage.setItem('selectedGroup', selectElement.value);
                                });

                                // Restaurer la valeur sauvegardée lors du chargement de la page
                                document.addEventListener('DOMContentLoaded', () => {
                                    const savedValue = localStorage.getItem('selectedGroup');
                                    if (savedValue) {
                                        selectElement.value = savedValue;
                                    }
                                });

                                function calculateMIAndMoy(input) {
                                    const row = input.closest('tr');

                                    // Récupérer les champs pour les notes des interrogations, des devoirs et du test
                                    const interroInputs = row.querySelectorAll('.interro-input');
                                    const devInputs = row.querySelectorAll('.dev-input');
                                    const testInput = row.querySelector('[name*="TEST"]');

                                    let interroSum = 0;
                                    let interroCount = 0;

                                    // Calculer la somme et le nombre d'interrogations
                                    interroInputs.forEach(interro => {
                                        const value = parseFloat(interro.value);
                                        if (!isNaN(value)) {
                                            interroSum += value;
                                            interroCount++;
                                        }
                                    });

                                    const miField = row.querySelector('.mi-input');
                                    const mi = interroCount > 0 ? (interroSum / interroCount).toFixed(2) : '';
                                    miField.value = mi;

                                    let devSum = 0;
                                    let devCount = 0;

                                    // Calculer la somme et le nombre de devoirs
                                    devInputs.forEach(dev => {
                                        const value = parseFloat(dev.value);
                                        if (!isNaN(value)) {
                                            devSum += value;
                                            devCount++;
                                        }
                                    });

                                    const moyField = row.querySelector('.ms1-input');
                                    let moy = '';

                                    // Si les devoirs sont présents mais pas les interrogations
                                    if (devCount > 0 && interroCount === 0) {
                                        moy = (devSum / devCount).toFixed(2);
                                    } else if (interroCount > 0) {
                                        // Si les interrogations sont présentes, calculer la Moyenne
                                        moy = devCount > 0 ? ((parseFloat(mi) + devSum) / (devCount + 1)).toFixed(2) : mi;
                                    }

                                    moyField.value = moy;

                                    const msField = row.querySelector('.ms-input');
                                    const testValue = parseFloat(testInput.value) || 0;

                                    // Nouvelle logique pour MS
                                    if (devCount === 0 && interroCount === 0 && testValue === 0) {
                                        // Si les champs dev, test sont vides, alors MI = MS1 = MS
                                        msField.value = mi || moyField.value;
                                    } else if (interroCount === 0 && devCount === 0) {
                                        // Si les champs int et dev sont vides, MS = TEST
                                        msField.value = testValue.toFixed(2);
                                    } else if (interroCount === 0) {
                                        // Si les champs int sont vides, MS1 est la somme des DEV entrée divisée par le nombre de DEV
                                        msField.value = (devSum / devCount).toFixed(2);
                                    } else {
                                        // Sinon, MS est calculé comme la moyenne de MS1 et TEST
                                        const ms = (parseFloat(moyField.value) + testValue) / (testValue > 0 ? 2 : 1);
                                        msField.value = ms.toFixed(2);
                                    }
                                }
                            </script> --}}


                        </div>
                    </div>
                    <br><br><br>
                    <!-- Modal -->
                    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Voulez vous vriament supprimer les
                                        notes?</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('delete-notes') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">

                                        <input type="hidden" id="modalClasse" name="CODECLAS" value="">
                                        <input type="hidden" id="modalMatiere" name="CODEMAT" value="">
                                        <input type="hidden" id="modalSemestre" name="champ1" value="">


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Supprimer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> --}}
                </form>


                            <!-- Modal de permutation -->
            <div class="modal fade" id="permuterModal" tabindex="-1" aria-labelledby="permuterModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('permuter_notes') }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="permuterModalLabel">Permuter les notes</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-4"> {{-- même "row" pour que ça tienne sur une ligne --}}
                                {{-- ---- Bloc SOURCE (gauche) ---- --}}
                                <div class="col-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="source_classe" class="form-label">Classe source</label>
                                        <select class="form-select select2 w-100" id="tableSelect4" name="CODECLAS"
                                            onchange="redirectWithSelection()" disabled>
                                            <option value="">Classe</option>
                                            @foreach ($classes as $classeOption)
                                                <option value="{{ $classeOption->CODECLAS }}"
                                                    {{ $classeOption->CODECLAS == $classe ? 'selected' : '' }}>
                                                    {{ $classeOption->CODECLAS }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="CODECLAS" id="hidden_CODECLAS">
                                    </div>
                                    <div class="mb-3">
                                        <label for="source_mat" class="form-label">Matière source</label>
                                        <select class="form-select select2 w-100 mt-2" id="tableSelect5" name="CODEMAT"
                                            onchange="redirectWithSelection()" disabled>
                                            <option value="">Matières</option>
                                            @foreach ($matieres as $matiereOption)
                                                <option value="{{ $matiereOption->CODEMAT }}"
                                                    {{ $matiereOption->CODEMAT == $matiere ? 'selected' : '' }}>
                                                    {{ $matiereOption->LIBELMAT }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="CODEMAT" id="hidden_CODEMAT">
                                    </div>
                                    <div class="mb-3">
                                        <label for="source_periode" class="form-label">Période source</label>
                                        <select id="periode_source_affiche" name="periode_source_affiche"
                                            class="form-control" disabled>
                                            <option value="">Période</option>
                                            <option value="1"
                                                {{ isset($periode) && $periode == 1 ? 'selected' : '' }}>1ʳᵉ Période
                                            </option>
                                            <option value="2"
                                                {{ isset($periode) && $periode == 2 ? 'selected' : '' }}>2ᵉ Période
                                            </option>
                                            <option value="3"
                                                {{ isset($periode) && $periode == 3 ? 'selected' : '' }}>3ᵉ Période
                                            </option>
                                        </select>
                                        <input type="hidden" name="periode_source_affiche"
                                            id="hidden_periode_source_affiche">
                                    </div>
                                </div>
                                {{-- ---- Bloc CIBLE (droite) ---- --}}
                                <div class="col-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="target_classe" class="form-label">Classe cible</label>
                                        <input type="text" class="form-control" id="target_classe" name="target_classe" 
                                               value="{{ $classe }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="target_mat" class="form-label">Matière cible</label>
                                        <select name="target_mat" id="target_mat" class="form-select">
                                            @foreach ($matieres as $m)
                                                <option value="{{ $m->CODEMAT }}">{{ $m->LIBELMAT }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="target_periode" class="form-label">Période cible</label>
                                        <select name="target_periode" id="target_periode" class="form-select">
                                            <option value="1">1ʳᵉ Période</option>
                                            <option value="2">2ᵉ Période</option>
                                            <option value="3">3ᵉ Période</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-warning">Permuter</button>
                        </div>
                    </form>
                </div>
            </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                        Voulez-vous vraiment supprimer les notes pour la classe,
                                        la matière et la période sélectionnées ?
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <form action="{{ route('delete-notes') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <!-- Trois champs cachés mis à jour au moment de l'ouverture -->
                                        <input type="hidden" id="modalClasse" name="CODECLAS" value="">
                                        <input type="hidden" id="modalMatiere" name="CODEMAT" value="">
                                        <input type="hidden" id="modalSemestre" name="champ1" value="">
                                        <p>La suppression n'affectera que la classe, la matière et la période sélectionnées.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        function updateCodeMat() {
                            // Récupère la valeur sélectionnée dans le select de matières
                            var selectedMatiere = document.getElementById("tableSelect2").value;
                            // Met à jour le champ d'input avec le CODEMAT sélectionné
                            document.getElementById("champ2").value = selectedMatiere;
                        }

                        function updateInterroVisibility() {
                            const value = parseInt(document.getElementById('champ2').value);
                            document.querySelectorAll('.interro-checkbox').forEach(checkbox => {
                                const interroNumber = parseInt(checkbox.dataset.interro);
                                checkbox.style.display = interroNumber <= value ? 'inline-block' : 'none';
                            });

                            document.querySelectorAll('.interro-column').forEach(column => {
                                const interroNumber = parseInt(column.dataset.interro);
                                column.style.display = interroNumber <= value ? '' : 'none';
                            });
                        }

                        // function updateCheckbox() {
                        //     const periodSelect = document.getElementById('periodSelect');
                        //     const champ1 = document.getElementById('champ1');

                        //     // Met à jour la valeur de champ1 avec la valeur sélectionnée dans periodSelect
                        //     if (periodSelect.value) {
                        //         champ1.value = periodSelect.value;

                        //         // Sauvegarde de la sélection dans localStorage
                        //         localStorage.setItem('selectedPeriod', periodSelect.value);
                        //     } else {
                        //         champ1.value = '';
                        //         localStorage.removeItem('selectedPeriod');
                        //     }
                        // }

                        // Restaure la sélection après le rechargement
       /*                  window.addEventListener('DOMContentLoaded', () => {
                            const periodSelect = document.getElementById('periodSelect');
                            const champ1 = document.getElementById('champ1');
                            const savedPeriod = localStorage.getItem('selectedPeriod');

                            if (savedPeriod) {
                                periodSelect.value = savedPeriod;
                                champ1.value = savedPeriod;
                            }
                        }); */


                        function redirectWithSelection() {
                            const classe = document.getElementById("tableSelect4").value; // Récupère la classe sélectionnée
                            const matiere = document.getElementById("tableSelect5").value; // Récupère la matière sélectionnée
                            const periode = document.getElementById("periodSelect").value; // Récupère la periode sélectionnée

                            let url = '/filternotes'; // URL de redirection
                            let params = [];

                            if (classe) params.push(`classe=${encodeURIComponent(classe)}`); // Ajoute le paramètre de classe si sélectionné
                            if (matiere) params.push(`matiere=${encodeURIComponent(matiere)}`); // Ajoute le paramètre de matière si sélectionné
                            if (periode) params.push(`periode=${encodeURIComponent(periode)}`); // Ajoute le paramètre de matière si sélectionné


                            if (params.length > 0) {
                                url += '?' + params.join('&'); // Crée la chaîne de requête
                            }

                            window.location.href = url; // Redirige vers l'URL construite
                        }

                        // Ajoute les écouteurs d'événements pour les deux sélecteurs
                        // document.getElementById("tableSelect4").addEventListener("change", redirectWithSelection);
                        // document.getElementById("tableSelect5").addEventListener("change", redirectWithSelection);
                        // document.getElementById("tableSelec4").addEventListener("change", redirectWithSelection);
                        // document.getElementById("tableSelec5").addEventListener("change", redirectWithSelection);

                         function handleChange() {
                            redirectWithSelection();
                            updateCheckbox();
                         }
                    </script>

                    <script>
                        function toggleColumn(interroNumber) {
                            // Récupère l'état de la case à cocher
                            const checkbox = document.getElementById("optionINT" + interroNumber);
                            const isChecked = checkbox.checked;

                            // Récupère toutes les cellules de la colonne correspondante
                            const columns = document.querySelectorAll(`.interro-column[data-interro="${interroNumber}"]`);

                            // Affiche ou masque la colonne selon l'état de la case à cocher
                            columns.forEach(column => {
                                column.style.display = isChecked ? '' : 'none';
                            });
                        }

                        // Initialisation : Masque les colonnes Int3 à Int10 au chargement de la page
                        document.addEventListener("DOMContentLoaded", () => {
                            for (let i = 3; i <= 10; i++) {
                                toggleColumn(i);
                            }
                        });
                    </script>

                    {{-- ——— SCRIPT : toggle DEV3 ——— --}}
                            <script>
                                function toggleDev3() {
                                    const checkbox = document.getElementById("optionDEV3");
                                    const isChecked = checkbox.checked;
                                    document.querySelectorAll(".dev3-column").forEach(cell => {
                                        cell.style.display = isChecked ? '' : 'none';
                                    });
                                }
                            </script>

                            {{-- ——— SCRIPT : toggle TEST ——— --}}
                            <script>
                                function toggleTest() {
                                    const checkbox = document.getElementById("optionTEST");
                                    const isChecked = checkbox.checked;
                                    document.querySelectorAll(".test-column").forEach(cell => {
                                        cell.style.display = isChecked ? '' : 'none';
                                    });
                                }
                            </script>


                            {{-- <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                // Sélection de tous les inputs de notes
                                const noteInputs = Array.from(document.querySelectorAll('.interro-input, .dev-input, .test-input'));

                                // Fonction pour trouver l'input suivant non readonly
                                function findNextEditableInput(currentInput) {
                                const currentIndex = noteInputs.indexOf(currentInput);
                                for (let i = currentIndex + 1; i < noteInputs.length; i++) {
                                    const next = noteInputs[i];
                                    if (!next.hasAttribute('readonly') && !next.disabled) {
                                    return next;
                                    }
                                }
                                return null;
                                }

                                noteInputs.forEach(input => {
                                // On limite visuellement la saisie, même si la troncature est gérée en JS
                                input.setAttribute('maxlength', '6');
                                // Événement à chaque frappe
                                input.addEventListener('input', () => {
                                    // On ne garde que les chiffres
                                    let raw = input.value.replace(/\D/g, '');

                                    // Si l'utilisateur tape plus de 4 chiffres, on ne garde que les 4 premiers
                                    if (raw.length > 4) {
                                    raw = raw.slice(0, 4);
                                    }

                                    if (raw.length === 4) {
                                    // Conversion en nombre / 100, bornage entre 0 et 20
                                    let num = parseInt(raw, 10) / 100;
                                    if (num > 20) num = 20;
                                    if (num < 0) num = 0;
                                    // Formatage à deux décimales
                                    input.value = num.toFixed(2);

                                    // Recalcul des moyennes (à adapter à votre logique)
                                    calculateMIAndMoy(input);

                                    // Passage automatique à l'input suivant non readonly
                                    const next = findNextEditableInput(input);
                                    if (next) {
                                        next.focus();
                                    }
                                    } else {
                                    // Tant qu'on n'a pas 4 chiffres, on affiche juste les chiffres saisis
                                    input.value = raw;
                                    }
                                });

                                // Au blur : on vérifie, on borne et on formate à xx.xx
                                input.addEventListener('blur', () => {
                                    let val = parseFloat(input.value.replace(',', '.'));
                                    if (isNaN(val)) {
                                    input.value = '';
                                    calculateMIAndMoy(input);
                                    return;
                                    }
                                    if (val > 20) val = 20;
                                    if (val < 0) val = 0;
                                    input.value = val.toFixed(2);
                                    calculateMIAndMoy(input);
                                });
                                });
                            });

                            </script> --}}


                            <script>
                                // Au moment où le modal s’ouvre, on copie les valeurs sélectionnées dans les <select>
                                var exampleModal = document.getElementById('exampleModal');
                                exampleModal.addEventListener('show.bs.modal', function(event) {
                                    var valeurClasse = document.getElementById('tableSelect4').value;
                                    var valeurMatiere = document.getElementById('tableSelect5').value;
                                    var valeurPeriode = document.getElementById('periodSelect').value;

                                    document.getElementById('modalClasse').value = valeurClasse;
                                    document.getElementById('modalMatiere').value = valeurMatiere;
                                    document.getElementById('modalSemestre').value = valeurPeriode;
                                });
                            </script>
                    <style>
                        /* Améliore l'affichage des champs de saisie */
                        .table thead th,
                        .table tbody td {
                            text-align: center;
                            vertical-align: middle;
                        }

                        .form-control-sm {
                            width: 100%;
                            padding: 0px;
                            text-align: center;
                            border: 1px solid #ddd;
                        }

                        /* Ajustement des marges dans les cellules */
                        td {
                            padding: 0px;
                        }

                        .fixed-input {
                            width: 50px;
                            /* Fixe la largeur */
                            height: 30px;
                            /* Fixe la hauteur */
                        }

                        /* Style pour les colonnes fixes */
                        .table-responsive {
                            position: relative;
                            overflow-x: auto;
                        }

                        .table thead th:first-child,
                        .table tbody td:first-child {
                            position: sticky;
                            left: 0;
                            background-color: #f8f9fa;
                            z-index: 1;
                        }

                        .table thead th:nth-child(2),
                        .table tbody td:nth-child(2) {
                            position: sticky;
                            left: 100px; /* Ajustez cette valeur selon la largeur de la première colonne */
                            background-color: #f8f9fa;
                            z-index: 1;
                        }

                        /* Assure que les colonnes fixes restent au-dessus des autres */
                        .table thead th:first-child,
                        .table thead th:nth-child(2) {
                            z-index: 2;
                        }
                    </style>

                    
            <script>
                // Synchronise la période sélectionnée avec le modal Permuter
                document.addEventListener('DOMContentLoaded', function() {
                    var permuterModal = document.getElementById('permuterModal');
                    if (permuterModal) {
                        permuterModal.addEventListener('show.bs.modal', function() {
                            var selectedPeriod = document.getElementById('periodSelect').value;
                            var periodeSource = document.getElementById('periode_source_affiche');
                            if (periodeSource && selectedPeriod) {
                                periodeSource.value = selectedPeriod;
                            } else if (periodeSource) {
                                periodeSource.value = '';
                            }
                            // Synchronise les champs cachés pour l'envoi du formulaire
                            document.getElementById('hidden_CODECLAS').value = document.getElementById(
                                'tableSelect4').value;
                            document.getElementById('hidden_CODEMAT').value = document.getElementById(
                                'tableSelect5').value;
                            document.getElementById('hidden_periode_source_affiche').value = periodeSource.value;
                        });
                    }
                });
            </script>

                @endsection
