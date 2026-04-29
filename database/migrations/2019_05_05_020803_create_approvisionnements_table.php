<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovisionnementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvisionnements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_magasin');
            $table->string('numero')->nullable();
            $table->date('date_approv');
            $table->float('total')->default(0);
            $table->float('tva')->default(0);
            $table->integer('paye')->default(0);
            $table->integer('id_boutique');
            $table->integer('id_user');

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
        Schema::dropIfExists('approvisionnements');
    }
}
