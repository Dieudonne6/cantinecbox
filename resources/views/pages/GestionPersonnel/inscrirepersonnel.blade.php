@extends('layouts.master')

@section('content')
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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>

                <script>
                    // Optionnel : fermer automatiquement après 3 secondes
                    setTimeout(() => {
                        const alert = document.querySelector('.alert');
                        if(alert) alert.remove();
                    }, 3000);
                </script>
            @endif
            <div class="container-fluid">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">

                        <h3 class="mb-4 text-primary fw-bold">
                            <i class="bi bi-person-badge-fill me-2"></i> Enrégistrement d'un agent
                        </h3>

                        @if(isset($agentData))
                            <form action="{{ route('agents.update', $agentData->MATRICULE) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                            @else
                            <form action="{{ route('agents.store') }}" method="POST" enctype="multipart/form-data">
                        @endif
                            @csrf
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6 form-floating">
                                        <select name="LibelTypeAgent" class="form-select rounded-3">
                                            <option value=""></option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->LibelTypeAgent }}"
                                                    {{ old('LibelTypeAgent', $agentData->LibelTypeAgent ?? '') == $agent->LibelTypeAgent ? 'selected' : '' }}>
                                                    {{ $agent->LibelTypeAgent }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label>Type d'agent</label>                                  
                                    </div>
                                    <div class="col-md-4 form-floating">
                                        <input type="text" name="matricule" id="matricule" class="form-control rounded-3"
                                            value="{{ old('matricule', $agentData->MATRICULE ?? '') }}"
                                            @if(isset($agentData)) data-edit="1" readonly @endif>
                                        <label>Matricule</label>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center">
                                        <div class="form-check mt-3">
                                            <input type="checkbox" class="form-check-input" id="auto" name="auto" checked>
                                            <label for="auto" class="form-check-label">Auto</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- IFU / CNSS --}}
                                <div class="row g-3 mb-3">                               
                                    <div class="col-md-6 form-floating">
                                        <input type="text" name="ifu" class="form-control rounded-3"
                                            value="{{ old('ifu', $agentData->IFU ?? '') }}" required>
                                        <label>IFU</label>
                                    </div>
                                    <div class="col-md-6 form-floating">
                                        <input type="text" name="cnss" class="form-control rounded-3"
                                            value="{{ old('cnss', $agentData->NUMCNSS ?? '') }}" required>
                                        <label>CNSS</label>
                                    </div>
                                </div>

                                {{-- Nom / Prénom / Date naissance / Lieu --}}
                                <div class="row g-3 mb-3">
                                    <div class="col-md-3 form-floating">
                                        <input type="text" name="nom" class="form-control rounded-3"
                                            value="{{ old('nom', $agentData->NOM ?? '') }}" required>
                                        <label>Nom</label>
                                    </div>
                                    <div class="col-md-3 form-floating">
                                        <input type="text" name="prenom" class="form-control rounded-3"
                                            value="{{ old('prenom', $agentData->PRENOM ?? '') }}" required>
                                        <label>Prénom</label>
                                    </div>
                                    <div class="col-md-3 form-floating">
                                        <input type="date" name="date_naissance" class="form-control rounded-3"
                                            value="{{ old('date_naissance', $agentData->DATENAIS ?? '') }}" required>
                                        <label>Date de naissance</label>
                                    </div>
                                    <div class="col-md-3 form-floating">
                                        <input type="text" name="lieu" class="form-control rounded-3"
                                            value="{{ old('lieu', $agentData->LIEUNAIS ?? '') }}" required>
                                        <label>Lieu</label>
                                    </div>
                                </div>

                                {{-- Nationalité / Sexe / Matrimoniale / Nb enfants --}}
                                <div class="row g-3 mb-3">
                                    <div class="col-md-3 form-floating">
                                        <input type="text" name="nationalite" class="form-control rounded-3"
                                            value="{{ old('nationalite', $agentData->NATION ?? '') }}" required>
                                        <label>Nationalité</label>
                                    </div>
                                    <div class="col-md-3 form-floating">
                                        <select name="sexe" class="form-select rounded-3" required>
                                            <option value=""></option>
                                            <option value="0" {{ old('sexe', $agentData->SEXE ?? '') == "0" ? 'selected' : '' }}>Masculin</option>
                                            <option value="1" {{ old('sexe', $agentData->SEXE ?? '') == "1" ? 'selected' : '' }}>Féminin</option>
                                        </select>
                                        <label>Sexe</label>
                                    </div>
                                    <div class="col-md-3 form-floating">
                                        <select name="matrimoniale" class="form-select rounded-3" required>
                                            <option value=""></option>
                                            <option value="1" {{ old('matrimoniale', $agentData->MATRIMONIALE ?? '') == "1" ? 'selected' : '' }}>Célibataire</option>
                                            <option value="2" {{ old('matrimoniale', $agentData->MATRIMONIALE ?? '') == "2" ? 'selected' : '' }}>Marié</option>
                                            <option value="3" {{ old('matrimoniale', $agentData->MATRIMONIALE ?? '') == "3" ? 'selected' : '' }}>Divorcé</option>
                                            <option value="4" {{ old('matrimoniale', $agentData->MATRIMONIALE ?? '') == "4" ? 'selected' : '' }}>Veuf</option>
                                        </select>
                                        <label>Situation matrimoniale</label>
                                    </div>
                                    <div class="col-md-3 form-floating">
                                        <input type="number" name="nb_enfants" class="form-control rounded-3"
                                            value="{{ old('nb_enfants', $agentData->NBENF ?? '') }}" min="0" required>
                                        <label>Nombre d’enfants</label>
                                    </div>
                                </div>

                                {{-- Poste / Grade / Date entrée / Téléphone --}}
                                <div class="row g-3 mb-3">
                                    <div class="col-md-3 form-floating">
                                        <select id="poste_occupe" name="poste_occupe" class="form-select rounded-3" required>
                                            <option value=""></option>
                                            <option value="Enseignant" {{ old('poste_occupe', $agentData->POSTE ?? '') == "Enseignant" ? 'selected' : '' }}>Enseignant</option>
                                            <option value="Autres" {{ old('poste_occupe', $agentData->POSTE ?? '') == "Autres" ? 'selected' : '' }}>Autres</option>
                                        </select>
                                        <label>Poste Occupé</label>
                                    </div>
                                    <div class="col-md-3 form-floating">
                                        <input type="text" name="grade" class="form-control rounded-3"
                                            value="{{ old('grade', $agentData->GRADE ?? '') }}" required>
                                        <label>Grade</label>
                                    </div>
                                    <div class="col-md-3 form-floating">
                                        <input type="date" name="date_entree" class="form-control rounded-3"
                                            value="{{ old('date_entree', $agentData->DATEENT ?? '') }}" required>
                                        <label>Date d'entrée en service</label>
                                    </div>
                                    <div class="col-md-3 form-floating">
                                        <input type="text" name="telephone" class="form-control rounded-3"
                                            value="{{ old('telephone', $agentData->TelAgent ?? '') }}" required>
                                        <label>Téléphone</label>
                                    </div>
                                </div>

                                {{-- Diplômes et photo --}}
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4 form-floating">
                                        <input type="text" name="diplome_academique" class="form-control rounded-3"
                                            value="{{ old('diplome_academique', $agentData->DIPLOMEAC ?? '') }}" required>
                                        <label>Diplôme Académique</label>
                                    </div>
                                    <div class="col-md-4 form-floating">
                                        <input type="text" name="diplome_professionnel" class="form-control rounded-3"
                                            value="{{ old('diplome_professionnel', $agentData->DIPLOMEPRO ?? '') }}" required>
                                        <label>Diplôme Professionnel</label>
                                    </div>
                                    <div class="col-md-4 form-floating">
                                        <input type="file" name="photo" class="form-control rounded-3" accept="image/*">
                                        <label for="photo">Photo</label>
                                    </div>
                                </div>

                                {{-- Bloc responsabilités (matières déjà préremplies plus bas) --}}
                                <div id="responsabilites" style="display:none;">
                                <hr class="my-4">
                                <h5 class="fw-bold text-secondary mb-3">Responsabilités (Matières)</h5>

                                {{-- Classe principale et Cycle --}}
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4 form-floating">
                                        <select name="principal_classe" class="form-select rounded-3" id="principal_classe">
                                            <option value="">Sélectionner la classe</option>
                                            @foreach($classes as $classe)
                                                <option value="{{ $classe->CODECLAS }}"
                                                    {{ old('principal_classe', $agentData->CODECLAS ?? '') == $classe->CODECLAS ? 'selected' : '' }}>
                                                    {{ $classe->CODECLAS }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="principal_classe">Principal de la classe</label>
                                    </div>

                                    <div class="col-md-4 form-floating">
                                        <select name="cycle" class="form-select rounded-3">
                                            <option value=""></option>
                                            <option value="1" {{ old('cycle', $agentData->CYCLES ?? '') == 1 ? 'selected' : '' }}>Cycle 1</option>
                                            <option value="2" {{ old('cycle', $agentData->CYCLES ?? '') == 2 ? 'selected' : '' }}>Cycle 2</option>
                                            <option value="0" {{ old('cycle', $agentData->CYCLES ?? '') == 0 ? 'selected' : '' }}>Cycle 1 & 2</option>
                                        </select>
                                        <label>Cycle tenu</label>
                                    </div>
                                </div>

                                {{-- Tableau des matières --}}
                                <table class="table table-bordered" id="matiereTable">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Libellé</th>
                                            <th>Nom court</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="code[]" class="form-control code" readonly></td>
                                            <td><input type="text" name="libelle[]" class="form-control libelle" readonly></td>
                                            <td>
                                                <select name="nomcourt[]" class="form-select nomcourt">
                                                    <option value="">-- Choisir --</option>
                                                    @foreach($matieres as $matiere)
                                                        <option value="{{ $matiere->NOMCOURT }}" 
                                                                data-code="{{ $matiere->CODEMAT }}" 
                                                                data-libel="{{ $matiere->LIBELMAT }}"
                                                                {{ in_array($matiere->CODEMAT, $selectedCodes ?? []) ? 'selected' : '' }}>
                                                            {{ $matiere->NOMCOURT }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success addRow">+</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Rémunération --}}
                            <hr class="my-4">
                            <h5 class="fw-bold text-secondary mb-3">Données sur la rémunération</h5>
                            <div class="row g-3 mb-3">
                                <div class="col-md-3 form-floating">
                                    <select name="profil" class="form-select rounded-3" id="profil" required>
                                        <option value="">--  --</option>
                                        @foreach($profils as $profil)
                                            <option value="{{ $profil->Numeroprofil }}"
                                                {{ old('profil', $agentData->PROFIL ?? '') == $profil->Numeroprofil ? 'selected' : '' }}>
                                                {{ $profil->NomProfil }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="profil">Profil</label>
                                </div>
                                <div class="col-md-3 form-floating">
                                    <input type="text" name="banque" class="form-control rounded-3"
                                        value="{{ old('banque', $agentData->CBanque ?? '') }}">
                                    <label>Domiciliation (banque)</label>
                                </div>
                                <div class="col-md-3 form-floating">
                                    <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#profilModal">
                                        <i class="typcn typcn-plus btn-icon-prepend"></i> Nouveau profil
                                    </a>
                                </div>
                                <div class="col-md-3 form-floating">
                                    <a class="btn btn-primary btn-sm" href="{{ route('confTauxH')}}">
                                        Configuration
                                    </a>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm me-2">
                                    <i class="bi bi-save me-1"></i> Enregistrer
                                </button>
                                <button type="reset" class="btn btn-outline-secondary px-4 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-x-circle me-1"></i> Annuler
                                </button>
                                <br><br><br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>        
        </div>
    </div>



    {{-- Modal pour nouveaux profil--}}
    <div class="modal fade " id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="false" style="width: 50%; background-color: white; margin-left: 25%;">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold" id="profilModalLabel">Créer un profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('profils.store') }}">
                     @csrf
                    {{-- Nom du profil --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-12 form-floating">
                            <input type="text" class="form-control rounded-3" name="NomProfil" id="nom_profil" required>
                            <label for="nom_profil">Nom du profil</label>
                        </div>
                    </div>

                    {{-- Salaire, Nb heures, Type imposition --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6 form-floating">
                            <input type="number" class="form-control rounded-3" name="SalaireBase" id="salaire_base" min="0" required>
                            <label for="salaire_base">Salaire de base</label>
                        </div>
                        <div class="col-md-6 form-floating">
                            <input type="number" class="form-control rounded-3" name="NbHeuresDu" id="nb_heures" min="0" >
                            <label for="nb_heures">Nb heure du</label>
                        </div>                    
                    </div>

                   <div class="row g-3 mb-3">
                        <div class="col-md-12 form-floating">
                            <select class="form-select rounded-3" name="TypeImpot" id="type_imposition" required>
                                <option value="">--  --</option>
                                <option value="Aucun">Aucun</option>
                                <option value="Normal">Normal</option>
                                <option value="Prélèvement">Prélèvement</option>
                            </select>
                            <label for="type_imposition">Type d’imposition</label>
                        </div>
                    </div>

                    {{-- Case à cocher --}}
                    <div class="form-check ">
                        <input type="checkbox" class="form-check-input" name="CalculerCnss" id="calcul_cnss" value="1" style="margin-left: 0.8rem;">
                        <label class="form-check-label fw-semibold" for="calcul_cnss" >Calculer CNSS</label>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4 form-floating">
                            <input type="number" class="form-control rounded-3" id="tauxheuresup" min="0">
                            <label for="tauxheuresup">Taux heure Sup </label>
                        </div>
                        <div class="col-md-4 form-floating">
                            <input type="number" class="form-control rounded-3" id="tauxheuresupuniq" min="0" >
                            <label for="tauxheuresupuniq">T heure Sup Unique</label>
                        </div>  
                        <div class="col-md-4 form-floating">
                            <input type="number" class="form-control rounded-3" id="nbheure" min="0" >
                            <label for="nbheure">Nb d'heures</label>
                        </div>                    
                    </div>


                    {{-- Tableau primes/retenues --}}
                    <label style="font-size: 10px;">
                    Cocher les primes et retenues applicables à ce profil 
                    <a class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#primeModal" style="margin-left: 2rem;">
                        Créer Prime/retenus
                    </a>
                    </label>

                    <div class="d-flex justify-content-center">
                        <table class="table table-bordered table-hover" >
                            <thead class="table-primary text-center">
                                <tr>
                                    <th></th>
                                    <th>Libellé</th>
                                    <th>Montant</th>
                                    <th>%</th>
                                    <th>Base</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($primes as $prime)
                                    <tr>
                                        <td class="text-center">
                                            <input style="margin-left: 0.1rem;" type="checkbox" class="form-check-input" name="primes[]" value="{{ $prime->CODEPR }}">
                                        </td>
                                        <td>{{ $prime->LIBELPR }}</td>
                                        <td>
                                        <input type="number" name="montantfixe[{{ $prime->CODEPR }}]" 
                                            class="form-control rounded-2"
                                            style="min-width: 100px;" 
                                            value="{{ $prime->MONTANTFIXE }}" min="0" >
                                    </td>
                                    <td>
                                        <input type="number" name="montantvar[{{ $prime->CODEPR }}]" 
                                            class="form-control rounded-2"
                                            style="min-width: 90px;" 
                                            value="{{ $prime->MONTANTVAR }}" min="0" max="100" >
                                    </td>
                                    <td>
                                        <select name="base[{{ $prime->CODEPR }}]" 
                                            class="form-select rounded-2" 
                                            style="min-width: 120px;"> 
                                            <option value="XX">Aucune</option>
                                            <option value="SB" >SAL_BASE</option>
                                            <option value="ST" >SAL_BASE + Prime</option>
                                        </select>
                                    </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Aucune prime</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>

            </div>
        </div>
    </div>

    {{-- Modal pour créer une prime --}}
    <div class="modal fade" id="primeModal" tabindex="-1" aria-labelledby="primeModalLabel" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-3 shadow-lg">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title fw-bold" id="primeModalLabel">Fiche TPRIMES</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('prime.store') }}" method="POST">
                        @csrf
                        <p class="text-danger small fw-semibold">
                            Les rubriques IPTS, CNSS, Avance/Acompte, Prêts sont pris en compte automatiquement
                        </p>

                       {{-- Code et Intitulé --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-4 form-floating">
                                <input type="text" class="form-control rounded-3" 
                                    id="code_rubrique" name="code_rubrique" maxlength="4">
                                <label for="code_rubrique">Code rubrique</label>
                                <small class="text-muted">4 caractères au plus</small>
                            </div>
                            <div class="col-md-8 form-floating">
                                <input type="text" class="form-control rounded-3" 
                                    id="intitule_rubrique" name="intitule_rubrique">
                                <label for="intitule_rubrique">Intitulé rubrique</label>
                                <small class="text-muted">Ex: prime de suggestion, Indemnité de logement</small>
                            </div>
                        </div>

                        {{-- Type --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-12 form-floating">
                                <select class="form-select rounded-3" id="type_prime" name="type_prime">
                                    <option value=""></option>
                                    <option>PRIME IMPOSABLE</option>
                                    <option>PRIME NON IMPOSABLE</option>
                                    <option>RETENUE</option>
                                </select>
                                <label for="type_prime">Type</label>
                            </div>
                        </div>

                        {{-- Montants --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6 form-floating">
                                <input type="number" class="form-control rounded-3" 
                                    id="montant_fixe" name="montant_fixe" min="0">
                                <label for="montant_fixe">Montant fixe</label>
                            </div>
                            <div class="col-md-6 form-floating">
                                <input type="number" class="form-control rounded-3" 
                                    id="montant_variable" name="montant_variable" min="0" max="100">
                                <label for="montant_variable">% Montant variable</label>
                                <small class="text-muted">Pourcentage à appliquer pour la partie variable</small>
                            </div>
                        </div>

                        {{-- Base variable --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-12 form-floating">
                                <select class="form-select rounded-3" id="base_variable" name="base_variable">
                                    <option value=""></option>
                                    <option>SALAIRE BASE</option>
                                    <option>SALAIRE BASE + Prime</option>
                                    <option>Aucune</option>
                                </select>
                                <label for="base_variable">Base variable</label>
                                <small class="text-muted">(Sur quelle base appliquer la partie variable)</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Valider</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>                    
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const auto = document.getElementById("auto");
            const matricule = document.getElementById("matricule");

            function toggleMatricule() {
                if (auto.checked) {
                    matricule.setAttribute("readonly", true);

                    // ⚠️ On efface seulement si on est en création (pas d'agentData)
                    if (!matricule.dataset.edit) {
                        matricule.value = ""; 
                    }
                } else {
                    matricule.removeAttribute("readonly");
                }
            }

            auto.addEventListener("change", toggleMatricule);
            toggleMatricule();
        });

    </script>

    

   <script>
        $(function(){
            // afficher responsibilities si poste = Enseignant au chargement
            function toggleResp() {
                if ($('#poste_occupe').val() === 'Enseignant') $('#responsabilites').show();
                else $('#responsabilites').hide();
            }
            toggleResp();
            $('#poste_occupe').on('change', toggleResp);

            // remplir code/libel quand on change nomcourt
            $(document).on('change', '.nomcourt', function(){
                var $sel = $(this);
                var code = $sel.find('option:selected').data('code') || '';
                var libel = $sel.find('option:selected').data('libel') || '';
                var $tr = $sel.closest('tr');
                $tr.find('.code').val(code);
                $tr.find('.libelle').val(libel);
            });

            // si on a déjà des selects sélectionnés (édition), déclencher change pour remplir les champs
            $('.nomcourt').each(function(){ $(this).trigger('change'); });

            // add row
            $(document).on('click', '.addRow', function(){
                var $tr = $(this).closest('tr');
                var $clone = $tr.clone();
                // nettoyer valeurs
                $clone.find('input').val('');
                $clone.find('select').val('');
                // transformer bouton + en - pour suppression
                $clone.find('.addRow')
                    .removeClass('btn-success addRow')
                    .addClass('btn-danger removeRow')
                    .text('-');
                $tr.after($clone);
            });

            // remove row
            $(document).on('click', '.removeRow', function(){
                $(this).closest('tr').remove();
            });
        });
    </script>


    
    <script> 
        document.addEventListener('DOMContentLoaded', function () {
            // Remplir automatiquement Code et Libellé selon Nom Court
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('nomcourt')) {
                    let option = e.target.selectedOptions[0];
                    let row = e.target.closest('tr');
                    row.querySelector('.code').value = option.getAttribute('data-code') || '';
                    row.querySelector('.libelle').value = option.getAttribute('data-libel') || '';
                }
            });

            // // Gestion ajout / suppression lignes
            // document.addEventListener('click', function(e) {
            //     // Ajouter
            //     if (e.target.classList.contains('addRow')) {
            //         let table = document.querySelector('#matiereTable tbody');
            //         let newRow = table.rows[0].cloneNode(true);

            //         // vider les champs
            //         newRow.querySelector('.code').value = '';
            //         newRow.querySelector('.libelle').value = '';
            //         newRow.querySelector('.nomcourt').selectedIndex = 0;

            //         // bouton transformer en -
            //         let btn = newRow.querySelector('.addRow');
            //         btn.classList.remove('btn-success', 'addRow');
            //         btn.classList.add('btn-danger', 'removeRow');
            //         btn.textContent = '-';

            //         table.appendChild(newRow);
            //     }

            //     // Supprimer
            //     if (e.target.classList.contains('removeRow')) {
            //         e.target.closest('tr').remove();
            //     }
            // });
        });
    </script>

 
@endsection