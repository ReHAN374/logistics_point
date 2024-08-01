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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('sales_user');
            $table->string('invoice_no')->unique();
            $table->timestamp('invoice_date');
            $table->string('customer_name');
            $table->string('customer_address');
            $table->string('vat_no')->nullable();
            $table->double('vat_amount')->default(0);
            $table->double('grand_total')->default(0);
            $table->timestamp('printed_at')->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
