<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->increments('id');
            $table->float('prix_unitaire');
            $table->integer('quantite');
            $table->integer('id_produit');
            $table->integer('id_facture');
            $table->float('reduction')->default(0);
            $table->float('total')->default(0);
            $table->date('date_vente');
            $table->integer('id_boutique');
            $table->float('prix_achat');

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
        Schema::dropIfExists('ventes');
    }
}
