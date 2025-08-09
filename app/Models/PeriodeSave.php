<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeSave extends Model
{
    use HasFactory;

    protected $table = 'periode_saves';

    protected $fillable = [
        'key',
        'periode',
    ];
}
