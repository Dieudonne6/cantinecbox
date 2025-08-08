<?php

namespace App\Http\Controllers;

use const RTF_UNICODE;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Params2;
use Roundcube\Rtf\Html; 
use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;
use RtfHtmlPhp\RtfDocument;
use PHPRtfLite\Rtf;



class ParametreController extends Controller
{
    public function inscriptionsDiscipline()
    {
        return view('parametre.inscriptions');
    }

    // public function tables()
    // {
    //     // On récupère le premier (ou unique) enregistrement de params2
    //     $settings = Params2::first();

    //     // On récupère la liste des attributs "fillable" (convertible en inputs)
    //     $fields = (new Params2)->getFillable();
          
    //     return view('pages.parametre.tables', compact('settings', 'fields'));
    // }

private function convertHtmlToRtf($html)
{
    require_once base_path('vendor/phprtflite/phprtflite/lib/PHPRtfLite.php');
    \PHPRtfLite::registerAutoloader();

    $rtf = new \PHPRtfLite();
    $sect = $rtf->addSection();

    // Nettoyer scripts/styles inutiles
    $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

    // Supprimer les balises vides
    $html = preg_replace('/<p>(&nbsp;|\s)*<\/p>/i', '', $html);
    $html = preg_replace('/<div>(&nbsp;|\s)*<\/div>/i', '', $html);

    $dom = new \DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    libxml_clear_errors();

    $this->writeDomNodeToRtf($dom->getElementsByTagName('body')->item(0), $sect);

    $tmp = tempnam(sys_get_temp_dir(), 'rtf');
    $rtf->save($tmp);
    $content = file_get_contents($tmp);
    @unlink($tmp);

    return $content;
}

private function writeDomNodeToRtf($node, $section, $parentFont = null, $parentPar = null)
{
    foreach ($node->childNodes as $child) {
        if ($child->nodeType === XML_TEXT_NODE) {
            $text = trim($child->nodeValue);
            if ($text !== '') {
                $section->writeText($text, $parentFont, $parentPar);
            }
        } elseif ($child->nodeType === XML_ELEMENT_NODE) {
            $tag = strtolower($child->nodeName);
            $style = $this->parseStyle($child->getAttribute('style'));

            // Hérite des styles parents
            $fontOptions = $parentFont ? $parentFont->getAttributes() : [];
            $fontSize = $parentFont ? $parentFont->getSize() : 12;
            $fontColor = $parentFont ? $parentFont->getColor() : '#000000';

            // Styles par balise
            if (in_array($tag, ['b', 'strong']) || (isset($style['font-weight']) && $style['font-weight'] === 'bold')) {
                $fontOptions['bold'] = true;
            }
            if (in_array($tag, ['i', 'em']) || (isset($style['font-style']) && $style['font-style'] === 'italic')) {
                $fontOptions['italic'] = true;
            }
            if ($tag === 'u' || (isset($style['text-decoration']) && $style['text-decoration'] === 'underline')) {
                $fontOptions['underline'] = true;
            }

            // Font size
            if (isset($style['font-size'])) {
                $fontSize = (int) filter_var($style['font-size'], FILTER_SANITIZE_NUMBER_INT);
            }

            // Font color
            if (isset($style['color'])) {
                $fontColor = $style['color'];
            }

            // Création de la police courante
            $font = new \PHPRtfLite_Font($fontSize, 'Arial', $fontColor, $fontOptions);

            // Paragraphe courant
            $paragraph = $parentPar ?? new \PHPRtfLite_ParFormat();
            if (isset($style['text-align'])) {
                switch (trim($style['text-align'])) {
                    case 'center': $paragraph->setTextAlignment('center'); break;
                    case 'right':  $paragraph->setTextAlignment('right'); break;
                    case 'justify':$paragraph->setTextAlignment('justify'); break;
                    default:       $paragraph->setTextAlignment('left'); break;
                }
            }

            // Traitement des balises spécifiques
            if ($tag === 'br') {
                $section->writeText("\n");
            } elseif (in_array($tag, ['p', 'div'])) {
                $text = trim($child->textContent);
                if ($text !== '') {
                    $section->writeText($text, $font, $paragraph);
                    $section->writeText("\n");
                }
            } elseif ($tag === 'span' || $tag === 'font') {
                $text = trim($child->textContent);
                if ($text !== '') {
                    $section->writeText($text, $font, $paragraph);
                }
            } else {
                // Récursion avec styles hérités
                $this->writeDomNodeToRtf($child, $section, $font, $paragraph);
            }
        }
    }
}


private function parseStyle($styleString)
{
    $styles = [];

    if (!$styleString) return $styles;

    $declarations = explode(';', $styleString);

    foreach ($declarations as $decl) {
        if (strpos($decl, ':') !== false) {
            [$property, $value] = explode(':', $decl, 2);
            $styles[trim($property)] = trim($value);
        }
    }

    return $styles;
}

private function parseDomToRtf($node)
{
    $rtf = '';

    foreach ($node->childNodes as $child) {
        switch ($child->nodeType) {
            case XML_TEXT_NODE:
                $rtf .= $child->nodeValue;
                break;

            case XML_ELEMENT_NODE:
                $content = $this->parseDomToRtf($child);

                switch (strtolower($child->nodeName)) {
                    case 'b':
                    case 'strong':
                        $rtf .= '{\\b ' . $content . '}';
                        break;

                    case 'i':
                    case 'em':
                        $rtf .= '{\\i ' . $content . '}';
                        break;

                    case 'u':
                        $rtf .= '{\\ul ' . $content . '}';
                        break;

                    case 'br':
                        $rtf .= "\\line ";
                        break;

                    case 'p':
                        $rtf .= $content . "\\line ";
                        break;

                    default:
                        $rtf .= $content;
                        break;
                }
                break;
        }
    }

    return $rtf;
}

public function updateMessages(Request $request)
{
    $request->validate([
        'message_fiche_notes'       => 'nullable|string',
        'message_des_recus'         => 'nullable|string',
        'entete_des_documents'      => 'nullable|string',
        'texte_fiche_engagement'    => 'nullable|string',
        'entete_bulletins'          => 'nullable|string',
    ]);

    $params = Params2::firstOrNew([]);

    if (!empty($request->message_fiche_notes)) {
        $params->EnteteFiches = $this->convertHtmlToRtf($request->message_fiche_notes);
    }

    if (!empty($request->message_des_recus)) {
        $params->EnteteRecu = $this->convertHtmlToRtf($request->message_des_recus);
    }

    if (!empty($request->entete_des_documents)) {
        $params->EnteteDoc = $this->convertHtmlToRtf($request->entete_des_documents);
    }

    if (!empty($request->texte_fiche_engagement)) {
        $params->EnteteEngage = $this->convertHtmlToRtf($request->texte_fiche_engagement);
    }

    if (!empty($request->entete_bulletins)) {
        $params->EnteteBull = $this->convertHtmlToRtf($request->entete_bulletins);
    }

    $params->save();

    return redirect()->back()->with('success', 'Les contenus ont été convertis en RTF et enregistrés.');
}
    
public function tables()
{
    $settings = Params2::first();

    function convertRTFToHTML($rtf)
    {
        try {
            $document = new Document($rtf);
            $formatter = new HtmlFormatter();
            return $formatter->Format($document);
        } catch (\Throwable $e) {
            return '';
        }
    }

    $entete = convertRTFToHTML($settings->EnteteBull ?? '');
    $enteteEngage = convertRTFToHTML($settings->EnteteEngage ?? '');
    $enteteDoc = convertRTFToHTML($settings->EnteteDoc ?? '');
    $enteteRecu = convertRTFToHTML($settings->EnteteRecu ?? '');
    $enteteFiches = convertRTFToHTML($settings->EnteteFiches ?? '');

    $fields = (new Params2)->getFillable();

    return view('pages.parametre.tables', compact('settings', 'fields', 'entete', 'enteteEngage', 'enteteDoc', 'enteteRecu', 'enteteFiches'));
}

