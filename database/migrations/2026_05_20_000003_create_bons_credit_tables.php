<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonsCreditTables extends Migration
{
    public function up()
    {
        Schema::create('bons_credit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero')->nullable();
            $table->integer('id_client');
            $table->integer('id_boutique');
            $table->integer('id_user');
            $table->decimal('montant_credit', 15, 2)->default(0);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->date('date_debut_remboursement')->nullable();
            $table->date('date_fin_remboursement')->nullable();
            $table->string('statut')->default('actif');
            $table->text('observations')->nullable();
            $table->timestamps();
        });

        Schema::create('bon_credit_echeances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_bon_credit');
            $table->date('date_echeance');
            $table->decimal('montant', 15, 2)->default(0);
            $table->decimal('montant_paye', 15, 2)->default(0);
            $table->string('statut')->default('en_attente');
            $table->timestamps();
        });

        Schema::create('bon_credit_remboursements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_bon_credit');
            $table->integer('id_echeance')->nullable();
            $table->integer('id_caisse')->nullable();
            $table->integer('id_user');
            $table->date('date_paiement');
            $table->decimal('montant', 15, 2)->default(0);
            $table->string('numero')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });

        Schema::table('factures', function (Blueprint $table) {
            if (!Schema::hasColumn('factures', 'id_bon_credit')) {
                $table->integer('id_bon_credit')->nullable();
            }
            if (!Schema::hasColumn('factures', 'mode_vente')) {
                $table->string('mode_vente')->default('cash');
            }
        });
    }

    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            if (Schema::hasColumn('factures', 'id_bon_credit')) {
                $table->dropColumn('id_bon_credit');
            }
            if (Schema::hasColumn('factures', 'mode_vente')) {
                $table->dropColumn('mode_vente');
            }
        });

        Schema::dropIfExists('bon_credit_remboursements');
        Schema::dropIfExists('bon_credit_echeances');
        Schema::dropIfExists('bons_credit');
    }
}
