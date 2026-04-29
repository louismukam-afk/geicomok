<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaiementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_personnel');
            $table->integer('id_user');
            $table->float('montant');
            $table->date('date_p');
            $table->string('numero')->nullable();
            $table->string('mois');
            $table->float('primes')->default(0);
            $table->float('acomptes')->default(0);
            $table->float('cnps')->default(0);
            $table->float('cas_social')->default(0);
            $table->float('assurance')->default(0);
            $table->float('autre_retenues')->default(0);
            $table->float('total')->default(0);
            $table->float('total_retenu')->default(0);
            $table->float('net_a_payer')->default(0);
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
        Schema::dropIfExists('paiements');
    }
}
