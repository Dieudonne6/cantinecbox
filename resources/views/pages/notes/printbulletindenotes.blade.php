@extends('layouts.master')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <!-- Bouton pour imprimer la liste -->
                <button class="btn btn-primary" onclick="imprimerliste()">Imprimer</button>
                <br>
                @foreach ($resultats as $index => $resultat)
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
                    @endphp
                    <div class="bulletin" style="{{ $index < count($resultats) - 1 ? 'page-break-after: always;' : '' }}">
                        <br>
                        <br>
                        @if (isset($option['entete']) && $option['entete'])
                            <h6>{{ $entete }}</h6>
                        @endif
                        <div id="carre"
                            style="width: 125px; height: 125px; background-color: transparent; border: 1px solid black;">
                        </div>
                        <br>
                        <div class="watermark text-center fw-bold mb-4"
                            style="height: 90px; font-size: 1.5rem; color: gray;">Scodelux</div>
                        <div class="d-flex">
                            <div id="donneeleve"
                                style="width: 40%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h5 class="ml-2" style="margin-top: 5px;">NOM : {{ $resultat['nom'] }}</h5>
                                <h5 class="ml-2">PRENOM : {{ $resultat['prenom'] }}</h5>
                                <div class="d-flex">
                                    <h5 class="ml-2">Redoublant (e) :
                                        <input class="ml-2" type="checkbox" name="redoublant" id="redoublant_oui" disabled
                                            {{ $resultat['redoublant'] == 1 ? 'checked' : '' }}>
                                        <label for="redoublant_oui">Oui</label>
                                        <input type="checkbox" name="redoublant" id="redoublant_non" disabled
                                            {{ $resultat['redoublant'] == 2 ? 'checked' : '' }}>
                                        <label for="redoublant_non">Non</label>
                                    </h5>
                                    @if (isset($option['matricule']) && $option['matricule'])
                                        <h5 style="margin-left: 40px;">Matricule : {{ $resultat['matricule'] }}</h5>
                                    @endif
                                </div>
                            </div>
                            <div
                                style="width: 30%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h5 class="ml-2" style="margin-top: 15px;">Année scolaire :
                                    {{ $resultat['anneScolaire'] }}</h5>
                                <br>
                                <h5 id="periode" class="text-center">
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
                                style="width: 30%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h5 class="ml-2" style="margin-top: 15px;">Classe : {{ $resultat['classe'] }}</h5>
                                <br>
                                <h5 class="ml-2">Effectif : {{ $resultat['effectif'] }}</h5>
                            </div>
                        </div>
                        <table style="width: 100%;" id="tableau">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-weight: normal; width: 150px;">Disciplines</th>
                                    <th class="text-center" style="font-weight: normal; width: 50px;">Cf</th>
                                    <th class="text-center" style="font-weight: normal; width: 50px;">Moy.Int</th>
                                    <th class="text-center" style="font-weight: normal; width: 50px;">Dev.1</th>
                                    <th class="text-center" style="font-weight: normal; width: 50px;">Dev.2</th>
                                    @if (!isset($option['masquer_devoir3']))
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Dev.3</th>
                                    @endif
                                    @if (!isset($option['note_test']) || !$option['note_test'])
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Moy.20</th>
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Moy.coef</th>
                                    @endif
                                    @if (isset($option['note_test']) && $option['note_test'])
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Moy. part</th>
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Compo</th>
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Moy.20</th>
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Moy.coef</th>
                                    @else
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Faible moy.</th>
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Forte moy.</th>
                                    @endif
                                    @if (isset($option['rang_matiere']) && $option['rang_matiere'])
                                        <th class="text-center" style="font-weight: normal; width: 50px;">Rang par matière
                                        </th>
                                    @endif
                                    <th class="text-center" style="font-weight: normal; width: 150px;">Appréciations
                                        des<br>professeurs</th>
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
                                            $moyenne_coeff = $moyenne_sur_20 * $matiere['coefficient'];
                                            $total_coefficients += $matiere['coefficient'];
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
                                    <tr>
                                        <td>{{ $matiere['nom_matiere'] }}</td>
                                        <td>{{ $matiere['coefficient'] }}</td>
                                        <td>{{ $matiere['moyenne_interro'] ?? '**.**' }}</td>
                                        <td>{{ $matiere['devoir1'] ?? '**.**' }}</td>
                                        <td>{{ $matiere['devoir2'] ?? '**.**' }}</td>
                                        @if (!isset($option['masquer_devoir3']))
                                            <td>{{ $matiere['devoir3'] ?? '**.**' }}</td>
                                        @endif
                                        @if (!isset($option['note_test']) || !$option['note_test'])
                                            <td>{{ number_format($moyenne_sur_20, 2) ?? '**.**' }}</td>
                                            @if ($matiere['coefficient'] == -1 && $request->input('bonificationType') == 'integral')
                                                <td>+ {{ number_format($moyenne_coeff, 2) ?? '**.**' }}</td>
                                            @else
                                                <td>{{ number_format($moyenne_coeff, 2) ?? '**.**' }}</td>
                                            @endif
                                        @endif
                                        @if (isset($option['note_test']) && $option['note_test'])
                                            <td>{{ number_format($moyenne_part, 2) ?? '**.**' }}</td>
                                            <td>{{ $matiere['test'] ?? '**.**' }}</td>
                                            <td>{{ number_format($moyenne_sur_20, 2) ?? '**.**' }}</td>
                                            <td>{{ number_format($moyenne_coeff, 2) ?? '**.**' }}</td>
                                        @else
                                            <td>{{ number_format($matiere['plusFaibleMoyenne'], 2) ?? '**.**' }}</td>
                                            <td>{{ number_format($matiere['plusForteMoyenne'], 2) ?? '**.**' }}</td>
                                        @endif
                                        @if (isset($option['rang_matiere']) && $option['rang_matiere'])
                                            <td>{{ $matiere['rang'] }}</td>
                                        @endif
                                        @if (isset($option['appreciation_prof']))
                                            <td>{{ $matiere['mentionProf'] }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td>{{ $total_coefficients }}</td>
                                    @if (!isset($option['masquer_devoir3']))
                                    <td colspan="5"></td>
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
                                </tr>
                            </tbody>
                        </table>
                        <div id="ligne" style="width: 100%; height: 1px; background-color: rgb(0, 0, 0);"></div>
                        <div class="d-flex" style="height: 90px;">
                            <div
                                style="width: 29.5%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h4 class="text-center" style="margin-top: 20px;">Bilan {{ $texte }}</h4>
                            </div>
                            @php
                                // Calcul de la moyenne
                                $moyenne = 0;
                                $moyenne = $total_moyenne_coeffs / $total_coefficients;
                            @endphp
                            <div style="width: 40%; margin-left: 0.5%">
                                <div class="d-flex">
                                    <h6 style="text-align: center" class="mt-1">Moyenne {{ $texte2 }} :
                                        &nbsp&nbsp{{ $total_moyenne_coeffs != 0 ? number_format($moyenne, 2) : '**.**' }}
                                    </h6>
                                    @if (isset($option['rang_general']) && $option['rang_general'])
                                        <h6 style="margin-left: 40px;" class="mt-1">Rang
                                            :&nbsp&nbsp&nbsp{{ $resultat['rang_1'] != -1 ? $resultat['rang_1'] : '**.**' }}
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
                            <div class="mt-2"
                                style="width: 30%; background-color: transparent; border: 1px solid black; border-radius: 10px; margin-left: 10px;">
                                <h8><strong>Bilan des matières littéraires :
                                        {{ $resultat['moyenne_bilan_litteraire_1'] == -1 ? '**' : $resultat['moyenne_bilan_litteraire_1'] }}</strong>
                                </h8>
                                <br>
                                <h8><strong>Bilan des matières scientifiques :
                                        {{ $resultat['moyenne_bilan_scientifique_1'] == -1 ? '**' : $resultat['moyenne_bilan_scientifique_1'] }}</strong>
                                </h8>
                                <br>
                                <h8><strong>Bilan des matières fondamentales :
                                        {{ $resultat['moyenne_bilan_fondamentale_1'] == -1 ? '**' : $resultat['moyenne_bilan_fondamentale_1'] }}</strong>
                                </h8>
                            </div>
                        </div>
                        @if (($typean == 1 && $resultat['periode'] == 2) || ($typean == 2 && $resultat['periode'] == 3))
                            <div id="bilan_annuel" class="d-flex"
                                style="border: 1px solid black; border-radius: 10px; width:1190px">
                                <div>
                                    <h6 style="margin-left: 20px;">BILAN ANNUEL</h6>
                                    <h7>Lettres
                                        :&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{{ $resultat['moyenneBilanLitteraire'] != -1 ? number_format($resultat['moyenneBilanLitteraire'], 2) : '**.**' }}
                                    </h7>
                                    <br>
                                    <h7>Sciences
                                        :&nbsp&nbsp&nbsp&nbsp&nbsp{{ $resultat['moyenneBilanScientifique'] != -1 ? number_format($resultat['moyenneBilanScientifique'], 2) : '**.**' }}
                                    </h7>
                                    <br>
                                    <h7>Moy. Fond
                                        :&nbsp&nbsp&nbsp{{ $resultat['moyenneBilanFondamentale'] != -1 ? number_format($resultat['moyenneBilanFondamentale'], 2) : '**.**' }}
                                    </h7>
                                </div>
                                <div style="margin-left: 60px;">
                                    <div class="d-flex" style="margin-left: 60px;">
                                        <h5>Moyenne Annuelle
                                            :&nbsp&nbsp&nbsp{{ $resultat['moyenneAnnuel'] != -1 ? number_format($resultat['moyenneAnnuel'], 2) : '**.**' }}
                                        </h5>
                                        @if (isset($option['rang_general']) && $option['rang_general'])
                                            <h5 style="margin-left: 40px;">Rang
                                                :&nbsp&nbsp&nbsp{{ $resultat['rangAnnuel'] != -1 ? $resultat['rangAnnuel'] : '**.**' }}
                                            </h5>
                                        @endif
                                    </div>
                                    <table id="tableau_bilan_annuel"
                                        style="width: 450px; margin-left: 60px; margin-top: 20px;">
                                        <thead>
                                            <tr>
                                                <th style="font-weight: normal;">Plus forte Moy.</th>
                                                <th style="font-weight: normal;">Plus faible Moy.</th>
                                                <th style="font-weight: normal;">Moy. de la classe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center" style="">
                                                    <strong>{{ number_format($resultat['plus_grande_moyenne_classe'], 2) }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($resultat['plus_faible_moyenne_classe'], 2) }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($resultat['moyenne_classe_1'], 2) }}</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div style="margin-left: 70px;">
                                    <h6>RECAPITULATIF ANNUEL</h6>
                                    <div class="d-flex">
                                        <h7>Moy. 1er {{ $periode_abr }}
                                            :&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{{ $resultat['moyenne1erTrimestre_Semestre'] != -1 ? number_format($resultat['moyenne1erTrimestre_Semestre'], 2) : '**.**' }}
                                        </h7>
                                        @if (isset($option['rang_1']) && $option['rang_1'])
                                            <h7 style="margin-left: 40px;">Rang
                                                :&nbsp&nbsp{{ $resultat['rang1'] != -1 ? $resultat['rang1'] : '**.**' }}
                                            </h7>
                                        @endif
                                    </div>
                                    <div class="d-flex">
                                        <h7>Moy. 2ème {{ $periode_abr }}
                                            :&nbsp&nbsp{{ $resultat['moyenne2emTrimestre_Semestre'] != -1 ? number_format($resultat['moyenne2emTrimestre_Semestre'], 2) : '**.**' }}
                                        </h7>
                                        @if (isset($option['rang_2']) && $option['rang_2'])
                                            <h7 style="margin-left: 40px;">Rang
                                                :&nbsp&nbsp&nbsp{{ $resultat['rang2'] != -1 ? $resultat['rang2'] : '**.**' }}
                                            </h7>
                                        @endif
                                    </div>
                                    @if ($typean == 2)
                                        <div class="d-flex">
                                            <h7>Moy. 3ème {{ $periode_abr }}
                                                :&nbsp&nbsp&nbsp{{ $resultat['moyenne3emTrimestre_Semestre'] != -1 ? number_format($resultat['moyenne3emTrimestre_Semestre'], 2) : '**.**' }}
                                            </h7>
                                            @if (isset($option['rang_3']) && $option['rang_3'])
                                                <h7 style="margin-left: 40px;">Rang
                                                    :&nbsp&nbsp&nbsp{{ $resultat['rang3'] != -1 ? $resultat['rang3'] : '**.**' }}
                                                </h7>
                                            @endif
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
                                style="width: 33%; height: 170px background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h6 style="margin-top: 5px;" class="text-center">Mention du conseil des Prof.</h6>
                                <div class="d-flex">
                                    <h8>Félicitations........................................................</h8>
                                    <input style="margin-left: 5px;" type="checkbox" name="felicitation"
                                        id="felicitation" disabled
                                        {{ $moyenne >= 16 && $mention_conseil ? 'checked' : '' }}>
                                </div>
                                <div class="d-flex">
                                    <h8>Encouragements........................................</h8>
                                    <input style="margin-left: 5px;" type="checkbox" name="encouragement"
                                        id="encouragement" disabled
                                        {{ $moyenne >= 14 && $mention_conseil ? 'checked' : '' }}>
                                </div>
                                <div class="d-flex">
                                    <h8>Tableau d'honneur....................................</h8>
                                    <input style="margin-left: 5px;" type="checkbox" name="tableau_dhonneur"
                                        id="tableau_dhonneur" disabled
                                        {{ $moyenne >= 12 && $mention_conseil ? 'checked' : '' }}>
                                </div>
                                <div class="d-flex">
                                    <h8>Avertissement/Travail...........................</h8>
                                    <input style="margin-left: 5px;" type="checkbox" name="avertissement_travail"
                                        id="avertissement_travail" disabled
                                        {{ $moyenne <= 8.5 && $mention_conseil ? 'checked' : '' }}>
                                </div>
                                <div class="d-flex">
                                    <h8>Avertissement/Discipline...................</h8>
                                    <input style="margin-left: 5px;" type="checkbox" name="avertissement_discipline"
                                        id="avertissement_discipline" disabled
                                        {{ $note_conduite <= 10 && $mention_conseil ? 'checked' : '' }}>
                                </div>
                                <div class="d-flex">
                                    <h8>Blâme/Travail...................................................</h8>
                                    <input style="margin-left: 5px;" type="checkbox" name="blame_work" id="blame_work"
                                        disabled {{ $moyenne <= 8.5 && $mention_conseil ? 'checked' : '' }}>
                                </div>
                                <div class="d-flex">
                                    <h8>Blâme/Discipline..........................................</h8>
                                    <input style="margin-left: 5px;" type="checkbox" name="blame_discipline"
                                        id="blame_discipline" disabled
                                        {{ $note_conduite <= 6 && $mention_conseil ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div id="appreciation"
                                style="width: 37%; background-color: transparent; border: 1px solid black; border-radius: 10px; display: flex; flex-direction: column;">
                                <div style="flex: 1; display: flex;justify-content: center;" class="row">
                                    <h6 style="margin-top: 5px;" class="text-center"><u>Appréciation du chef
                                            d'établissement</u></h6>
                                    @if (isset($option['appreciation_directeur']) && $option['appreciation_directeur'])
                                        <h7 style="font-weight: bold;" class="text-center">{{ $resultat['mentionDir'] }}
                                        </h7>
                                    @endif
                                </div>
                                <hr style="border: 1px solid black; margin: 0;">
                                <div style="flex: 1;justify-content: center;">
                                    <h6 class="text-center"><u>Appréciations du professeur principal</u></h6>
                                    <h7>Conduite : <span
                                            style="border-bottom: 1px dotted #000; width: 131px; display: inline-block;">{{ $note_conduite }}</span>
                                    </h7>
                                    <h7>Travail.............................................</h7>
                                    <h7>....................................................................................................................................................
                                    </h7>
                                </div>
                            </div>
                            <div id="signature"
                                style="width: 30%; background-color: transparent; border: 1px solid black; border-radius: 10px;">
                                <h5 id="signature_chef" style="margin-top: 50px;" class="text-center"><u>Signature et
                                        cachet du chef d'établissement</u></h5>
                                <h6 class="text-center" style="margin-left: 0%;">{{ $request->input('signature') }}</h6>
                            </div>
                        </div>
                        <br>
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p>Code web: {{ $resultat['codeweb'] }}</p>
                            </div>
                            <div class="flex-grow-1 justify-content-end" style="margin-left: 600px;">
                                <p>Edité le {{ date('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <div id="message" style="border: none; width: 100%; height: auto; text-align: justify;">
                                {!! html_entity_decode($request->input('msgEnBasBulletin')) !!}
                            </div>
                        </div>
                    </div>

                    <br>
                @endforeach
            </div>
        </div>
        <style>
            th,
            td {
                border: 1px solid black;
            }

            .footer {
                display: none
            }

            h8 {
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

                .card-body {
                    overflow: hidden;
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

                .sidebar,
                .navbar,
                .footer,
                .noprint,
                button {
                    display: none !important;
                    overflow: hidden !important;
                }

                /*     #tableau {
              width: 968px !important;
            } */
                #ligne {
                    width: 965px !important;
                }

                #carre {
                    display: block !important;
                }

                .card-body {
                    margin-top: 50px !important;
                }

                /*     #tableau_bilan {
              width: 400px !important;
            } */
                #appreciation {
                    width: 560px !important;
                }

                /*     #signature {
              width: auto !important;
            } */
                #signature_chef {
                    font-size: 16px !important;
                }

                #donneeleve {
                    width: 500px;
                }

                #periode {
                    margin-top: -20px !important;
                }

            }
        </style>
        <script>
            function imprimerliste() {
                var content = document.querySelector('.main-panel').innerHTML;
                var originalContent = document.body.innerHTML;

                document.body.innerHTML = content;
                window.print();

                document.body.innerHTML = originalContent;
            }
        </script>
    @endsection
