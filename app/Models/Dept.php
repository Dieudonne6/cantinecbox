<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Dept extends Model
{
    protected $table = 'dept';
    protected $primaryKey = 'CODEDEPT';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CODEDEPT',
        'LIBELDEP',
    ];
}