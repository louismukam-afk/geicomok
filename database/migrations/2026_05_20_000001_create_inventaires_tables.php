<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventairesTables extends Migration
{
    public function up()
    {
        Schema::create('inventaires', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_boutique');
            $table->integer('id_user');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('statut')->default('brouillon');
            $table->dateTime('date_consolidation')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });

        Schema::create('inventaire_lignes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_inventaire');
            $table->integer('id_produit');
            $table->integer('id_categorie')->default(0);
            $table->decimal('quantite_theorique', 15, 2)->default(0);
            $table->decimal('quantite_reelle', 15, 2)->nullable();
            $table->decimal('ecart', 15, 2)->default(0);
            $table->decimal('prix_achat', 15, 2)->default(0);
            $table->decimal('valeur_ecart', 15, 2)->default(0);
            $table->decimal('stock_consolide', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventaire_lignes');
        Schema::dropIfExists('inventaires');
    }
}
