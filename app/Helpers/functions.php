<?php

use App\Helpers\PermissionHelper;

if (!function_exists('hasPermission')) {
    /**
     * Vérifier si l'utilisateur connecté a une permission spécifique
     */
    function hasPermission($permission)
    {
        return PermissionHelper::hasPermission($permission);
    }
}

if (!function_exists('canView')) {
    /**
     * Vérifier si l'utilisateur peut voir une ressource
     */
    function canView($basePermission)
    {
        return PermissionHelper::hasPermission($basePermission . '.view');
    }
}

if (!function_exists('canManage')) {
    /**
     * Vérifier si l'utilisateur peut gérer une ressource
     */
    function canManage($basePermission)
    {
        return PermissionHelper::canManage($basePermission);
    }
}

if (!function_exists('canAccess')) {
    /**
     * Vérifier si l'utilisateur peut accéder à une ressource (view ou manage)
     */
    function canAccess($basePermission)
    {
        return PermissionHelper::canAccess($basePermission);
    }
}

if (!function_exists('canOnlyView')) {
    /**
     * Vérifier si l'utilisateur peut seulement voir (pas gérer)
     */
    function canOnlyView($basePermission)
    {
        return PermissionHelper::canOnlyView($basePermission);
    }
}

if (!function_exists('getPermissionLevel')) {
    /**
     * Obtenir le niveau de permission (none, view, manage)
     */
    function getPermissionLevel($basePermission)
    {
        return PermissionHelper::getPermissionLevel($basePermission);
    }
}

if (!function_exists('isAdmin')) {
    /**
     * Vérifier si l'utilisateur connecté est ADMINISTRATEUR
     */
    function isAdmin()
    {
        $userInfo = PermissionHelper::getUserInfo();
        return $userInfo && isset($userInfo['groupe']) && strcasecmp(trim($userInfo['groupe']), 'ADMINISTRATEUR') === 0;
    }
}

if (!function_exists('getCurrentUserGroup')) {
    /**
     * Obtenir le groupe de l'utilisateur connecté
     */
    function getCurrentUserGroup()
    {
        $userInfo = PermissionHelper::getUserInfo();
        return $userInfo ? $userInfo['groupe'] : null;
    }
}

if (!function_exists('getUserPermissions')) {
    /**
     * Obtenir toutes les permissions de l'utilisateur connecté
     */
    function getUserPermissions()
    {
        return PermissionHelper::getAllUserPermissions();
    }
}

if (!function_exists('redirectIfNoPermission')) {
    /**
     * Rediriger si l'utilisateur n'a pas la permission
     */
    function redirectIfNoPermission($permission, $redirectTo = '/')
    {
        if (!hasPermission($permission)) {
            return redirect($redirectTo)->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        return null;
    }
}
