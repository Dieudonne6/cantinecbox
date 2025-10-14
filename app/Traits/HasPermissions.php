<?php

namespace App\Traits;

use App\Helpers\PermissionHelper;
use Illuminate\Http\Response;

trait HasPermissions
{
    /**
     * Vérifier si l'utilisateur a une permission et retourner une erreur 403 si non
     */
    protected function checkPermission($permission)
    {
        if (!PermissionHelper::hasPermission($permission)) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
        }
    }

    /**
     * Vérifier si l'utilisateur peut voir une ressource
     */
    protected function checkViewPermission($basePermission)
    {
        $this->checkPermission($basePermission . '.view');
    }

    /**
     * Vérifier si l'utilisateur peut gérer une ressource
     */
    protected function checkManagePermission($basePermission)
    {
        $this->checkPermission($basePermission . '.manage');
    }

    /**
     * Vérifier si l'utilisateur peut accéder à une ressource (view ou manage)
     */
    protected function checkAccessPermission($basePermission)
    {
        if (!PermissionHelper::canAccess($basePermission)) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour accéder à cette ressource.');
        }
    }

    /**
     * Retourner une réponse JSON d'erreur de permission
     */
    protected function permissionDeniedResponse($message = 'Permission refusée')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => 'PERMISSION_DENIED'
        ], 403);
    }

    /**
     * Vérifier une permission et retourner une réponse JSON si refusée
     */
    protected function checkPermissionJson($permission, $message = null)
    {
        if (!PermissionHelper::hasPermission($permission)) {
            $defaultMessage = "Vous n'avez pas la permission '{$permission}' nécessaire.";
            return $this->permissionDeniedResponse($message ?: $defaultMessage);
        }
        return null;
    }

    /**
     * Middleware de permission pour les méthodes de contrôleur
     */
    protected function applyPermissionMiddleware($permissions = [])
    {
        foreach ($permissions as $method => $permission) {
            $this->middleware(function ($request, $next) use ($permission) {
                $this->checkPermission($permission);
                return $next($request);
            })->only($method);
        }
    }

    /**
     * Obtenir les permissions de l'utilisateur pour une vue
     */
    protected function getUserPermissionsForView($basePermissions = [])
    {
        $permissions = [];
        foreach ($basePermissions as $key => $basePermission) {
            $permissions[$key] = [
                'can_view' => PermissionHelper::hasPermission($basePermission . '.view'),
                'can_manage' => PermissionHelper::canManage($basePermission),
                'level' => PermissionHelper::getPermissionLevel($basePermission)
            ];
        }
        return $permissions;
    }

    /**
     * Filtrer les données selon les permissions de l'utilisateur
     */
    protected function filterDataByPermissions($data, $basePermission)
    {
        $level = PermissionHelper::getPermissionLevel($basePermission);
        
        if ($level === 'none') {
            return collect();
        }
        
        if ($level === 'view') {
            // Retourner les données en lecture seule
            return collect($data)->map(function ($item) {
                if (is_array($item)) {
                    $item['readonly'] = true;
                }
                return $item;
            });
        }
        
        return collect($data);
    }
}
