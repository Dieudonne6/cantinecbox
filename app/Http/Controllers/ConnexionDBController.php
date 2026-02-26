<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\ConnexionBDRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Paramcontrat;
class ConnexionDBController extends Controller
{
    
    public function connexion(ConnexionBDRequest $request){
        $nameserveur = $request->input('nameserveur');
        $namebase = $request->input('namebase');
        $user = $request->input('user');
        $password = $request->input('password');
        $envContent = "";
        $envContent = File::get(base_path('.env'));

        $envContent = preg_replace('/^DB_DATABASE=.*$/m', "DB_DATABASE=$namebase", $envContent);

        $envContent = preg_replace('/^DB_USERNAME=.*$/m', "DB_USERNAME=$user", $envContent);

        $envContent = preg_replace('/^DB_PASSWORD=.*$/m', "DB_PASSWORD=$password", $envContent);

        File::put(base_path('.env'), $envContent);
        
        // Extraire l'année du nom de la base de données et mettre à jour paramcontrat
        $this->updateAnneeEnCours($namebase);
        
        return back()->with('status','Enregistrer avec succes');
    }
    
    /**
     * Extrait l'année du nom de la base et met à jour la table paramcontrat
     */
    private function updateAnneeEnCours($databaseName)
    {
        try {
            // Extraire l'année du nom de la base (ex: 2025_2026 -> 2025)
            if (preg_match('/^(\d{4})_\d{4}$/', $databaseName, $matches)) {
                $anneeEnCours = $matches[1];
                
                // Mettre à jour la table paramcontrat
                DB::table('paramcontrat')->update([
                    'anneencours_paramcontrat' => $anneeEnCours
                ]);
                
                // Log pour le débogage
                Log::info("Année en cours mise à jour: {$anneeEnCours} pour la base {$databaseName}");
            }
        } catch (\Exception $e) {
            // En cas d'erreur, logger sans bloquer le processus
            Log::error("Erreur lors de la mise à jour de l'année en cours: " . $e->getMessage());
        }
    }
}
