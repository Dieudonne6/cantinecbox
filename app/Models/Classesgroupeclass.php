<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classesgroupeclass extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'classes_groupeclasse';
    protected $fillable = ['LibelleGroupe', 'CODECLAS'];
}