<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decision_config_annuel', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('seuil_Passage', 8, 2);
            $table->decimal('Min_Cycle1', 8, 2);
            $table->decimal('Min_Cycle2', 8, 2);
            $table->string('Promotion');
            $table->boolean('Statut')->default(0);
            $table->enum('StatutF', ['P', 'X', 'R'])->default('P');
            $table->decimal('Seuil_Felicitations', 8, 2);
            $table->decimal('Seuil_Encouragements', 8, 2);
            $table->decimal('Seuil_tableau_Honneur', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decision_config_annuel');
    }
};
