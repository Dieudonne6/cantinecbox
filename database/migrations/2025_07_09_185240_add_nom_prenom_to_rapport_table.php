<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rapport', function (Blueprint $table) {
            $table->string('NOM')->nullable()->after('MATRICULE');
            $table->string('PRENOM')->nullable()->after('NOM');
        });
    }

    public function down()
    {
        Schema::table('rapport', function (Blueprint $table) {
            $table->dropColumn(['NOM', 'PRENOM']);
        });
    }
};
