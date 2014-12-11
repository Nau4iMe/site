<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelationships extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function($table) {
            $table->foreign('type')->references('type')->on('category_types')->onDelete('cascade')->onUpdate('restrict');
        });

        Schema::table('content_rating', function($table) {
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade')->onUpdate('restrict');
        });

        Schema::table('content', function($table) {
            $table->foreign('catid')->references('id')->on('categories')->onDelete('cascade')->onUpdate('restrict');
        });

        Schema::table('videos', function($table) {
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
        Schema::table('categories', function($table) {
            $table->dropIndex('categories_type_foreign');
        });

        Schema::table('content_rating', function($table) {
            $table->dropIndex('content_rating_content_id_foreign');
        });

        Schema::table('content', function($table) {
            $table->dropIndex('content_catid_foreign');
        });

        Schema::table('videos', function($table) {
            $table->dropIndex('videos_content_id_foreign');
        });

    }

}
