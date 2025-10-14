<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Http\Middleware\CheckGroupePermission;

class BladeServiceProvider extends ServiceProvider
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
        // Directive pour vérifier les permissions
        Blade::if('hasPermission', function ($permission) {
            return CheckGroupePermission::userHasPermission($permission);
        });

        // Directive pour vérifier si l'utilisateur appartient à un groupe
        Blade::if('inGroup', function ($groupeNom) {
            if (!auth()->check()) {
                return false;
            }
            
            $user = auth()->user();
            $groupe = \App\Models\Groupe::find($user->nomgroupe);
            
            return $groupe && $groupe->nomgroupe === $groupeNom;
        });

        // Directive pour vérifier plusieurs permissions (OU logique)
        Blade::if('hasAnyPermission', function (...$permissions) {
            foreach ($permissions as $permission) {
                if (CheckGroupePermission::userHasPermission($permission)) {
                    return true;
                }
            }
            return false;
        });

        // Directive pour vérifier toutes les permissions (ET logique)
        Blade::if('hasAllPermissions', function (...$permissions) {
            foreach ($permissions as $permission) {
                if (!CheckGroupePermission::userHasPermission($permission)) {
                    return false;
                }
            }
            return true;
        });
    }
}
