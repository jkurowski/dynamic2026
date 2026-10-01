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
        Schema::create('investment_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_id');
            $table->unsignedBigInteger('template_send_thanks')->nullable();
            $table->unsignedBigInteger('template_offer')->nullable();
            $table->unsignedBigInteger('template_offer_mail')->nullable();
            $table->unsignedBigInteger('template_offer_reminder')->nullable();
            $table->unsignedBigInteger('template_open_day')->nullable();
            $table->unsignedBigInteger('template_preliminary_agreement')->nullable();
            $table->unsignedBigInteger('template_local_review')->nullable();
            $table->unsignedBigInteger('template_local_pickup')->nullable();
            $table->unsignedBigInteger('template_transfer_of_ownership')->nullable();
            $table->unsignedBigInteger('template_tenant_changes')->nullable();
            $table->unsignedBigInteger('template_documents_for_credit')->nullable();
            $table->unsignedBigInteger('template_invoices')->nullable();
            $table->unsignedBigInteger('template_documents')->nullable();
            $table->unsignedBigInteger('template_special_offers')->nullable();
            $table->unsignedBigInteger('template_meeting_invitation')->nullable();
            $table->unsignedBigInteger('template_meeting_reminder')->nullable();
            $table->unsignedBigInteger('template_offer_expiration')->nullable();
            $table->timestamps();

            $table->foreign('investment_id')->references('id')->on('investments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_templates');
    }
};
