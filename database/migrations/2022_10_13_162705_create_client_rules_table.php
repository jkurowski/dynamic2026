<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('duration');
            $table->tinyInteger('months');
            $table->string('ip', 15)->nullable();
            $table->string('source', 255)->nullable();
            $table->text('text');
            $table->boolean('status');
            $table->timestamp('canceled_at')->nullable();
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
        Schema::dropIfExists('client_rules');
    }
}
