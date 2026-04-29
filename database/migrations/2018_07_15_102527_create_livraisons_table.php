<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLivraisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livraisons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_fournisseur')->default(0);
            $table->string('numero')->nullable();
            $table->date('date_approv');
            $table->float('total')->default(0);
            $table->float('tva')->default(0);
            $table->integer('paye')->default(0);
            $table->integer('id_commande')->default(0);
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
        Schema::dropIfExists('livraisons');
    }
}
