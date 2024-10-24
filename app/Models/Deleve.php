<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deleve extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'delevea';
    public $timestamps = false;
    
}