<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matieres extends Model
{
    use HasFactory;
    protected $table = 'matieres';
    protected $primaryKey = 'CODEMAT';
    public $timestamps = false;
}