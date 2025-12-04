@extends('layouts.master')
@section('content')
    <div class="container-fluid"><!-- Changé pour container-fluid pour plus d'espace sur petits écrans -->
        @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif

        <div class="col-12 grid-margin stretch-card"><!-- Changé pour col-12 pour assurer la pleine largeur -->
            <div class="card">
                <div class="card-header py-1 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0" style="text-align: center;">Paiement pour <strong>{{ $eleve->NOM }} {{ $eleve->PRENOM }} </strong></h4>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.history.back();" aria-label="Retour">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                </div>
                <div>
                    <style>
                        /* Styles pour réduire l'espace vertical sur desktop */
                        @media (min-width: 768px) {
                            .card-body {
                                padding: 0.75rem;
                            }
                            
                            .form-control, .form-select {
                                padding: 0.25rem 0.5rem;
                                font-size: 0.875rem;
                                min-height: 36px;
                                height: calc(1.5em + 0.5rem + 2px);
                                transition: all 0.3s ease;
                            }
                            
                            label {
                                margin-bottom: 0.25rem;
                                font-size: 0.875rem;
                            }
                            
                            .btn {
                                padding: 5rem 5rem;
                                font-size: 1.1rem;
                                transition: transform 0.2s ease, background-color 0.3s ease;
                            }
                            
                            .table td, .table th {
                                padding: 0.25rem 0.5rem;
                                font-size: 0.875rem;
                                transition: background-color 0.3s ease;
                            }
                            
                            .mb-3 {
                                margin-bottom: 0.5rem !important;
                            }
                            
                            .row {
                                margin-bottom: 0.5rem;
                            }
                        }
                        
                        @media (max-width: 767.98px) {
                            .form-control, .form-select {
                                min-height: 40px;
                            }
                        }
                        
                        :root {
                            --primary-color: #0056b3; /* Bleu plus foncé pour meilleur contraste */
                            --secondary-color: #1e7e34; /* Vert plus foncé */
                            --accent-color: #5a2a9d; /* Violet plus foncé */
                            --warning-color: #d39e00; /* Jaune plus foncé pour contraste */
                            --danger-color: #c82333; /* Rouge plus foncé */
                            --light-color: #f8f9fa;
                            --dark-color: #212529; /* Noir plus foncé pour texte */
                            --text-color: #212529; /* Texte principal plus foncé */
                        }
                        
                        .text-primary { color: var(--primary-color) !important; }
                        .text-secondary { color: var(--secondary-color) !important; }
                        .text-accent { color: var(--accent-color) !important; }
                        .text-warning { color: var(--warning-color) !important; }
                        .text-danger { color: var(--danger-color) !important; }
                        
                        .bg-primary { background-color: var(--primary-color) !important; color: white !important; }
                        .bg-secondary { background-color: var(--secondary-color) !important; color: white !important; }
                        .bg-accent { background-color: var(--accent-color) !important; color: white !important; }
                        .bg-warning { background-color: var(--warning-color) !important; color: var(--dark-color) !important; }
                        .bg-danger { background-color: var(--danger-color) !important; color: white !important; }
                        
                        /* Effets de survol pour les boutons */
                        .btn:hover {
                            transform: translateY(-2px);
                        }
                        
                        /* Animation pour les champs de saisie au focus */
                        .form-control:focus, .form-select:focus {
                            border-color: var(--primary-color);
                            box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
                            transform: scale(1.02);
                        }
                        
                        /* Effet de survol sur les lignes du tableau */
                        .table-hover tbody tr:hover {
                            background-color: rgba(0, 86, 179, 0.075);
                        }
                        
                        /* Animation pour les cards */
                        .card {
                            transition: box-shadow 0.3s ease, transform 0.3s ease;
                        }
                        
                        .card:hover {
                            box-shadow: 0 4px 20px rgba(0, 86, 179, 0.15);
                            transform: translateY(-5px);
                        }
                    </style>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-lg-9 col-md-8 col-sm-12 mb-2"><!-- Classes responsives ajoutées -->
                            <div class="card">
                                <div class="card-header py-1 text-center">
                                    <h6 class="mb-0 small">Historique des paiements</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto; overflow-x: auto;">
                                        <table class="table table-hover table-striped mb-0">
                                            <thead class="table-dark sticky-top">
                                                <tr>
                                                    <th class="text-nowrap">Numéro</th>
                                                    <th class="text-nowrap">Date</th>
                                                    <th class="text-nowrap">Montant</th>
                                                    <th class="text-nowrap">Désignation</th>
                                                    <th class="text-nowrap">Mode Paiement</th>
                                                    <th class="text-nowrap">Signature</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-striped">
                                                @forelse ($scolarite as $item)
                                                    <tr>
                                                        <td class="text-nowrap"><strong>{{ $item->NUMERO }}</strong></td>
                                                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($item->DATEOP)->format('d/m/Y') }}</td>
                                                        <td class="text-nowrap"><span>{{ number_format($item->MONTANT, 0, ',', ' ') }} F</span></td>
                                                        <td class="text-nowrap">
                                                            @switch($item->AUTREF)
                                                                @case(1)
                                                                    <span>Scolarité</span>
                                                                @break
                                                                @case(2)
                                                                    <span>Arriéré</span>
                                                                @break
                                                                @case(3)
                                                                    <span>{{ $libelle->LIBELF1 }}</span>
                                                                @break
                                                                @case(4)
                                                                    <span>{{ $libelle->LIBELF2 }}</span>
                                                                @break
                                                                @case(5)
                                                                    <span>{{ $libelle->LIBELF3 }}</span>
                                                                @break
                                                                @case(6)
                                                                    <span>{{ $libelle->LIBELF4 }}</span>
                                                                @break
                                                            @endswitch
                                                        </td>
                                                        <td class="text-nowrap">
                                                            @switch($item->MODEPAIE)
                                                                @case(1)
                                                                    ESPÈCES
                                                                @break
                                                                @case(2)
                                                                    CHÈQUES
                                                                @break
                                                                @case(4)
                                                                    AUTRE
                                                                @break
                                                            @endswitch
                                                        </td>
                                                        <td class="text-nowrap">{{ $item->SIGNATURE }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-4">
                                                            Aucun paiement enregistré
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-12 mb-2"><!-- Classes responsives ajoutées -->
                            <div class="card text-accent h-100">
                                <div class="card-header py-1 text-center">
                                    <h6 class="mb-0 small">Ordre de priorité des composantes</h6>
                                </div>
                                <div class="card-body p-2">
                                    <!-- Label supprimé car déjà dans le header -->
                                    <select id="priority-select" class="form-select" multiple
                                        aria-label="Sélection multiple" style="height: 120px; overflow-y: auto;">
                                        <option value="1">{{ $libelle->LIBELF1 }}</option>
                                        <option value="2">Arriéré</option>
                                        <option value="3">{{ $libelle->LIBELF2 }}</option>
                                        <option value="4">{{ $libelle->LIBELF3 }}</option>
                                        <option value="5">{{ $libelle->LIBELF4 }}</option>
                                        <option value="6">Scolarité</option>
                                    </select>
                                                              @if(Session::has('account') && Session::get('account')->groupe && strtoupper(Session::get('account')->groupe->nomgroupe) === 'ADMINISTRATEUR')
                                    <div class="mt-1" style="text-align: center">
                                        <button id="monter-btn" type="button" class="btn btn-sm bg-primary">Monter</button>
                                        <button id="descendre-btn" type="button" class="btn btn-sm bg-accent">Descendre</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Formulaire -->

                    <form action="{{ route('enregistrer.paiement', $eleve->MATRICULE) }}" method="POST">
                        @csrf
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="row">
                                    <!-- Champs fixes -->
                                    <div class="col-md-6 col-lg-3 mb-3"><!-- Classes responsives ajoutées -->
                                        <label for="date-operation">Date Opération</label>
                                        <input id="date-operation" name="date_operation" class="form-control"
                                            type="datetime-local" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}"
                                            required aria-label="Date et heure de l'opération">
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3"><!-- Classes responsives ajoutées -->
                                        <label for="montant-paye">Montant payé</label>
                                        <input id="montant-paye" name="montant_paye" class="form-control" type="number"
                                            placeholder="Entrez le montant payé" oninput="repartirMontant()" required 
                                            style="border: 2px solid var(--primary-color); box-shadow: 0 0 5px var(--primary-color); font-weight: bold;"
                                            aria-label="Montant payé en FCFA">
                                    </div>
                                </div>

                                <div class="form-group row g-1 mt-1" id="form-fields">
                                    <!-- First Frais field (Frais 1) -->
                                    <div class="col-md-4 col-lg-2 mb-2" data-id="1">
                                        <label for="libelle-0">{{ $libelle->LIBELF1 }}</label>
                                        @php
                                            $fraisField = 'FRAIS1';
                                            $totalLibelle = $totalLibelle1 ?? 0;
                                        @endphp
                                        <input id="libelle-0" name="libelle_0"
                                            class="form-control composante" type="number"
                                            data-priorite="1" data-due="{{ $eleve->$fraisField - $totalLibelle }}"
                                            placeholder="{{ $eleve->$fraisField - $totalLibelle }}"
                                            value="0" oninput="verifierSaisie(event)"
                                            aria-label="Montant pour {{ $libelle->LIBELF1 }}">
                                    </div>

                                    <!-- Arriéré field -->
                                    <div class="col-md-4 col-lg-2 mb-2" data-id="2">
                                        <label for="arriere">Arriéré</label>
                                        <input id="arriere" name="arriere" class="form-control composante" type="number"
                                            placeholder="{{ $eleve->ARRIERE ? $eleve->ARRIERE - $totalArriere : $eleve->ARRIERE }}"
                                            value="0" data-priorite="2" data-due="{{ $eleve->ARRIERE ? $eleve->ARRIERE - $totalArriere : $eleve->ARRIERE }}" oninput="verifierSaisie(event)"
                                            {{ $eleve->ARRIERE == 0 ? 'disabled' : '' }}
                                            aria-label="Montant des arriérés">
                                    </div>

                                    <!-- Other Frais fields (Frais 2, 3, 4) -->
                                    @foreach (['LIBELF2', 'LIBELF3', 'LIBELF4'] as $key => $libelleField)
                                        <div class="col-md-4 col-lg-2 mb-2" data-id="{{ $key + 3 }}">
                                            <label for="libelle-{{ $key + 1 }}">{{ $libelle->$libelleField }}</label>
                                            @php
                                                $fraisField = 'FRAIS' . ($key + 2);
                                                $totalLibelle = ${'totalLibelle' . ($key + 2)} ?? 0;
                                            @endphp
                                            <input id="libelle-{{ $key + 1 }}" name="libelle_{{ $key + 1 }}"
                                                class="form-control composante" type="number"
                                                data-priorite="{{ $key + 3 }}" data-due="{{ $eleve->$fraisField - $totalLibelle }}"
                                                placeholder="{{ $eleve->$fraisField - $totalLibelle }}"
                                                value="0" oninput="verifierSaisie(event)"
                                                aria-label="Montant pour {{ $libelle->$libelleField }}">
                                        </div>
                                    @endforeach

                                    <div class="col-md-4 col-lg-2 mb-2" data-id="6"><!-- Classes responsives ajoutées -->
                                        <label for="scolarite">Scolarité</label>
                                        <input id="scolarite" name="scolarite" class="form-control composante"
                                            type="number" placeholder="{{ $eleve->APAYER - $totalScolarite }}"
                                            value="0" data-priorite="6" data-due="{{ $eleve->APAYER - $totalScolarite }}" oninput="verifierSaisie(event)"
                                            aria-label="Montant pour la scolarité">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reliquat restant -->
                        <div class="text-danger mb-2" style="font-weight: bold;">
                            <label for="reliquat">Reliquat restant : </label>
                            <span id="reliquat" name="reliquat" aria-live="polite">0</span> F CFA
                            <input type="hidden" id="reliquat-hidden" name="reliquat_hidden" value="0">
                        </div>

                        <!-- Mode de paiement -->
                        <div class="col-12 mb-3">
                            <div class="form-group row align-items-center">
                                <div class="col-md-5 col-lg-4 d-flex align-items-center mb-2 mb-md-0">
                                    <label for="mode-paiement" class="form-label me-2 mb-0">Mode de Paiement :</label>
                                    <select id="mode-paiement" name="mode_paiement" class="form-select" required style="flex: 1;"
                                        aria-label="Sélectionner le mode de paiement">
                                        <option value="" disabled selected>Choisir</option>
                                        <option value="1" selected>ESPECES</option>
                                        <option value="2">CHEQUES</option>
                                        <option value="4">AUTRE</option>
                                    </select>
                                </div>

                                <div class="col-md-4 col-lg-3  align-items-center">
                                    <input type="hidden" name="matricule" value="{{ $eleve->MATRICULE }}">
                                    <button id="btn-enregistrer" type="submit" class="btn btn-primary w-100" style="padding: 0.5rem 1rem;">
                                        Enregistrer le paiement
                                    </button>
                                </div>
                            </div>
                        </div>
                       
                    </form>
                </div>
                <br><br>
                <!-- Suppression des sauts de ligne inutiles -->
            </div>
        </div>
    </div>

    <script>
        let saisieManuelle = false;
        let messageContainer = null;

        // Gestion de la répartition dynamique des montants
        document.getElementById('montant-paye').addEventListener('input', function() {
            if (this.value.trim() === '') {
                resetFields();
            } else {
                repartirMontant();
            }
            verifierEtat();
        });

        document.querySelectorAll('.composante').forEach(element => {
            element.addEventListener('input', function(event) {
                verifierSaisie(event);
                verifierEtat(); // Vérifie l'état après chaque saisie
            });
        });

        function repartirMontant() {
            const montantPaye = parseFloat(document.getElementById('montant-paye').value) || 0;
            if (montantPaye <= 0) {
                resetFields();
                return;
            }

            const composantes = Array.from(document.querySelectorAll('.composante'));

            // Vérification : Si un champ a un montant dû de 0, il est désactivé
            composantes.forEach(c => {
                if (parseFloat(c.dataset.due) === 0) {
                    c.disabled = true;
                    c.style.backgroundColor = "#e9ecef"; // Couleur de fond pour indiquer le blocage
                    c.value = 0; // Remet à 0 pour éviter toute saisie
                } else {
                    c.disabled = false; // Réactiver les champs qui ont un montant dû supérieur à 0
                    c.style.backgroundColor = ""; // Remettre la couleur de fond par défaut
                }
            });

            // Réinitialiser la saisie manuelle
            saisieManuelle = false;
            composantes.forEach(c => c.dataset.saisieManuelle = 'false');

            const priorites = composantes.map(c => ({
                element: c,
                priorite: parseInt(c.dataset.priorite),
                montant: parseFloat(c.value) || 0,
                montantDu: parseFloat(c.dataset.due) || 0
            })).sort((a, b) => a.priorite - b.priorite);

            let montantRestant = montantPaye;

            // Répartition des montants en respectant la priorité
            priorites.forEach(item => {
                if (montantRestant > 0 && item.montantDu > 0 && !item.element.disabled) {
                    const paiement = Math.min(item.montantDu, montantRestant);
                    item.element.value = paiement;
                    montantRestant -= paiement;
                } else {
                    item.element.value = 0;
                }
            });

            // Met à jour l'affichage du reliquat restant
            document.getElementById('reliquat').textContent = montantRestant.toFixed(2);
            document.getElementById('reliquat-hidden').value = montantRestant.toFixed(2);

            if (montantRestant > 0) {
                console.log("Le montant payé dépasse les besoins calculés par priorité.");
            }
        }

        function verifierSaisie(event) {
            const composante = event.target;
            const valeur = parseFloat(composante.value) || 0;

            // Empêcher toute modification si le montant dû avant répartition est 0
            if (parseFloat(composante.dataset.due) === 0) {
                composante.value = 0;
                composante.disabled = true;
                composante.style.backgroundColor = "#e9ecef"; // Couleur de fond pour indiquer le blocage
                showMessage("Ce champ est bloqué car le montant dû est 0.");
                return; // Empêche toute saisie supplémentaire
            }

            // Si le montant est inférieur à 0, l'annuler
            if (valeur < 0) {
                composante.value = 0;
                showMessage("Les montants doivent être positifs.");
            }

            // Marquer le champ comme ayant une saisie manuelle
            composante.dataset.saisieManuelle = 'true';
            saisieManuelle = true;
            ajusterMontants();
        }

        function ajusterMontants() {
            const montantPaye = parseFloat(document.getElementById('montant-paye').value) || 0;
            const composantes = Array.from(document.querySelectorAll('.composante'));

            let totalSaisie = composantes.reduce((total, elem) => {
                return total + (elem.dataset.saisieManuelle === 'true' ? parseFloat(elem.value) || 0 : 0);
            }, 0);

            let montantRestant = montantPaye - totalSaisie;

            composantes.forEach(elem => {
                if (elem.dataset.saisieManuelle !== 'true' && !elem.disabled) {
                    elem.value = montantRestant > 0 ? montantRestant : 0;
                    montantRestant -= parseFloat(elem.value);
                }
            });

            // Met à jour l'affichage du reliquat restant
            document.getElementById('reliquat').textContent = montantRestant.toFixed(2);
            document.getElementById('reliquat-hidden').value = montantRestant.toFixed(2);
        }

        function resetFields() {
            document.querySelectorAll('.composante').forEach(element => {
                if (parseFloat(element.dataset.due) === 0) {
                    element.disabled = true; // Désactiver les champs avec un montant dû de 0
                    element.style.backgroundColor = "#e9ecef"; // Couleur de fond pour indiquer le blocage
                    element.value = 0; // Remet à zéro
                } else {
                    element.disabled = false; // Réactiver les champs avec un montant dû supérieur à 0
                    element.value = 0; // Remet à zéro
                    element.style.backgroundColor = ""; // Réinitialiser la couleur de fond
                }
            });
            // Réinitialiser le reliquat à 0
            document.getElementById('reliquat').textContent = '0';
        }

        function verifierEtat() {
            const composantes = Array.from(document.querySelectorAll('.composante'));
            const montantPaye = parseFloat(document.getElementById('montant-paye').value) || 0;

            // Vérifier si tous les champs sont à zéro
            const tousZeros = composantes.every(c => parseFloat(c.value) === 0);

            // Griser le bouton et le champ de mode de paiement si tous les champs sont à zéro
            const boutonEnregistrer = document.getElementById('btn-enregistrer'); // Remplacez par l'ID de votre bouton
            const champModePaiement = document.getElementById(
                'mode-paiement'); // Remplacez par l'ID de votre champ de mode de paiement

            if (tousZeros && montantPaye === 0) {
                boutonEnregistrer.disabled = true;
                boutonEnregistrer.style.backgroundColor = "#e9ecef"; // Changez la couleur pour indiquer qu'il est désactivé
                champModePaiement.disabled = true;
                champModePaiement.style.backgroundColor = "#e9ecef"; // Changez la couleur pour indiquer qu'il est désactivé
            } else {
                boutonEnregistrer.disabled = false;
                boutonEnregistrer.style.backgroundColor = ""; // Réinitialiser la couleur
                champModePaiement.disabled = false;
                champModePaiement.style.backgroundColor = ""; // Réinitialiser la couleur
            }
        }

        function showMessage(message) {
            if (!messageContainer) {
                messageContainer = document.createElement('div');
                messageContainer.style.cssText = 'position: fixed; bottom: 20px; right: 20px; background-color: #fff3cd; color: #856404; padding: 10px 20px; border: 1px solid #ffeeba; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; max-width: 300px;';
                document.body.appendChild(messageContainer);
            }
            messageContainer.textContent = message;
            setTimeout(() => {
                messageContainer.textContent = '';
            }, 3000);
        }
    </script>

    <script>
        // 1) Déclarations hors de DOMContentLoaded pour pouvoir y accéder globalement
        function moveUp() {
        console.log("moveUp appelé");
        const select = document.getElementById("priority-select");
        Array.from(select.selectedOptions).forEach(option => {
            const idx = option.index;
            if (idx > 0) {
            // Déplace l'option dans le <select>
            select.insertBefore(option, select.options[idx - 1]);

            // Déplace le champ correspondant dans le formulaire
            const sel = `#form-fields [data-id="${option.value}"]`;
            const field = document.querySelector(sel);
            const prev = field?.previousElementSibling;
            if (prev) {
                field.style.transition = prev.style.transition = 'transform 0.3s ease';
                document.getElementById("form-fields").insertBefore(field, prev);
            }
            }
        });
        mettreAJourPriorites();
        mettreAJourEtatBoutons();
        }

        function moveDown() {
        console.log("moveDown appelé");
        const select = document.getElementById("priority-select");
        const opts = Array.from(select.selectedOptions);
        for (let i = opts.length - 1; i >= 0; i--) {
            const option = opts[i], idx = option.index;
            if (idx < select.options.length - 1) {
            select.insertBefore(select.options[idx + 1], option);

            const sel = `#form-fields [data-id="${option.value}"]`;
            const field = document.querySelector(sel);
            const next = field?.nextElementSibling;
            if (next) {
                field.style.transition = next.style.transition = 'transform 0.3s ease';
                document.getElementById("form-fields").insertBefore(next, field);
            }
            }
        }
        mettreAJourPriorites();
        mettreAJourEtatBoutons();
        }

        function mettreAJourPriorites() {
        const select = document.getElementById("priority-select");
        Array.from(select.options).forEach((opt, i) => {
            const sel = `#form-fields [data-id="${opt.value}"]`;
            const field = document.querySelector(sel);
            if (field) {
            field.querySelector('.composante').dataset.priorite = i + 1;
            }
        });
        repartirMontant();
        }

        function mettreAJourEtatBoutons() {
        const select = document.getElementById("priority-select");
        const actif = select.selectedOptions.length > 0;
        document.getElementById("monter-btn").disabled = !actif;
        document.getElementById("descendre-btn").disabled = !actif;
        }

        // 2) On attend que le DOM soit prêt avant de lier les boutons et le select
        document.addEventListener('DOMContentLoaded', () => {
        console.log("DOM chargé, on attache les events");
        const select = document.getElementById("priority-select");
        document.getElementById("monter-btn").addEventListener('click', moveUp);
        document.getElementById("descendre-btn").addEventListener('click', moveDown);
        select.addEventListener('change', mettreAJourEtatBoutons);

        // initialise l’état des boutons
        mettreAJourEtatBoutons();
        verifierEtat();
        
        // Ajouter un effet de pulsation au champ Montant payé pour attirer l'attention
        const montantPaye = document.getElementById("montant-paye");
        let animation = montantPaye.animate([
            { transform: 'scale(1)' },
            { transform: 'scale(1.05)' },
            { transform: 'scale(1)' }
        ], {
            duration: 1500,
            iterations: Infinity
        });
        
        // Arrêter l'animation quand on clique dans le champ
        montantPaye.addEventListener('click', () => {
            animation.cancel();
        });
        
        // Défilement automatique vers le dernier paiement dans l'historique
        const historiqueContainer = document.querySelector('.table-responsive');
        if (historiqueContainer) {
            const dernierPaiement = document.querySelector('.table tbody tr:last-child');
            if (dernierPaiement) {
                setTimeout(() => {
                    dernierPaiement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Ajouter une animation de surbrillance pour le dernier paiement
                    dernierPaiement.animate([
                        { backgroundColor: 'rgba(0, 86, 179, 0.2)' },
                        { backgroundColor: 'rgba(0, 86, 179, 0)' },
                        { backgroundColor: 'rgba(0, 86, 179, 0.2)' }
                    ], {
                        duration: 2000,
                        iterations: 3
                    });
                }, 500);
            }
        }
        
        // Animation d'entrée pour les cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.animate([
                    { opacity: '0', transform: 'translateY(20px)' },
                    { opacity: '1', transform: 'translateY(0)' }
                ], {
                    duration: 800,
                    easing: 'ease-out',
                    fill: 'forwards'
                });
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 200);
        });
        });
    </script>
@endsection