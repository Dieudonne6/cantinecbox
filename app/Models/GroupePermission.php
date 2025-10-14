<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupePermission extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'groupe_permissions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'nomgroupe',
        'permissions',  
        'module',       
        'description'
    ];

    /**
     * Relation avec le groupe
     */
    public function groupe()
    {
        // Lier sur la clé non incrémentale 'nomgroupe' (string)
        return $this->belongsTo(Groupe::class, 'nomgroupe', 'nomgroupe');
    }

    /**
     * Scopes pour filtrer les permissions
     */
    public function scopeForGroupe($query, $nomgroupe)
    {
        return $query->where('nomgroupe', $nomgroupe);
    }
    
    public function scopeWithPermission($query, $permission)
    {
        return $query->where('permissions', $permission);
    }
    
    public function scopeForModule($query, $module)
    {
        return $query->where('module', $module);
    }
}
