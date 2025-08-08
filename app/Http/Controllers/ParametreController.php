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
private function extractTextFromRtf($rtfContent)
{
    if (empty($rtfContent)) {
        return '';
    }

    try {
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        return strip_tags($formatter->Format($document));
    } catch (\Throwable $e) {
        return '';
    }
}

private function replaceRtfTextContent($rtfContent, $newText)
{
    $oldText = $this->extractTextFromRtf($rtfContent);

    $escapedNewText = str_replace(
        ['\\', '{', '}', "\n"],
        ['\\\\', '\{', '\}', '\\par '],
        strip_tags($newText)
    );

    return str_replace($oldText, $escapedNewText, $rtfContent);
}

private function convertHtmlToRtf($html)
{
    // 1) Charger la bibliothèque
    require_once base_path('vendor/phprtflite/phprtflite/lib/PHPRtfLite.php');
    \PHPRtfLite::registerAutoloader();

    // 2) Créer un document RTF via la méthode statique
    $rtf = new \PHPRtfLite();

    // 3) Créer une section
    $sect = $rtf->addSection();

    // 4) Nettoyage HTML
    $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

    // 5) Charger dans DOM
    $dom = new \DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    libxml_clear_errors();

    // 6) Extraire texte
    $body = $dom->getElementsByTagName('body')->item(0);
    $text = $this->parseDomToRtf($body);
    $sect->writeText($text);

    // 7) Sauvegarde dans un fichier temporaire
    $tmp = tempnam(sys_get_temp_dir(), 'rtf');
    $rtf->save($tmp); // pas de php://output ici

    // 8) Lire et retourner le contenu
    $content = file_get_contents($tmp);
    @unlink($tmp);
    return $content;
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
                switch (strtolower($child->nodeName)) {
                    case 'b':
                    // case 'strong':
                    //     $rtf .= '{\\b ' . $this->parseDomToRtf($child) . '}';
                    //     break;
                    // case 'i':
                    // case 'em':
                    //     $rtf .= '{\\i ' . $this->parseDomToRtf($child) . '}';
                    //     break;
                    // case 'u':
                    //     $rtf .= '{\\ul ' . $this->parseDomToRtf($child) . '}';
                    //     break;
                    // case 'br':
                    //     $rtf .= "\\line ";
                    //     break;
                    // case 'p':
                    //     $rtf .= $this->parseDomToRtf($child) . "\\line ";
                    //     break;
                    default:
                        $rtf .= $this->parseDomToRtf($child);
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

    if (!empty($request->message_fiche_notes) && !empty($params->EnteteFiches)) {
        $params->EnteteFiches = $this->replaceRtfTextContent($params->EnteteFiches, $request->message_fiche_notes);
    }

    if (!empty($request->message_des_recus) && !empty($params->EnteteRecu)) {
        $params->EnteteRecu = $this->replaceRtfTextContent($params->EnteteRecu, $request->message_des_recus);
    }

    if (!empty($request->entete_des_documents) && !empty($params->EnteteDoc)) {
        $params->EnteteDoc = $this->replaceRtfTextContent($params->EnteteDoc, $request->entete_des_documents);
    }

    if (!empty($request->texte_fiche_engagement) && !empty($params->EnteteEngage)) {
        $params->EnteteEngage = $this->replaceRtfTextContent($params->EnteteEngage, $request->texte_fiche_engagement);
    }

    if (!empty($request->entete_bulletins) && !empty($params->EnteteBull)) {
        $params->EnteteBull = $this->replaceRtfTextContent($params->EnteteBull, $request->entete_bulletins);
    }

    $params->save();

    return redirect()->back()->with('success', 'Seuls les champs remplis ont été mis à jour. Les styles RTF sont conservés.');
}
    
    public function tables()
    {
        // On récupère le premier (ou unique) enregistrement de params2
        $settings = Params2::first();

        // Pour l'enteteRecu
        $rtfContent = Params2::first()->EnteteRecu;
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        $enteteNonStyle = $formatter->Format($document);
        $enteteRecu = '
        <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
            <style>
                p { margin: 0; padding: 0; line-height: 1.2; }
                span { display: inline-block; }
            </style>
            ' . $enteteNonStyle . '
        </div>
        ';

        // Pour l'EnteteDoc
        $rtfContent = Params2::first()->EnteteDoc;
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        $enteteNonStyle = $formatter->Format($document);
        $enteteDoc = '
        <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
            <style>
                p { margin: 0; padding: 0; line-height: 1.2; }
                span { display: inline-block; }
            </style>
            ' . $enteteNonStyle . '
        </div>
        ';

        // Pour l'EnteteEngage
        $rtfContent = Params2::first()->EnteteEngage;
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        $enteteNonStyle = $formatter->Format($document);
        $enteteEngage = '
        <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
            <style>
                p { margin: 0; padding: 0; line-height: 1.2; }
                span { display: inline-block; }
            </style>
            ' . $enteteNonStyle . '
        </div>
        ';

         // Pour l'enteteFiches
        if ($settings && !empty($settings->EnteteFiches)) {
            $document = new Document($settings->EnteteFiches);
            $formatter = new HtmlFormatter();
            $enteteNonStyle = $formatter->Format($document);
            $enteteFiches = '
                <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
                    <style>
                        p { margin: 0; padding: 0; line-height: 1.2; }
                        span { display: inline-block; }
                    </style>
                    ' . $enteteNonStyle . '
                </div>';
        } else {
            $enteteFiches = '<p></p>';
        }

        // Pour l'entête
        $rtfContent = Params2::first()->EnteteBull;
        $document = new Document($rtfContent);
        $formatter = new HtmlFormatter();
        $enteteNonStyle = $formatter->Format($document);
        $entete = '
        <div style="text-align: center; font-size: 1.5em; line-height: 1.2;">
            <style>
                p { margin: 0; padding: 0; line-height: 1.2; }
                span { display: inline-block; }
            </style>
            ' . $enteteNonStyle . '
        </div>
        ';

        // On récupère la liste des attributs "fillable" (convertible en inputs)
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

// public function updateGeneraux(Request $request)
// {
//     $settings = Params2::first();

//     if (!$settings) {
//         return back()->with('error', 'Paramètres non trouvés.');
//     }

//     // Récupération des tableaux
//     $libels = $request->input('libel');
//     $montants = $request->input('montant');

//     // ✅ Assigner tous les champs correctement
//     $settings->MTS     = $montants[0];
//     $settings->LIBELF1 = $libels[1];
//     $settings->MT1     = $montants[1];
//     $settings->LIBELF2 = $libels[2];
//     $settings->MT2     = $montants[2];
//     $settings->LIBELF3 = $libels[3];
//     $settings->MT3     = $montants[3];
//     $settings->LIBELF4 = $libels[4];
//     $settings->MT4     = $montants[4];

//     // Sauvegarde
//     $settings->save();

//     return back()->with('success', 'Paramètres mis à jour avec succès.');
// }




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
}