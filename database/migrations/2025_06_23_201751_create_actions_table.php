<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user');
            $table->string('action_name');
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->timestamps();
        });
        $adminUsers = \GEICOM\User::whereRaw('role = 0 OR role = 1')->get();
        foreach ($adminUsers as $u) {
           // $a = new Action();
            $a=new \GEICOM\action();

            if ($u->role == 0) {
                $a->action_name = \GEICOM\action::ACTION_ALL;
            } else {
                $a->action_name = \GEICOM\action::ACTION_ALMOST_ALL;

            }
            $a->id_user = $u->id;
            $a->save();

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
}
