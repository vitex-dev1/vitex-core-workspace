<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableArticleMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_meta', function (Blueprint $table) {
            $table->bigIncrements('meta_id')->unsigned();
            $table->bigInteger('post_id')->default(0)->unsigned();
            $table->string('meta_key');
            $table->longText('meta_value');
            $table->index('post_id');
            $table->index('meta_key');

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('post_meta');
    }
}
