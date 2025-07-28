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

use Roundcube\Rtf\Html; 
// use RtfHtmlPhp\Document;
use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;



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
            'NOMINTEND' => $request->comptable,
            'TITREINTENDANT' => $request->titre_comptable,
            'DeviseEtab' => $request->devise,
            'Typeetab' => is_array($request->Typeetab) ? implode('', $request->Typeetab) : '',
        ];

        // Gestion des fichiers logo
        if ($request->hasFile('logo_gauche')) {
            $data['logoimage1'] = $request->file('logo_gauche')->store('logos', 'public');
        }

        if ($request->hasFile('logo_droit')) {
            $data['LOGO1'] = $request->file('logo_droit')->store('logos', 'public');
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
        // Valider les données reçues
        $validated = $request->validate([
            'libel'       => 'required|array|size:5',
            'montant'     => 'required|array|size:5',
            'date1'       => 'nullable|date',
            'periodicite' => 'required|integer|min:0',
            'echeancier_frais'  => 'sometimes|accepted',
            'type_matricule'    => 'required|in:manuel,auto',
            'TYPEAN'            => 'required|in:Semestrielle,Trimestrielle',
            'NMININOTES'        => 'required|integer',
            't1'                => 'required|numeric|min:0|max:100',
            't2'                => 'required|numeric|min:0|max:100',
            't3'                => 'required|numeric|min:0|max:100',
            'ModeSalaire'       => 'nullable|string',
            'moyenne'           => 'nullable|in:classique,avance',
            'Ponderation_MI'    => 'nullable|numeric|min:0|max:100',
            'Ponderation_Dev'   => 'nullable|numeric|min:0|max:100',
            'Ponderation_Comp'  => 'required|numeric|min:0|max:100',
        ]);

        // Récupérer l'instance existante
        $params = Params2::firstOrFail();

        // Mettre à jour les libellés et montants
        $fields = ['Scolarité', 'LIBELF1', 'LIBELF2', 'LIBELF3', 'LIBELF4'];
        foreach ($fields as $i => $column) {
            if ($i === 0) {
                $params->MTS = $validated['montant'][$i];
            } else {
                $params->{$column} = $validated['libel'][$i];
                $mtKey = 'MT'. $i;
                $params->{$mtKey} = $validated['montant'][$i];
            }
        }

        // Échéancier standard
        $params->Date1erPaie_Standard   = $validated['date1'];
        $params->Periodicite_Standard   = $validated['periodicite'];
        $params->Echeancier_tous_frais  = $request->has('echeancier_frais');

        // Tranches
        $params->pcen1_standard = $validated['t1'];
        $params->pcen2_standard = $validated['t2'];
        $params->pcent3_standard = $validated['t3'];

        // Type de matricule et périodicité
        $params->TYPEMATRI = $validated['type_matricule'];
        $params->TYPEAN    = $validated['TYPEAN'];

        // Absences
        $params->NMININOTES = $validated['NMININOTES'];

        // Mode de remunération et calcul moyenne
        $params->ModeSalaire      = $request->input('ModeSalaire');
        $params->mode_moyenne     = $validated['moyenne'];
        if ($validated['moyenne'] === 'avance') {
            $params->Ponderation_MI   = $validated['Ponderation_MI'] / 100;
            $params->Ponderation_Dev  = $validated['Ponderation_Dev'] / 100;
        }
        $params->Ponderation_Comp    = $validated['Ponderation_Comp'] / 100;

        // Sauvegarde
        $params->save();

        return redirect()->back()
                        ->with('success', 'Paramètres mis à jour avec succès.');
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
}