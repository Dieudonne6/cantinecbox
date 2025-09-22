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
            <div class="container-fluid">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">

                    <h3 class="mb-4 text-primary fw-bold">
                        <i class="bi bi-person-badge-fill me-2"></i> Enrégistrement d'un agent
                    </h3>

                    <form action="#" method="POST" enctype="multipart/form-data">

                        {{-- Ligne matricule, IFU, CNSS --}}
                        <div class="row g-3 mb-3">
                        <div class="col-md-3 form-floating">
                            <input type="text" name="matricule" class="form-control rounded-3" placeholder=" ">
                            <label>Matricule</label>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" id="auto" name="auto" checked>
                            <label for="auto" class="form-check-label">Auto</label>
                            </div>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="text" name="ifu" class="form-control rounded-3" placeholder=" ">
                            <label>IFU</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="text" name="cnss" class="form-control rounded-3" placeholder=" ">
                            <label>CNSS</label>
                        </div>
                        </div>

                        {{-- Nom, Prénom, Date naissance, Lieu --}}
                        <div class="row g-3 mb-3">
                        <div class="col-md-3 form-floating">
                            <input type="text" name="nom" class="form-control rounded-3" placeholder=" ">
                            <label>Nom</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="text" name="prenom" class="form-control rounded-3" placeholder=" ">
                            <label>Prénom</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="date" name="date_naissance" class="form-control rounded-3" placeholder=" ">
                            <label>Date de naissance</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="text" name="lieu" class="form-control rounded-3" placeholder=" ">
                            <label>Lieu</label>
                        </div>
                        </div>

                        {{-- Nationalité, Sexe, Situation matrimoniale --}}
                        <div class="row g-3 mb-3">
                        <div class="col-md-3 form-floating">
                            <input type="text" name="nationalite" class="form-control rounded-3" placeholder=" ">
                            <label>Nationalité</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <select name="sexe" class="form-select rounded-3">
                            <option value=""></option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                            </select>
                            <label>Sexe</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <select name="matrimoniale" class="form-select rounded-3">
                            <option value=""></option>
                            <option value="Célibataire">Célibataire</option>
                            <option value="Marié">Marié</option>
                            </select>
                            <label>Situation matrimoniale</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="number" name="nb_enfants" class="form-control rounded-3" placeholder=" " min="0">
                            <label>Nombre d’enfants</label>
                        </div>
                        </div>

                        {{-- Grade, Indice, Téléphone --}}
                        <div class="row g-3 mb-3">
                        <div class="col-md-3 form-floating">
                                <select name="matrimoniale" class="form-select rounded-3">
                                <option value=""></option>
                                <option value="Célibataire">Enseignant</option>
                                <option value="Marié">Autres</option>
                                </select>
                                <label>Poste Occupé</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="text" name="grade" class="form-control rounded-3" placeholder=" ">
                            <label>Grade</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="date" name="date_naissance" class="form-control rounded-3" placeholder=" ">
                            <label>Date d'entrée en service</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="text" name="telephone" class="form-control rounded-3" placeholder=" ">
                            <label>Téléphone</label>
                        </div>
                        </div>

                        <div class="row g-3 mb-3">
                        <div class="col-md-3 form-floating">
                            <input type="text" name="grade" class="form-control rounded-3" placeholder=" ">
                            <label>Diplôme Académique</label>
                        </div>
                        <div class="col-md-3 form-floating">
                            <input type="text" name="grade" class="form-control rounded-3" placeholder=" ">
                            <label>Diplôme Professionnel</label>
                        </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="fw-bold text-secondary mb-3">Responsabilités au niveau de l’établissement</h5>

                        <div class="row g-3 mb-3">
                        <div class="col-md-4 form-floating">
                            <input type="text" name="principal_classe" class="form-control rounded-3" placeholder=" ">
                            <label>Principal de la classe</label>
                        </div>
                        <div class="col-md-4 form-floating">
                            <select name="cycle" class="form-select rounded-3">
                            <option value=""></option>
                            <option value="Cycle 1">Cycle 1</option>
                            <option value="Cycle 2">Cycle 2</option>
                            </select>
                            <label>Cycle tenu</label>
                        </div>
                        </div>

                        {{-- Matières enseignées --}}
                        <div class="mb-3">
                        <label class="fw-semibold">Matières enseignées</label>
                        <table class="table table-bordered table-hover align-middle text-center shadow-sm">
                            <thead class="table-primary">
                            <tr>
                                <th>Code</th>
                                <th>Nom Court</th>
                                <th>Libellé Matière</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" name="code[]" class="form-control rounded-3" ></td>
                                <td><input type="text" name="nomcourt[]" class="form-control rounded-3" ></td>
                                <td><input type="text" name="libelle_matiere[]" class="form-control rounded-3" ></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>

                        <hr class="my-4">
                        <h5 class="fw-bold text-secondary mb-3">Données sur la rémunération</h5>

                        <div class="row g-3 mb-3">
                        <div class="col-md-4 form-floating">
                            <input type="text" name="profil" class="form-control rounded-3" >
                            <label>Profil</label>
                        </div>
                        <div class="col-md-4 form-floating">
                            <input type="text" name="banque" class="form-control rounded-3" >
                            <label>Domiciliation (banque)</label>
                        </div>
                        </div>

                        <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm me-2">
                            <i class="bi bi-save me-1"></i> Enregistrer
                        </button>
                        <button type="reset" class="btn btn-outline-secondary px-4 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-x-circle me-1"></i> Fermer
                        </button>
                        </div>
                        <br><br>
                    </form>
                    </div>
                </div>
            </div>
          
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   
@endsection