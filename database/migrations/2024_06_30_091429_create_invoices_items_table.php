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
        Schema::create('invoices_items', function (Blueprint $table) {
            $table->id();
            $table->integer('warehouse_id');
            $table->integer('invoice_id');
            $table->string('product_name');
            $table->string('product_code');
            $table->double('qty')->default(0);
            $table->double('unit_price')->default(0);
            $table->double('value')->default(0);
            $table->double('sub_total')->default(0);
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
        Schema::dropIfExists('invoices_items');
    }
};
