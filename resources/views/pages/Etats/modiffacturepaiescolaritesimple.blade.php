
@extends('layouts.master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .btn-arrow {
            position: absolute;
            top: 0px; /* Ajustez la position verticale */
            left: 0px; /* Positionnez à gauche */
            background-color: transparent !important;
            border:1px !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
            cursor: pointer!important;
            font-size: 17px!important; /* Taille de l'icône */
            color: #b51818!important; /* Couleur de l'icône */
        }
        
        .btn-arrow:hover {
            color: #b700ff !important; /* Couleur au survol */
        }
    </style>
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
                            
                            /* .btn {
                                padding: 0.25rem 0.5rem;
                                font-size: 0.875rem;
                                transition: transform 0.2s ease, background-color 0.3s ease;
                            } */
                            
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

    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
       <button class="btn btn-arrow" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Retour
        </button></br>

        @if(Session::has('status'))
            <div id="statusAlert" class="alert alert-succes btn-primary">
            {{ Session::get('status')}}
            </div></br>
        @endif

        @if(Session::has('erreur'))
            <div id="statusAlert" class="alert alert-danger btn-primary">
            {{ Session::get('erreur')}}
            </div></br>
        @endif
                <h4 class="card-title" style="text-align: center">Annulation de la facture de paiement de <strong>{{ $factureOriginale->nom }}</strong></h4>

                <form action="{{url('modiffacturescolaritsimple/'.$id)}}" method="POST">
                    @csrf
                    {{-- @if(Session::has('id_usercontrat'))
                        <input type="hidden" value="{{$id_usercontrat}}" name="id_usercontrat">
                    @endif --}}
                    <div class=" col-md-12 mx-auto grid-margin stretch-card">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Infos de la facture de vente</h4>
                                {{-- <p class="card-description">
                                    Veuillez remplir les champs
                                </p> --}}
                                <div class="form-group row">
                                    <div class="col">
                                        <label>Date de paiement</label>
                                        <div id="the-basics">
                                            <input class="typeaheads" type="datetime-local" id="date" name="dateAncienne"
                                                value="{{ $factureOriginale->dateHeure }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>Montant</label>
                                        <div id="bloodhound">
                                            <input class="typeaheads" id="fraismensuelleAvoir" name="montantcontratAncien" type="text" value="{{ $factureOriginale->montant_total }}" readonly>
                                            {{-- <input class="typeaheads" id="fraismensuelleAvoircache" name="montantcontratReelAncien" type="hidden" value="{{ $fraismensuelle }}" > --}}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class=" col-md-12 mx-auto grid-margin stretch-card">
                        <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Type de Modification</h4>
                            <div class="form-group row">
                                {{-- <div class="col">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="typeFormulaire" id="radio1" value="corriger_eleve" >
                                        <label class="form-check-label" for="radio1">Corriger Elève</label>
                                    </div>
                                </div> --}}
                                <div class="col">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="typeFormulaire" id="radio2" value="corriger_paiement" checked>
                                        <label class="form-check-label" for="radio2">Corriger Paiement</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire 1 : Par Codemef -->
                    {{-- <div id="form1-container">
                        <div class=" col-md-12 mx-auto grid-margin stretch-card">
                            <select class="form-select js-example-basic-multiple" id="eleve" name="eleve" style="width: 100%;">
                              <option value="">Sélectionner l'élève</option>
                                @foreach ($eleves as $eleve)
                                    <option value="{{ $eleve->MATRICULE }}">{{ $eleve->NOM }} {{ $eleve->PRENOM }}</option>
                                @endforeach
                            </select>
                          </div>
                    </div> --}}

                    <!-- Formulaire 2 : Par Justificatif -->
                    <div id="form2-container">
                        <div class=" col-md-12 mx-auto grid-margin stretch-card">
                            <div class="card">
                                {{--  --}}
                                <div class="card-body">
                                    <h4 class="card-title">Paiement pour <strong>{{ $infoeleve->NOM }} {{ $infoeleve->PRENOM }}</strong></h4><br><br>

                                    <div class="row">
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
                                                        <option value="1">Arriéré</option>
                                                        <option value="2">{{ $libelle->LIBELF1 }}</option>
                                                        <option value="3">{{ $libelle->LIBELF2 }}</option>
                                                        <option value="4">{{ $libelle->LIBELF3 }}</option>
                                                        <option value="5">{{ $libelle->LIBELF4 }}</option>
                                                        <option value="6">Scolarité</option>
                                                    </select>
                                                    <div class="mt-1" style="text-align: center">
                                                        <button id="monter-btn" type="button" class="btn btn-sm bg-primary">Monter</button>
                                                        <button id="descendre-btn" type="button" class="btn btn-sm bg-accent">Descendre</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><br><br>
                                    <!-- Formulaire -->

                                    {{-- <form action="{{ route('enregistrer.paiement', $infoeleve->MATRICULE) }}" method="POST"> --}}
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
                                                    <div class="col-md-4 col-lg-2 mb-2" data-id="1"><!-- Classes responsives ajoutées -->
                                                        <label for="arriere">Arriéré</label>
                                                        <input id="arriere" name="arriere" class="form-control composante" type="number"
                                                            placeholder="{{ $infoeleve->ARRIERE ? $infoeleve->ARRIERE - $totalArriere : $infoeleve->ARRIERE }}"
                                                            value="0" data-priorite="1" data-due="{{ $infoeleve->ARRIERE ? $infoeleve->ARRIERE - $totalArriere : $infoeleve->ARRIERE }}" oninput="verifierSaisie(event)"
                                                            {{ $infoeleve->ARRIERE == 0 ? 'disabled' : '' }}
                                                            aria-label="Montant des arriérés">
                                                    </div>

                                                    <!-- Champs générés dynamiquement -->
                                                    @foreach (['LIBELF1', 'LIBELF2', 'LIBELF3', 'LIBELF4'] as $key => $libelleField)
                                                        <div class="col-md-4 col-lg-2 mb-2" data-id="{{ $key + 2 }}"><!-- Classes responsives ajoutées -->
                                                            <label for="libelle-{{ $key }}">{{ $libelle->$libelleField }}</label>
                                                            @php
                                                                $fraisField = 'FRAIS' . ($key + 1);
                                                                $totalLibelle = ${'totalLibelle' . ($key + 1)} ?? 0;
                                                            @endphp
                                                            <input id="libelle-{{ $key }}" name="libelle_{{ $key }}"
                                                            class="form-control composante" type="number"
                                                            data-priorite="{{ $key + 2 }}" data-due="{{ $infoeleve->$fraisField - $totalLibelle }}"
                                                            placeholder="{{ $infoeleve->$fraisField - $totalLibelle }}"
                                                            value="0" oninput="verifierSaisie(event)"
                                                            aria-label="Montant pour {{ $libelle->$libelleField }}">
                                                        </div>
                                                    @endforeach

                                                    <div class="col-md-4 col-lg-2 mb-2" data-id="6"><!-- Classes responsives ajoutées -->
                                                        <label for="scolarite">Scolarité</label>
                                                        <input id="scolarite" name="scolarite" class="form-control composante"
                                                            type="number" placeholder="{{ $infoeleve->APAYER - $totalScolarite }}"
                                                            value="0" data-priorite="6" data-due="{{ $infoeleve->APAYER - $totalScolarite }}" oninput="verifierSaisie(event)"
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

                                            </div>
                                        </div>

                                        <!-- Champ caché pour le MATRICULE -->
                                        <input type="hidden" name="matricule" value="{{ $infoeleve->MATRICULE }}">

                                        {{-- <button type="submit" class="btn btn-primary">Modifier le paiement</button> --}}
                                    {{-- </form> --}}
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
                                                        <p style="margin: 0; font-size: 16px;">{{ $infoeleve->CODECLAS }}</p>
                                                        <p style="margin: 0; font-size: 16px; display: flex; justify-content: flex-end;"><strong>QUITANCE N°</strong> {{ Session::get('numeroRecu') }}/{{ $infoeleve->anneeacademique }}</p>
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                        <p style="margin: 0; font-size: 16px; text-align: center;">{{ $infoeleve->NOM }} {{ $eleve->PRENOM }}</p>
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
                                {{--  --}}
                            </div>
                        </div>
                    </div>

                    <div class=" col-md-12 mx-auto grid-margin stretch-card mt-5 mb-5">

                        <input type="submit" class="btn btn-primary mr-2" value="Confirmer">
                        <input type="reset" class="btn btn-light" value="Annuler">
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const radio1 = document.getElementById("radio1");
        const radio2 = document.getElementById("radio2");
        const form1 = document.getElementById("form1-container");
        const form2 = document.getElementById("form2-container");
    
        function toggleForms() {
            if (radio1.checked) {
                form1.classList.remove("d-none");
                form2.classList.add("d-none");
            } else {
                form1.classList.add("d-none");
                form2.classList.remove("d-none");
            }
        }
    
        radio1.addEventListener("change", toggleForms);
        radio2.addEventListener("change", toggleForms);
    
        toggleForms(); // Au cas où une valeur est déjà cochée
    });
