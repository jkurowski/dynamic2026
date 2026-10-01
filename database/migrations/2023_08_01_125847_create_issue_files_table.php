<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('issue_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Assuming the 'user_id' will reference the 'id' column of the 'users' table
            $table->unsignedBigInteger('issue_id');
            $table->integer('type');
            $table->string('name', 191);
            $table->string('description', 191);
            $table->string('file', 191);
            $table->string('extension', 191);
            $table->string('mime', 191);
            $table->string('size', 191);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('issue_id')->references('id')->on('issues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_files');
    }
};
