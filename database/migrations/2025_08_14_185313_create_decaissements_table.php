<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDecaissementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decaissements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_personnel');
            $table->string('motif',2048);
            $table->float('montant');
            $table->date('date');
            $table->integer('id_ligne_budgetaire')->default(0);
            $table->integer('id_categorie_budgetaire')->default(0);
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
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
        Schema::dropIfExists('decaissements');
    }
}
