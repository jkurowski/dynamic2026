<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRodoRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rodo_rules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('text');
            $table->boolean('required')->default(0)->unsigned();
            $table->integer('time');
            $table->boolean('active')->default(1)->unsigned();
            $table->integer('sort')->default(0)->unsigned();
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
        Schema::dropIfExists('rodo_rules');
    }
}
