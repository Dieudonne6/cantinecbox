<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DecisionConfiguration extends Model
{
    // If your table name differs from the pluralized model name, uncomment the line below:
     protected $table = 'decision_configurations';

    // Allow mass assignment on these fields
    protected $fillable = [
        'promotion',
        // Nouveau intervals
        'NouveauBorneI1A', 'NouveauBorneI1B', 'NouveauLibelleI1',
        'NouveauBorneI2A', 'NouveauBorneI2B', 'NouveauLibelleI2',
        'NouveauBorneI3A', 'NouveauBorneI3B', 'NouveauLibelleI3',
        'NouveauBorneI4A', 'NouveauBorneI4B', 'NouveauLibelleI4',
        'NouveauBorneI5A', 'NouveauBorneI5B', 'NouveauLibelleI5',
        // Ancien intervals
        'AncienBorneI1A', 'AncienBorneI1B', 'AncienLibelleI1',
        'AncienBorneI2A', 'AncienBorneI2B', 'AncienLibelleI2',
        'AncienBorneI3A', 'AncienBorneI3B', 'AncienLibelleI3',
        'AncienBorneI4A', 'AncienBorneI4B', 'AncienLibelleI4',
        'AncienBorneI5A', 'AncienBorneI5B', 'AncienLibelleI5',
    ];
}