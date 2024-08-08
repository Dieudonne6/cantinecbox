<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elevea extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'elevea';
    public $timestamps = false;
}
