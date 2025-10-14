<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\Groupe;

class AuthPermissionService
{
    /**
     * Vérifie la session utilisateur et ses permissions.
     * Retourne soit un redirect, soit le tableau $pagePermission.
     */
    public function checkPermission(string $basePermission)
    {
        // 1. Vérifier session
        $account = Session::get('account');
        if (!$account) {
            return redirect('/')->with('error', 'Vous devez être connecté.');
        }

        // 2. Vérifier que le compte est actif
        if (isset($account->user_actif) && $account->user_actif != 1) {
            Session::forget('account');
            return redirect('/')->with('error', 'Votre compte est désactivé.');
        }

        // 3. Bypass admin (insensible à la casse)
        $isAdministrateur = isset($account->nomgroupe) && 
            strcasecmp(trim($account->nomgroupe), 'ADMINISTRATEUR') === 0;

        if ($isAdministrateur) {
            return [
                'base' => $basePermission,
                'level' => 'manage',
                'isReadOnly' => false,
                'canManage' => true,
            ];
        }

        // 4. Récupérer le groupe
        $groupe = Groupe::where('nomgroupe', $account->nomgroupe)->first();
        if (!$groupe) {
            abort(403, 'Aucun groupe assigné à votre compte.');
        }

        // 5. Vérifier permissions
        $canView = $groupe->hasPermission($basePermission . '.view');
        $canManage = $groupe->hasPermission($basePermission . '.manage');

        if (!$canView) {
            abort(403, 'Vous n\'avez pas la permission de voir cette page.');
        }

        $level = $canManage ? 'manage' : 'view';

        return [
            'base' => $basePermission,
            'level' => $level,
            'isReadOnly' => ($level === 'view'),
            'canManage' => ($level === 'manage'),
        ];
    }
}
