<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use App\Models\Groupe;
use App\Models\Users;

class CheckGroupePermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        // Vérifier si l'utilisateur est connecté via la session
        $account = Session::get('account');
        if (!$account) {
            return redirect('/')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifier si l'utilisateur est actif
        if ($account->user_actif != 1) {
            Session::flush();
            return redirect('/')->with('error', 'Votre compte a été désactivé.');
        }

        // Bypass complet pour le groupe ADMINISTRATEUR (insensible à la casse)
        // afin de garantir l'accès même si la table groupe_permissions est vide
        if (isset($account->nomgroupe) && strcasecmp(trim($account->nomgroupe), 'ADMINISTRATEUR') === 0) {
            // Admin = niveau manage par défaut
            $base = self::extractBasePermission($permission);
            $pagePermission = [
                'base' => $base,
                'level' => 'manage',
                'isReadOnly' => false,
                'canManage' => true,
            ];
            $request->attributes->set('pagePermission', $pagePermission);
            View::share('pagePermission', $pagePermission);
            return $next($request);
        }

        // Obtenir le groupe de l'utilisateur
        $groupe = Groupe::where('nomgroupe', $account->nomgroupe)->first();
        
        if (!$groupe) {
            return redirect()->back()->with('error', 'Aucun groupe assigné à votre compte.');
        }

        // Vérifier si le groupe a la permission
        if (!$groupe->hasPermission($permission)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
        }

        // Partager le niveau de permission avec les vues (lecture seule vs gestion)
        $base = self::extractBasePermission($permission);
        $level = self::getUserPermissionLevel($base);
        $pagePermission = [
            'base' => $base,
            'level' => $level,
            'isReadOnly' => ($level === 'view'),
            'canManage' => ($level === 'manage'),
        ];
        $request->attributes->set('pagePermission', $pagePermission);
        View::share('pagePermission', $pagePermission);

        return $next($request);
    }

    /**
     * Vérifier si l'utilisateur a une permission spécifique
     */
    public static function userHasPermission($permission)
    {
        // Vérifier si l'utilisateur est connecté via la session
        $account = Session::get('account');
        if (!$account) {
            return false;
        }
        
        // Vérifier si l'utilisateur est actif
        if ($account->user_actif != 1) {
            return false;
        }
        
        // Bypass complet pour le groupe ADMINISTRATEUR (insensible à la casse)
        if (isset($account->nomgroupe) && strcasecmp(trim($account->nomgroupe), 'ADMINISTRATEUR') === 0) {
            return true;
        }
        
        $groupe = Groupe::where('nomgroupe', $account->nomgroupe)->first();
        
        return $groupe ? $groupe->hasPermission($permission) : false;
    }

    /**
     * Vérifier si l'utilisateur peut seulement voir (read-only)
     */
    public static function userCanOnlyView($basePermission)
    {
        $viewPermission = $basePermission . '.view';
        $managePermission = $basePermission . '.manage';
        
        return self::userHasPermission($viewPermission) && !self::userHasPermission($managePermission);
    }

    /**
     * Vérifier si l'utilisateur peut gérer (actions complètes)
     */
    public static function userCanManage($basePermission)
    {
        $managePermission = $basePermission . '.manage';
        return self::userHasPermission($managePermission);
    }

    /**
     * Obtenir le niveau de permission de l'utilisateur pour une ressource
     */
    public static function getUserPermissionLevel($basePermission)
    {
        if (self::userCanManage($basePermission)) {
            return 'manage'; // Peut tout faire
        } elseif (self::userCanOnlyView($basePermission)) {
            return 'view'; // Lecture seule
        } else {
            return 'none'; // Aucun accès
        }
    }

    /**
     * Extraire la permission de base (ex: 'eleves' à partir de 'eleves.view')
     */
    private static function extractBasePermission($permission)
    {
        $dotPos = strrpos($permission, '.');
        return $dotPos !== false ? substr($permission, 0, $dotPos) : $permission;
    }
}
