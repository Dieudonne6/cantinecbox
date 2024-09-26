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
                <div class="card-body">
                    <h4 class="card-title">Paiement</h4>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" checked>
                                    Envoyer un SMS accuse de reception aux parents au numero
                                </label>
                            </div>
                        </div>
                        <div class="col-2">
                            <div>
                                <input class="form-control" type="text" value="+229"
                                    style="text-align: center; color:black;" readonly>
                            </div>
                        </div>
                        <div class="col-3">
                            <div>
                                @php
                                    $tel = $eleve->TEL;
                                    $formattedTel =
                                        strlen($tel) > 10 ? substr($tel, 0, 8) . ' / ' . substr($tel, 8) : $tel;
                                @endphp
                                <input id="numero" class="form-control" type="text" value="{{ $formattedTel }}"
                                    placeholder="numero" readonly>
                            </div>

                        </div>
                    </div><br><br>

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
                                            <th>Montant Paie</th>
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
                                                            Arriéré
                                                        @break

                                                        @case(2)
                                                            Scolarité
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
                                                            Espèce
                                                        @break

                                                        @case(2)
                                                            Chèque
                                                        @break

                                                        @case(3)
                                                            Opposition
                                                        @break

                                                        @case(4)
                                                            Autre
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
                                        <option value="1">Arriéré</option>
                                        <option value="2">Scolarité</option>
                                        <option value="3">{{ $libelle->LIBELF1 }}</option>
                                        <option value="4">{{ $libelle->LIBELF2 }}</option>
                                        <option value="5">{{ $libelle->LIBELF3 }}</option>
                                        <option value="6">{{ $libelle->LIBELF4 }}</option>
                                    </select>
                                    <div class="mt-2" style="text-align: center">
                                        <button id="monter-btn" type="button" class="btn btn-primary btn-sm"
                                            onclick="moveUp()">Monter</button>
                                        <button id="descendre-btn" type="button" class="btn btn-secondary btn-sm"
                                            onclick="moveDown()">Descendre</button>
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
                                        <input id="date-operation" name="date_operation" class="form-control" type="date"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col">
                                        <label for="montant-paye">Montant payé</label>
                                        <input id="montant-paye" name="montant_paye" class="form-control" type="number"
                                            placeholder="Entrez le montant payé" oninput="repartirMontant()" required>
                                    </div>
                                </div>

                                <div class="form-group row mt-4" id="form-fields">
                                    <div class="col-md-2" data-id="1">
                                        <label for="arriere">Arriéré</label>
                                        <input id="arriere" name="arriere" class="form-control composante" type="number"
                                            placeholder="{{ $eleve->ARRIERE ? $eleve->ARRIERE - $totalArriere : $eleve->ARRIERE }}"
                                            data-priorite="1" oninput="verifierSaisie()"
                                            {{ $eleve->ARRIERE == 0 ? 'disabled' : '' }}>
                                    </div>

                                    <div class="col-md-2" data-id="2">
                                        <label for="scolarite">Scolarité</label>
                                        <input id="scolarite" name="scolarite" class="form-control composante"
                                            type="number" placeholder="{{ $eleve->APAYER - $totalScolarite }}"
                                            data-priorite="2" oninput="verifierSaisie()">
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
                                                placeholder="{{ $eleve->$fraisField - $totalLibelle }}"
                                                oninput="verifierSaisie()">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Reliquat restant -->
                        <div style="color: red; font-weight: bold;">
                            <label for="reliquat">Reliquat restant : </label>
                            <span id="reliquat">0</span> F CFA
                        </div>

                        <br>

                        <!-- Mode de paiement -->
                        <div class="form-group row">
                            <div class="col-12">
                                <label for="mode-paiement" class="form-label">Mode de Paiement</label>
                                <select id="mode-paiement" name="mode_paiement" class="form-select" required>
                                    <option value="" disabled selected>Choisir un mode de paiement</option>
                                    <option value="1">Espèce</option>
                                    <option value="2">Chèque</option>
                                    <option value="3">Opposition</option>
                                    <option value="4">Autre</option>
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
                                <div class="recu-container"
                                    style="display: flex; justify-content: space-between; gap: 20px; border: 1px solid #ccc; padding: 20px; background-color: #f9f9f9;">
                                    @php
                                        $libelles = ['LIBELF1', 'LIBELF2', 'LIBELF3'];
                                    @endphp

                                    @foreach (['Souche', 'Original'] as $type)
                                        <div class="recu-section"
                                            style="flex: 1; border: 1px solid #333; padding: 20px; background-color: #fff;">
                                            <h5
                                                style="text-align: center; font-size: 18px; margin-bottom: 20px; font-weight: bold;">
                                                Reçu de Paiement ({{ $type }})
                                            </h5>
                                            <div style="margin-bottom: 15px;">
                                                <p style="margin: 0; font-size: 14px;"><strong>Élève:</strong>
                                                    {{ $eleve->NOM }} {{ $eleve->PRENOM }}</p>
                                                <p style="margin: 0; font-size: 14px;"><strong>Classe:</strong>
                                                    {{ $eleve->CODECLAS }}</p>
                                                <p style="margin: 0; font-size: 14px;"><strong>Date:</strong>
                                                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                                                    <strong>Numéro de reçu :</strong> {{ Session::get('numeroRecu') }}
                                            </div>
                                            <hr style="border-top: 1px dashed #333; margin: 15px 0;">
                                            <div style="margin-bottom: 15px;">
                                                <p style="margin: 0; font-size: 14px;"><strong>Montant payé:</strong>
                                                    {{ Session::get('montantPaye') }} F CFA</p>
                                                <p style="margin: 0; font-size: 14px;"><strong>Mode de paiement:</strong>
                                                    {{ Session::get('mode_paiement') }}</p>
                                                <p style="margin: 0; font-size: 14px;"><strong>Arriéré:</strong>
                                                    {{ Session::get('arriéré') }}</p>
                                                <p style="margin: 0; font-size: 14px;"><strong>Scolarité:</strong>
                                                    {{ Session::get('scolarite') }}</p>
                                            </div>
                                            <hr style="border-top: 1px dashed #333; margin: 15px 0;">
                                            @foreach ($libelles as $index => $libelleKey)
                                                <p style="margin: 0; font-size: 14px;">
                                                    <strong>{{ $libelle->$libelleKey }}:</strong>
                                                    {{ Session::get('libelle_' . ($index + 1)) }}
                                                </p>
                                            @endforeach
                                            <div style="font-weight: bold;">
                                                <label for="reliquat">Reliquat restant : </label>
                                                <span id="reliquat">0</span> F CFA
                                            </div>
                                            <hr style="border-top: 1px dashed #333; margin: 15px 0;">
                                            <div class="recu-footer" style="text-align: center; margin-top: 20px;">
                                                <p style="font-size: 14px;"><strong>CCC, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</strong> 
                                                </p>
                                                <p style="font-size: 14px;"><strong>Le Comptable Gestion</strong> 
                                                </p>
                                                {{ Session::get('signature') }}
                                            </div>

                                        </div>
                                    @endforeach
                                </div>

                                <div class="bordered"
                                    style="border-top: 1px dashed #333; margin-top: 20px; padding-top: 10px; text-align: center;">
                                    <p style="font-size: 14px; color: #666;">Merci d'avoir effectué votre paiement.</p>
                                </div>

                                <!-- Bouton pour imprimer le reçu -->
                                <button onclick="imprimerRecu()" class="btn btn-success mt-4">Imprimer le Reçu</button>
                            </div>

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
                                            body {
                                                font-family: Arial, sans-serif;
                                                padding: 20px;
                                            }
                                            .recu-container {
                                                display: flex;
                                                justify-content: space-between;
                                                border: 1px solid #000;
                                                padding: 20px;
                                                background-color: #f9f9f9;
                                            }
                                            .recu-section {
                                                width: 48%;
                                                border: 1px solid black;
                                                padding: 15px;
                                                box-sizing: border-box;
                                                background-color: #fff;
                                            }
                                            h5 {
                                                text-align: center;
                                                font-size: 18px;
                                                font-weight: bold;
                                                margin-bottom: 20px;
                                            }
                                            .recu-footer {
                                                text-align: center;
                                                margin-top: 20px;
                                            }
                                            .bordered {
                                                border-top: 1px dashed black;
                                                margin-top: 20px;
                                                padding-top: 10px;
                                                text-align: center;
                                                color: #666;
                                            }
                                            p {
                                                font-size: 14px;
                                                margin: 5px 0;
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
                            </script>
                        </div>
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
                    });

                    document.querySelectorAll('.composante').forEach(element => {
                        element.addEventListener('input', verifierSaisie);
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
                </script>

                <script>
                    // Gestion des priorités dynamiques pour monter/descendre les options et les champs correspondants
                    function moveUp() {
                        var select = document.getElementById("priority-select");
                        var selectedOptions = Array.from(select.selectedOptions);

                        selectedOptions.forEach(function(option) {
                            var index = option.index;
                            if (index > 0) {
                                // Déplacer les options dans le select
                                select.insertBefore(option, select.options[index - 1]);

                                // Déplacer les champs correspondants dans le formulaire
                                var field = document.querySelector(`#form-fields [data-id="${option.value}"]`);
                                var prevField = field.previousElementSibling;
                                if (prevField) {
                                    field.style.transition = 'transform 0.3s ease'; // Animation
                                    prevField.style.transition = 'transform 0.3s ease';
                                    document.getElementById("form-fields").insertBefore(field, prevField);
                                }
                            }
                        });

                        mettreAJourPriorites(); // Mettre à jour les priorités après avoir changé l'ordre
                        mettreAJourEtatBoutons();
                    }

                    function moveDown() {
                        var select = document.getElementById("priority-select");
                        var selectedOptions = Array.from(select.selectedOptions);

                        for (var i = selectedOptions.length - 1; i >= 0; i--) {
                            var option = selectedOptions[i];
                            var index = option.index;
                            if (index < select.options.length - 1) {
                                // Déplacer les options dans le select
                                select.insertBefore(select.options[index + 1], option);

                                // Déplacer les champs correspondants dans le formulaire
                                var field = document.querySelector(`#form-fields [data-id="${option.value}"]`);
                                var nextField = field.nextElementSibling;
                                if (nextField) {
                                    field.style.transition = 'transform 0.3s ease'; // Animation
                                    nextField.style.transition = 'transform 0.3s ease';
                                    document.getElementById("form-fields").insertBefore(nextField, field);
                                }
                            }
                        }

                        mettreAJourPriorites(); // Mettre à jour les priorités après avoir changé l'ordre
                        mettreAJourEtatBoutons();
                    }

                    function mettreAJourPriorites() {
                        // Met à jour l'attribut data-priorite en fonction de l'ordre dans le select
                        var select = document.getElementById("priority-select");
                        var options = Array.from(select.options);

                        options.forEach((option, index) => {
                            // Met à jour les priorités dans le formulaire
                            var field = document.querySelector(`#form-fields [data-id="${option.value}"]`);
                            field.querySelector('.composante').dataset.priorite = index + 1; // L'index correspond à la priorité
                        });
                    }
                    // document.getElementById("priority-select").addEventListener("change", mettreAJourEtatBoutons);
                </script>

                <style>
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
