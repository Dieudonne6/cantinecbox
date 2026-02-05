<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SwitchDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si une base de données est spécifiée dans la session ou la requête
        $database = $request->input('database') ?? Session::get('selected_database');
        
        if ($database) {
            // Configurer la connexion à la base de données sélectionnée
            Config::set('database.connections.mysql.database', $database);
            
            // Purger la connexion pour forcer Laravel à utiliser la nouvelle configuration
            DB::purge('mysql');
            
            // Stocker en session pour les requêtes suivantes
            Session::put('selected_database', $database);
        }
        
        return $next($request);
    }
}
