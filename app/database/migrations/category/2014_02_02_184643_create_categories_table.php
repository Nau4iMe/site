<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\Nestedset;

class CreateCategoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            //$table->integer('_lft')->index();
            //$table->integer('_rgt')->index();
            //$table->integer('parent_id')->index()->unsigned()->nullable();
            $table->string('slug');
            $table->integer('level');
            $table->string('path');
            $table->tinyInteger('is_link');
            $table->string('type')->index();
            $table->integer('hits');
            NestedSet::columns($table); // Performs all actions at the commented lines
            $table->timestamps();
        });

        NestedSet::createRoot('categories', array(
            'title' => 'root',
        ));

        // Schema::table('categories', function($table) {
        //  $table->foreign('parent_id')->references('id')->on('categories');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }

}
