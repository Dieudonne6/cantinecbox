<?php

namespace App\Http\Controllers;
use App\Models\Classes;
use App\Models\Matieres;
use App\Models\PeriodeSave;
use App\Models\Eleve;
use App\Models\Notes;
use App\Models\Clasmat;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class ImportnoteController extends Controller
{
    public function index(){

        $classes = Classes::get();
        $matieres = Matieres::get();
        $current = PeriodeSave::where('key', 'active')->value('periode');
       
        return view('pages.notes.importnote', [
            'classes' => $classes,
            'matieres' => $matieres,
            'current' => $current,
        ]);
    }

    
    public function importGlobal(Request $request)
    {
        $request->validate([
            'classe' => 'required',
            'periode' => 'required',
            'fichier' => 'required|mimes:xls,xlsx'
        ]);

        $classe = $request->classe;
        $periode = $request->periode;
        $file = $request->file('fichier');

        // Lire toutes les feuilles
        $sheets = Excel::toArray([], $file);

        $matieresData = [];

        foreach ($sheets as $sheetIndex => $rows) {

            if (count($rows) <= 1) continue; // feuille vide

            // nom de la matière = nom de la feuille Excel
            $sheetName = $file->getClientOriginalName(); // fallback

            // Laravel Excel ne donne pas le nom ici → on le récupère autrement
            // Solution : relecture via PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $worksheet = $spreadsheet->getSheet($sheetIndex);
            $sheetName = $worksheet->getTitle();

            $data = [];

            foreach ($rows as $index => $row) {
                
                // ignorer ligne entête si contient texte MATRICULE
                if ($index === 0 || str_contains(strtoupper($row[0]), 'MATRICULE')) {
                    continue;
                }

                if (!isset($row[0]) || !$row[0]) continue;

                $data[] = [
                    'matricule' => $row[0],
                    'nom' => $row[1] ?? '',
                    'prenom' => $row[2] ?? '',
                    'moyenne' => $row[3] ?? '',
                    'dev1' => $row[4] ?? '',
                    'dev2' => $row[5] ?? '',
                ];
            }
            $matieresData[$sheetName] = $data;
        }

        return view('pages.notes.previewImport', [
            'classe' => $classe,
            'periode' => $periode,
            'matieresData' => $matieresData
        ]);
    } 

    public function saveImport(Request $request)
    {
        $codeuser = Auth::id();
        $classe = $request->classe;
        $periode = $request->periode;

        $matieresData = json_decode(base64_decode($request->data), true);
        //dd($request->data, $matieresData);
        $today = Carbon::now();

        foreach ($matieresData as $matiere => $rows) {

            // ================================
            // 1️ Supprimer anciennes notes
            // ================================
            DB::table('notes')
                ->where('CODECLAS', $classe)
                ->where('SEMESTRE', $periode)
                ->where('CODEMAT', function($q) use ($matiere) {
                    $q->select('CODEMAT')
                    ->from('matieres')
                    ->where('LIBELMAT', $matiere)
                    ->limit(1);
                })
                ->delete();
         
            // ================================
            // 2️ Trouver CODEMAT
            // ================================
            $codemat = DB::table('matieres')
                ->where('LIBELMAT', $matiere)
                ->value('CODEMAT');

            if (!$codemat) continue;

            // ================================
            // 3️ Trouver COEF
            // ================================
            $coef = DB::table('clasmat')
                ->where('CODEMAT', $codemat)
                ->where('CODECLAS', $classe)
                ->value('COEF');

            foreach ($rows as $r) {

                // ================================
                // 4️ Trouver MATRICULE élève
                // ================================
                $nom = strtoupper(trim($r['nom']));
                $prenom = strtoupper(trim($r['prenom']));

                $eleve = DB::table('eleve')
                    ->whereRaw('UPPER(NOM) = ?', [$nom])
                    ->whereRaw('UPPER(PRENOM) = ?', [$prenom])
                    ->first();

                if (!$eleve) continue;

                // ================================
                // 5 Insertion note
                // ================================
                // ================================
                // Nettoyage des notes
                // ================================
                $mi   = trim($r['moyenne']);
                $dev1 = trim($r['dev1']);
                $dev2 = trim($r['dev2']);

                // Remplacer "." ou vide par 21
                $mi   = (!is_numeric($mi)) ? 21 : floatval($mi);
                $dev1 = (!is_numeric($dev1)) ? 21 : floatval($dev1);
                $dev2 = (!is_numeric($dev2)) ? 21 : floatval($dev2);

                // ================================
                // Calcul de MS et MS1
                // ================================
                $notes = [$mi, $dev1, $dev2];

                $validNotes = array_filter($notes, function ($n) {
                    return $n != 21 && $n != -1;
                });

                $count = count($validNotes);

                switch ($count) {
                    case 3:
                        $ms = array_sum($validNotes) / 3;
                        break;
                    case 2:
                        $ms = array_sum($validNotes) / 2;
                        break;
                    case 1:
                        $ms = reset($validNotes);
                        break;
                    default:
                        $ms = 21;
                }

                $ms = round($ms, 2);

                DB::table('notes')->insert([
                    'MATRICULE' => $eleve->MATRICULE,
                    'CODECLAS' => $classe,
                    'SEMESTRE' => $periode,
                    'CODEMAT' => $codemat,
                    'COEF' => $coef ?? 0,

                    'MI' => $mi,
                    'DEV1' => $dev1,
                    'DEV2' => $dev2,
                    
                    'MS' => $ms,
                    'MS1' => $ms,

                    'INT1' => 21,
                    'INT2' => 21,
                    'INT3' => 21,
                    'INT4' => 21,
                    'INT5' => 21,
                    'INT6' => 21,
                    'INT7' => 21,
                    'INT8' => 21,
                    'INT9' => 21,
                    'INT10' => 21,
                    'DEV3' => 21,
                    'TEST' => 21,

                    'DATECREE' => $today,
                    'DATEMODIF' => $today,

                    'CODEUSER' => $codeuser,
                    'RANG' => null,
                    'FILLER_T' => null,
                    'FILLER_E' => null,
                    'MODIFIER' => null,
                    'VERROUILLE' => null,
                    'SystemeNotes' => null,
                ]);
            }
        }

        return redirect()
                ->route('importnote')
                ->with('success', 'Notes importées avec succès ');
    }

}
