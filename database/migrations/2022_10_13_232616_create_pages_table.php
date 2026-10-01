<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('parent_id');
            $table->string('title', 100)->unique();
            $table->string('slug', 200)->unique();
            $table->unsignedInteger('_lft');
            $table->unsignedInteger('_rgt');
            $table->string('uri')->nullable();
            $table->string('url')->nullable();
            $table->string('url_target', 10)->nullable();
            $table->text('content')->nullable();
            $table->string('content_header')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_robots', 20)->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('type')->default(1);
            $table->unsignedInteger('sort')->default(0);
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
        Schema::dropIfExists('pages');
    }
}
