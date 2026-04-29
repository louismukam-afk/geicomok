<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrixGros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->float('prix_semi_gros')->default(0);
            $table->float('prix_comptoir')->default(0);
            $table->float('prix_gros')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->removeColumn('prix_semi_gros');
            $table->removeColumn('prix_comptoir');
            $table->removeColumn('prix_gros');
        });
    }
}
