@extends('layouts.master')
@section('content')
        @if (isset($option['fond']) || isset($option['fond']))
                <div style=" position: relative; margin-left: 10px; margin-right: 10px; min-height: 100vh; overflow: hidden;">    

                           <div class="bulletin-bg" style="position: absolute;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                background-image: url('{{ $image ? asset('img/fonds/' . $image) : '' }}');
                                background-position: center;
                                "> </div>
            <div style="
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(255, 255, 255, 0.7);
            "></div> 

        @else

        <div style="margin-left: 10px; margin-right: 10px;">    
        @endif
              

        <div class="col-lg-12" style="margin-top: 0; padding-top: 0;">
            <div class="card-body" {{-- style="margin-top: 0; padding-top: 0;" --}}>
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
                    <button class="btn btn-primary" style="margin-left: 89%;" onclick="imprimerliste()">Imprimer</button>
                </div>

                @php
                    $sortedResultats = collect($resultats)->sortBy('matricule');
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
                        style="position: relative; {{ $index < count($resultats) - 1 ? 'page-break-after: always;' : '' }}">

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
                        <div class="d-flex">
                            <div id="donneeleve"
                                style="width: 60%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h4 class="ml-2" style="margin-top: 5px; font-weight: 200;">NOM : <span
                                        class="font-weight-bold">{{ $resultat['nom'] }}</span></h4>
                                <h4 class="ml-2" style="margin-top: 5px; font-weight: 200;">PRENOMS : <span
                                        class="font-weight-bold">{{ $resultat['prenom'] }}</span></h4>
                                <div class="d-flex">
                                    <h4 class="ml-2" style="margin-top: 5px; font-weight: 400; white-space: nowrap;">
                                        Redoublant (e) :
                                        <input class="ml-2 disable" type="checkbox" name="redoublant" id="redoublant_oui"
                                            readonly {{ $resultat['redoublant'] == 1 ? 'checked' : '' }}>
                                        <label for="redoublant_oui">OUI</label>
                                        <input class="disable" type="checkbox" name="redoublant" id="redoublant_non"
                                            readonly {{ $resultat['redoublant'] == 0 ? 'checked' : '' }}>
                                        <label for="redoublant_non">NON</label>
                                        @if (isset($option['matricule']) && $option['matricule'])
                                            <label
                                                style="margin-left: 10px; font-size:23px; white-space: nowrap; min-width: 200px;">Mat.
                                                {{ $resultat['matriculex'] }}</label>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                            <div
                                style="width: 20%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h5 class="ml-2 d-inline-block"
                                    style="margin-top: 20px; margin-bottom: 0; font-size: 14px; font-weight: 400;"
                                    id="sco">
                                    Année scolaire : {{ $resultat['anneScolaire'] }}
                                </h5>
                                <br>
                                <br>
                                <h5 id="periode" class="text-center" style="margin-top: 8px; font-weight: bold;">
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
                                style="width: 20%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h5 class="ml-2" style="margin-top: 20px; font-weight: 400;" id="sco">Classe : <span
                                        class="font-weight-bold">{{ $resultat['classe'] }}</span></h5>
                                <br>
                                <h5 class="ml-2" style="margin-top: 0; font-weight: 400;">Effectif : <span
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
                                    @php
                                        $dev1Valid =
                                            isset($matiere['devoir1']) &&
                                            $matiere['devoir1'] != 21 &&
                                            $matiere['devoir1'] != -1;
                                        $dev2Valid =
                                            isset($matiere['devoir2']) &&
                                            $matiere['devoir2'] != 21 &&
                                            $matiere['devoir2'] != -1;
                                    @endphp

                                    {{-- Si la matière n'est pas "conduite" et qu'aucun des deux devoirs n'est valide, on passe à la suivante --}}
                                    @if ($matiere['code_matiere'] != $request->input('conduite') && !$dev1Valid && !$dev2Valid)
                                        @continue
                                    @endif
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
                                                $moyenne_coeff = number_format(($moyenne_sur_20 * $matiere['coefficient']), 2);
                                                $total_coefficients += $matiere['coefficient'];
                                            } else {
                                                // Si $moyenne_sur_20 est null, on ne fait rien (ou on peut initialiser $moyenne_coeff à 0 ou null selon ce que vous souhaitez)
                                                $moyenne_coeff = null;
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
                                                    {{ 
                                                        isset($moyenne_sur_20) 
                                                            ? number_format($moyenne_sur_20, 2) 
                                                            : '**.**' 
                                                    }}
                                            </td>
                                            @if ($matiere['coefficient'] == -1 && $request->input('bonificationType') == 'integral')

                                                <td>+ {{ isset($moyenne_coeff) ? number_format($moyenne_coeff, 2) : '**.**' }}</td>
                                            @else
                                                {{-- @php
                                                    
                                                    var_dump($moyenne_coeff)
                                                @endphp --}}
                                                <td>{{ isset($moyenne_coeff) ? number_format($moyenne_coeff, 2) : '**.**' }}</td>
                                            @endif
                                        @endif
                                        @if (isset($option['note_test']) && $option['note_test'])
                                            {{-- <td></td>
                                            <td></td> --}}
                                            <td>{{ number_format($moyenne_part, 2) ?? '**.**' }}</td>
                                            <td>{{ $matiere['test'] ?? '**.**' }}</td>
                                            <td class="bold-text" style="font-weight: bold">
                                            {{ isset($moyenne_sur_20) ? number_format($moyenne_sur_20, 2) : '**.**' }}
                                            </td>
                                                                                            {{-- @php
                                                    
                                                    var_dump($moyenne_coeff)
                                                @endphp --}}
                                            <td>{{ isset($moyenne_coeff) ? number_format($moyenne_coeff, 2) : '**.**' }}</td>
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
                                            <td style="text-align: left;">{{ $matiere['mentionProf'] }}</td>
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
                                    <td>{{ number_format($total_moyenne_coeffs, 2) }}</td>
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
                        <div class="d-flex" style="height: 90px; margin-top:5px; margin-bottom:5px;">
                            <div
                                style="width: 23%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h4 style="margin-top: 32px; text-align:left;">BILAN {{ strtoupper($texte) }}</h4>

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
                            <div style="width: 45%; margin-left: 1%">
                                <div class="d-flex">
                                    <h6 style="text-align: center; font-weight: bold" class="mt-1">Moyenne
                                        {{ $texte2 }} :
                                        &nbsp&nbsp <span
                                            style="font-size: 20px;">{{ $total_moyenne_coeffs != 0 && $resultat['rang_1'] != null  ? number_format($moyenne, 2) : '**.**' }}</span>
                                            {{-- style="font-size: 20px;">{{ $total_moyenne_coeffs != 0 ? number_format($moyenne, 2) : '**.**' }}</span> --}}
                                    </h6>
                                    @if (isset($option['rang_general']) && $option['rang_general'])
                                        <h6 style="margin-left: 40px; font-weight: 50" class="mt-1">Rang
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
                                <table id="tableau_bilan" style="width: 100%;">
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
                                                <strong>{{ number_format($resultat['moyenne_forte_1'], 2) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($resultat['moyenne_faible_1'], 2) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($resultat['moyenne_classe_1'], 2) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class=""
                                style="width: 32%; background-color: transparent; border: 1px solid black; border-radius: 10px; margin-left: 10px;">
                                <div style="display: flex; justify-content: space-between; font-size: 16px;"
                                    class="mt-2">
                                    <span><strong>Bilan Matières Littéraires </strong></span>
                                    <span
                                        style="font-weight: bold; font-size:18px;">{{ $resultat['moyenne_bilan_litteraire_1'] == -1 ? '**' : number_format($resultat['moyenne_bilan_litteraire_1'], 2) }}</span>
                                </div>

                                <div style="display: flex; justify-content: space-between; font-size: 16px;"
                                    class="mt-1">
                                    <span><strong>Bilan Matières Scientifiques </strong></span>
                                    <span
                                        style="font-weight: bold; font-size:18px;">{{ $resultat['moyenne_bilan_scientifique_1'] == -1 ? '**' : number_format($resultat['moyenne_bilan_scientifique_1'], 2) }}</span>
                                </div>

                                <div style="display: flex; justify-content: space-between; font-size: 16px;"
                                    class="mt-1">
                                    <span><strong>Bilan Matières Fondamentales </strong></span>
                                    <span
                                        style="font-weight: bold; font-size:18px;">{{ $resultat['moyenne_bilan_fondamentale_1'] == -1 ? '**' : number_format($resultat['moyenne_bilan_fondamentale_1'], 2) }}</span>
                                </div>
                            </div>

                        </div>
                        @if (($typean == 1 && $resultat['periode'] == 2) || ($typean == 2 && $resultat['periode'] == 3))
                            <div {{-- id="bilan_annuel" --}} class="d-flex"
                                style="border: 1px solid black; border-radius: 10px; background-color: rgb(128, 128, 128, 0.2)">
                                <!-- Bloc Bilan Annuel -->
                                <div style="margin-left: 20px; padding-top: 10px;">
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
                                <div style="margin-left: 70px;">
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
                                <div style="margin-left: 60px; padding-top: 10px;">
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
                        <div class="d-flex">
                            <div
                                style="width: 25%; height: 230px; background-color: transparent; border: 1px solid black; border-radius: 10px; padding: 10px; box-sizing: border-box;">
                                <h6 style="margin-top: 5px;" class="text-center">Mention du conseil des Prof.</h6>

                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span style="font-size: 14px;">Félicitations</span>
                                    <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                    </div>
                                    <input type="checkbox" class="disable" name="felicitation" id="felicitation"
                                        readonly {{ $moyenne >= 16 && $mention_conseil ? 'checked' : '' }}>
                                </div>

                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span style="font-size: 14px;">Encouragements</span>
                                    <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                    </div>
                                    <input type="checkbox" class="disable" name="encouragement" id="encouragement"
                                        readonly {{ $moyenne >= 14 && $mention_conseil ? 'checked' : '' }}>
                                </div>

                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span style="font-size: 14px;">Tableau d'honneur</span>
                                    <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                    </div>
                                    <input type="checkbox" class="disable" name="tableau_dhonneur" id="tableau_dhonneur"
                                        readonly {{ $moyenne >= 12 && $mention_conseil ? 'checked' : '' }}>
                                </div>

                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span style="font-size: 14px;">Avertissement/Travail</span>
                                    <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                    </div>
                                    <input type="checkbox" class="disable" name="avertissement_travail"
                                        id="avertissement_travail" readonly
                                        {{ $moyenne <= 8.5 && $mention_conseil ? 'checked' : '' }}>
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
                                        {{ $moyenne <= 8.5 && $mention_conseil ? 'checked' : '' }}>
                                </div>

                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span style="font-size: 14px;">Blâme/Discipline</span>
                                    <div style="flex-grow: 1; border-bottom: 1px dotted #000; margin-left: 10px;">
                                    </div>
                                    <input type="checkbox" class="disable" name="blame_discipline" id="blame_discipline"
                                        readonly {{ $note_conduite <= 6 && $mention_conseil ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div id="appreciation"
                                style="width: 45%; background-color: transparent; border: 1px solid black; border-radius: 10px; display: flex; flex-direction: column; padding: 10px;">
                                <div
                                    style="flex: 1; display: flex; flex-direction: column; align-items: center; margin-bottom: 10px;">
                                    <h6 style="margin-top: 5px; text-align: center; text-decoration: underline;">
                                        Appréciation du chef d'établissement 
                                    </h6>

                                    @if (isset($option['appreciation_directeur']) && $option['appreciation_directeur'])
                                        <p style="font-weight: bold; text-align: center; margin-top: 10px; font-size:18px">
                                            {{ $resultat['mentionDir'] }}
                                        </p>
                                    @endif
                                </div>
                                <hr style="border: 1px solid black; margin: 0;">                              
                                <div
                                    style="flex: 1; display: flex; flex-direction: column; align-items: center; margin-top: 10px;">
                                    <h6 style="text-align: center; text-decoration: underline; margin-bottom: 10px;">
                                        Appréciations du professeur principal</h6>
                                    <div
                                        style="display: flex; justify-content: space-between; width: 100%; padding: 0 10px;">
                                        <p style="margin: 0;">Conduite :
                                            {{-- <span
                                                style="border-bottom: 1px dotted black; width: 131px; display: inline-block;">{{ $note_conduite }}</span> --}}
                                            <span
                                                style="border-bottom: 1px dotted black; width: 131px; display: inline-block;">
                                            </span>
                                        </p>
                                        <p style="margin: 0;">Travail :
                                            <span
                                                style="border-bottom: 1px dotted black; width: 131px; display: inline-block;"></span>
                                        </p>
                                    </div><br>

                                    @if (isset($option['decision_conseil']) && $option['decision_conseil'])
                                        @if(
                                        ($typean === 2 && request('periode') == 3) ||
                                        ($typean === 1 && request('periode') == 2)
                                        )
                                            <p style="margin: 10px 0; font-weight:bold; text-align:center;">
                                                {{ $resultat['decisionAnnuelle'] }}
                                            </p>
                                        @endif
                                    @else
                                            <p style="margin: 10px 0; font-weight:bold; text-align:center;">
                                                <span
                                                style="border-bottom: 1px dotted black; width: 250px; display: inline-block;"></span>
                                            </p>
                                    @endif


                                    {{-- <p style="margin: 10px 0;">
                                        {{ $resultat['decisionAnnuelle'] }}
                                    </p> --}}
                                </div>
                            </div>
                            <div id="signature"
                                style="width: 30%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h5 id="signature_chef" style="margin-top: 5px; font-weight: 500; font-size: 15px;"
                                    class="text-center">
                                    Signature et cachet <br> du Chef d'établissement
                                </h5>
                                <h5 class="text-center">{{ $params2->NOMETAB }}</h5>
                                <u>
                                    <h6 class="text-center"
                                        style="margin-left: 0%; padding-top: 38%; font-weight: bold; white-space: normal; word-wrap: break-word;">
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
                    margin: 5mm;
                    /* Ajustez à votre convenance */
                }

                body {
                    margin: 0;
                    padding: 0;
                }
            }
        </style>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


                    {{-- <script>
                        function imprimerliste() {
                            var content = document.querySelector('.main-panel').innerHTML;
                            var originalContent = document.body.innerHTML;

                            document.body.innerHTML = content;
                            window.print();

                            document.body.innerHTML = originalContent;
                        }

                        // document.getElementById('redoublant_oui').addEventListener('click', function(event) {
                        //     event.preventDefault(); // Empêche la modification
                        // });

                        // document.getElementById('redoublant_non').addEventListener('click', function(event) {
                        //     event.preventDefault(); // Empêche la modification
                        // });
                    </script> --}}


        {{-- <script>
            function imprimerliste() {
                // Sélectionner tous les bulletins individuels
                var bulletinElements = document.querySelectorAll('.bulletin');
                
                if (bulletinElements.length === 0) {
                    console.error("Aucun bulletin trouvé pour l'archivage.");
                    window.print();
                    return;
                }
                
                var promises = [];
                
                bulletinElements.forEach(function(bulletinElement, index) {
                    // Récupérer le nom et la classe depuis les attributs data-nom et data-classe
                    var studentName = bulletinElement.getAttribute('data-nom') || 'unknown';
                    studentName = studentName.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
                    
                    var classCode = bulletinElement.getAttribute('data-classe') || 'default';
                    classCode = classCode.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
                    
                    var promise = html2canvas(bulletinElement).then(function(canvas) {
                        var imgData = canvas.toDataURL('image/jpeg', 1.0);
                        const { jsPDF } = window.jspdf;
                        var pdf = new jsPDF('p', 'mm', 'a4');
                        var pdfWidth = pdf.internal.pageSize.getWidth();
                        var pdfHeight = (canvas.height * pdfWidth) / canvas.width;
                        pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
                        
                        // Créer un nom de fichier en utilisant le code de la classe et le nom de l'élève
                        var filename = 'bulletin_' + classCode + '_' + studentName + '_' + new Date().getTime() + '.pdf';
                        
                        var pdfBase64 = pdf.output('datauristring');
                        
                        return fetch('/archiveBulletin', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                pdf: pdfBase64,
                                filename: filename,
                                class: classCode
                            })
                        });
                    }).catch(function(err) {
                        console.error('Erreur lors de la capture d'un bulletin :', err);
                    });
                    
                    promises.push(promise);
                });
                
                Promise.all(promises).then(function() {
                    var contentN = document.querySelector('.main-panel').innerHTML;
                    var originalContent = document.body.innerHTML;
                    document.body.innerHTML = contentN;
                    window.print();
                    document.body.innerHTML = originalContent;
                }).catch(function(error) {
                    console.error('Erreur lors de l'archivage de certains bulletins:', error);
                    window.print();
                });
            }


            </script> 
        --}}

        <script>
            function imprimerliste() {
                var content = document.querySelector('.main-panel').innerHTML;
                var originalContent = document.body.innerHTML;

                document.body.innerHTML = content;
                window.print();

                document.body.innerHTML = originalContent;
            }

            // document.getElementById('redoublant_oui').addEventListener('click', function(event) {
            //     event.preventDefault(); // Empêche la modification
            // });

            // document.getElementById('redoublant_non').addEventListener('click', function(event) {
            //     event.preventDefault(); // Empêche la modification
            // });
        </script>

    </div>
@endsection