<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBanTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ban', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique();
            $table->string('reason', 255);
            $table->timestamps();

            //$table->foreign('user_id')->references('id_member')->on('smf_members')->onDelete('cascade')->onUpdate('restrict');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ban');
    }

}
