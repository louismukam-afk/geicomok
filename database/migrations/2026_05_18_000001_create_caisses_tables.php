<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaissesTables extends Migration
{
    public function up()
    {
        Schema::create('caisses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->string('type', 20);
            $table->integer('id_boutique')->default(0);
            $table->integer('active')->default(1);
            $table->timestamps();
        });

        Schema::create('caisse_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_caisse');
            $table->integer('id_user');
            $table->timestamps();
        });

        Schema::create('mouvements_caisse', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_caisse');
            $table->integer('id_user')->default(0);
            $table->string('type', 20);
            $table->string('source_type')->nullable();
            $table->integer('source_id')->default(0);
            $table->float('montant')->default(0);
            $table->float('solde_avant')->default(0);
            $table->float('solde_apres')->default(0);
            $table->dateTime('date_mouvement');
            $table->string('description', 2048)->nullable();
            $table->timestamps();
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->integer('id_caisse')->default(0);
        });

        Schema::table('livraisons', function (Blueprint $table) {
            $table->integer('id_caisse')->default(0);
        });

        Schema::table('decaissements', function (Blueprint $table) {
            $table->integer('id_caisse')->default(0);
        });
    }

    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn('id_caisse');
        });

        Schema::table('livraisons', function (Blueprint $table) {
            $table->dropColumn('id_caisse');
        });

        Schema::table('decaissements', function (Blueprint $table) {
            $table->dropColumn('id_caisse');
        });

        Schema::dropIfExists('mouvements_caisse');
        Schema::dropIfExists('caisse_user');
        Schema::dropIfExists('caisses');
    }
}
