<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero')->nullable();
            $table->date('date_vente');
            $table->float('reduction')->default(0);
            $table->integer('id_client')->default(0);
            $table->float('tva')->default(0);
            $table->integer('paye')->default(1);
            $table->float('total')->default(0);
            $table->float('verse')->default(0);
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
        Schema::dropIfExists('factures');
    }
}
