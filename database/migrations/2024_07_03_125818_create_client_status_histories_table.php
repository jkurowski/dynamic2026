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
        Schema::create('client_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('old_status');
            $table->string('new_status');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('changed_at')->default(now());

            // Klucz obcy do tabeli clients
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            // Klucz obcy do tabeli users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_status_histories');
    }
};
