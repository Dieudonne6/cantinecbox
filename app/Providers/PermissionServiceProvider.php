<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\PermissionHelper;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enregistrer des directives Blade personnalisées pour les permissions
        
        // @canAccess('permission') ... @endcanAccess
        Blade::if('canAccess', function ($permission) {
            return PermissionHelper::canAccess($permission);
        });

        // @canView('permission') ... @endcanView
        Blade::if('canView', function ($permission) {
            return PermissionHelper::hasPermission($permission . '.view');
        });

        // @canManage('permission') ... @endcanManage
        Blade::if('canManage', function ($permission) {
            return PermissionHelper::canManage($permission);
        });

        // @hasPermission('exact.permission') ... @endhasPermission
        Blade::if('hasPermission', function ($permission) {
            return PermissionHelper::hasPermission($permission);
        });

        // @isAdmin ... @endisAdmin (insensible à la casse)
        Blade::if('isAdmin', function () {
            $userInfo = PermissionHelper::getUserInfo();
            return $userInfo && isset($userInfo['groupe']) && strcasecmp(trim($userInfo['groupe']), 'ADMINISTRATEUR') === 0;
        });

        // @canOnlyView('permission') ... @endcanOnlyView
        Blade::if('canOnlyView', function ($permission) {
            return PermissionHelper::canOnlyView($permission);
        });

        // Directive pour afficher le niveau de permission
        Blade::directive('permissionLevel', function ($permission) {
            return "<?php echo App\\Helpers\\PermissionHelper::getPermissionLevel($permission); ?>";
        });

        // Directive pour afficher toutes les permissions de l'utilisateur (debug)
        Blade::directive('userPermissions', function () {
            return "<?php \$permissions = App\\Helpers\\PermissionHelper::getAllUserPermissions(); echo json_encode(\$permissions, JSON_PRETTY_PRINT); ?>";
        });
    }
}
