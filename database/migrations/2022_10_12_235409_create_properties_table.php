<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->integer('investment_id');
            $table->integer('building_id')->nullable();
            $table->integer('floor_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('name');
            $table->string('name_list');
            $table->string('number');
            $table->integer('number_order');
            $table->integer('rooms');
            $table->string('area');
            $table->string('price')->nullable();
            $table->string('garden_area')->nullable();
            $table->string('balcony_area')->nullable();
            $table->string('balcony_area_2')->nullable();
            $table->string('terrace_area')->nullable();
            $table->string('loggia_area')->nullable();
            $table->string('parking_space')->nullable();
            $table->string('garage')->nullable();
            $table->integer('type')->default(0);
            $table->text('html')->nullable();
            $table->text('cords')->nullable();
            $table->string('file')->nullable();
            $table->string('file_webp')->nullable();
            $table->string('file_pdf')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->integer('views')->default(0);
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
        Schema::dropIfExists('properties');
    }
}
