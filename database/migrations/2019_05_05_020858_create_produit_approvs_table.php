<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduitApprovsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produit_approvs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_produit');
            $table->integer('quantite');
            $table->float('prix');
            $table->float('total')->default(0);
            $table->integer('id_approvisionnement')->default(0);
            $table->date('date_approv');
            $table->integer('id_boutique');
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
        Schema::dropIfExists('produit_approvs');
    }
}
