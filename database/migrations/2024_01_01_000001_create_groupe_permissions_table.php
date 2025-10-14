<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->create('groupe_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomgroupe');
            $table->string('permission', 100); // ex: 'eleves.view', 'finance.create'
            $table->string('module', 50); // ex: 'Gestion Élèves', 'Finance'
            $table->string('description', 255)->nullable();
            
            $table->index(['nomgroupe', 'permission']);
            $table->unique(['nomgroupe', 'permission']);
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('groupe_permissions');
    }
};
