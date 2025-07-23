<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigClasseSup extends Model
{
    protected $table = 'config_classe_sup';
    public $timestamps = false;       // si vous n’avez pas created_at/updated_at
    protected $primaryKey = 'id';     // clé primaire
    protected $fillable = [
        'classeSup',
        'libelle_promotion',
        'libelle_classe_sup',
    ];
}
