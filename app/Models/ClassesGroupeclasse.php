<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassesGroupeclasse extends Model
{
    use HasFactory;
    
    protected $table = 'classes_groupeclasse';
    public $timestamps = false;
    
    protected $fillable = [
        'LibelleGroupe',
        'CODECLAS'
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'CODECLAS', 'CODECLAS');
    }

    public function groupeclasse()
    {
        return $this->belongsTo(Groupeclasse::class, 'LibelleGroupe', 'LibelleGroupe');
    }
}