    public function showLogo($side)
    {
        $settings = DB::table('params2')->first();

        if (!$settings) {
            abort(404);
        }

        if ($side === 'left' && $settings->logoimage1) {
            $imageData = $settings->logoimage1;
        } elseif ($side === 'right' && $settings->LOGO1) {
            $imageData = $settings->LOGO1;
        } else {
            abort(404);
        }

        // Détection automatique du mime-type
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        return response($imageData)->header('Content-Type', $mimeType);
    }

    public function showLogo($side)
    {
        $settings = DB::table('params2')->first();

        if (!$settings) {
            abort(404);
        }

        if ($side === 'left' && $settings->logoimage1) {
            $imageData = $settings->logoimage1;
        } elseif ($side === 'right' && $settings->LOGO1) {
            $imageData = $settings->LOGO1;
        } else {
            abort(404);
        }

        // Détection automatique du mime-type
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        return response($imageData)->header('Content-Type', $mimeType);
    }

    public function updateIdentification(Request $request)
    {
        $request->validate([
            'nom_etablissement' => 'nullable|string|max:255',
            'code_etablissement' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            // Ajoute d'autres validations si besoin
        ]);

        // Préparation des données à enregistrer
        $data = [
            'NOMETAB' => $request->nom_etablissement,
            'CodeSITE' => $request->code_etablissement,
            'EMAIL' => $request->email,
            'DEPARTEMEN' => $request->departement,
            'Secteur' => $request->statut,
            'ADRESSE' => $request->adresse,
            'VILLE' => $request->ville,
            'NOMDIRECT' => $request->directeur1,
            'TITRE' => $request->titre_directeur1,
            'NOMDIRECT2' => $request->directeur2,
            'TITRE2' => $request->titre_directeur2,
            'NOMDIRECT3' => $request->directeur3,
            'TITRE3' => $request->titre_directeur3,
            'NOMCENSEUR' => $request->censeur,
            'TITRECENSEUR' => $request->titre_censeur,
            'NOMINTEND' => $request->filled('comptable') ? $request->comptable : '',
            'TITREINTENDANT' => $request->titre_comptable,
            'DeviseEtab' => $request->devise,
            'Typeetab' => is_array($request->Typeetab) ? implode('', $request->Typeetab) : '',
        ];

        // Gestion des fichiers logo
        if ($request->hasFile('logo_gauche')) {
            $data['logoimage1'] = file_get_contents($request->file('logo_gauche')->getRealPath());
        }

        if ($request->hasFile('logo_droit')) {
            // image enregistrée sur le disque
            $path = $request->file('logo_droit')->store('logos', 'public'); // storage/app/public/logos/...
            $filename = basename($path); // on ne garde que le nom pour respecter le VARCHAR(20)
            $data['LOGO1'] = substr($filename, 0, 20); // coupe à 20 caractères si nécessaire
        }

        // Mise à jour ou insertion (s'il n'existe pas encore)
        $exists = DB::table('params2')->exists();

        if ($exists) {
            DB::table('params2')->update($data); // mise à jour du seul enregistrement
        } else {
            DB::table('params2')->insert($data); // insertion si vide
        }

        return redirect()->back()->with('success', 'Les informations ont été enregistrées avec succès.');
    }

