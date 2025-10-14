<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('mysql2');

        if (!$schema->hasTable('groupe_permissions')) {
            return;
        }

        // 1) Ajouter la colonne 'permissions' si elle n'existe pas
        $schema->table('groupe_permissions', function (Blueprint $table) use ($schema) {
            if (!$schema->hasColumn('groupe_permissions', 'permissions')) {
                $table->string('permissions', 100)->nullable()->after('nomgroupe');
            }
        });

        // 2) Copier les données de 'permission' -> 'permissions' si applicable
        if ($schema->hasColumn('groupe_permissions', 'permission')) {
            DB::connection('mysql2')->statement("UPDATE groupe_permissions SET permissions = permission WHERE permissions IS NULL OR permissions = ''");
        }

        // 3) Supprimer les index basés sur (nomgroupe, permission) s'ils existent
        try {
            $schema->table('groupe_permissions', function (Blueprint $table) {
                $table->dropUnique('groupe_permissions_nomgroupe_permission_unique');
            });
        } catch (\Throwable $e) { /* ignore */ }
        try {
            $schema->table('groupe_permissions', function (Blueprint $table) {
                $table->dropIndex('groupe_permissions_nomgroupe_permission_index');
            });
        } catch (\Throwable $e) { /* ignore */ }

        // 4) Normaliser le type de 'nomgroupe' en string (sans DBAL) via colonne temporaire
        if (!$schema->hasColumn('groupe_permissions', 'nomgroupe_str')) {
            $schema->table('groupe_permissions', function (Blueprint $table) {
                $table->string('nomgroupe_str', 100)->nullable()->after('id');
            });
            DB::connection('mysql2')->statement('UPDATE groupe_permissions SET nomgroupe_str = nomgroupe');

            // Supprimer l'ancienne colonne nomgroupe (numérique) puis la recréer en string
            $schema->table('groupe_permissions', function (Blueprint $table) {
                $table->dropColumn('nomgroupe');
            });
            $schema->table('groupe_permissions', function (Blueprint $table) {
                $table->string('nomgroupe', 100)->nullable()->after('id');
            });
            DB::connection('mysql2')->statement('UPDATE groupe_permissions SET nomgroupe = nomgroupe_str');
            $schema->table('groupe_permissions', function (Blueprint $table) {
                $table->dropColumn('nomgroupe_str');
            });
        }

        // 5) Supprimer l'ancienne colonne 'permission' si elle existe
        if ($schema->hasColumn('groupe_permissions', 'permission')) {
            $schema->table('groupe_permissions', function (Blueprint $table) {
                $table->dropColumn('permission');
            });
        }

        // 6) Recréer les index sur (nomgroupe, permissions)
        $schema->table('groupe_permissions', function (Blueprint $table) {
            $table->index(['nomgroupe', 'permissions']);
            $table->unique(['nomgroupe', 'permissions']);
        });
    }

    public function down(): void
    {
        $schema = Schema::connection('mysql2');

        if (!$schema->hasTable('groupe_permissions')) {
            return;
        }

        // Recréer 'permission' et recopier depuis 'permissions'
        $schema->table('groupe_permissions', function (Blueprint $table) use ($schema) {
            if (!$schema->hasColumn('groupe_permissions', 'permission')) {
                $table->string('permission', 100)->nullable()->after('nomgroupe');
            }
        });
        if ($schema->hasColumn('groupe_permissions', 'permissions')) {
            DB::connection('mysql2')->statement("UPDATE groupe_permissions SET permission = permissions WHERE permission IS NULL OR permission = ''");
        }

        // Supprimer les nouveaux index et rétablir les anciens
        try {
            $schema->table('groupe_permissions', function (Blueprint $table) {
                $table->dropUnique('groupe_permissions_nomgroupe_permissions_unique');
            });
        } catch (\Throwable $e) { /* ignore */ }
        try {
            $schema->table('groupe_permissions', function (Blueprint $table) {
                $table->dropIndex('groupe_permissions_nomgroupe_permissions_index');
            });
        } catch (\Throwable $e) { /* ignore */ }

        $schema->table('groupe_permissions', function (Blueprint $table) {
            $table->index(['nomgroupe', 'permission']);
            $table->unique(['nomgroupe', 'permission']);
        });

        // NB: on ne reconvertit pas 'nomgroupe' en entier ici (opération risquée)
    }
};
