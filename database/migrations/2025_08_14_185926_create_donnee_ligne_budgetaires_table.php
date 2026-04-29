<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonneeLigneBudgetairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donnee_ligne_budgetaires', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_ligne_budgetaire')->default(0);
            $table->integer('id_categorie_budgetaire')->default(0);
            $table->double('montant')->default(0);
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
        Schema::dropIfExists('donnee_ligne_budgetaires');
    }
}
