<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'groupe';
    public $timestamps = false;
    protected $primaryKey = 'nomgroupe';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nomgroupe'
    ];

    /**
     * Relation avec les utilisateurs
     */
    public function users()
    {
        // Lier users.nomgroupe (nom du groupe) à groupe.nomgroupe
        return $this->hasMany(Users::class, 'nomgroupe', 'nomgroupe');
    }

    /**
     * Obtenir les permissions du groupe
     */
    public function permissions()
    {
        return $this->hasMany(GroupePermission::class, 'nomgroupe', 'nomgroupe');
    }

    /**
     * Vérifier si le groupe a une permission
     */
    public function hasPermission($permission)
    {
        // Bypass complet: le groupe ADMINISTRATEUR a tous les droits (insensible à la casse)
        if (isset($this->nomgroupe) && strcasecmp(trim($this->nomgroupe), 'ADMINISTRATEUR') === 0) {
            return true;
        }

        // 1) Support: permission enregistrée telle quelle dans 'permissions' (ex: 'discipline.view')
        if ($this->permissions()->where('permissions', $permission)->exists()) {
            return true;
        }

        // 2) Support: format séparé en colonnes 'module' + 'permissions'
        // Exemple: permission demandée 'discipline.view' => module = 'discipline', permissions = 'view'
        $dotPos = strrpos($permission, '.');
        if ($dotPos !== false) {
            $module = substr($permission, 0, $dotPos);
            $type = substr($permission, $dotPos + 1);

            // Si on vérifie '<base>.view', alors '<base>.manage' doit également accorder l'accès (manage ⊇ view)
            if ($type === 'view') {
                // Vérifier format brut enregistré dans la colonne 'permissions'
                if ($this->permissions()->where('permissions', $module . '.manage')->exists()) {
                    return true;
                }
                // Vérifier format séparé module + permissions (compat héritage)
                if ($this->permissions()
                    ->where('module', $module)
                    ->where('permissions', 'manage')
                    ->exists()) {
                    return true;
                }
            }

            if ($module !== '' && $type !== '') {
                return $this->permissions()
                    ->where('module', $module)
                    ->where('permissions', $type)
                    ->exists();
            }
        }

        return false;
    }

    /**
     * Assigner une permission au groupe
     */
    public function givePermission($permission)
    {
        if (!$this->hasPermission($permission)) {
            $this->permissions()->create(['permissions' => $permission]);
        }
    }

    /**
     * Retirer une permission du groupe
     */
    public function revokePermission($permission)
    {
        $this->permissions()->where('permissions', $permission)->delete();
    }

    /**
     * Synchroniser les permissions
     */
    public function syncPermissions(array $permissions)
    {
        $this->permissions()->delete();
        foreach ($permissions as $permission) {
            $this->givePermission($permission);
        }
    }

    /**
     * Obtenir le nom du groupe (alias pour nomgroupe)
     */
    public function getLibelleAttribute()
    {
        return $this->nomgroupe;
    }

    /**
     * Obtenir les permissions sous forme de tableau
     */
    public function getPermissionsArrayAttribute()
    {
        $permissions = $this->permissions()->pluck('permissions');
        return $permissions->toArray();
    }

}
