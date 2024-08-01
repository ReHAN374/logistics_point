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
        Schema::create('issue_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('issue_note_no');
            $table->string('customer_name');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('issued_by');
            $table->integer('is_active')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign key constraints (optional)
            // $table->foreign('invoice_id')->references('id')->on('invoices');
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('issued_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_notes');
    }
};