     public function editAppreciation()
    {
        $appreciations = Params2::first();
        return view('pages.parametre.appreciations', compact('appreciations'));
    }
    
    public function updateAppreciation(Request $request)
    {
        $appreciations = Params2::first();

        for ($i = 1; $i <= 8; $i++) {
            $appreciations->{'Borne' . $i} = $request->input('Borne' . $i);
            $appreciations->{'Mention' . $i . 'p'} = $request->input('Mention' . $i . 'p');
            $appreciations->{'Mention' . $i . 'd'} = $request->input('Mention' . $i . 'd');
        }

        $appreciations->save();

        return redirect()->back()->with('success', 'Grille des appréciations mise à jour avec succès.');
    }

    public function updateGeneraux(Request $request)
    {
        // 2. Chargement du modèle de settings (supposé unique)
        $settings = Params2::first(); // ou find(1) selon votre logique

        // 3. Mise à jour des composantes de scolarité
        $settings->MTS       = $request->input('montant.0');
        $settings->LIBELF1   = $request->input('libel.1');
        $settings->MT1       = $request->input('montant.1');
        $settings->LIBELF2   = $request->input('libel.2');
        $settings->MT2       = $request->input('montant.2');
        $settings->LIBELF3   = $request->input('libel.3');
        $settings->MT3       = $request->input('montant.3');
        $settings->LIBELF4   = $request->input('libel.4');
        $settings->MT4       = $request->input('montant.4');

        // 4. Échéancier standard
        $settings->Date1erPaie_Standard = $request->input('date1');
        $settings->Periodicite_Standard = $request->input('periodicite');
        $settings->Echeancier_tous_frais = $request->has('echeancier_frais');
        $settings->pcen1_standard = $request->input('t1');
        $settings->pcen2_standard = $request->input('t2');
        $settings->pcent3_standard = $request->input('t3');

        // 5. Type de matricule, périodicité, absences
        $settings->TYPEMATRI  = $request->input('type_matricule');
        $settings->TYPEAN = (int) $request->input('TYPEAN');
        $settings->NMININOTES = $request->input('NMININOTES');

        // 6. Pondérations (on stocke en fraction 0–1)
        $settings->Ponderation_MI   = $request->input('Ponderation_MI')  / 100;
        $settings->Ponderation_Dev  = $request->input('Ponderation_Dev') / 100;
        $settings->Ponderation_Comp = $request->input('Ponderation_Comp')/ 100;

        // 7. Ne pas toucher à ModeSalaire ici
        $settings->ModeSalaire = $request->input('ModeSalaire');
        $mode = $request->input('moyenne'); // 'classique' ou 'avance'
        if ($mode === 'classique') {
            // Seulement Pondération Composition
            $settings->Ponderation_Comp = $request->input('Ponderation_Comp') / 100;

            // Ne pas enregistrer les autres (mettre à null ou 0 si nécessaire)
            // $settings->Ponderation_MI = null;   
            // $settings->Ponderation_Dev = null;
        } elseif ($mode === 'avance') {
            // Prend toutes les pondérations
            $settings->Ponderation_MI  = $request->input('Ponderation_MI') / 100;
            $settings->Ponderation_Dev = $request->input('Ponderation_Dev') / 100;
            $settings->Ponderation_Comp = $request->input('Ponderation_Comp') / 100;
                    // dd($settings->Ponderation_MI, $settings->Ponderation_Dev, $settings->Ponderation_Comp);

        }
        // 8. Sauvegarde et retour
        $settings->save();

        return redirect()
            ->back()
            ->with('success', 'Les paramètres ont bien été mis à jour.');
    }

    public function updateNumerotation(Request $request)
    {
        // Valider
        $validated = $request->validate([
            'NUMDERATT'    => 'required|integer|min:0',
            'NUMDERCARTE'  => 'required|integer|min:0',
            'NUMDERCERTIF' => 'required|integer|min:0',
        ]);

        $params = Params2::firstOrFail();

        $params->NUMDERATT    = $validated['NUMDERATT'];
        $params->NUMDERCARTE  = $validated['NUMDERCARTE'];
        $params->NUMDERCERTIF = $validated['NUMDERCERTIF'];

        $params->save();

        return redirect()->back()
                         ->with('success', 'Numérotation mise à jour avec succès.');
    }



    public function bornes()
    {
        $exercices = DB::connection('mysql2')->table('exercice')->get();

        return view('pages.parametre.bornes-exercice', compact('exercices'));
    }

    public function opOuverture()
    {
        return view('pages.parametre.op-ouverture');
    }

    public function configImprimante()
    {
        return view('pages.parametre.config-imprimante');
    }

    public function changementTrimestre()
    {
        return view('pages.parametre.changement-trimestre');
    }
    public function set(Request $request)
    {
        session(['periode' => $request->input('periode')]);
        return back()->with('success', 'Période définie !');
    } 
}