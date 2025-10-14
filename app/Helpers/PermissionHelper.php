<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use App\Models\Groupe;
use App\Models\GroupePermission;

class PermissionHelper
{
    /**
     * Vérifier si l'utilisateur connecté a une permission spécifique
     */
    public static function hasPermission($permission)
    {
        $account = Session::get('account');
        if (!$account) {
            return false;
        }

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
     * Vérifier si l'utilisateur peut seulement voir (lecture seule)
     */
    public static function canOnlyView($basePermission)
    {
        $viewPermission = $basePermission . '.view';
        $managePermission = $basePermission . '.manage';
        
        return self::hasPermission($viewPermission) && !self::hasPermission($managePermission);
    }

    /**
     * Vérifier si l'utilisateur peut gérer (actions complètes)
     */
    public static function canManage($basePermission)
    {
        $managePermission = $basePermission . '.manage';
        return self::hasPermission($managePermission);
    }

    /**
     * Obtenir le niveau de permission de l'utilisateur
     */
    public static function getPermissionLevel($basePermission)
    {
        if (self::canManage($basePermission)) {
            return 'manage';
        } elseif (self::canOnlyView($basePermission)) {
            return 'view';
        } else {
            return 'none';
        }
    }

    /**
     * Obtenir toutes les permissions de l'utilisateur connecté
     */
    public static function getAllUserPermissions()
    {
        $account = Session::get('account');
        if (!$account) {
            return [];
        }

        return GroupePermission::where('nomgroupe', $account->nomgroupe)
            ->pluck('permissions')
            ->filter()
            ->toArray();
    }

    /**
     * Vérifier si l'utilisateur peut accéder à une page (view ou manage)
     */
    public static function canAccess($basePermission)
    {
        return self::hasPermission($basePermission . '.view') || 
               self::hasPermission($basePermission . '.manage');
    }

    /**
     * Obtenir les informations de l'utilisateur connecté avec ses permissions
     */
    public static function getUserInfo()
    {
        $account = Session::get('account');
        if (!$account) {
            return null;
        }

        return [
            'account' => $account,
            'permissions' => self::getAllUserPermissions(),
            'groupe' => $account->nomgroupe
        ];
    }
}
