<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typeclasse extends Model
{
    use HasFactory;
    protected $table = 'typeclasses';
    public $timestamps = false;
    protected $primaryKey = 'idtype';
  
}
