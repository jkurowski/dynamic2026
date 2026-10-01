<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->integer('investment_id');
            $table->integer('building_id')->nullable();
            $table->string('name');
            $table->string('number');
            $table->integer('position');
            $table->integer('type');
            $table->string('area_range')->nullable();
            $table->string('price_range')->nullable();
            $table->text('html')->nullable();
            $table->text('cords')->nullable();
            $table->string('file')->nullable();
            $table->string('file_webp')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_robots')->nullable();
            $table->boolean('active');
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
        Schema::dropIfExists('floors');
    }
}
