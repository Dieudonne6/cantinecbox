@extends('layouts.master')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <button class="btn btn-arrow" onclick="window.history.back();">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                    </div>

                    <div class="card-title"> Liste Profils des Agents </div>
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Aide</button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                        aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasRightLabel">Liste Rubrique du salaire</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        {{-- <div class="offcanvas-body">
                            <p><strong> 1-Sélectionner une classe:</strong> Affiche uniquement la liste des élèves de la
                                classe ayant un contrat. </p>
                            <p><strong> 2-Possibilité de rechercher un élève.</strong>
                            </p>
                            <p><strong> 3-Nouveau contrat:</strong> Permet de créer un nouveau contrat. <br>
                                Le montant affiché correspond aux frais d’inscription de cantine de la classe sélectionnée.
                            </p>
                            <p><strong>4-Paiement:</strong></br>
                                Remplir les informations de paiement (Date, montant) </br>
                                Sélectionner le (les) mois à payer. </br>
                                Le coût total est affiché automatiquement. </br>
                                <strong>Enregistrer:</strong> Génère la facture normalisée. </br>
                                <strong>Annuler:</strong> Réinitialise les informations entrées.
                            </p>
                            <p><strong>5-Suspendre:</strong> Suspend le contrat. </p>
                        </div> --}}
                    </div>
                </div>
                @if (Session::has('status'))
                    <div id="statusAlert" class="alert alert-success btn-primary">
                        {{ Session::get('status') }}
                    </div>
                @endif
                @if (Session::has('erreur'))
                    <div id="statusAlert" class="alert alert-danger btn-primary">
                        {{ Session::get('erreur') }}
                    </div>
                @endif

                <div class="form-group row">

                    <div class="col-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#nouveaucontrat">
                            Nouveau Profil
                        </button>
                    </div>


                </div>
                <div class="table-responsive mb-4">
                    <table id="myTable" class="table">
                        <thead>
                            <tr>

                                <th>Nom PROFIL </th>

                                <th>Salaire Base </th>

                                <th>Nb Heure du </th>

                                <th>Type Impot</th>

                                {{-- <th>Base</th> --}}

                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody id="eleve-details">
                            @foreach ($profils as $profil)
                                <tr class="eleve">
                                    <td>
                                        {{ $profil->NomProfil }}
                                    </td>
                                    <td>
                                        {{ $profil->SalaireBase }}
                                    </td>
                                    <td>
                                        {{ $profil->NbHeuresDu }}
                                    </td>
                                    <td>
                                        {{ $profil->TypeImpot }}
                                    </td>

                                    <td>


                                        <!-- Bouton Voir (ouvre le modal d'affichage) -->
                                        <button type="button"
                                                class="btn btn-info btn-view"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewProfilModal"
                                                data-id="{{ $profil->Numeroprofil }}"
                                                data-nomprofil="{{ e($profil->NomProfil) }}">
                                        Voir
                                        </button>

                                        <button type="button" class="btn btn-primary btn-edit" data-bs-toggle="modal"
                                            data-bs-target="#nouveaucontrat" data-mode="edit"
                                            data-id="{{ $profil->Numeroprofil }}"
                                            data-nomprofil="{{ e($profil->NomProfil) }}"
                                            data-salairebase="{{ e($profil->SalaireBase) }}"
                                            data-nbheuresdu="{{ e($profil->NbHeuresDu) }}"
                                            data-typeimpot="{{ e($profil->TypeImpot) }}"
                                            data-tauxheuresupunique="{{ e($profil->TauxHeureSupUnique ?? '') }}"
                                            data-tauxheursupc2="{{ e($profil->TauxHeureSupC2 ?? '') }}"
                                            data-calculercnss="{{ $profil->CalculerCnss ? 1 : 0 }}">
                                            Modifier
                                        </button>



                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal-{{ $profil->Numeroprofil }}">Supprimer</button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal-{{ $profil->Numeroprofil }}"
                                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de
                                                            suppression</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer cet profil ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ url('supprimerprofil') }}" method="POST">
                                                            {{-- <form action="{{ url('supprimercontrat/'.$eleves->MATRICULE)}}" method="POST"> --}}
                                                            @csrf
                                                            {{-- @method('PUT') --}}
                                                            <input type="hidden" value="{{ $profil->Numeroprofil }}"
                                                                name="codepr">
                                                            {{-- <a href='/admin/deletecashier/{{$eleves->MATRICULE}}' class='btn btn-danger w-50'>Suspendre</a> --}}
                                                            <input type="submit" class="btn btn-danger" value="Confirmer">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->

    <!-- Modal d'ajout : nouveau profil (modal-xl pour plus grand) -->
    <div class="modal fade" id="nouveaucontrat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- modal-xl pour plus grand -->
            <div class="modal-content">
                <form id="modalForm" method="POST" action="{{ url('enregistrerprofilagents') }}">
                    @csrf

                    <input type="hidden" name="_method" id="modalMethod" value="POST">
                    <input type="hidden" name="NumeroProfil" id="modalNumeroProfil" value="">
                    <div class="modal-header">
                        <h4 class="modal-title">Nouveau profil</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">


                        <div class="form-group row">
                            <div class="col-sm-4 mb-1">
                                <label for="nomprofil"><strong>Nom du Profil</strong>
                                    {{-- <p>4 caracteres au plus</p> --}}
                                </label>

                                <input type="text" id="nomprofil" name="nomprofil" class="form-control"
                                    style="background-color: rgb(226, 236, 235) !important"
                                    value="{{ old('nomprofil') }}" required>
                            </div>


                            <div class="col-sm-4 mb-1">
                                <label for="salairebase"><strong>Salaire de Base</strong>
                                    {{-- <p>Ex: Prime de suggestion</p> --}}
                                </label>
                                <input type="number" id="salairebase" name="salairebase" class="form-control"
                                    min="1" style="background-color: rgb(226, 236, 235) !important"
                                    value="{{ old('salairebase') }}" required>
                            </div>

                            <div class="col-sm-4 mb-1">
                                <label for="nbheuresdu"><strong>Nb Heure Du</strong>
                                    {{-- <p>Partie fixe du montant de la prise/retenu</p> --}}
                                </label>
                                <input type="number" id="nbheuresdu" name="nbheuresdu" class="form-control" required
                                    style="background-color: rgb(226, 236, 235) !important" min="1">
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 mb-1">
                                <label for="typeimpot"><strong>Type d'Imposition</strong>
                                    {{-- <p>choisir le type de la rubrique prise/retenu </p> --}}
                                </label>
                                <select id="typeimpot" name="typeimpot"
                                    style="background-color: rgb(226, 236, 235) !important"
                                    class="js-example-basic-multiple form-control w-100">
                                    <option value="AUCUN">AUCUN</option>
                                    <option value="NORMAL">NORMAL</option>
                                    <option value="PRELEVEMENT">PRELEVEMENT</option>
                                </select>

                            </div>

                            <div class="col-sm-4 mb-1">
                                <label for="tauxheuresupunique"><strong>Taux heure sup unique</strong>
                                    {{-- <p>Pourcentage a appliquer pour la partie variable</p> --}}
                                </label>
                                <input type="number" id="tauxheuresupunique" name="tauxheuresupunique"
                                    style="background-color: rgb(226, 236, 235) !important" class="form-control" required
                                    min="1">
                            </div>
                            <div class="col-sm-4 mb-1">
                                <label for="tauxheursupc2"><strong>Taux heure sup cycle 2</strong>
                                    {{-- <p>Sur quelle base appliquer la partie variable</p> --}}
                                </label>
                                <input type="number" id="tauxheursupc2" name="tauxheursupc2" class="form-control"
                                    style="background-color: rgb(226, 236, 235) !important" required min="1">

                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-1">

                                <input class="form-check-input ml-1 mt-1" style="background-color: gray !important"
                                    type="checkbox" id="calculercnss" name="calculercnss">
                                <label class="form-check-label ml-4" for="voirMoisCheckbox">
                                    Calculer CNSS
                                </label>

                            </div>
                        </div>

                        <!-- ===== Table des primes (tprimes) ===== -->
                        <p style="font-size: 16px !important; font-weigth: 300 !important">cocher les primes et retenues
                            appliquables a ce profil</p></br>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Libellé</th>
                                        <th class="text-center">Cocher</th>
                                        <th class="text-end">Montant fixe</th>
                                        <th class="text-center"> </th>
                                        <th class="text-center">Pourcentage (%)</th>
                                        <th class="text-center">Base</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tprimes as $t)
                                        @php $code = $t->CODEPR ?? $t->code ?? $t->id; @endphp
                                        <tr>
                                            <td>
                                                {{ $t->LIBEL ?? ($t->LIBELPR ?? ($t->libelle ?? $code)) }}
                                            </td>

                                            <td class="text-center align-middle">
                                                <input type="checkbox" class="form-check-input prime-checkbox"
                                                    style="background-color: gray !important; margin-top: -0.3rem !important"
                                                    id="primechk_{{ $code }}" name="primes_selected[]"
                                                    value="{{ $code }}">
                                            </td>

                                            <td class="text-end">
                                                <!-- montant fixe editable si sélectionnée -->
                                                <input type="number" step="0.01"
                                                    name="montantfixe[{{ $code }}]"
                                                    id="montantfixe_{{ $code }}"
                                                    class="form-control form-control-sm montant-input text-end"
                                                    value="{{ $t->MONTANTFIXE ?? ($t->montantfixe ?? 0) }}" disabled>
                                            </td>

                                            <td class="text-center">+</td>

                                            <td class="text-end">
                                                <input type="number" step="0.01"
                                                    name="montantvar[{{ $code }}]"
                                                    id="montantvar_{{ $code }}"
                                                    class="form-control form-control-sm montantvar-input text-end"
                                                    value="{{ $t->MONTANTVAR ?? ($t->montantvar ?? 0) }}" disabled>
                                            </td>

                                            <td>
                                                <select name="base[{{ $code }}]" id="base_{{ $code }}"
                                                    class="form-select form-select-sm no-arrow" disabled>
                                                    <option value="">AUCUNE</option>
                                                    <option value="SB"
                                                        {{ ($t->BASEVARIABLE ?? '') === 'SB' ? 'selected' : '' }}>
                                                        SALAIRE BASE</option>
                                                    <option value="ST"
                                                        {{ ($t->BASEVARIABLE ?? '') === 'ST' ? 'selected' : '' }}>
                                                        SALAIRE BRUT</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- ===== fin table primes ===== -->

                    </div> {{-- fin modal-body --}}

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



