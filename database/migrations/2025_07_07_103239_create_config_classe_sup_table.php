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
        Schema::create('config_classe_sup', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codeClas')->unique();
            $table->string('libelle_promotion');
            $table->string('libelle_classe_sup');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_classe_sup');
    }
};
