<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceTelephoneToEntreesSpeciales extends Migration
{
    public function up()
    {
        Schema::table('entrees_speciales', function (Blueprint $table) {
            if (!Schema::hasColumn('entrees_speciales', 'source_telephone')) {
                $table->string('source_telephone')->nullable()->after('source_nom');
            }
        });
    }

    public function down()
    {
        Schema::table('entrees_speciales', function (Blueprint $table) {
            if (Schema::hasColumn('entrees_speciales', 'source_telephone')) {
                $table->dropColumn('source_telephone');
            }
        });
    }
}
