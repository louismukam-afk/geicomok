<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecuritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('securites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_produit')->default(0);
            $table->integer('id_user')->default(0);
            $table->integer('id_stock')->default(0);
            $table->float('stock_minimum')->default(0);
            $table->integer('id_boutique')->default(0);
            $table->integer('statut')->default(0);
            $table->string('observation')->null();
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
        Schema::dropIfExists('securites');
    }
}
