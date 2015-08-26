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
            $table->index(array('_lft', '_rgt', 'parent_id'));
        });

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
            $table->dropForeign('categories_type_foreign');
        });

        Schema::table('categories', function($table) {
            $table->dropIndex('categories__lft__rgt_parent_id_index');
        });

        Schema::table('content_rating', function($table) {
            $table->dropForeign('content_rating_content_id_foreign');
        });

        Schema::table('content', function($table) {
            $table->dropForeign('content_catid_foreign');
        });
        Schema::table('videos', function($table) {
            $table->dropForeign('videos_content_id_foreign');
        });
    }

}