<!-- Modal "Voir profil" -->
<div class="modal fade" id="viewProfilModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails du profil : <span id="viewProfilNom"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-12">
            <h6>Agents rattachés</h6>
            <div class="table-responsive">
              <table class="table table-sm table-striped" id="tableAgentsProfil">
                <thead>
                  <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- rempli par JS -->
                </tbody>
              </table>
            </div>
          </div>
        </div></br>

        <hr>

        <div class="row">
          <div class="col-12">
            <h6>Primes / retenues liées au profil</h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered" id="tablePrimesProfil">
                <thead>
                  <tr>
                    {{-- <th>Code</th> --}}
                    <th>Libellé</th>
                    <th class="text-end">Montant fixe</th>
                    <th class="text-end">Montant variable</th>
                    <th>Base</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- rempli par JS -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('nouveaucontrat');
            var modalForm = document.getElementById('modalForm');
            var modalMethod = document.getElementById('modalMethod');
            var modalNumeroProfil = document.getElementById('modalNumeroProfil');

            var fldNom = document.getElementById('nomprofil');
            var fldSalaire = document.getElementById('salairebase');
            var fldNbHeures = document.getElementById('nbheuresdu');
            var fldTypeImpot = document.getElementById('typeimpot');
            var fldTauxUnique = document.getElementById('tauxheuresupunique');
            var fldTauxC2 = document.getElementById('tauxheursupc2');
            var fldCalculerCnss = document.getElementById('calculercnss');

            function resetAllPrimesUI() {
                document.querySelectorAll('.prime-checkbox').forEach(function(chk) {
                    chk.checked = false;
                    var code = String(chk.value || '').trim();
                    var mf = document.getElementById('montantfixe_' + code);
                    var mv = document.getElementById('montantvar_' + code);
                    var base = document.getElementById('base_' + code);
                    if (mf) {
                        mf.disabled = true; /* optionnel: mf.value = mf.getAttribute('value') || 0; */
                    }
                    if (mv) {
                        mv.disabled = true; /* optionnel: mv.value = mv.getAttribute('value') || 0; */
                    }
                    if (base) {
                        base.disabled = true; /* base.value = ''; */
                    }
                });
            }

            function enablePrimeUI(code, montantFixe, montantVar, baseValue) {
                code = String(code).trim();
                var chk = document.getElementById('primechk_' + code) ||
                    document.querySelector('.prime-checkbox[value="' + code + '"]') ||
                    null;
                if (!chk) {
                    // parfois la valeur est numérique ou sans trim => essayer en number
                    var alt = String(Number(code));
                    chk = document.getElementById('primechk_' + alt) ||
                        document.querySelector('.prime-checkbox[value="' + alt + '"]') ||
                        null;
                }
                if (!chk) {
                    console.warn('checkbox prime introuvable pour CODEPR =', code);
                    return;
                }

                // cocher et déclencher change pour activer les inputs
                chk.checked = true;
                chk.dispatchEvent(new Event('change', {
                    bubbles: true
                }));

                var mf = document.getElementById('montantfixe_' + code) || document.getElementById('montantfixe_' +
                    String(Number(code)));
                var mv = document.getElementById('montantvar_' + code) || document.getElementById('montantvar_' +
                    String(Number(code)));
                var base = document.getElementById('base_' + code) || document.getElementById('base_' + String(
                    Number(code)));

                if (mf && (montantFixe !== undefined && montantFixe !== null)) mf.value = montantFixe;
                if (mv && (montantVar !== undefined && montantVar !== null)) mv.value = montantVar;
                if (base && (baseValue !== undefined && baseValue !== null && baseValue !== '')) {
                    base.value = baseValue;
                    // trigger change in case plugin is used
                    try {
                        if (window.jQuery && jQuery(base).hasClass('select2-hidden-accessible')) {
                            jQuery(base).val(baseValue).trigger('change');
                        } else {
                            base.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                        }
                    } catch (e) {}
                }
            }

            modalEl.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var mode = button ? button.getAttribute('data-mode') : null;

                // nettoyage primes UI
                resetAllPrimesUI();

                if (mode === 'edit') {
                    var id = button.getAttribute('data-id') || '';
                    var nom = button.getAttribute('data-nomprofil') || '';
                    var salaire = button.getAttribute('data-salairebase') || '';
                    var nbheures = button.getAttribute('data-nbheuresdu') || '';
                    var typeimpot = (button.getAttribute('data-typeimpot') || '').trim();
                    var tauxUnique = button.getAttribute('data-tauxheuresupunique') || '';
                    var tauxC2 = button.getAttribute('data-tauxheursupc2') || '';
                    var calculerCnssAttr = button.getAttribute('data-calculercnss');

                    console.log('OPEN modal EDIT id=', id, 'typeimpot=', typeimpot, 'calculercnssAttr=',
                        calculerCnssAttr);

                    // remplir champs simples
                    if (fldNom) fldNom.value = nom;
                    if (fldSalaire) fldSalaire.value = salaire;
                    if (fldNbHeures) fldNbHeures.value = nbheures;
                    if (fldTauxUnique) fldTauxUnique.value = tauxUnique;
                    if (fldTauxC2) fldTauxC2.value = tauxC2;

                    // checkbox CNSS : prioriser data-attribute ; fallback -> fetch /profils/{id}
                    function setCalculerCnssFromAttr(attr) {
                        if (!fldCalculerCnss) return;
                        var chkVal = String(attr).toLowerCase();
                        fldCalculerCnss.checked = (chkVal === '1' || chkVal === 'true' || chkVal === 'on' ||
                            attr === 1 || attr === true);
                    }

                    if (calculerCnssAttr !== null && calculerCnssAttr !== undefined) {
                        setCalculerCnssFromAttr(calculerCnssAttr);
                    } else if (id) {
                        // fallback: récupérer profil pour avoir valeur CNSS
                        fetch('/profils/' + encodeURIComponent(id), {
                                method: 'GET',
                                credentials: 'same-origin',
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(function(resp) {
                                if (!resp.ok) throw new Error('Network response not ok');
                                return resp.json();
                            })
                            .then(function(profile) {
                                console.log('profile fetched for CNSS:', profile);
                                var attr = profile.CalculerCNSS ?? profile.calculer_cnss ?? profile
                                    .calculerCnss ?? profile.calculer ?? 0;
                                setCalculerCnssFromAttr(attr);
                            })
                            .catch(function(err) {
                                console.warn('Impossible de récupérer profil pour calculer CNSS', err);
                            });
                    }

                    // SELECT typeimpot : set + trigger change (pour Select2)
                    if (fldTypeImpot) {
                        fldTypeImpot.value = typeimpot;
                        try {
                            if (window.jQuery && jQuery(fldTypeImpot).hasClass(
                                'select2-hidden-accessible')) {
                                jQuery(fldTypeImpot).val(typeimpot).trigger('change');
                            } else {
                                fldTypeImpot.dispatchEvent(new Event('change', {
                                    bubbles: true
                                }));
                            }
                        } catch (e) {
                            console.warn('Erreur set typeimpot:', e);
                        }

                        // fallback si value non trouvée
                        if (!fldTypeImpot.querySelector('option[value="' + typeimpot + '"]')) {
                            fldTypeImpot.selectedIndex = 0;
                        }
                    }

                    // hidden + method + action
                    if (modalNumeroProfil) modalNumeroProfil.value = id;
                    if (modalMethod) modalMethod.value = 'PUT';
                    modalForm.action = '/modifierprofil/' + encodeURIComponent(id);

                    // fetch primes liées au profil -> précocher / préremplir
                    if (id) {
                        var url = '/profils/' + encodeURIComponent(id) + '/primes';
                        console.log('fetch primes for profile:', url);
                        fetch(url, {
                                method: 'GET',
                                credentials: 'same-origin',
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(function(resp) {
                                if (!resp.ok) throw new Error('Network response not ok');
                                return resp.json();
                            })
                            .then(function(primes) {
                                console.log('primes response:', primes);
                                if (!Array.isArray(primes)) {
                                    console.warn('Primes attendues sous forme de tableau. Received:',
                                        primes);
                                    return;
                                }
                                primes.forEach(function(p) {
                                    // tolérer différents noms de clés
                                    var code = p.CODEPR ?? p.code ?? p.CODE ?? p.id ?? p
                                    .CODE_PR;
                                    var mf = p.MONTANTFIXE ?? p.montantfixe ?? p.montant ?? 0;
                                    var mv = p.MONTANTVAR ?? p.montantvar ?? p.pourcentage ?? 0;
                                    var base = p.BASEVARIABLE ?? p.base ?? p.BASE ?? '';

                                    if (!code) return;
                                    enablePrimeUI(code, mf, mv, base);
                                });
                            })
                            .catch(function(err) {
                                console.error('Erreur récupération primes du profil:', err);
                            });
                    }

                } else {
                    // CREATE mode : reset / defaults
                    modalForm.action = '{{ url('enregistrerprofilagents') }}';
                    if (modalMethod) modalMethod.value = 'POST';
                    if (modalNumeroProfil) modalNumeroProfil.value = '';
                    if (fldNom) fldNom.value = '{{ old('nomprofil') ?? '' }}';
                    if (fldSalaire) fldSalaire.value = '{{ old('salairebase') ?? '' }}';
                    if (fldNbHeures) fldNbHeures.value = '{{ old('nbheuresdu') ?? '' }}';
                    if (fldTypeImpot) fldTypeImpot.value = '{{ old('typeimpot') ?? 'AUCUN' }}';
                    if (fldTauxUnique) fldTauxUnique.value = '{{ old('tauxheuresupunique') ?? '' }}';
                    if (fldTauxC2) fldTauxC2.value = '{{ old('tauxheursupc2') ?? '' }}';
                    if (fldCalculerCnss) fldCalculerCnss.checked =
                        {{ old('calculercnss') ? 'true' : 'false' }};
                }
            });

            // comportement checkbox primes (activer/désactiver champs)
            document.querySelectorAll('.prime-checkbox').forEach(function(chk) {
                chk.addEventListener('change', function(e) {
                    var code = String(this.value).trim();
                    var mf = document.getElementById('montantfixe_' + code) || document
                        .getElementById('montantfixe_' + String(Number(code)));
                    var mv = document.getElementById('montantvar_' + code) || document
                        .getElementById('montantvar_' + String(Number(code)));
                    var base = document.getElementById('base_' + code) || document.getElementById(
                        'base_' + String(Number(code)));
                    if (this.checked) {
                        if (mf) mf.disabled = false;
                        if (mv) mv.disabled = false;
                        if (base) base.disabled = false;
                    } else {
                        if (mf) mf.disabled = true;
                        if (mv) mv.disabled = true;
                        if (base) base.disabled = true;
                    }
                });
            });

        }); // DOMContentLoaded
    </script>


