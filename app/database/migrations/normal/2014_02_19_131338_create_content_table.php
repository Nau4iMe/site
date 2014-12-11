<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->mediumText('introtext');
            $table->mediumText('fullcontent');
            $table->tinyInteger('state')->unsigned()->index();
            $table->integer('catid')->unsigned()->index();
            $table->integer('ordering');
            $table->integer('access')->unsigned()->index();
            $table->integer('hits')->unsigned();
            $table->tinyInteger('featured')->unsigned()->index();
            $table->integer('created_by')->unsigned()->index();
            $table->string('created_by_alias');
            $table->integer('updated_by')->unsigned()->index();
            $table->timestamps();
        });     
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content');
    }

}
