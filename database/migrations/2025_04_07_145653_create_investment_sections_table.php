<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('investment_sections', function (Blueprint $table) {
            $table->id(); // id - int (auto-increment)
            $table->unsignedBigInteger('investment_id');
            $table->string('fields', 190);
            $table->string('title', 190);
            $table->string('subtitle', 190);
            $table->text('content');
            $table->boolean('content_editor')->default(false);
            $table->text('code')->nullable();
            $table->string('file', 190)->nullable();
            $table->string('file_alt', 190)->nullable();
            $table->boolean('lock')->default(false);
            $table->integer('position')->default(0);
            $table->timestamps();

            // Optional FK
            // $table->foreign('investment_id')->references('id')->on('investments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('investment_sections');
    }
};
