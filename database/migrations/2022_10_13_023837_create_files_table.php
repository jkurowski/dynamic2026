<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0)->nullable();
            $table->integer('user_id')->default(0);
            $table->integer('type')->default(0);
            $table->integer('_lft')->unsigned();
            $table->integer('_rgt')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('file')->nullable();
            $table->string('extension')->nullable();
            $table->string('mime')->nullable();
            $table->string('size')->nullable();
            $table->integer('download')->default(0);
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
        Schema::dropIfExists('files');
    }
}
