<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('decision_configurations', function (Blueprint $table) {
            $table->id();
            // Promotion (year or name)
            $table->string('promotion');
            // Student type: ancien or nouveau
            $table->string('STATUTG');

            // NOUVEAU
            // Interval bounds
            $table->decimal('NouveauBorneI1A', 4, 2);
            $table->decimal('NouveauBorneI1B', 4, 2);
            // Label for this interval
            $table->string('NouveauLibelleI1');

            $table->decimal('NouveauBorneI2A', 4, 2);
            $table->decimal('NouveauBorneI2B', 4, 2);
            // Label for this interval
            $table->string('NouveauLibelleI2');

            // Label for this interval
            $table->decimal('NouveauBorneI3A', 4, 2);
            $table->decimal('NouveauBorneI3B', 4, 2);
            // Label for this interval
            $table->string('NouveauLibelleI3');

            // Label for this interval
            $table->decimal('NouveauBorneI4A', 4, 2);
            $table->decimal('NouveauBorneI4B', 4, 2);
            // Label for this interval
            $table->string('NouveauLibelleI4');

            // Label for this interval
            $table->decimal('NouveauBorneI5A', 4, 2);
            $table->decimal('NouveauBorneI5B', 4, 2);
            // Label for this interval
            $table->string('NouveauLibelleI5');

            // ANCIEN
            // Interval bounds
            $table->decimal('AncienBorneI1A', 4, 2);
            $table->decimal('AncienBorneI1B', 4, 2);
            // Label for this interval
            $table->string('AncienLibelleI1');

            $table->decimal('AncienBorneI2A', 4, 2);
            $table->decimal('AncienBorneI2B', 4, 2);
            // Label for this interval
            $table->string('AncienLibelleI2');

            // Label for this interval
            $table->decimal('AncienBorneI3A', 4, 2);
            $table->decimal('AncienBorneI3B', 4, 2);
            // Label for this interval
            $table->string('AncienLibelleI3');

            // Label for this interval
            $table->decimal('AncienBorneI4A', 4, 2);
            $table->decimal('AncienBorneI4B', 4, 2);
            // Label for this interval
            $table->string('AncienLibelleI4');

            // Label for this interval
            $table->decimal('AncienBorneI5A', 4, 2);
            $table->decimal('AncienBorneI5B', 4, 2);
            // Label for this interval
            $table->string('AncienLibelleI5');

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
        Schema::dropIfExists('decision_configurations');
    }
};
