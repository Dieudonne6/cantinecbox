@extends('layouts.master')

@section('content')
<div class="container">
  <div class="row">
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
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Informations générales</button>
            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Informations complémentaires</button>
          </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
          @if (Session::has('status'))
          <div id="statusAlert" class="alert alert-success btn-primary">
            {{ Session::get('status') }}
          </div>
          @endif
          {{-- erreur concernant l'inscription d'un eleve --}}
          @if($errors->any())
          <div id="statusAlert" class="alert alert-danger">
              <ul>
                  @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
          @endif
          <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <!-- Formulaire Classe -->
                  <form  action="{{ url('/nouveaueleve') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row mt-1">
                      <label for="classe" class="col-sm-2 col-form-label">Classe</label>
                      <div class="col-sm-3">
                        <select class="js-example-basic-multiple w-100" id="classe" name="classe" value="{{ old('classe') }}">
                          @foreach ($allClasse as $classe)
                          <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                          @endforeach
                        </select>
                      </div>
                      <label for="classe-entree" class="col-sm-3 col-form-label">Classe d'entrée collège</label>
                      <div class="col-sm-3">
                        <select class="js-example-basic-multiple w-100 select2-hidden-accessible" id="classe-entree" name="classeEntre" value="{{ old('classeEntre') }}">
                          <option value="idem">idem</option>
                          @foreach ($allClasse as $classe)
                          <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row mt-3">
                      <label for="numero-ordre" class="col-sm-2 col-form-label">Numéro d'ordre</label>
                      <div class="col-sm-3">
                        <input class="form-control" type="text" id="numero-ordre" name="numOrdre" value="{{ $newMatricule }}"  readonly>
                      </div>
                      <div class="col-sm-3">
                        <button type="button" class="btn btn-secondary">Classe précédente</button>
                      </div>
                      {{-- <div class="col-sm-4 text-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          Données financières (Factures)
                        </button>
                      </div> --}}
                    </div>
                    {{-- </form> --}}
                    
                    <hr>
                    <h4 class="card-title mt-3">Informations personnelles</h4>
                    {{-- <form> --}}
                      <!-- Section Photo -->
                      <div class="form-group row">
                        <div class="col-md-4">
                          <label for="photo">Photo</label>
                          <input type="file" id="photo" name="photo" class="form-control">
                        </div>

                        <div class="col-md-4">
                          <label for="matricule">Matricule</label>
                          <input type="text" id="matricule" class="form-control" value="AUTO" readonly>
                        </div>
                        <div class="col-md-4 d-flex align-items-center" style="margin-top: 2rem">
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                            Vérifier archives
                          </button>
                        </div>
                      {{--   <div class="col-md-4">
                          <label for="profil-reduction">Profil de réduction</label>
                          <select id="profil-reduction" name="reduction" class="js-example-basic-multiple w-100" value="{{ old('reduction') }}">
                            @foreach ($allReduction as $reduction)
                            <option value="{{ $reduction->CodeReduction }}">{{ $reduction->LibelleReduction }}</option>
                            @endforeach
                            {{-- <option value="plein-tarif">Plein Tarif</option>
                            <option value="fils-enseignant">Fils d'enseignant</option>
                          </select>
                        </div> --}}
                      </div>
                      
                      <!-- Section Informations Personnelles -->
                      <div class="form-group row mt-3">
                        <div class="col-md-4">
                          <label for="nom">Nom</label>
                          <input type="text" id="nom" name="nom" class="form-control" value="{{ old('nom') }}">
                        </div>
                        <div class="col-md-4">
                          <label for="prenom">Prénom</label>
                          <input type="text" id="prenom" name="prenom" class="form-control" value="{{ old('prenom') }}">
                        </div>
                        <div class="col-md-4">
                          <label for="date-naissance">Date de naissance</label>
                          <input type="date" id="date-naissance" name="dateNaissance" class="form-control" value="{{ old('dateNaissance') }}">
                        </div>
                      </div>
                      
                      <div class="form-group row mt-3">
                        <div class="col-md-4">
                          <label for="lieu-naissance">Lieu de naissance</label>
                          <input type="text" id="lieu-naissance" name="lieuNaissance" class="form-control" value="{{ old('lieuNaissance') }}">
                        </div>
                        <div class="col-md-4">
                          <label for="date-inscription">Date d'inscription</label>
                          <input type="date" id="date-inscription" name="dateInscription" class="form-control" value="{{ old('dateInscription') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="departement">Département</label>
                            <select class="js-example-basic-multiple w-100" id="departement" name="departement">
                                <option value=""></option>
                                @foreach($departements as $dep)
                                    <option value="{{ $dep->CODEDEPT }}">{{ $dep->LIBELDEP }}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                      
                      <!-- Section Détails Additionnels -->
                      <div class="form-group row mt-3">
                        <div class="col-md-4">
                          <label for="sexe">Sexe</label>
                          <select id="sexe" name="sexe" class="js-example-basic-multiple w-100" value="{{ old('sexe') }}">
                            {{-- <option  >Sélectionner</option> --}}
                            <option value="1">Masculin</option>
                            <option value="2">Féminin</option>
                          </select>
                        </div>
                        <div class="col-md-4">
                          <label for="type-eleve">Type d'élève</label>
                          <select id="type-eleve" name="typeEleve" class="js-example-basic-multiple w-100"  value="{{ old('typeEleve') }}">
                            {{-- <option  >Sélectionner</option> --}}
                            <option value="1" selected>Nouveau</option>
                            <option value="2">Ancien</option>
                            {{-- <option value="3">Transferer</option> --}}
                          </select>
                        </div>
                        <div class="col-md-4">
                          <label for="aptitude-sport">Aptitude Sport</label>
                          <select id="aptitude-sport" name="aptituteSport" class="js-example-basic-multiple w-100" value="{{ old('aptituteSport') }}">
                            {{-- <option  >Sélectionner</option> --}}
                            <option value="1">Apte</option>
                            <option value="2">Inapte</option>
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group row mt-3">
                        <div class="col-md-4">
                          <label for="adresse-personnelle">Adresse personnelle</label>
                          <input type="text" id="adresse-personnelle" placeholder="Elève au ScoDelux" name="adressePersonnelle" class="form-control" value="{{ old('adressePersonnelle') }}">
                        </div>
                        <div class="col-md-4">
                          <label for="etablissement-origine">Etablissement d'origine</label>
                          <input type="text" id="etablissement-origine" name="etablissementOrigine" class="form-control" value="{{ old('etablissementOrigine') }}">
                        </div>
                        <div class="col-md-4">
                          <label for="nationalite">Nationalité</label>
                          <input type="text" id="nationalite" name="nationalite" class="form-control" value="{{ old('nationalite') }}">
                        </div>
                      </div>
                      
                      <!-- Section Redoublant -->
                      <div class="form-group row">
                        <div class="col-md-4">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="redoublant" id="redoublant" value="1" style="margin-left: 0.30rem;" value="{{ old('redoublant') }}">
                            <label class="form-check-label" for="redoublant">Cocher si c'est un redoublant</label>
                          </div>
                        </div>
                      </div>
                      
                      <hr>
                      <h4 class="card-title mt-3">Filiation</h4>
                      
                      <!-- Section Filiation -->
                      <div class="form-group row">
                        <div class="col-md-6">
                          <label for="nom-pere">Nom du père</label>
                          <input type="text" id="nom-pere" name="nomPere" class="form-control" value="{{ old('nomPere') }}">
                        </div>
                        <div class="col-md-6">
                          <label for="nom-mere">Nom de la mère</label>
                          <input type="text" id="nom-mere" name="nomMere" class="form-control" value="{{ old('nomMere') }}">
                        </div>
                      </div>
                      
                      <div class="form-group row mt-3">
                        <div class="col-md-6">
                          <label for="adresses-parents">Adresses parents</label>
                          <input type="text" id="adresses-parents" name="adressesParents" class="form-control" value="{{ old('adressesParents') }}">
                        </div>
                        <div class="col-md-6">
                          <label for="autres-renseignements">Autres renseignements</label>
                          <input type="text" id="autres-renseignements" name="autresRenseignements" class="form-control" value="{{ old('autresRenseignements') }}">
                        </div>
                      </div>
                      
                      <!-- Section Contacts Parents -->
                      <div class="form-group row mt-3">
                        <div class="col-md-2">
                          <label for="contacts-parents">Contacts parents</label>
                          <select id="contacts-parents" name="indicatifParent" class="js-example-basic-multiple w-100" value="{{ old('indicatifParent') }}">
                            <option value="+229">+229</option>
                          </select>
                        </div>
                        <div class="col-md-5">
                          <label for="telephone-1">Téléphone Parent</label>
                          <input type="text" id="telephone-1" name="telephoneParent" class="form-control" value="{{ old('telephoneParent') }}">
                        </div>
                        <div class="col-md-5">
                          <label for="telephone-2">Téléphone Eleve</label>
                          <input type="text" id="telephone-2" name="telephoneEleve" class="form-control" value="{{ old('telephoneEleve') }}">
                        </div>
                      </div>
                      
                      <!-- Section Actions -->
                      <div class="form-group mt-3 text-center">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="reset" class="btn btn-danger">Annuler</button>
                      </div>
                    </form>
                    {{--                             
                    <script>
                      function previewPhoto(event) {
                      var reader = new FileReader();
                      reader.onload = function(){
                      var output = document.getElementById('photo-preview');
                      output.src = reader.result;
                      output.style.display = 'block';
                      };
                      reader.readAsDataURL(event.target.files[0]);
                      }
                    </script> --}}
                  </div>                           
                </div>
              </div>
            </div>
            
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
              <div class="card">
                <div class="card-body">
                  <form  action="{{url('enregistrerinfo')}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="matricule" value="{{ $newMatricule }}">
                    <!-- Section: Health Information -->
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-5">
                          {{-- <div class="d-flex align-items-center"> --}}
                            <label for="maladies_chroniques" class="mr-2">Maladies chroniques et allergies connues</label>
                            <input class="form-control" type="text" name="maladieschroniques" id="maladies_chroniques" value="">
                          {{-- </div> --}}
                        </div>
                        <div class="col-md-3">
                          {{-- <div class="d-flex align-items-center"> --}}
                            <label for="interdit_alimentaires" class="mr-2">Interdit alimentaires</label>
                            <input class="form-control" type="text" name="interditalimentaires" id="interdit_alimentaires" value="">
                          {{-- </div> --}}
                        </div>
                        <div class="col-md-2">
                          {{-- <div class="d-flex align-items-center"> --}}
                            <label for="groupe_sanguin" class="mr-2">Groupe sanguin</label>
                            <select class="form-control js-example-basic-multiple w-100" id="groupe_sanguin" name="groupesanguin">
                              <option>A+</option>
                              <option>A-</option>
                              <option>B+</option>
                              <option>B-</option>
                              <option>O+</option>
                              <option>O-</option>
                              <option>AB+</option>
                              <option>AB-</option>
                            </select>
                          {{-- </div> --}}
                        </div>
                        <div class="col-md-1">
                          {{-- <div class="d-flex align-items-center"> --}}
                            <label for="type_hemoglobine" class="mr-2">Hémoglobine</label>
                            <input type="text" class="form-control" id="type_hemoglobine" name="typehemoglobine" style="width: 110px">
                            {{-- <select class="form-control js-example-basic-multiple w-100" id="type_hemoglobine" name="typehemoglobine">
                              <option>A</option>
                              <option>O</option>
                              <option>B</option>
                              <option>AB</option>
                            </select> --}}
                          {{-- </div> --}}
                        </div>
                      </div>
                    </div>
                    <hr>
                    
                    <!-- Section: Mother Information -->
                    <h4 class="card-title" style="margin-top: 15px">Mère</h4>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="nom_mere" class="mr-2">Nom</label>
                            <input class="form-control" type="text" name="nommere" id="nom_mere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="prenom_mere" class="mr-2">Prénom</label>
                            <input class="form-control" type="text" name="prenommere" id="prenom_mere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="telephone_mere" class="mr-2">Numéro de téléphone</label>
                            <input class="form-control" type="text" name="telephonemere" id="telephone_mere" value="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="email_mere" class="mr-2">Adresse e-mail</label>
                            <input class="form-control" type="text" name="emailmere" id="email_mere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="profession_mere" class="mr-2">Profession</label>
                            <input class="form-control" type="text" name="professionmere" id="profession_mere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="adresse_employeur_mere" class="mr-2">Adresse employeur</label>
                            <input class="form-control" type="text" name="adresseemployeurmere" id="adresse_employeur_mere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="adresse_personnelle_mere" class="mr-2">Adresse personnelle</label>
                            <input class="form-control" type="textarea" name="adressepersonnellemere" id="adresse_personnelle_mere" value="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    
                    <!-- Section: Father Information -->
                    <h4 class="card-title" style="margin-top: 15px">Père</h4>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="nom_pere" class="mr-2">Nom</label>
                            <input class="form-control" type="text" name="nompere" id="nom_pere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="prenom_pere" class="mr-2">Prénom</label>
                            <input class="form-control" type="text" name="prenompere" id="prenom_pere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="telephone_pere" class="mr-2">Numéro de téléphone</label>
                            <input class="form-control" type="text" name="telephonepere" id="telephone_pere" value="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="email_pere" class="mr-2">Adresse e-mail</label>
                            <input class="form-control" type="text" name="emailpere" id="email_pere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="profession_pere" class="mr-2">Profession</label>
                            <input class="form-control" type="text" name="professionpere" id="profession_pere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="adresse_employeur_pere" class="mr-2">Adresse employeur</label>
                            <input class="form-control" type="text" name="adresseemployeurpere" id="adresse_employeur_pere" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="adresse_personnelle_pere" class="mr-2">Adresse personnelle</label>
                            <input class="form-control" type="textarea" name="adressepersonnellepere" id="adresse_personnelle_pere" value="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    
                    <!-- Section: Guardian Information -->
                    <h4 class="card-title" style="margin-top: 15px">Tuteur</h4>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="nom_tuteur" class="mr-2">Nom</label>
                            <input class="form-control" type="text" name="nomtuteur" id="nom_tuteur" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="prenom_tuteur" class="mr-2">Prénom</label>
                            <input class="form-control" type="text" name="prenomtuteur" id="prenom_tuteur" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="telephone_tuteur" class="mr-2">Numéro de téléphone</label>
                            <input class="form-control" type="text" name="telephonetuteur" id="telephone_tuteur" value="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="email_tuteur" class="mr-2">Adresse e-mail</label>
                            <input class="form-control" type="text" name="emailtuteur" id="email_tuteur" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="profession_tuteur" class="mr-2">Profession</label>
                            <input class="form-control" type="text" name="professiontuteur" id="profession_tuteur" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="adresse_employeur_tuteur" class="mr-2">Adresse employeur</label>
                            <input class="form-control" type="text" name="adresseemployeurtuteur" id="adresse_employeur_tuteur" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="adresse_personnelle_tuteur" class="mr-2">Adresse personnelle</label>
                            <input class="form-control" type="textarea" name="adressepersonnelletuteur" id="adresse_personnelle_tuteur" value="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    
                    <!-- Section: Emergency Contact Information -->
                    <h4 class="card-title" style="margin-top: 15px">Personne à contacter en cas d'urgence</h4>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="nom_urgence" class="mr-2">Nom</label>
                            <input class="form-control" type="text" name="nomurgence" id="nom_urgence" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="prenom_urgence" class="mr-2">Prénom</label>
                            <input class="form-control" type="text" name="prenomurgence" id="prenom_urgence" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="telephone_urgence" class="mr-2">Numéro de téléphone</label>
                            <input class="form-control" type="text" name="telephoneurgence" id="telephone_urgence" value="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="email_urgence" class="mr-2">Adresse e-mail</label>
                            <input class="form-control" type="text" name="emailurgence" id="email_urgence" value="">
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex align-items-center">
                            <label for="adresse_personnelle_urgence" class="mr-2">Adresse personnelle</label>
                            <input class="form-control" type="textarea" name="adressepersonnelleurgence" id="adresse_personnelle_urgence" value="">
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Section: Permissions -->
                    <div class="form-group">
                      <div class="form-check">
                        <input class="form-check-input" name="autorisevideo" type="checkbox" value="" id="flexCheckDefault" style="margin-left: 0.4rem">
                        <label class="form-check-label" for="flexCheckDefault">
                          Autorisation d'utiliser les vidéos à des fins publicitaires
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" name="autoriseimage" type="checkbox" value="" id="flexCheckChecked" checked style="margin-left: 0.4rem">
                        <label class="form-check-label" for="flexCheckChecked">
                          Autorisation d'utiliser les images à des fins publicitaires
                        </label>
                      </div>
                    </div>
                    
                    <!-- Section: Submit and Cancel Buttons -->
                    <div class="form-group text-center">
                      <button type="submit" class="btn btn-primary">Enregistrer</button>
                      <button type="button" class="btn btn-danger">Annuler</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            {{-- <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">...</div>
            <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div> --}}
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal n*1 -->
 
</div>
</div>

<!-- Modal n*1 -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content calcul-modal">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Données financières</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body profile-content">
        <div class="row">
          <div class="" id="calcul-one">
            <form class="forms-sample">
              <div class="form-group row">
                <label for="profilReduction" class="col-sm-12">Profil de réduction</label>
                <div class="col-sm-12 mb-2">
                  <select id="profilReduction" class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
                    <option value="fils-enseignant">Fils d'enseignant</option>
                    <option value="plein-tarif">Plein Tarif</option>
                  </select>
                </div>
              </div>
              <button type="submit" class="btn btn-primary col-sm-10">Créer un profil de réduction</button>
              <hr>
              <div class="form-group row">
                <label for="scolarite" class="col-sm-8 col-form-label">Scolarité</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="scolarite" placeholder="0">
                </div>
              </div>
              <div class="form-group row">
                <label for="arriere" class="col-sm-8 col-form-label">Arriéré</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="arriere" placeholder="0">
                </div>
              </div>
              <div class="form-group row">
                <label for="autre1" class="col-sm-8 col-form-label"></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="autre1" placeholder="0">
                </div>
              </div>
              <div class="form-group row">
                <label for="autre2" class="col-sm-8 col-form-label"></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="autre2" placeholder="0">
                </div>
              </div>
              <div class="form-group row">
                <label for="autre3" class="col-sm-8 col-form-label"></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="autre3" placeholder="0">
                </div>
              </div>
              <div class="form-group row">
                <label for="autre4" class="col-sm-8 col-form-label"></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="autre4" placeholder="0">
                </div>
              </div>
              <div class="form-group row">
                <label for="autre5" class="col-sm-8 col-form-label"></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="autre5" placeholder="0">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Valider</button>
      </div>
    </div>
  </div>
  
</div>
<!-- Modal n*2-->
<div class="modal fade" id="exampleModal1" tabindex="-2" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel1">Liste d'archives</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body profile-content">
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="table-responsive" style="height: 300px; overflow: auto;">
                <table class="table table-bordered table-striped"  style="min-width: 600px; font-size: 10px;">
                  <thead>
                    <tr>
                      <th>Matricule</th>
                      <th>Nom</th>
                      <th>Prénoms</th>
                      <th>Sexe</th>
                      <th>Date nai</th>
                      <th>Lieu nais</th>
                      <th>Photo</th>
                      <th>Sortir l'eleve</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      @foreach ($archive as $archive)
                      
                      <tr>
                        <td>{{ $archive->MATRICULE }}</td>
                        <td>{{ $archive->NOM }}</td>
                        <td>{{ $archive->PRENOM }}</td>
                        <td>{{ $archive->SEXE }}</td>
                        <td>{{ $archive->DATENAIS }}</td>
                        <td>{{ $archive->LIEUNAIS }}</td>

                      <td><img src="photo2.jpg" alt="" width="50"></td>
                      <td>
                        <button type="button" class="btn btn-primary fill-form-btn" 
                        data-matricule="{{ $archive->MATRICULE }}" 
                        data-classe="{{ $archive->CODECLAS }}" 
                        data-classeanterieur="{{ $archive->ANCCLASSE }}" 
                        data-departement="{{ $archive->CODEDEPT }}" 
                        data-addresseperso="{{ $archive->ADRPERS }}" 
                        data-etaborigine="{{ $archive->ETABORIG }}" 
                        data-nationnalite="{{ $archive->NATIONALITE }}" 
                        data-nompere="{{ $archive->NOMPERE }}" 
                        data-nommere="{{ $archive->NOMMERE }}" 
                        data-adresseparent="{{ $archive->ADRPAR }}" 
                        data-autrerenseignement="{{ $archive->AUTRE_RENS }}" 
                        data-telephone="{{ $archive->TEL }}" 
                        data-telephoneeleve="{{ $archive->TelEleve }}" 
                        data-nom="{{ $archive->NOM }}"
                        data-prenom="{{ $archive->PRENOM }}"
                        data-sexe="{{ $archive->SEXE }}"
                        data-datenaiss="{{ $archive->DATENAIS }}"
                        data-apte="{{ $archive->APTE }}"
                        data-lieunaiss="{{ $archive->LIEUNAIS }}">
                        <i class="typcn typcn-archive btn-icon-append"></i>
                        </button>
                      </td>
                      @endforeach
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
  <style>
    .custom-file-container {
      position: relative;
      width: 100px;
      height: 100px; /* Ajuster la hauteur pour rendre le carré */
      border: 2px dashed #007bff;
      border-radius: 5px;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      overflow: hidden;
      background-color: #f8f9fa; /* Fond gris clair pour correspondre à Bootstrap */
    }
    
    .file-upload-label {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100%;
      color: #007bff;
      font-size: 18px;
      text-align: center;
    }
    
    .file-upload-label:hover {
      background-color: rgba(0, 123, 255, 0.1);
    }
    
    .custom-file-container.dragover {
      border-color: #0056b3;
    }
    
    .custom-file-container.dragover .file-upload-label {
      background-color: rgba(0, 123, 255, 0.1);
    }
  </style>
  
  <!-- Custom JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      function handleDragOver(container, input) {
        container.addEventListener('dragover', function(event) {
          event.preventDefault();
          container.classList.add('dragover');
        });
        
        container.addEventListener('dragleave', function() {
          container.classList.remove('dragover');
        });
        
        container.addEventListener('drop', function(event) {
          event.preventDefault();
          container.classList.remove('dragover');
          input.files = event.dataTransfer.files;
        });
        
        container.addEventListener('click', function() {
          input.click();
        });
      }
      
      var imageInput = document.getElementById('imageInput');
      var customFileContainer = document.getElementById('customFileContainer');
      handleDragOver(customFileContainer, imageInput);
    });


    // script pour sortir un eleve de l'archive et le mettre dans le formulaire 

    $(document).ready(function(){
    $('.fill-form-btn').on('click', function() {
        var matricule = $(this).data('matricule');
        var nom = $(this).data('nom');
        var prenom = $(this).data('prenom');
        var sexe = $(this).data('sexe');
        var datenaiss = $(this).data('datenaiss');
        var lieunaiss = $(this).data('lieunaiss');
        var classe = $(this).data('classe');
        var classeanterieur = $(this).data('classeanterieur');
        var departement = $(this).data('departement');
        var addresseperso = $(this).data('addresseperso');
        var etaborigine = $(this).data('etaborigine');
        var nationnalite = $(this).data('nationnalite');
        var nompere = $(this).data('nompere');
        var nommere = $(this).data('nommere');
        var adresseparent = $(this).data('adresseparent');
        var autrerenseignement = $(this).data('autrerenseignement');
        var telephone = $(this).data('telephone');
        var telephoneeleve = $(this).data('telephoneeleve');
        var apte = $(this).data('apte');

        // Remplir le formulaire avec les informations de l'élève
       // $('#numero-ordre').val(matricule);
        $('#nom').val(nom);
        $('#prenom').val(prenom);
        $('#formSexe').val(sexe);
        $('#date-naissance').val(datenaiss);
        $('#sexe').val(sexe).change();
        $('#classe').val(classe);
        $('#classe-entree').val(classeanterieur);
        $('#departement').val(departement);
        $('#adresse-personnelle').val(addresseperso);
        $('#etablissement-origine').val(etaborigine);
        $('#lieu-naissance').val(lieunaiss);
        $('#lieu').val(nationnalite);
        $('#nom-pere').val(nompere);
        $('#nom-mere').val(nommere);
        $('#adresses-parents').val(adresseparent);
        $('#autres-renseignements').val(autrerenseignement);
        $('#telephone-1').val(telephone);
        $('#telephone-2').val(telephoneeleve);
        $('#aptitude-sport').val(apte).change();

        // Cocher ou décocher la case à cocher en fonction de la valeur
        $('#formArchive').prop('checked', archive == 1); // Supposons que 1 signifie "coché" et 0 signifie "décoché"

        // Fermer le modal
        $('#exampleModal1').modal('hide');

        // Appeler la fonction pour mettre à jour la liste des élèves dans la vue des réductions
        displayEleves(); // Ajout de cette ligne pour mettre à jour la liste des élèves

        // Optionnel : Faire défiler la page jusqu'au formulaire
        // $('html, body').animate({
        //     scrollTop: $("#formulaire").offset().top
        // }, 1000);
    });
});
</script>
@endsection
