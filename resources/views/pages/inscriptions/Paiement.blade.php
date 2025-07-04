@extends('layouts.master')
@section('content')
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif

        <div class="col-md-12 grid-margin stretch-card">
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
                    <h4 class="card-title">Paiement pour <strong>{{ $eleve->NOM }} {{ $eleve->PRENOM }} </strong></h4><br><br>

                    <div class="row">
                        <div class="col-9">
                            <div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Numero</th>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th></th>
                                            <th>Mode Paiement</th>
                                            <th>SIGNATURE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($scolarite as $item)
                                            <tr>
                                                <td>{{ $item->NUMERO }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->DATEOP)->format('d-m-Y') }}</td>
                                                <td>{{ $item->MONTANT }}</td>
                                                <td>
                                                    @switch($item->AUTREF)
                                                        @case(1)
                                                            Scolarité
                                                        @break

                                                        @case(2)
                                                            Arriéré
                                                        @break

                                                        @case(3)
                                                            {{ $libelle->LIBELF1 }}
                                                        @break

                                                        @case(4)
                                                            {{ $libelle->LIBELF2 }}
                                                        @break

                                                        @case(5)
                                                            {{ $libelle->LIBELF3 }}
                                                        @break

                                                        @case(6)
                                                            {{ $libelle->LIBELF4 }}
                                                        @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @switch($item->MODEPAIE)
                                                        @case(1)
                                                            ESPECES
                                                        @break

                                                        @case(2)
                                                            CHEQUES
                                                        @break

                                                        @case(4)
                                                            AUTRE
                                                        @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $item->SIGNATURE }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="card">
                                <div>
                                    <label for="priority-select" class="form-label" style="text-align: center">Ordre de
                                        priorité des
                                        composantes</label>
                                    <select id="priority-select" class="form-select" multiple
                                        aria-label="Sélection multiple">
                                        <option value="1">Scolarité</option>
                                        <option value="2">Arriéré</option>
                                        <option value="3">{{ $libelle->LIBELF1 }}</option>
                                        <option value="4">{{ $libelle->LIBELF2 }}</option>
                                        <option value="5">{{ $libelle->LIBELF3 }}</option>
                                        <option value="6">{{ $libelle->LIBELF4 }}</option>
                                    </select>
                                    <div class="mt-2" style="text-align: center">
                                        <button id="monter-btn" type="button" class="btn btn-primary btn-sm">Monter</button>
                                        <button id="descendre-btn" type="button" class="btn btn-secondary btn-sm">Descendre</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><br><br>
                    <!-- Formulaire -->

                    <form action="{{ route('enregistrer.paiement', $eleve->MATRICULE) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <!-- Champs fixes -->
                                    <div class="col">
                                        <label for="date-operation">Date Opération</label>
                                        <input id="date-operation" name="date_operation" class="form-control"
                                            type="datetime-local" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label for="montant-paye">Montant payé</label>
                                        <input id="montant-paye" name="montant_paye" class="form-control" type="number"
                                            placeholder="Entrez le montant payé" oninput="repartirMontant()" required>
                                    </div>
                                </div>

                                <div class="form-group row mt-4" id="form-fields">
                                    <div class="col-md-2" data-id="1">
                                        <label for="scolarite">Scolarité</label>
                                        <input id="scolarite" name="scolarite" class="form-control composante"
                                            type="number" placeholder="{{ $eleve->APAYER - $totalScolarite }}"
                                            value="0" data-priorite="1" oninput="verifierSaisie()">
                                    </div>

                                    <div class="col-md-2" data-id="2">
                                        <label for="arriere">Arriéré</label>
                                        <input id="arriere" name="arriere" class="form-control composante" type="number"
                                            placeholder="{{ $eleve->ARRIERE ? $eleve->ARRIERE - $totalArriere : $eleve->ARRIERE }}"
                                            value="0" data-priorite="2" oninput="verifierSaisie()"
                                            {{ $eleve->ARRIERE == 0 ? 'disabled' : '' }}>
                                    </div>

                                    <!-- Champs générés dynamiquement -->
                                    @foreach (['LIBELF1', 'LIBELF2', 'LIBELF3', 'LIBELF4'] as $key => $libelleField)
                                        <div class="col-md-2" data-id="{{ $key + 3 }}">
                                            <label for="libelle-{{ $key }}">{{ $libelle->$libelleField }}</label>
                                            @php
                                                $fraisField = 'FRAIS' . ($key + 1);
                                                $totalLibelle = ${'totalLibelle' . ($key + 1)} ?? 0;
                                            @endphp
                                            <input id="libelle-{{ $key }}" name="libelle_{{ $key }}"
                                                class="form-control composante" type="number"
                                                data-priorite="{{ $key + 3 }}"
                                                placeholder="{{ $eleve->$fraisField - $totalLibelle }}" value="0"
                                                oninput="verifierSaisie()">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Reliquat restant -->
                        <div style="color: red; font-weight: bold;">
                            <label for="reliquat">Reliquat restant : </label>
                            <span id="reliquat" name="reliquat">0</span> F CFA
                            <input type="hidden" id="reliquat-hidden" name="reliquat_hidden" value="0">
                        </div>

                        <br>

                        <!-- Mode de paiement -->
                        <div class="form-group row">
                            <div class="col-12">
                                <label for="mode-paiement" class="form-label">Mode de Paiement</label>
                                <select id="mode-paiement" name="mode_paiement" class="form-select" required>
                                    <option value="" disabled selected>Choisir un mode de paiement</option>
                                    <option value="1">ESPECES</option>
                                    <option value="2">CHEQUES</option>
                                    <option value="4">AUTRE</option>
                                </select>
                            </div>
                        </div>

                        <!-- Champ caché pour le MATRICULE -->
                        <input type="hidden" name="matricule" value="{{ $eleve->MATRICULE }}">

                        <button type="submit" class="btn btn-primary">Enregistrer le paiement</button>
                    </form>
                    @if (Session::has('success'))
                        <div id="recu" class="mt-4">
                            <div class="row">
                                @php
                                    $libelles = ['LIBELF1', 'LIBELF2', 'LIBELF3', 'LIBELF4'];
                                    $recentMontants = Session::get('recent_montants', []);
                                @endphp

                                <!-- Conteneur des reçus -->
                                <!-- Updated structure: wrap receipts in Bootstrap rows -->
                                <div class="container">
                                <!-- Row for a pair of receipts -->
                                <div class="row mb-4">
                                    <!-- Reçu Souche -->
                                    <div class="col-md-6">
                                    <div class="recu-section" style="border: 1px solid #007bff; border-radius: 8px; padding: 20px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                        <p style="margin: 0; font-size: 20px;"><strong>CBOX</strong></p>
                                        <h5 style="text-align: center; font-size: 20px; font-weight: bold; color: #007bff;">Reçu de Paiement (Souche)</h5>
                                        <div style="margin-bottom: 15px; text-align: right;">
                                        <p style="margin: 0; font-size: 16px;">{{ $eleve->CODECLAS }}</p>
                                        <p style="margin: 0; font-size: 16px; display: flex; justify-content: flex-end;"><strong>QUITANCE N°</strong> {{ Session::get('numeroRecu') }}/{{ $eleve->anneeacademique }}</p>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                        <p style="margin: 0; font-size: 16px; text-align: center;">{{ $eleve->NOM }} {{ $eleve->PRENOM }}</p>
                                        <p style="margin: 0; font-size: 16px;"><strong>Montant payé:</strong> {{ Session::get('montantPaye') }} F CFA</p>
                                        <p style="margin: 0; font-size: 16px;"><strong>Mode de paiement:</strong>
                                            @if (Session::get('modePaiement') == 1) ESPECES
                                            @elseif(Session::get('modePaiement') == 2) CHEQUES
                                            {{-- @elseif(Session::get('modePaiement') == 3) Opposition --}}
                                            @else AUTRE @endif
                                        </p>
                                        </div>
                                        <hr style="border-top: 1px solid #007bff; margin: 15px 0;">
                                        <div style="margin-bottom: 15px;">
                                        <p style="margin: 0; font-size: 16px; display: flex; justify-content: space-between;"><strong>Arriéré:</strong><span>{{ Session::get('arriere', 0) }} F CFA</span></p>
                                        <p style="margin: 0; font-size: 16px; display: flex; justify-content: space-between;"><strong>Scolarité:</strong><span>{{ Session::get('scolarite', 0) }} F CFA</span></p>
                                        @foreach ($libelles as $index => $libelleKey)
                                            <p style="margin: 0; font-size: 16px; display: flex; justify-content: space-between;"><strong>{{ $libelle->$libelleKey }}:</strong><span>@if(isset($recentMontants['libelle_' . $index])){{ $recentMontants['libelle_' . $index] }} F CFA @else 0 F CFA @endif</span></p>
                                        @endforeach
                                        </div>
                                        <hr style="border-top: 1px solid #007bff; margin: 15px 0;">
                                        <div style="text-align: right;"><p style="margin: 0; font-size: 16px;"><strong>Reliquat restant:</strong> {{ Session::get('reliquat') }} F CFA</p></div>
                                        <hr style="border-top: 1px solid #007bff; margin: 15px 0;">
                                        <div class="recu-footer" style="text-align: center; margin-top: 20px;"><p style="font-size: 16px; color: #333;"><strong>CCC, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</strong></p><p style="font-size: 16px; color: #333;"><strong>Le Comptable Gestion</strong></p><p style="font-size: 16px; color: #333;">{{ Session::get('signature') }}</p></div>
                                    </div>
                                    </div>
                                    <!-- Reçu Original -->
                                    <div class="col-md-6">
                                    <div class="recu-section" style="border: 1px solid #28a745; border-radius: 8px; padding: 20px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                        <p style="margin: 0; font-size: 16px;"><strong>{{ $libelle->NOMETAB }}</strong></p>
                                        <h5 style="text-align: center; font-size: 20px; font-weight: bold; color: #28a745;">Reçu de Paiement (Original)</h5>
                                        <div style="text-align: right;"><p style="margin: 0; font-size: 16px;">{{ $eleve->CODECLAS }}</p><p style="margin: 0; font-size: 16px;"><strong>QUITANCE N°</strong> {{ Session::get('numeroRecu') }}/{{ $eleve->anneeacademique }}</p></div>
                                        <div style="margin-bottom: 15px;"><p style="margin: 0; font-size: 16px;"><strong>{{ $eleve->NOM }} {{ $eleve->PRENOM }}</strong></p><p style="margin: 0; font-size: 16px;"><strong>Montant payé:</strong> {{ Session::get('montantPaye') }} F CFA</p><p style="margin: 0; font-size: 16px;"><strong>Mode de paiement:</strong> @if(Session::get('modePaiement')==1) ESPECES @elseif(Session::get('modePaiement')==2) CHEQUES {{-- @elseif(Session::get('modePaiement')==3) Opposition --}} @else AUTRE @endif</p></div>
                                        <hr style="border-top: 1px solid #28a745; margin: 15px 0;">
                                        <div style="margin-bottom: 15px;">
                                        <p style="margin: 0; font-size: 16px;"><strong>Arriéré:</strong><span class="float-right">{{ Session::get('arriere', 0) }} F CFA Payer</span></p>
                                        <p><strong>Reste:</strong><span class="float-right">{{ $eleve->ARRIERE ? $eleve->ARRIERE - $totalArriere : $eleve->ARRIERE }} F CFA</span></p>
                                        <p style="margin: 0; font-size: 16px;"><strong>Scolarité:</strong><span class="float-right">{{ Session::get('scolarite', 0) }} F CFA Payer</span></p>
                                        <p><strong>Reste:</strong><span class="float-right">{{ $eleve->APAYER - $totalScolarite }} F CFA</span></p>
                                        @php $sommeReste = 0; @endphp
                                        @foreach ($libelles as $index => $libelleKey)
                                            @php
                                            $fraisField = 'FRAIS' . ($index + 1);
                                            $totalLibelle = ${'totalLibelle' . ($index + 1)} ?? 0;
                                            $fraisValue = $eleve->$fraisField ?? 0;
                                            $reste = $fraisValue - $totalLibelle;
                                            $sommeReste += $reste;
                                            @endphp
                                            <p style="margin: 0; font-size: 16px;"><strong>{{ $libelle->$libelleKey }}:</strong><span class="float-right">@if(isset($recentMontants['libelle_' . $index])){{ $recentMontants['libelle_' . $index] }} F CFA @else 0 F CFA @endif</span><br><strong>Reste:</strong><span class="float-right" style="font-size: 13px;">{{ $reste }} F CFA</span></p>
                                        @endforeach
                                        </div>
                                        <hr style="border-top: 1px solid #28a745; margin: 15px 0;">
                                        <div style="text-align: right;"><p style="margin: 0; font-size: 16px;"><strong>Reliquat restant:</strong> {{ Session::get('reliquat') }} F CFA</p><p style="margin: 0; font-size: 16px;"><strong>Reste a payer:</strong> {{ $sommeReste }} F CFA</p></div>
                                        <hr style="border-top: 1px solid #28a745; margin: 15px 0;">
                                        <div class="recu-footer" style="text-align: center; margin-top: 20px;"><p style="font-size: 16px; color: #333;"><strong>CCC, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</strong></p><p style="font-size: 16px; color: #333;"><strong>Le Comptable Gestion</strong></p><p style="font-size: 16px; color: #333;">{{ Session::get('signature') }}</p></div>
                                    </div>
                                    </div>
                                </div>
                                </div>

                            </div>
                        </div>

                        {{-- <!-- Bouton d'Impression -->
                        <button onclick="imprimerRecu()" class="btn btn-success mt-4">Imprimer le Reçu</button>

                        <!-- Script d'Impression -->
                        <script>
                            function imprimerRecu() {
                                var contenu = document.getElementById('recu').innerHTML;
                                var fenetre = window.open('', '_blank', 'width=800,height=600');
                                fenetre.document.open();
                                fenetre.document.write(`
                                <html>
                                    <head>
                                        <title>Reçu de Paiement</title>
                                        <style>
                                            body { font-family: Arial, sans-serif; padding: 20px; }
                                            .recu-section {
                                                border: 1px solid black;
                                                padding: 15px;
                                                background-color: #fff;
                                                margin: 10px;
                                                display: inline-block;
                                                width: calc(50% - 20px); /* Pour un affichage côte à côte */
                                                vertical-align: top; /* Aligner les sections */
                                            }
                                            h5 { text-align: center; font-size: 18px; font-weight: bold; }
                                            p { font-size: 14px; margin: 5px 0; }
                                            .recu-footer { text-align: center; margin-top: 20px; }
                                            @media print {
                                                .recu-section { page-break-inside: avoid; }
                                                body { margin: 0; padding: 0; }
                                            }
                                        </style>
                                    </head>
                                    <body>${contenu}</body>
                                </html>
                            `);
                                fenetre.document.close();
                                fenetre.onload = function() {
                                    fenetre.focus();
                                    fenetre.print();
                                    fenetre.onafterprint = function() {
                                        fenetre.close();
                                    };
                                };
                            }

                            @if (Session::has('success'))
                                window.onload = function() {
                                    imprimerRecu();
                                };
                            @endif
                        </script> --}}
                    @endif
                </div>
                <br>
                <br>


                   <script>
                    let saisieManuelle = false;

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
                        element.addEventListener('input', function() {
                            verifierSaisie(event);
                            verifierEtat(); // Vérifie l'état après chaque saisie
                        });
                    });

                    function repartirMontant() {
                        const montantPaye = parseFloat(document.getElementById('montant-paye').value) || 0;
                        if (montantPaye <= 0) return;

                        const composantes = Array.from(document.querySelectorAll('.composante'));

                        // Vérification : Si un champ a un montant dû de 0, il est désactivé
                        composantes.forEach(c => {
                            if (parseFloat(c.placeholder) === 0) {
                                c.disabled = true;
                                c.style.backgroundColor = "#e9ecef"; // Changer la couleur de fond pour indiquer le blocage
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
                            montantDu: parseFloat(c.placeholder) || 0
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
                        if (parseFloat(composante.placeholder) === 0) {
                            composante.value = 0;
                            composante.disabled = true;
                            composante.style.backgroundColor = "#e9ecef"; // Indique que le champ est bloqué
                            alert("Ce champ est bloqué car le montant dû est 0.");
                            return; // Empêche toute saisie supplémentaire
                        }

                        // Si le montant est inférieur à 0, l'annuler
                        if (valeur < 0) {
                            composante.value = 0;
                            alert("Les montants doivent être positifs.");
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
                            if (parseFloat(element.placeholder) === 0) {
                                element.disabled = true; // Désactiver les champs avec un montant dû de 0
                                element.style.backgroundColor = "#e9ecef"; // Indiquer qu'ils sont bloqués
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
                            alert("L'élève est à jour."); // Message à afficher
                        } else {
                            boutonEnregistrer.disabled = false;
                            boutonEnregistrer.style.backgroundColor = ""; // Réinitialiser la couleur
                            champModePaiement.disabled = false;
                            champModePaiement.style.backgroundColor = ""; // Réinitialiser la couleur
                        }
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
    });
  </script>
                <style>
                    /* Cacher le placeholder avec une opacité réduite */
                    input[id^="libelle-"]::placeholder,
                    input#arriere::placeholder,
                    input#scolarite::placeholder {
                        opacity: 0;
                    }

                    /* Animation et mise en évidence lors du changement de priorité */
                    #form-fields .col-md-2 {
                        transition: transform 0.3s ease, background-color 0.3s ease;
                    }

                    #priority-select option {
                        transition: background-color 0.3s ease;
                    }

                    #form-fields .col-md-2.moving,
                    #priority-select option.moving {
                        background-color: #f0f8ff;
                    }

                    #form-fields .composante:disabled {
                        background-color: #e9ecef;
                        /* Grise les champs désactivés */
                    }
                </style>
@endsection