<script>
document.addEventListener('DOMContentLoaded', function () {
  var viewModalEl = document.getElementById('viewProfilModal');
  var viewProfilNom = document.getElementById('viewProfilNom');
  var tableAgentsBody = document.querySelector('#tableAgentsProfil tbody');
  var tablePrimesBody = document.querySelector('#tablePrimesProfil tbody');

  viewModalEl.addEventListener('show.bs.modal', function (event) {
    var btn = event.relatedTarget;
    if (!btn) return;

    var profilId = btn.getAttribute('data-id');
    var profilNom = btn.getAttribute('data-nomprofil') || '';

    viewProfilNom.textContent = profilNom;

    // vider les tableaux et afficher loader
    tableAgentsBody.innerHTML = '<tr><td colspan="3">Chargement...</td></tr>';
    tablePrimesBody.innerHTML = '<tr><td colspan="5">Chargement...</td></tr>';

    // 1) fetch agents
    fetch('/profils/' + encodeURIComponent(profilId) + '/agents', {
      method: 'GET',
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    })
    .then(function(resp){ if(!resp.ok) throw resp; return resp.json(); })
    .then(function(agents) {
      if (!Array.isArray(agents) || agents.length === 0) {
        tableAgentsBody.innerHTML = '<tr><td colspan="3">Aucun agent lié à ce profil.</td></tr>';
        return;
      }
      var html = '';
      agents.forEach(function(a) {
        // adapte la colonne ID si tu veux un lien "Consulter" vers une page agent
        var consultUrl = '/inscrirepersonnel/' + (a.MATRICULE) ; // adapte si besoin
        html += '<tr>';
        html += '<td>' + (a.NOM ?? '') + '</td>';
        html += '<td>' + (a.PRENOM ?? '') + '</td>';
        html += '<td><a class="btn btn-sm btn-outline-primary" href="' + consultUrl + '">Consulter</a></td>';
        html += '</tr>';
      });
      tableAgentsBody.innerHTML = html;
    })
    .catch(function(err){
      console.error('Erreur fetch agents:', err);
      tableAgentsBody.innerHTML = '<tr><td colspan="3">Erreur lors du chargement des agents.</td></tr>';
    });

    // 2) fetch primes liées (route getPrimes)
    fetch('/profils/' + encodeURIComponent(profilId) + '/primes', {
      method: 'GET',
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    })
    .then(function(resp){ if(!resp.ok) throw resp; return resp.json(); })
    .then(function(primes) {
      if (!Array.isArray(primes) || primes.length === 0) {
        tablePrimesBody.innerHTML = '<tr><td colspan="5">Aucune prime liée à ce profil.</td></tr>';
        return;
      }
      var html = '';
      primes.forEach(function(p) {  
         let baseLabel = '';
        switch (p.BASEVARIABLE) {
            case 'SB':
            baseLabel = 'SALAIRE BASE';
            break;
            case 'ST':
            baseLabel = 'SALAIRE BRUT';
            break;
            default:
            baseLabel = 'AUCUNE';
        }

        html += '<tr>';
        // html += '<td>' + (p.CODEPR ?? '') + '</td>';
        html += '<td>' + (p.LIBELPR ?? '') + '</td>';
        html += '<td class="text-end">' + (p.MONTANTFIXE ?? 0) + '</td>';
        html += '<td class="text-end">' + (p.MONTANTVAR ?? 0) + '</td>';
        html += '<td>' + baseLabel + '</td>';
        html += '</tr>';
      });
      tablePrimesBody.innerHTML = html;
    })
    .catch(function(err){
      console.error('Erreur fetch primes:', err);
      tablePrimesBody.innerHTML = '<tr><td colspan="5">Erreur lors du chargement des primes.</td></tr>';
    });

  });
});
</script>





    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('nouveaucontrat'));

            @if ($errors->any())
                myModal.show();
            @endif

            // Réinitialiser les champs du formulaire à la fermeture du modal
            document.getElementById('nouveaucontrat').addEventListener('hidden.bs.modal', function() {
                document.getElementById('myModalForm').reset();
                document.querySelectorAll('#myModalForm .form-control').forEach(input => input.value = '');
            });
        });
    </script>















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


        /* retire la flèche native des select (Chrome, Firefox, Safari, Edge, IE) */
        .form-select.no-arrow {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;

            /* Bootstrap peut ajouter un background ; on le neutralise */
            background-image: none !important;
            background-repeat: no-repeat !important;
            background-position: right .75rem center !important;
            background-size: auto !important;

            /* ajuste le padding si besoin pour éviter chevauchement texte/bord */
            padding-right: 0.75rem;
        }

        /* Internet Explorer / old Edge */
        .form-select.no-arrow::-ms-expand {
            display: none;
        }

        /* Optionnel : pour garder l'aspect Bootstrap (bordure, fond, focus) */
        .form-select.no-arrow:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
            /* ou laisse Bootstrap gérer */
        }
    </style>
@endsection
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}
