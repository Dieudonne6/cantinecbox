<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Groupe;
use App\Models\GroupePermission;

// Configuration de la base de données
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'cantinecbox',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
], 'mysql2');

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🔍 ANALYSE DES NIVEAUX DE VÉRIFICATION DES PERMISSIONS\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// 1. Structure de la table groupe_permissions
echo "📋 STRUCTURE DE LA TABLE groupe_permissions :\n";
try {
    $columns = Capsule::connection('mysql2')->select("DESCRIBE groupe_permissions");
    foreach ($columns as $column) {
        echo "   - {$column->Field} ({$column->Type}) " . 
             ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . 
             ($column->Key ? " [{$column->Key}]" : '') . "\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur : " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("-", 60) . "\n\n";

// 2. Données actuelles dans la table
echo "📊 DONNÉES ACTUELLES DANS LA TABLE :\n";
try {
    $permissions = Capsule::connection('mysql2')
        ->table('groupe_permissions')
        ->select('nomgroupe', 'permissions', 'module', 'description')
        ->orderBy('nomgroupe')
        ->orderBy('permissions')
        ->get();
    
    $groupedByGroupe = $permissions->groupBy('nomgroupe');
    
    foreach ($groupedByGroupe as $nomgroupe => $groupePermissions) {
        echo "🔐 Groupe: {$nomgroupe}\n";
        echo "   Nombre de permissions: " . $groupePermissions->count() . "\n";
        echo "   Permissions:\n";
        
        foreach ($groupePermissions->take(10) as $perm) {
            echo "     - {$perm->permissions}";
            if ($perm->module) echo " (Module: {$perm->module})";
            echo "\n";
        }
        
        if ($groupePermissions->count() > 10) {
            echo "     ... et " . ($groupePermissions->count() - 10) . " autres\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur : " . $e->getMessage() . "\n";
}

echo str_repeat("-", 60) . "\n\n";

// 3. Test des méthodes de vérification
echo "🧪 TEST DES MÉTHODES DE VÉRIFICATION :\n";

try {
    // Test avec le groupe ADMINISTRATEUR
    $adminGroupe = Capsule::connection('mysql2')
        ->table('groupe')
        ->where('nomgroupe', 'ADMINISTRATEUR')
        ->first();
    
    if ($adminGroupe) {
        echo "✅ Groupe 'ADMINISTRATEUR' trouvé\n";
        
        // Test de quelques permissions spécifiques
        $testPermissions = [
            'admin/roles.manage',
            'eleves.view',
            'eleves.manage',
            'typesclasses.view',
            'discipline.manage'
        ];
        
        foreach ($testPermissions as $testPerm) {
            $hasPermission = Capsule::connection('mysql2')
                ->table('groupe_permissions')
                ->where('nomgroupe', 'ADMINISTRATEUR')
                ->where('permissions', $testPerm)
                ->exists();
            
            echo "   🔍 Permission '{$testPerm}': " . ($hasPermission ? "✅ OUI" : "❌ NON") . "\n";
        }
    } else {
        echo "❌ Groupe 'ADMINISTRATEUR' non trouvé\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur : " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("-", 60) . "\n\n";

// 4. Analyse des patterns de permissions
echo "📈 ANALYSE DES PATTERNS DE PERMISSIONS :\n";

try {
    $allPermissions = Capsule::connection('mysql2')
        ->table('groupe_permissions')
        ->select('permissions')
        ->distinct()
        ->pluck('permissions')
        ->filter()
        ->sort();
    
    echo "📊 Total des permissions uniques: " . $allPermissions->count() . "\n\n";
    
    // Grouper par type (.view, .manage, etc.)
    $byType = [];
    foreach ($allPermissions as $perm) {
        if (strpos($perm, '.view') !== false) {
            $byType['view'][] = $perm;
        } elseif (strpos($perm, '.manage') !== false) {
            $byType['manage'][] = $perm;
        } else {
            $byType['other'][] = $perm;
        }
    }
    
    foreach ($byType as $type => $perms) {
        echo "🏷️  Type '{$type}': " . count($perms) . " permissions\n";
        if ($type === 'view') {
            echo "   Exemples: " . implode(', ', array_slice($perms, 0, 3)) . "\n";
        } elseif ($type === 'manage') {
            echo "   Exemples: " . implode(', ', array_slice($perms, 0, 3)) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur : " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 RÉSUMÉ DES NIVEAUX DE VÉRIFICATION :\n";
echo "1. 🔍 Vérification au niveau MIDDLEWARE (CheckGroupePermission)\n";
echo "2. 🔍 Vérification au niveau MODÈLE (Groupe::hasPermission)\n";
echo "3. 🔍 Vérification au niveau BASE DE DONNÉES (table groupe_permissions)\n";
echo "4. 🔍 Vérification au niveau VUE (directives Blade)\n";
echo "5. 🔍 Vérification au niveau HELPER (PermissionHelper)\n";
echo str_repeat("=", 60) . "\n";
