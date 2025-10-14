<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\CheckGroupePermission;

class DashboardController extends Controller
{
    /**
     * Rediriger l'utilisateur vers la page vitrine après connexion
     */
    public function redirectToDashboard()
    {
        $account = Session::get('account');
        
        if (!$account) {
            return redirect('/')->with('error', 'Vous devez être connecté.');
        }

        // Rediriger directement vers la page vitrine pour tous les utilisateurs
        return redirect('/vitrine')->with('success', 'Bienvenue dans l\'application');
    }

    /**
     * Obtenir les permissions de l'utilisateur connecté
     */
    public function getUserPermissions()
    {
        $account = Session::get('account');
        
        if (!$account) {
            return response()->json(['error' => 'Non connecté'], 401);
        }

        $permissions = [];
        
        // Liste des permissions à vérifier
        $allPermissions = [
            'dashboard.view' => 'Tableau de bord',
            'classes.view' => 'Classes - Lecture',
            'classes.manage' => 'Classes - Gestion',
            'inscriptions.view' => 'Inscriptions - Lecture',
            'inscriptions.manage' => 'Inscriptions - Gestion',
            'eleves.view' => 'Élèves - Lecture',
            'eleves.manage' => 'Élèves - Gestion',
            'notes.view' => 'Notes - Lecture',
            'notes.manage' => 'Notes - Gestion',
            'editions.view' => 'Éditions - Lecture',
            'editions.manage' => 'Éditions - Gestion',
            'admin.manage' => 'Administration',
            'system.manage' => 'Système',
        ];

        foreach ($allPermissions as $permission => $description) {
            if (CheckGroupePermission::userHasPermission($permission)) {
                $permissions[$permission] = $description;
            }
        }

        return response()->json([
            'user' => [
                'login' => $account->login,
                'nomgroupe' => $account->nomgroupe,
                'fullname' => $account->prenomuser . ' ' . $account->nomuser
            ],
            'permissions' => $permissions
        ]);
    }
}
