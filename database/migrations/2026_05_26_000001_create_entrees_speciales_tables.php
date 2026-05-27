<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntreesSpecialesTables extends Migration
{
    public function up()
    {
        Schema::create('entrees_speciales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero')->nullable();
            $table->integer('id_caisse');
            $table->integer('id_boutique');
            $table->integer('id_user');
            $table->string('type');
            $table->string('source_nom')->nullable();
            $table->decimal('montant', 15, 2)->default(0);
            $table->date('date_apport');
            $table->date('date_debut_remboursement')->nullable();
            $table->date('date_fin_remboursement')->nullable();
            $table->integer('nombre_echeances')->nullable();
            $table->string('statut')->default('actif');
            $table->text('observations')->nullable();
            $table->timestamps();
        });

        Schema::create('entree_speciale_echeances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_entree_speciale');
            $table->date('date_echeance');
            $table->decimal('montant', 15, 2)->default(0);
            $table->decimal('montant_paye', 15, 2)->default(0);
            $table->string('statut')->default('en_attente');
            $table->timestamps();
        });

        Schema::create('entree_speciale_remboursements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_entree_speciale');
            $table->integer('id_echeance')->nullable();
            $table->integer('id_caisse');
            $table->integer('id_user');
            $table->string('numero')->nullable();
            $table->date('date_remboursement');
            $table->decimal('montant', 15, 2)->default(0);
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entree_speciale_remboursements');
        Schema::dropIfExists('entree_speciale_echeances');
        Schema::dropIfExists('entrees_speciales');
    }
}
