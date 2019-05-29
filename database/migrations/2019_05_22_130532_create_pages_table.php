<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unique_name')->default('unique_name');
//            $table->unsignedBigInteger("parent_id")->default(0);
            $table->unsignedBigInteger("topic_id");
            $table->unsignedInteger("position");
            $table->string("title");
            $table->json("content")->nullable();
            $table->timestamps();

//            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('cascade');
            $table->nestedSet();
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
