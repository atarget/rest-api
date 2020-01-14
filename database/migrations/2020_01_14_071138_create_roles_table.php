<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name")->index();
            $table->text("rights")->index();
            $table->string("description");
            $table->float("sort");
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->bigInteger("user_id")->index();
            $table->bigInteger("role_id")->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_roles');
    }
}
