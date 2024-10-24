<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve_pont extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'eleve_pont';
    public $timestamps = false;
    
}