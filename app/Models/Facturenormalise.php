<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturenormalise extends Model
{
    use HasFactory;
    protected $table = 'facturenormalises';

    protected $fillable = ['id'];

}
