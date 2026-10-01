<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_payments', function (Blueprint $table) {
            $table->boolean('status')->default(false);
            $table->date('paid_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_payments', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('paid_at');
        });
    }
};
