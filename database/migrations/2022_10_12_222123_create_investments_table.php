<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('type');
            $table->smallInteger('status');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('date_start')->nullable();
            $table->string('date_end')->nullable();
            $table->string('areas_amount')->nullable();
            $table->string('area_range')->nullable();
            $table->string('office_address')->nullable();
            $table->string('entry_content')->nullable();
            $table->text('content')->nullable();
            $table->text('end_content')->nullable();
            $table->string('file_thumb')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_robots')->nullable();
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
        Schema::dropIfExists('investments');
    }
}
