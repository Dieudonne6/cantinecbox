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
        Schema::create('facturescolarit', function (Blueprint $table) {
            //  External reference UID
            $table->string('uid')->index();

            // Primary key: id
            $table->string('id')->primary();

            $table->string('codemecef')->nullable();
            $table->string('codemeceffacoriginale')->nullable();
            $table->string('counters')->nullable();
            $table->string('nim')->nullable();
            $table->dateTime('dateHeure')->nullable();
            $table->string('ifuEcole')->nullable();
            $table->string('MATRICULE')->nullable();
            $table->string('nom')->nullable();
            $table->string('classe')->nullable();
            $table->string('mode_paiement')->nullable();
            $table->string('NUMRECU')->nullable();

            // JSON content of items
            $table->json('itemfacture')->nullable();

            // monetary values
            $table->decimal('montant_total', 15, 2)->default(0);
            $table->string('tax_group')->nullable();

            $table->dateTime('date_time')->nullable();

            // QR code stored as binary blob
            $table->binary('qrcode')->nullable();

            // status flag
            $table->integer('statut')->default(1);

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
        Schema::dropIfExists('facturescolarit');
    }
};




