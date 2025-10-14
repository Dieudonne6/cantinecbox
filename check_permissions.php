<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Groupe;
use App\Models\GroupePermission;

echo "=== Vérification des permissions ===\n";

// Vérifier le groupe ADMINISTRATEUR
$groupe = Groupe::where('nomgroupe', 'ADMINISTRATEUR')->first();

if ($groupe) {
    echo "✅ Groupe trouvé: " . $groupe->nomgroupe . "\n";
    
    $permissions = $groupe->permissions;
    echo "📋 Nombre de permissions: " . $permissions->count() . "\n";
    
    if ($permissions->count() > 0) {
        echo "📝 Liste des permissions:\n";
        foreach ($permissions as $perm) {
            echo "   - " . $perm->permissions . "\n";
        }
        
        // Vérifier spécifiquement admin.manage
        $hasAdminManage = $groupe->hasPermission('admin.manage');
        echo "\n🔐 Permission 'admin.manage': " . ($hasAdminManage ? "✅ OUI" : "❌ NON") . "\n";
        
    } else {
        echo "⚠️  Aucune permission trouvée pour ce groupe\n";
        echo "💡 Ajout de la permission admin.manage...\n";
        
        GroupePermission::create([
            'nomgroupe' => 'ADMINISTRATEUR',
            'permissions' => 'admin.manage',
            'module' => 'Administration',
            'description' => 'Accès complet à l\'administration'
        ]);
        
        echo "✅ Permission admin.manage ajoutée!\n";
    }
    
} else {
    echo "❌ Groupe 'ADMINISTRATEUR' non trouvé\n";
    echo "💡 Création du groupe ADMINISTRATEUR...\n";
    
    $groupe = Groupe::create(['nomgroupe' => 'ADMINISTRATEUR']);
    
    GroupePermission::create([
        'nomgroupe' => 'ADMINISTRATEUR',
        'permissions' => 'admin.manage',
        'module' => 'Administration',
        'description' => 'Accès complet à l\'administration'
    ]);
    
    echo "✅ Groupe et permission créés!\n";
}

echo "\n=== Fin de la vérification ===\n";
