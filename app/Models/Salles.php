<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salles extends Model
{
    protected $table = 'salles';
    protected $primaryKey = 'CODESALLE';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['CODESALLE', 'LOCALISATION'];
}