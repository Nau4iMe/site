<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentForumPivot extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_forum_pivot', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('content_id')->unsigned()->unique();
            $table->integer('id_msg')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('content_forum_pivot', function($table) {
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade')->onUpdate('restrict');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content_forum_pivot');
    }

}
