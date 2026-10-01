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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('model_type'); // Polymorphic relation
            $table->unsignedBigInteger('model_id'); // Polymorphic relation
            $table->text('text');
            $table->tinyInteger('pinned')->default(0); // 0 for not pinned, 1 for pinned
            $table->timestamps();

            // Indexes
            $table->index(['model_type', 'model_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
