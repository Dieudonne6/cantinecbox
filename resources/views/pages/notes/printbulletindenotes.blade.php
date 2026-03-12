@extends('layouts.master')
@section('content')
    @if (isset($option['fond']))
        <div style="position: relative; margin-left: 10px; margin-right: 10px; min-height: 100vh; overflow: hidden;">
            <div class="bulletin-bg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('{{ $image ? asset('img/fonds/' . $image) : '' }}'); background-position: center;"></div>
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.7);"></div>
    @else
        <div style="margin-left: 10px; margin-right: 10px;">
    @endif
              

        <div class="col-lg-12" style="margin-top: 0; padding-top: 0;">
            <div class="card-body" {{-- style="margin-top: 0; padding-top: 0;" --}}>
                <div>
                    <style>
                        /* Boutons flottants (fixés dans la fenêtre) */
                        /* .floating-action-left {
                            position: fixed;
                            top: 16px; /* ajuste si tu as une navbar 
                            left: 16px;
                            z-index: 9999;
                        } */
                    
                        .floating-actions-right {
                            position: fixed;
                            top: 100px; /* ajuste si tu as une navbar */
                            right: 16px;
                            z-index: 9999;
                            display: flex;
                            gap: 8px;
                            align-items: center;
                        }
                    
                        /* Style du bouton retour (reprend ton style mais sans position:absolute) */
                        .btn-arrow {
                            background-color: transparent !important;
                            border: none !important;
                            text-transform: uppercase !important;
                            font-weight: bold !important;
                            cursor: pointer !important;
                            font-size: 17px !important;
                            color: #b51818 !important;
                            padding: .4rem .6rem;
                        }
                    
                        .btn-arrow:hover {
                            color: #b700ff !important;
                        }
                    
                        /* Ne pas afficher ces boutons dans la version imprimée */
                        @media print {
                            .floating-action-left,
                            .floating-actions-right {
                                display: none !important;
                            }
                        }
                    </style>
                    
                    <div class="floating-action-left">
                        <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                    </div>
                    
                    <div class="floating-actions-right">
                        <button class="btn btn-primary" onclick="imprimerliste()">Imprimer</button>
                    </div>
                    
                </div>

                @php
                    $sortedResultats = collect($resultats)->sortBy('nom');
                @endphp


                    @php
                        $page = 1;
                    @endphp
                @foreach ($sortedResultats as $index => $resultat)
                    @php
                        // Initialisation des variables en fonction du type d'année
                        $periode = null;
                        $texte = null;
                        $texte2 = null;
                        $periode_abr = null;
                        if ($typean == 1) {
                            $periode = 'Semestre';
                            $texte = 'Semestriel';
                            $texte2 = 'Semestrielle';
                            $periode_abr = 'Sem.';
                        } else {
                            $periode = 'Trimestre';
                            $texte = 'Trimestriel';
                            $texte2 = 'Trimestrielle';
                            $periode_abr = 'Trim.';
                        }

                        // Trier les matières par CODEMAT
                        usort($resultat['matieres'], function ($a, $b) {
                            return $a['code_matiere'] - $b['code_matiere'];
                                                });
                    @endphp
                    </br>


                    <div class="bulletin" data-nom="{{ $resultat['nom'] }} .''.{{ $resultat['prenom'] }} "
                        data-classe="{{ $resultat['classe'] }}"
                        {{-- style="position: relative; {{ $index < count($resultats) - 1 ? 'page-break-after: always;' : '' }}"> --}}
                        style="position: relative; page-break-after: always;">

                        <div class="row" style="display: flex; align-items: flex-start;">
                            {{-- Logo, aligné en haut --}}
                            <div class="col-md-3 p-0">
                                @if (!empty($logoBase64))
                                    <div style="align-self: flex-start; margin-bottom: 5px;" class="ml-4">
                                        <img src="data:{{ $mimeType }};base64,{{ $logoBase64 }}" alt="Logo"
                                            style="width: 145px; height: 140px;">
                                        
                                    </div>
                                @endif

                                {{-- Carré, sans marge à gauche pour être collé au logo --}}
                                {{-- <div id="carre" class="ml-5"
                                        style="width: 80px; height: 80px; background-color: transparent; border: 1px solid black; margin-left: 10px; margin-right: 20px;">
                                    </div> --}}
                            </div>
                            <div class="col-md-6 p-0">

                                {{-- Entête, centré et aligné par le bas --}}
                                @if (isset($option['entete']) && $option['entete'])
                                    @php
                                        // Augmenter la taille du College
                                        $entete = str_replace(
                                            'font-size:19px',
                                            'font-size:20px', // nouvelle taille pour le College
                                            $entete,
                                        );
                                        // Augmenter la taille du Bulletin de Notes
                                        $entete = str_replace(
                                            'font-size:28px',
                                            'font-size:32px', // nouvelle taille pour le Bulletin de Notes
                                            $entete,
                                        );
                                    @endphp
                                    <div style="flex: 1; text-align: center;">
                                        <style>
                                            p,
                                            span {
                                                margin: 0 !important;
                                                padding: 0 !important;
                                                line-height: 1 !important;
                                            }
                                        </style>
                                        {!! $entete !!}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-3 p-0" style="text-align: center;">
                                @if (isset($option['photo_par_logo']) && $option['photo_par_logo'] && !empty($logoBase64))
                                    {{-- <div
                                            style="width: 145px; height: 140px; margin: 0 auto 20px auto; background-color: transparent; border: 1px solid black; border-radius: 20px; display: flex; align-items: center; justify-content: center;"> --}}
                                    <img src="data:{{ $mimeType }};base64,{{ $logoBase64 }}" alt="Logo"
                                        style="width: 145px; height: 140px;">
                                    {{-- </div> --}}
                                @else
                                    <div id="photo" class="ml-9"
                                        style="width: 145px; height: 140px; margin: 0 auto 20px auto; background-color: transparent; border: 1px solid black; border-radius: 20px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if (!empty($resultat['photo']))
                                            <img src="data:image/jpeg;base64,{{ base64_encode($resultat['photo']) }}"
                                                alt="Photo élève" style="width: 145px; height: 140px;">
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        </br>
                        </br>
                        <div class="d-flex" style="align-items: stretch;">
                            <div id="donneeleve"
                                style="width: 60%; background-color: transparent; border: 1px solid black; border-radius: 10px; display: flex; flex-direction: column; padding: 10px; box-sizing: border-box;">
                                <h4 class="ml-2" style="margin-top: 5px; font-weight: 200;">NOM : <span
                                        class="font-weight-bold">{{ $resultat['nom'] }}</span></h4>
                                <h4 class="ml-2" style="margin-top: 5px; font-weight: 200;">PRENOMS : <span
                                        class="font-weight-bold">{{ $resultat['prenom'] }}</span></h4>
                                <div class="d-flex" style="margin-top: auto;">
                                    <h4 class="ml-2" style="margin-top: 5px; font-weight: 400; white-space: nowrap;">
                                        Redoublant (e) :
                                        <label for="redoublant_oui">OUI</label>
                                        <input class="disable" type="checkbox" name="redoublant" id="redoublant_oui"
                                            readonly {{ $resultat['redoublant'] == 1 ? 'checked' : '' }}>&nbsp;&nbsp;
                                            <label for="redoublant_non">NON</label>
                                        <input class="disable" type="checkbox" name="redoublant" id="redoublant_non"
                                            readonly {{ $resultat['redoublant'] == 0 ? 'checked' : '' }}>
                                        @if (isset($option['matricule']) && $option['matricule'])
                                            <label
                                                style="margin-left: 10px; font-size:23px; white-space: nowrap; min-width: 200px;">Mat.
                                                {{ $resultat['matriculex'] }}</label>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                            <div
                                style="width: 20%; background-color: transparent; border: 1px solid black; border-radius: 10px; display: flex; flex-direction: column; justify-content: center; padding: 10px; box-sizing: border-box;">
                                <h5 class="ml-2 d-inline-block"
                                    style="margin-bottom: 0; font-size: 14px; font-weight: 400; text-align: center;"
                                    id="sco">
                                    Année scolaire : {{ $resultat['anneScolaire'] }}
                                </h5>
                                <h5 id="periode" class="text-center" style="margin-top: 20px; font-weight: bold; flex-grow: 1; display: flex; align-items: center; justify-content: center;">
                                    @switch($resultat['periode'])
                                        @case('1')
                                            1er {{ $periode }}
                                        @break

                                        @case('2')
                                            2e {{ $periode }}
                                        @break

                                        @default
                                            3e {{ $periode }}
                                    @endswitch
                                </h5>
                            </div>
                            <div id="classe"
                                style="width: 20%; background-color: transparent; border: 1px solid black; border-radius: 10px; display: flex; flex-direction: column; justify-content: center; padding: 10px; box-sizing: border-box;">
                                <h5 class="ml-2" style="margin-top: 0; font-weight: 400;" id="sco">Classe : <span
                                        class="font-weight-bold">{{ $resultat['classe'] }}</span></h5>
                                        <br>
                                <h5 class="ml-2" style="margin-top: 20px; font-weight: 400;">Effectif : <span
                                        class="font-weight-bold">{{ $resultat['effectif'] }}</span></h5>
                            </div>
                        </div>
                        <table style="width: 100%; margin-top:2px" id="tableau">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size:20px; font-weight: bold; width: 150px;">
                                        DISCIPLINES</th>
                                    <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">Cf</th>
                                    <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">Moy.
                                        <br> Int
                                    </th>
                                    <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">Dev.
                                        <br>1
                                    </th>
                                    <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">Dev.
                                        <br>2
                                    </th>
                                    @if (!isset($option['masquer_devoir3']))
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">Dev.
                                            <br>3
                                        </th>
                                    @endif
                                    @if (!isset($option['note_test']) || !$option['note_test'])
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">
                                            Moy.<br>20</th>
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">
                                            Moy.<br>coef</th>
                                    @endif
                                    @if (isset($option['note_test']) && $option['note_test'])
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">
                                            Moy.<br> part</th>
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">
                                            Compo</th>
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">
                                            Moy.<br>20</th>
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">
                                            Moy.<br>coef</th>
                                    @else
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">
                                            Faible moy.</th>
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">
                                            Forte moy.</th>
                                    @endif
                                    @if (isset($option['rang_matiere']) && $option['rang_matiere'])
                                        <th class="text-center" style="font-size:20px; font-weight: bold; width: 50px;">
                                            Rang
                                        </th>
                                    @endif
                                    <th class="text-center" style="font-size:20px; font-weight: bold; width: 150px;">
                                        Appréciations
                                        <br>professeurs
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                    $note_conduite = null;
                                    $total_coefficients = 0;
                                    $total_moyenne_coeffs = 0;
                                   
                                @endphp
                                @foreach ($resultat['matieres'] as $matiere)
                                    {{-- DEBUT ignorer la matiere si l'eleve n'a pas fait aucun des deux devoirs --}}
                                    {{-- @php
                                        $dev1Valid =
                                            isset($matiere['devoir1']) &&
                                            $matiere['devoir1'] != 21 &&
                                            $matiere['devoir1'] != -1;
                                        $dev2Valid =
                                            isset($matiere['devoir2']) &&
                                            $matiere['devoir2'] != 21 &&
                                            $matiere['devoir2'] != -1;
                                    @endphp --}}

                                    {{-- Si la matière n'est pas "conduite" et qu'aucun des deux devoirs n'est valide, on passe à la suivante --}}
                                    {{-- @if ($matiere['code_matiere'] != $request->input('conduite') && !$dev1Valid && !$dev2Valid)
                                        @continue
                                    @endif --}}
                                    {{-- FIN ignorer la matiere si l'eleve n'a pas fait aucun des deux devoirs --}}


                                    @php
                                        $i++;
                                        $moyenne_part = $matiere['moyenne_sur_20'];
                                        
                                        if (
                                            $matiere['test'] != null &&
                                            isset($option['note_test']) &&
                                            $option['note_test']
                                        ) {
                                            $moyenne_sur_20 = ($moyenne_part + $matiere['test']) / 2;
                                        } else {
                                            $moyenne_sur_20 = $matiere['moyenne_sur_20'];
                                        }
                                        if ($matiere['coefficient'] != -1) {
                                            if (!is_null($moyenne_sur_20)) {
                                                // Si $moyenne_sur_20 existe, on calcule et on ajoute le coefficient
                                                if ($moyenne_sur_20 === 21) {
                                                    $moyenne_coeff = 0;
                                                } else {
                                                    $moyenne_coeff = number_format($moyenne_sur_20 * $matiere['coefficient'], 2);
                                                    $total_coefficients += $matiere['coefficient'];
                                                }
                                            } else {
                                                // Si $moyenne_sur_20 est null, on ne fait rien (ou on peut initialiser $moyenne_coeff à 0 ou null selon ce que vous souhaitez)
                                                $moyenne_coeff = 0;
                                            }
                                            // $moyenne_coeff = $moyenne_sur_20 * $matiere['coefficient'];
                                            // $total_coefficients += $matiere['coefficient'];
                                        } elseif (
                                            $matiere['coefficient'] == -1 &&
                                            $request->input('bonificationType') == 'integral'
                                        ) {
                                            $moyenne_coeff = $matiere['surplus'];
                                        } elseif (
                                            $matiere['coefficient'] == -1 &&
                                            $request->input('bonificationType') == 'Aucun'
                                        ) {
                                            $moyenne_coeff = 0;
                                        } elseif (
                                            $matiere['coefficient'] == -1 &&
                                            $request->input('bonificationType') == 'intervalle'
                                        ) {
                                            $moyenne_coeff = $matiere['moyenne_intervalle'];
                                        }
                                        if ($matiere['code_matiere'] == $request->input('conduite')) {
                                            $note_conduite = $matiere['moyenne_sur_20'];
                                        }
                                        $total_moyenne_coeffs += $moyenne_coeff;
                                        if (
                                            !isset($option['note_conduite']) &&
                                            $matiere['code_matiere'] == $request->input('conduite')
                                        ) {
                                            $total_moyenne_coeffs = $total_moyenne_coeffs - $note_conduite;
                                            $total_coefficients = $total_coefficients - $matiere['coefficient'];
                                        }
                                    @endphp
                                    @if (!isset($option['note_conduite']) && $matiere['code_matiere'] == $request->input('conduite'))
                                        @continue;
                                    @endif
                                    <tr style="white-space: nowrap;">
                                        <td style="text-align: left; font-weight:400">{{ $matiere['nom_matiere'] }}</td>
                                        <td>{{ $matiere['coefficient'] }}</td>
                                        {{-- <td>{{ number_format($matiere['moyenne_interro'], 2) ?? '**.**' }}</td> --}}
                                        <td>{{ isset($matiere['moyenne_interro']) && $matiere['moyenne_interro'] != 21 ? number_format((float) $matiere['moyenne_interro'], 2) : '**.**' }}
                                        <td>{{ isset($matiere['devoir1']) && $matiere['devoir1'] != 21 ? $matiere['devoir1'] : '**.**' }}
                                        </td>
                                        <td>{{ isset($matiere['devoir2']) && $matiere['devoir2'] != 21 ? $matiere['devoir2'] : '**.**' }}
                                        </td>
                                        @if (!isset($option['masquer_devoir3']))
                                            <td>{{ isset($matiere['devoir3']) && $matiere['devoir3'] != 21 ? $matiere['devoir3'] : '**.**' }}
                                            </td>
                                        @endif
                                        @if (!isset($option['note_test']) || !$option['note_test'])
                                            <td class="bold-text" style="font-weight: bold">
                                                {{-- @php
                                                    
                                                    var_dump($moyenne_sur_20)
                                                @endphp --}}
                                                    {{-- {{ 
                                                        isset($moyenne_sur_20) 
                                                            ? number_format($moyenne_sur_20, 2) 
                                                            : '**.**' 
                                                    }} --}}

                                                    {{ isset($moyenne_sur_20) && $moyenne_sur_20 != 21 ? number_format((float) $moyenne_sur_20, 2) : '**.**' }}
                                            </td>
                                            @if ($matiere['coefficient'] == -1 && $request->input('bonificationType') == 'integral')
                                                
                                                <td>
                                                    @if (
                                                        $matiere['code_matiere'] == $request->input('conduite') || $matiere['code_matiere'] == $request->input('eps')                        
                                                        )
                                                        {{ isset($moyenne_coeff) && $moyenne_sur_20 != 21 ? number_format((float) $moyenne_coeff, 2) : '**.**' }}
                                                    @else
                                                        + {{ isset($moyenne_coeff) && $moyenne_sur_20 != 21 ? number_format((float) $moyenne_coeff, 2) : '**.**' }}
                                                    @endif
                                                </td>
                                            @else
                                                {{-- @php
                                                    
                                                    var_dump($moyenne_coeff)
                                                @endphp --}}
                                                {{-- <td>{{ isset($moyenne_coeff) ? number_format($moyenne_coeff, 2) : '**.**' }}</td> --}}
                                                <td>
                                                    @if (
                                                        $matiere['code_matiere'] == $request->input('conduite') || $matiere['code_matiere'] == $request->input('eps')                        
                                                        )
                                                        {{ isset($moyenne_coeff) && $moyenne_coeff != 0 ? number_format((float) $moyenne_coeff, 2) : '**.**' }}
                                                    @else
                                                         {{ isset($moyenne_coeff) && $moyenne_coeff != 0 ? number_format((float) $moyenne_coeff, 2) : '**.**' }}
                                                    @endif
                                                </td>
                                        @endif
                                        @endif
                                        @if (isset($option['note_test']) && $option['note_test'])
                                            {{-- <td></td>
                                            <td></td> --}}
                                            {{-- <td>{{ number_format((float) $moyenne_part, 2) }}</td> --}}
                                            <td>
                                                @if (
                                                $matiere['code_matiere'] == $request->input('conduite') || $matiere['code_matiere'] == $request->input('eps')
                                                && $moyenne_part == 0
                                                )
                                                {{ $moyenne_part != 0 ? number_format((float) $moyenne_part, 2) : '**.**' }}
                                                @else
                                                {{ $moyenne_sur_20 != 21 ? number_format((float) $moyenne_part, 2) : '**.**' }}
                                                @endif
                                            </td>
                                            {{-- <td>{{  $matiere['test'] ?? '**.**' }}</td> --}}
                                            <td>
                                           
                                                {{ $matiere['test'] ?? '**.**' }}

                                            </td>
                                            <td class="bold-text" style="font-weight: bold">
                                                @if (
                                                $matiere['code_matiere'] == $request->input('conduite') || $matiere['code_matiere'] == $request->input('eps')
                                                && $moyenne_part == 0
                                                )
                                                {{ isset($moyenne_sur_20) ? number_format($moyenne_sur_20, 2) : '**.**' }}
                                            @else
                                                {{ isset($moyenne_sur_20) && $moyenne_sur_20 != 21 ? number_format($moyenne_sur_20, 2) : '**.**' }}
                                            @endif
                                            </td>
                                                                                            {{-- @php
                                                    
                                                    var_dump($moyenne_coeff)
                                                @endphp --}}
                                            <td>
                                            @if (
                                                $matiere['code_matiere'] == $request->input('conduite') || $matiere['code_matiere'] == $request->input('eps')
                                                && $moyenne_part == 0
                                                )
                                                {{ isset($moyenne_coeff) && $moyenne_part != 0 ? number_format((float) $moyenne_coeff, 2) : '**.**' }}
                                            @else
                                                {{ isset($moyenne_coeff) && $moyenne_sur_20 != 21 ? number_format((float) $moyenne_coeff, 2) : '**.**' }}
                                            @endif
                                            </td>
                                        @else
                                            {{-- <td>{{ number_format($matiere['plusFaibleMoyenne'], 2) ?? '**.**' }}</td> --}}
                                            <td>
                                                {{ 
                                                    isset($matiere['plusFaibleMoyenne'])
                                                    ? number_format($matiere['plusFaibleMoyenne'], 2)
                                                    : '**.**'
                                                }}
                                            </td>
                                            <td>
                                                {{ 
                                                    isset($matiere['plusForteMoyenne'])
                                                    ? number_format($matiere['plusForteMoyenne'], 2)
                                                    : '**.**'
                                                }}
                                            </td>
                                            {{-- <td>{{ number_format($matiere['plusForteMoyenne'], 2) ?? '**.**' }}</td> --}}
                                            {{-- <td>{{ number_format($matiere['plusFaibleMoyenne'], 2) ?? '**.**' }}</td>
                                            <td>{{ number_format($matiere['plusForteMoyenne'], 2) ?? '**.**' }}</td> --}}
                                        @endif

                                        @if (isset($option['rang_matiere']) && $option['rang_matiere'])
                                            <td>
                                                @if (isset($matiere['rang']))
                                                    {{-- Affiche le rang + suffixe --}}
                                                    {{ $matiere['rang'] }}
                                                    @php
                                                        $suffixe = $matiere['rang'] == 1 ? 'er' : 'ème';
                                                    @endphp
                                                    {{ $suffixe }}
                                                @else
                                                    {{-- Placeholder quand 'rang' n'existe pas --}}
                                                    **.**
                                                @endif
                                            </td>
                                        @endif
                                        {{-- @if (isset($option['rang_matiere']) && $option['rang_matiere'])
                                            <td>
                                                {{ $matiere['rang'] }}
                                                @php
                                                    $suffixe = $matiere['rang'] == 1 ? 'er' : 'ème';
                                                @endphp
                                                {{ $suffixe }}
                                            </td>
                                        @endif --}}
                                        @if (isset($option['appreciation_prof']))
                                            <td style="text-align: left;">
                                            {{ $matiere['moyenne_sur_20'] != 21 ? $matiere['mentionProf'] : 'Résultats médiocres' }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    {{-- @dd($option) --}}
                                    <td>{{ $total_coefficients }}</td>
                                    @if (!isset($option['masquer_devoir3']))
                                        <td colspan="5"></td>
                                    @elseif (isset($option['note_test']) && isset($option['masquer_devoir3']))
                                        <td colspan="6"></td>
                                    @elseif (isset($option['note_test']))
                                        <td colspan="7"></td>
                                    @else
                                        <td colspan="4"></td>
                                    @endif
                                    {{--                                     @if (isset($option['note_test']) && $option['note_test'])
                                    <td colspan="7"></td>
                                    @else
                                    <td colspan="4"></td>              
                                    @endif --}}
                                    {{-- <td>{{ number_format($total_moyenne_coeffs, 2) }}</td> --}}
                                    <td>{{ $total_moyenne_coeffs != 0 ? number_format((float) $total_moyenne_coeffs, 2) : '**.**' }}</td>
                                    @if (isset($option['rang_matiere']) && $option['rang_matiere'])
                                        <td colspan="4"></td>
                                    @else
                                        <td colspan="3"></td>
                                    @endif
                                    {{-- @if (isset($option['note_test']) && $option['note_test'])
                                        <td colspan="6"></td>                                        
                                    @else
                                        <td colspan="3"></td>                                        
                                    @endif --}}
                                </tr>
                            </tbody>
                        </table>
                        <div id="ligne" style="width: 100%; height: 1px; background-color: rgb(0, 0, 0);"></div>
                        <div class="d-flex" style="align-items: stretch; margin-top:5px; margin-bottom:5px;">
                            <div
                                style="width: 23%; min-width: 23%; max-width: 23%; background-color: transparent; border: 1px solid black; border-radius: 10px; display: flex; flex-direction: column; justify-content: center; padding: 10px; box-sizing: border-box; overflow: hidden;">
                                <h4 style="text-align:left; margin: 0; font-size: 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">BILAN {{ strtoupper($texte) }}</h4>

                            </div>
                            @php
                                // Calcul de la moyenne
                                $moyenne = 0;
                                if ($total_coefficients != 0) {
                                    $moyenne = $total_moyenne_coeffs / $total_coefficients;
                                } else {
                                    $moyenne = 0; // Ou une autre valeur par défaut
                                }
                            @endphp
                            <div style="width: 45%; min-width: 45%; max-width: 45%; margin-left: 1%; display: flex; flex-direction: column; padding: 10px; box-sizing: border-box; overflow: hidden;">
                                <div class="d-flex">
                                    <h6 style="text-align: center; font-weight: bold" class="mt-1; font-size: 18px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Moyenne
                                        {{ $texte2 }} :
                                        &nbsp&nbsp <span
                                            style="font-size: 20px;">{{ $total_moyenne_coeffs != 0 && $resultat['rang_1'] != null  ? number_format($moyenne, 2) : '**.**' }}</span>
                                            {{-- style="font-size: 20px;">{{ $total_moyenne_coeffs != 0 ? number_format($moyenne, 2) : '**.**' }}</span> --}}
                                    </h6>
                                    @if (isset($option['rang_general']) && $option['rang_general'])
                                        <h6 style="margin-left: 40px; font-weight: 50" class="mt-1; font-size: 18px; white-space: nowrap;">Rang
                                            :&nbsp&nbsp&nbsp
                                            @if ($resultat['rang_1'] == 1)
                                                <span
                                                    style="font-size: 20px; font-weight:bold;">{{ $resultat['rang_1'] != -1 }}
                                                    er</span>
                                            @else
                                                <span style="font-size: 20px; font-weight:bold;">
                                                    {{ $resultat['rang_1'] != -1 ? $resultat['rang_1'] : '**.**' }}
                                                    è</span>
                                            @endif
                                        </h6>
                                    @endif
                                </div>
                                <table id="tableau_bilan" style="width: 100%; margin-top: auto; font-size: 12px; table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th style="font-weight: normal; space-between; font-size: 16px; ">Plus forte Moy.</th>
                                            <th style="font-weight: normal; space-between; font-size: 16px; ">Plus faible Moy.</th>
                                            <th style="font-weight: normal; space-between; font-size: 16px; ">Moy. de la classe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" style=" word-break: break-all; space-between; font-size: 18px;">
                                                <strong>{{ number_format($resultat['moyenne_forte_1'], 2) }}</strong>
                                            </td>
                                            <td class="text-center" style=" word-break: break-all; space-between; font-size: 18px;">
                                                <strong>{{ number_format($resultat['moyenne_faible_1'], 2) }}</strong>
                                            </td>
                                            <td class="text-center" style=" word-break: break-all; space-between; font-size: 18px;">
                                                <strong>{{ number_format($resultat['moyenne_classe_1'], 2) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class=""
                                style="width: 30%; min-width: 30%; max-width: 30%; background-color: transparent; border: 1px solid black; border-radius: 10px; margin-left: 10px; display: flex; flex-direction: column; padding: 10px; box-sizing: border-box; overflow: hidden;">
                                <div style="display: flex; justify-content: space-between; font-size: 16px; margin-bottom: 3px;"
                                    class="mt-2">
                                    <span style="font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><strong>Bilan Matières Littéraires </strong></span>
                                    <span
                                        style="font-weight: bold; font-size: 15px; white-space: nowrap;">{{ $resultat['moyenne_bilan_litteraire_1'] == -1 ? '**' : number_format($resultat['moyenne_bilan_litteraire_1'], 2) }}</span>
                                </div>

                                <div style="display: flex; justify-content: space-between; font-size: 16px; margin-bottom: 3px;"
                                    class="mt-1">
                                    <span style="font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><strong>Bilan Matières Scientifiques </strong></span>
                                    <span
                                        style="font-weight: bold; font-size: 15px; white-space: nowrap;">{{ $resultat['moyenne_bilan_scientifique_1'] == -1 ? '**' : number_format($resultat['moyenne_bilan_scientifique_1'], 2) }}</span>
                                </div>

                                <div style="display: flex; justify-content: space-between; font-size: 16px;"
                                    class="mt-1">
                                    <span style="font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><strong>Bilan Matières Fondamentales </strong></span>
                                    <span
                                        style="font-weight: bold; font-size: 15px; white-space: nowrap;">{{ $resultat['moyenne_bilan_fondamentale_1'] == -1 ? '**' : number_format($resultat['moyenne_bilan_fondamentale_1'], 2) }}</span>
                                </div>
                            </div>

                        </div>
                        @if (($typean == 1 && $resultat['periode'] == 2) || ($typean == 2 && $resultat['periode'] == 3))
                            <div id="bilan_annuel_container" class="d-flex"
                                style="border: 1px solid black; border-radius: 10px; background-color: rgb(128, 128, 128, 0.2)">
                                <!-- Bloc Bilan Annuel -->
                                <div style="width: 23%; min-width: 23%; max-width: 23%; margin-left: 1%; padding-top: 10px;">
                                    <h5>BILAN ANNUEL</h5>
                                    <p style="font-size: 15px">Lettres :
                                        {{ $resultat['moyenneBilanLitteraire'] != -1 ? number_format($resultat['moyenneBilanLitteraire'], 2) : '**.**' }}
                                    </p>
                                    <p style="font-size: 15px">Sciences :
                                        {{ $resultat['moyenneBilanScientifique'] != -1 ? number_format($resultat['moyenneBilanScientifique'], 2) : '**.**' }}
                                    </p>
                                    <p style="font-size: 15px">Moy. Fond :
                                        {{ $resultat['moyenneBilanFondamentale'] != -1 ? number_format($resultat['moyenneBilanFondamentale'], 2) : '**.**' }}
                                    </p>
                                </div>

                                <!-- Bloc Moyenne Annuelle -->
                                <div style="width: 45%; min-width: 45%; max-width: 45%; margin-left: 1%; padding-top: 10px;">
                                    <div class="d-flex" style="align-items: normal; padding-top: 10px;">
                                        <h5 class="ml-5">Moyenne Annuelle :
                                            {{ $resultat['moyenneAnnuel'] != -1 && $resultat['moyenneAnnuel'] != 21 ? number_format($resultat['moyenneAnnuel'], 2) : '**.**' }}
                                        </h5>
                                        @if (isset($option['rang_general']) && $option['rang_general'])
                                            <h5 style="margin-left: 30px;">
                                                Rang :
                                                @if ($resultat['rangAnnuel'] == 1)
                                                    {{ $resultat['rangAnnuel'] != -1 }} er
                                                @else
                                                    {{ $resultat['rangAnnuel'] != -1 ? $resultat['rangAnnuel'] : '**.**' }}
                                                    è
                                                @endif
                                            </h5>
                                        @endif
                                    </div>
                                    <table id="tableau_bilan_annuel" style="width: 100%; margin-top: 5px;">
                                        <thead>
                                            <tr>
                                                <th style="font-weight: normal;">Plus forte Moy.</th>
                                                <th style="font-weight: normal;">Plus faible Moy.</th>
                                                <th style="font-weight: normal;">Moy. de la classe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    <strong>{{ number_format($resultat['plus_grande_moyenne_classe'], 2) }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($resultat['plus_faible_moyenne_classe'], 2) }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($resultat['moyenneClasseGlobale'], 2) }}</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Bloc Récapitulatif Annuel -->
                                <div style="width: 30%; min-width: 30%; max-width: 30%; margin-left: 1%; padding-top: 10px;">
                                    <h5>RECAPITULATIF ANNUEL</h5>
                                    <div class="d-flex">
                                        <p style="margin: 0;">Moy. 1er {{ $periode_abr }} :
                                            {{ $resultat['moyenne1erTrimestre_Semestre'] != -1 ? number_format($resultat['moyenne1erTrimestre_Semestre'], 2) : '**.**' }}
                                        </p>&nbsp;&nbsp;
                                        <p style="margin-left: 25px;">Rang :
                                            @if ($resultat['rang1'] == 1)
                                                {{ $resultat['rang1'] != -1 }} er
                                            @else
                                                {{ $resultat['rang1'] != -1 ? $resultat['rang1'] : '**.**' }} è
                                            @endif
                                        </p>
                                    </div>
                                    <div class="d-flex" style="align-items: center;">
                                        <p style="margin: 0;">Moy. 2ème {{ $periode_abr }} :
                                            {{ $resultat['moyenne2emTrimestre_Semestre'] != -1 ? number_format($resultat['moyenne2emTrimestre_Semestre'], 2) : '**.**' }}
                                        </p>&nbsp;&nbsp;
                                        <p style="margin-left: 25px;">Rang :
                                            @if ($resultat['rang2'] == 1)
                                                {{ $resultat['rang2'] != -1 }} er
                                            @else
                                                {{ $resultat['rang2'] != -1 ? $resultat['rang2'] : '**.**' }} è
                                            @endif
                                        </p>
                                    </div>
                                    @if ($typean == 2)
                                        <div class="d-flex" style="align-items: center;">
                                            <p style="margin: 0;">Moy. 3ème {{ $periode_abr }} :
                                                {{ $resultat['moyenne3emTrimestre_Semestre'] != -1 ? number_format($resultat['moyenne3emTrimestre_Semestre'], 2) : '**.**' }}
                                            </p>
                                            <p style="margin-left: 25px;">Rang :
                                                @if ($resultat['rang3'] == 1)
                                                    {{ $resultat['rang3'] != -1 }} er
                                                @else
                                                    {{ $resultat['rang3'] != -1 ? $resultat['rang3'] : '**.**' }} è
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @php
                            $mention_conseil = isset($option['mention_conseil']);
                        @endphp
                        <div class="d-flex" style="align-items: stretch; min-height: 250px !important;
">
                            <div
                                style="width: 25%; min-width: 25%; max-width: 25%; background-color: transparent; border: 1px solid black; border-radius: 10px; padding: 10px; box-sizing: border-box; display: flex; flex-direction: column; overflow: hidden;">
                                <h6 style="margin-top: 10px; font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="text-center">Mention du conseil des Prof.</h6>

                                {{-- utiliser la moyenne annuelle pour les mentions --}}

                                    @if(
                                        ($typean === 2 && request('periode') == 3) ||
                                        ($typean === 1 && request('periode') == 2)
                                    )

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Félicitations</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="felicitation" id="felicitation"
                                                readonly {{ $resultat['moyenneAnnuel'] != 21 && $resultat['moyenneAnnuel'] >= 16 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Encouragements</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="encouragement" id="encouragement"
                                                readonly {{ $resultat['moyenneAnnuel'] != 21 && $resultat['moyenneAnnuel'] >= 14 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Tableau d'honneur</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="tableau_dhonneur" id="tableau_dhonneur"
                                                readonly {{ $resultat['moyenneAnnuel'] != 21 && $resultat['moyenneAnnuel'] >= 12 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Avertissement/Travail</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="avertissement_travail"
                                                id="avertissement_travail" readonly
                                                {{ $resultat['moyenneAnnuel'] != 21 && $resultat['moyenneAnnuel'] <= 8.5 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Avertissement/Discipline</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="avertissement_discipline"
                                                id="avertissement_discipline" readonly
                                                {{ $note_conduite <= 10 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Blâme/Travail</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="blame_work" id="blame_work" readonly
                                                {{ $resultat['moyenneAnnuel'] != 21 && $resultat['moyenneAnnuel'] <= 6.5 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Blâme/Discipline</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="blame_discipline" id="blame_discipline"
                                                readonly {{ $note_conduite <= 6 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                    @else 
                                    {{-- utiliser la moyenne semestrielle pour les mentions --}}

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Félicitations</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="felicitation" id="felicitation"
                                                readonly {{ $total_moyenne_coeffs != 0 && $resultat['rang_1'] != null && $moyenne >= 16 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Encouragements</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="encouragement" id="encouragement"
                                                readonly {{ $total_moyenne_coeffs != 0 && $resultat['rang_1'] != null && $moyenne >= 14 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Tableau d'honneur</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="tableau_dhonneur" id="tableau_dhonneur"
                                                readonly {{ $total_moyenne_coeffs != 0 && $resultat['rang_1'] != null && $moyenne >= 12 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Avertissement/Travail</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="avertissement_travail"
                                                id="avertissement_travail" readonly
                                                {{ $total_moyenne_coeffs != 0 && $resultat['rang_1'] != null && $moyenne <= 8.5 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Avertissement/Discipline</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="avertissement_discipline"
                                                id="avertissement_discipline" readonly
                                                {{ $note_conduite <= 10 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Blâme/Travail</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="blame_work" id="blame_work" readonly
                                                {{ $total_moyenne_coeffs != 0 && $resultat['rang_1'] != null && $moyenne <= 6.5 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span style="font-size: 14px;">Blâme/Discipline</span>
                                            <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                            </div>
                                            <input type="checkbox" class="disable" name="blame_discipline" id="blame_discipline"
                                                readonly {{ $note_conduite <= 6 && $mention_conseil ? 'checked' : '' }}>
                                        </div>

                                    @endif
                            
                                </div>
                            <div id="appreciation"
                                style="width: 45%; min-width: 45%; max-width: 45%; background-color: transparent; border: 1px solid black; border-radius: 10px; display: flex; flex-direction: column; padding: 10px; overflow: hidden;">
                                <div
                                    style="flex: 1; display: flex; flex-direction: column; align-items: center; margin-bottom: 10px;">
                                    <h6 style="margin-top: 5px; text-align: center; text-decoration: underline; font-size: 12px;">
                                        Appréciation du chef d'établissement 
                                    </h6>

                                    @if (isset($option['appreciation_directeur']) && $option['appreciation_directeur'])
                                        <p style="font-weight: bold; text-align: center; margin-top: 10px; font-size: 18px; line-height: 1.2;">
                                            {{ $resultat['mentionDir'] }}
                                        </p>
                                    @endif
                                </div>
                                <hr style="border: 1px solid black; margin: 0;">                              
                                <div
                                    style="flex: 1; display: flex; flex-direction: column; align-items: center; margin-top: 10px;">
                                    <h6 style="text-align: center; text-decoration: underline; margin-bottom: 10px; font-size: 12px;">
                                        Appréciations du professeur principal</h6>
                                    <div
                                        style="display: flex; justify-content: space-between; width: 100%; padding: 0 10px;">
                                        <p style="margin: 0; font-size: 14px;">Conduite :
                                            {{-- <span
                                                style="border-bottom: 1px dotted black; width: 131px; display: inline-block;">{{ $note_conduite }}</span> --}}
                                            <span
                                                style="border-bottom: 1px dotted black; width: 90px; display: inline-block;">
                                            </span>
                                        </p>
                                        <p style="margin: 0; font-size: 14px;">Travail :
                                            <span
                                                style="border-bottom: 1px dotted black; width: 90px; display: inline-block;"></span>
                                        </p>
                                    </div>

                                    @if (isset($option['decision_conseil']) && $option['decision_conseil'])
                                        @if(
                                        ($typean === 2 && request('periode') == 3) ||
                                        ($typean === 1 && request('periode') == 2)
                                        )
                                            <p style="margin: 10px 0; font-weight:bold; text-align:center; font-size: 11px; line-height: 1.2;">
                                                {{ $resultat['decisionAnnuelle'] }}
                                            </p>
                                        @endif
                                    @else
                                            <p style="margin: 10px 0; font-weight:bold; text-align:center; font-size: 11px;">
                                                <span
                                                style="border-bottom: 1px dotted black; width: 180px; display: inline-block;"></span>
                                            </p>
                                    @endif


                                    {{-- <p style="margin: 10px 0;">
                                        {{ $resultat['decisionAnnuelle'] }}
                                    </p> --}}
                                </div>
                            </div>
                            <div id="signature"
                                style="width: 30%; min-width: 30%; max-width: 30%; background-color: transparent; border: 1px solid black; border-radius: 10px; display: flex; flex-direction: column; justify-content: space-between; padding: 10px; box-sizing: border-box; overflow: hidden;">
                                <h5 id="signature_chef" style="margin-top: 5px; font-weight: 500; font-size: 14px; line-height: 1.2;"
                                    class="text-center">
                                    Signature et cachet <br> du Chef d'établissement <br>
                                    <span style="margin-top: 10px; font-size: 16px; font-weight: bold;">{{ $params2->NOMETAB }}</span>
                                </h5>
                                <u>
                                    <h6 class="text-center"
                                        style="margin-left: 0%; font-weight: bold; white-space: normal; word-wrap: break-word; flex-grow: 1; display: flex; align-items: flex-end; justify-content: center; font-size: 13px;">
                                        {{ $params2->NOMDIRECT }}</h6>
                                </u>
                            </div>

                        </div>
                        <br>
                        <div class="d-flex">
                            {{-- <div class="flex-grow-1">
                                    <p>Code web: {{ $resultat['codeweb'] }}</p>
                                </div> --}}
                            <div class="flex-grow-1 justify-content-end" style="margin-left: 800px;">
                                <p>Edité le {{ date('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <div id="message" style="border: none; width: 100%; height: auto; text-align: justify;">
                                {!! html_entity_decode($request->input('msgEnBasBulletin')) !!}
                            </div>
                        </div>
                        <div class="container-parent">
                        <div class="paginer">
                            @php
                                 echo($page); 
                            @endphp
                        </div>
                        </div>
                    </div>
                    <br>

                    @php
                        $page++;
                    @endphp
                @endforeach
            </div>
        </div>
        {{-- </div> --}}


        <style>
            /* Pour que margin-left: auto fonctionne, le parent doit être un flex container */
            .container-parent { 
                display: flex;
                /* facultatif, pour que les éléments s’alignent sur une même ligne */
                align-items: center;
            }
            .paginer {
                /* Pour que le conteneur ne prenne que la place de son contenu */
                display: inline-block;
                /* Décale le bloc complètement à droite de son parent */
                margin-left: auto;
                /* Un peu d’air autour des liens/pages */
                padding: 0.5rem 1rem;
                /* Fond blanc (optionnel) pour bien voir l’ombre */
                background-color: #fff;
                /* Coins légèrement arrondis */
                border-radius: 4px;
                /* Ombre portée plus marquée */
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.514);
            }

            .bulletin {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.164);
            }

            th,
            td {
                border: 1px solid black;
                line-height: {{ $interligne }}mm;
                /* Ajout de l'interligne dynamique */
                padding: {{ $interligne / 2 }}mm 2px;
                /* Ajout d'un padding proportionnel à l'interligne */
            }

            /* Exclure l'interligne pour les tableaux spécifiques */
            #tableau_bilan th,
            #tableau_bilan td,
            #tableau_bilan_annuel th,
            #tableau_bilan_annuel td {
                line-height: normal;
                padding: 2px;
            }

            /* Exclure l'interligne pour l'en-tête et la ligne totale du tableau principal */
            #tableau thead th,
            #tableau thead td,
            #tableau tbody tr:last-child td {
                line-height: normal;
                padding: 2px;
            }

            /* Fixer les tailles des tableaux */
            #tableau_bilan {
                width: 450px !important;
            }

            #tableau_bilan_annuel {
                width: 450px !important;
            }

            .footer {
                display: none
            }

            .h8 {
                font-size: 12px;
            }

            .watermark {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                /* Rotation pour diagonale */
                font-size: 100px;
                color: rgb(0, 0, 0);
                opacity: 1;
                z-index: 1000;
                font-family: 'Cursive', sans-serif;
                user-select: none;
                pointer-events: none;
            }

            #tableau td {
                text-align: center;
            }

            @media print {
                #bilan_annuel {
                    width: 1000px !important;
                }

                .bulletin {
                box-shadow: 0 0px 0px rgba(0, 0, 0, 0);
            }

                .card-body {
                    overflow: hidden;
                }

                .paginer {
                    display: none;
                }
                .watermark {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-45deg);
                    font-size: 100px;
                    color: rgb(0, 0, 0);
                    opacity: 1;
                    z-index: 1000;
                    font-family: 'Cursive', sans-serif;
                    user-select: none;
                    pointer-events: none;
                }

                .sidebar,
                .navbar,
                .footer,
                .noprint,
                button {
                    display: none !important;
                    overflow: hidden !important;
                }

                #ligne {
                    width: 965px !important;
                }

                #carre {
                    display: block !important;
                }

                #appreciation {
                    width: 560px !important;
                }

                #signature_chef {
                    font-size: 16px !important;
                }

                #donneeleve {
                    width: 500px;
                }

                #periode {
                    margin-top: 10px !important;
                }

                #sco {
                    margin-top: 10px !important;
                }

                /* Styles spécifiques pour les en-têtes de colonnes */
                #tableau th {
                    font-size: 20px !important;
                    font-weight: bold !important;
                    text-align: center !important;
                    padding: 2px !important;
                    line-height: normal !important;
                }

                #tableau th br {
                    display: block !important;
                    content: " " !important;
                    margin: 2px 0 !important;
                }
            }

            .disable {
                pointer-events: none;
                /* Désactive l'interaction */
                cursor: not-allowed;
                /* Affiche un curseur interdit */
            }
        </style>

        <style>
            @media print {

                /* Empêcher Bootstrap de forcer les colonnes à 100% en mode impression */
                .row {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                    align-items: flex-start !important;
                    /* si vous voulez aligner par le bas */
                }

                .col-md-3 {
                    width: 25% !important;
                    /* Ajustez si besoin */
                    float: left !important;
                }

                .col-md-6 {
                    width: 50% !important;
                    /* Ajustez si besoin */
                    float: left !important;
                }

                /* Supprimez ou réduisez les marges automatiques du navigateur si nécessaire */
                @page {
                    margin: 1mm;
                    /* Ajustez à votre convenance */
                }

                body {
                    margin: 0;
                    padding: 0;
                }

                /* Fixer la taille de la ligne lors de l'impression */
                #ligne {
                    height: 1px !important;
                    min-height: 1px !important;
                    max-height: 1px !important;
                    background-color: rgb(0, 0, 0) !important;
                    width: 100% !important;
                }

                /* Fixer la taille et la mise en page du tableau bilan lors de l'impression */
                #tableau_bilan {
                    width: 100% !important;
                    table-layout: fixed !important;
                    font-size: 12px !important;
                    margin-top: auto !important;
                }

                #tableau_bilan th,
                #tableau_bilan td {
                    font-size: 16px !important;
                    padding: 2px !important;
                    word-break: break-all !important;
                }

                #tableau_bilan td {
                    font-size: 18px !important;
                }

                #tableau_bilan th {
                    font-weight: normal !important;
                }

                #tableau_bilan td strong {
                    font-weight: bold !important;
                }

                /* Fixer la taille et la mise en page du bilan annuel lors de l'impression */
                #bilan_annuel_container {
                    display: flex !important;
                    border: 1px solid black !important;
                    border-radius: 10px !important;
                    background-color: rgb(128, 128, 128, 0.2) !important;
                    width: 100% !important;
                }

                /* Bloc Bilan Annuel */
                #bilan_annuel_container > div:first-child {
                    width: 23% !important;
                    min-width: 23% !important;
                    max-width: 23% !important;
                    margin-left: 1% !important;
                    padding-top: 10px !important;
                }

                #bilan_annuel_container > div:first-child h5 {
                    font-size: inherit !important;
                    font-weight: bold !important;
                }

                #bilan_annuel_container > div:first-child p {
                    font-size: 15px !important;
                    margin: inherit !important;
                }

                /* Bloc Moyenne Annuelle */
                #bilan_annuel_container > div:nth-child(2) {
                    width: 45% !important;
                    min-width: 45% !important;
                    max-width: 45% !important;
                    margin-left: 1% !important;
                    padding-top: 10px !important;
                }

                #bilan_annuel_container > div:nth-child(2) .d-flex {
                    align-items: normal !important;
                    padding-top: 10px !important;
                }

                #bilan_annuel_container > div:nth-child(2) h5 {
                    font-size: inherit !important;
                    font-weight: bold !important;
                    margin: inherit !important;
                }

                #bilan_annuel_container > div:nth-child(2) h5.ml-5 {
                    margin-left: 20px !important;
                }

                #bilan_annuel_container > div:nth-child(2) h5:not(.ml-5) {
                    margin-left: 30px !important;
                }

                /* Tableau bilan annuel */
                #tableau_bilan_annuel {
                    width: 100% !important;
                    margin-top: 5px !important;
                }

                #tableau_bilan_annuel th {
                    font-weight: normal !important;
                }

                #tableau_bilan_annuel td {
                    text-align: center !important;
                }

                #tableau_bilan_annuel td strong {
                    font-weight: bold !important;
                }

                /* Bloc Récapitulatif Annuel */
                #bilan_annuel_container > div:last-child {
                    width: 30% !important;
                    min-width: 30% !important;
                    max-width: 30% !important;
                    margin-left: 1% !important;
                    padding-top: 10px !important;
                }

                #bilan_annuel_container > div:last-child h5 {
                    font-size: inherit !important;
                    font-weight: bold !important;
                }

                #bilan_annuel_container > div:last-child p {
                    margin: 0 !important;
                    font-size: inherit !important;
                }

                #bilan_annuel_container > div:last-child p[style*="margin-left: 25px"] {
                    margin-left: 25px !important;
                }

                #bilan_annuel_container > div:last-child .d-flex {
                    align-items: center !important;
                }

                /* Fixer la taille de l'élément de date d'édition lors de l'impression */
                .flex-grow-1.justify-content-end[style*="margin-left: 800px"] {
                    flex-grow: 1 !important;
                    justify-content: flex-end !important;
                    margin-left: 800px !important;
                }

                .flex-grow-1.justify-content-end[style*="margin-left: 800px"] p {
                    margin: 0 !important;
                    font-size: inherit !important;
                }

                /* Fixer les nouvelles tailles de police pour les blocs d'appréciation et bilans */
                #sco {
                    white-space: nowrap !important;
                    display: inline-block !important;
                }

                /* Bloc BILAN principal */
                .d-flex[style*="align-items: stretch"] > div:first-child h4 {
                    font-size: 20px !important;
                }

                /* Bloc Moyenne et Rang */
                .d-flex[style*="align-items: stretch"] > div:nth-child(2) h6 {
                    font-size: 18px !important;
                }

                .d-flex[style*="align-items: stretch"] > div:nth-child(2) h6 span {
                    font-size: 20px !important;
                }

                /* Bloc bilans matières */
                .d-flex[style*="align-items: stretch"] > div:last-child div[style*="justify-content: space-between"] {
                    font-size: 16px !important;
                }

                .d-flex[style*="align-items: stretch"] > div:last-child div[style*="justify-content: space-between"] span:first-child {
                    font-size: 16px !important;
                }

                .d-flex[style*="align-items: stretch"] > div:last-child div[style*="justify-content: space-between"] span:last-child {
                    font-size: 16px !important;
                }

                /* Mention du conseil des professeurs */
                h6.text-center[style*="Mention du conseil"] {
                    font-size: 16px !important;
                }

                /* Appréciations professeur principal */
                #appreciation h6[style*="Appréciations du professeur principal"] {
                    font-size: 12px !important;
                }

                #appreciation p {
                    margin: 0 !important;
                }

                /* Appréciation chef d'établissement */
                #appreciation h6[style*="Appréciation du chef d'établissement"] {
                    font-size: 12px !important;
                }

                #appreciation p[style*="mentionDir"] {
                    font-size: 14px !important;
                }

                /* Signature */
                #signature_chef {
                    font-size: 16px !important;
                }

                #signature_chef span {
                    font-size: 18px !important;
                }

                #signature h6 {
                    font-size: 16px !important;
                }

                /* Correction du débordement pour les noms de classe longs */
                #classe {
                    width: 20% !important;
                    min-width: 20% !important;
                    max-width: 20% !important;
                    background-color: transparent !important;
                    border: 1px solid black !important;
                    border-radius: 10px !important;
                    display: flex !important;
                    flex-direction: column !important;
                    justify-content: center !important;
                    padding: 10px !important;
                    box-sizing: border-box !important;
                    overflow: hidden !important;
                }

                #classe h5 {
                    margin-top: 0 !important;
                    font-weight: 400 !important;
                    white-space: normal !important;
                    overflow: visible !important;
                    word-wrap: break-word !important;
                }

                #classe.h5.span {
                    font-weight: bold !important;
                    font-size: 14px
                }
            }
        </style>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

        <script>
            function imprimerliste() {
                var content = document.querySelector('.main-panel').innerHTML;
                var originalContent = document.body.innerHTML;

                document.body.innerHTML = content;
                window.print();

                document.body.innerHTML = originalContent;
            }
        </script>
    </div>
@endsection