<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnrichLogsTableForAudit extends Migration
{
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            if (!Schema::hasColumn('logs', 'id_user')) {
                $table->integer('id_user')->nullable();
            }
            if (!Schema::hasColumn('logs', 'operation')) {
                $table->string('operation')->nullable();
            }
            if (!Schema::hasColumn('logs', 'route_name')) {
                $table->string('route_name')->nullable();
            }
            if (!Schema::hasColumn('logs', 'method')) {
                $table->string('method', 16)->nullable();
            }
            if (!Schema::hasColumn('logs', 'url')) {
                $table->text('url')->nullable();
            }
            if (!Schema::hasColumn('logs', 'ip')) {
                $table->string('ip', 64)->nullable();
            }
            if (!Schema::hasColumn('logs', 'user_agent')) {
                $table->text('user_agent')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            foreach (['id_user', 'operation', 'route_name', 'method', 'url', 'ip', 'user_agent'] as $column) {
                if (Schema::hasColumn('logs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
