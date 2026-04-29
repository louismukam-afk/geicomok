<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Personnel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->date('date_naiss')->nullable();
            $table->string('lieu_naiss')->nullable();
            $table->integer('id_pays')->default(0);
            $table->string('sexe');
            $table->date('date_entree')->nullable();
            $table->string('telephone');
            $table->string('addresse');
            $table->string('email')->nullable();
            $table->string('autres',2048)->nullable();
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
        Schema::dropIfExists('personnels');
    }
}
