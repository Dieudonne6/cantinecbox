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
            $table->decimal('seuil_Passage', 4, 2);
            $table->decimal('Min_Cycle1', 4, 2);
            $table->decimal('Min_Cycle2', 4, 2);
            $table->string('Promotion');
            $table->boolean('Statut')->default(0);
            $table->enum('StatutF', ['P', 'X', 'R'])->default('P');
            $table->decimal('Seuil_Felicitations', 4, 2);
            $table->decimal('Seuil_Encouragements', 4, 2);
            $table->decimal('Seuil_tableau_Honneur', 4, 2);
            $table->timestamps();
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
