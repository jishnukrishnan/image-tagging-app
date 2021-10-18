<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('additional_field')->nullable(true);
            $table->string('additional_value')->nullable(true);
            $table->integer('x_1')->unsigned();
            $table->integer('y_1')->unsigned();
            $table->integer('x_2')->unsigned();
            $table->integer('y_2')->unsigned();
            $table->integer('x_3')->unsigned();
            $table->integer('y_3')->unsigned();
            $table->integer('x_4')->unsigned();
            $table->integer('y_4')->unsigned();
            $table->integer('image_id')->unsigned();
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
        Schema::dropIfExists('tags');
    }
}
