<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;
    protected $table = 'matieres';
    public $timestamps = false;

    public function notes()
    {
        return $this->hasMany(Notes::class, 'CODEMAT', 'CODEMAT');
    }
}

