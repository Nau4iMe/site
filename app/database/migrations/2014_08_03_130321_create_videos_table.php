<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVideosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('content_id')->unsigned()->index()->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->string('name');
            $table->tinyInteger('legacy');
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
        Schema::drop('videos');
    }

}
