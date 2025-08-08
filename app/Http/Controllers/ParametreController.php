<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Params2;
use App\Models\PeriodeSave;

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
    $params = Params2::first(); // ou find(1) si tu connais l'ID

    if (!$params) {
        return back()->withErrors('Paramètres introuvables.');
    }

    // Mise à jour simple (attention aux noms des inputs HTML)
    $params->update([

        'Date1erPaie_Standard' => $request->input('date1'),
        'Periodicite_Standard' => $request->input('periodicite'),
        'Echeancier_tous_frais' => $request->has('echeancier_frais'),
        'TYPEMATRI' => $request->input('type_matricule'),
        'TYPEAN' => $request->input('TYPEAN'),
    ]);

    return back()->with('success', 'Paramètres mis à jour avec succès.');
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
        // récupère la période courante (ou null)
        $current = PeriodeSave::where('key', 'active')->value('periode');
        return view('pages.parametre.changement-trimestre', compact('current'));
    }

    public function storePeriode(Request $request)
    {
        $request->validate([
            'periode' => 'required|string|max:255',
            'action'  => 'nullable|string', // facultatif : "delete" si suppression
        ]);

        // si l'utilisateur a cliqué sur "Supprimé"
        if ($request->input('action') === 'delete') {
            PeriodeSave::where('key', 'active')->delete();
            return redirect()->back()->with('success', "La période a été supprimée.");
        }

        // enregistrement ou mise à jour (upsert)
        $periode = $request->input('periode');
        // <!-- dd($periode); -->

        $record = PeriodeSave::updateOrCreate(
            ['key' => 'active'],
            ['periode' => $periode]
        );

        return redirect()->back()->with('success', "Période enregistrée : {$record->periode}");
    }
}