</script>



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
    

    <style>
        input[type="radio"] {
            transform: scale(1.5); /* Agrandit le bouton */
            accent-color: #b51818; /* Change la couleur principale (fonctionne sur navigateurs modernes) */
            margin-right: 10px;
            cursor: pointer;
        }
    </style>
    
{{-- 
    <script>
        // Attend que le document soit prêt
        document.addEventListener("DOMContentLoaded", function() {
        // Sélectionne tous les éléments avec la classe checkbox-mois
        var checkboxes = document.querySelectorAll('.checkbox-mois');
        var fraismensuelle = document.querySelector('#fraismensuelle');
        var fraistotal = document.querySelector('#fraistotal');
    
        // Fonction pour mettre à jour le montant total en fonction du nombre de cases cochées et du frais mensuel
        function updateTotalAmount() {
            var valeurInput = fraismensuelle.value;
            var checkedCheckboxes = document.querySelectorAll('.checkbox-mois:checked');
            var numberOfCheckedCheckboxes = checkedCheckboxes.length;
            var montantTotal = numberOfCheckedCheckboxes * valeurInput;
            fraistotal.value = montantTotal;
        }
    
        // Écoute les changements de valeur de l'élément fraismensuelle
        fraismensuelle.addEventListener('input', function() {
            // Met à jour le montant total lorsque la valeur du frais mensuel change
            updateTotalAmount();
        });
    
        // Écoute les changements d'état des cases à cocher
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Met à jour le montant total lorsque le nombre de cases cochées change
                updateTotalAmount();
            });
        });
    
        // Met à jour le montant total initial
        updateTotalAmount();
    });
    </script> --}}