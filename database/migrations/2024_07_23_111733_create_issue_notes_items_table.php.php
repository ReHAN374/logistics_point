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
        Schema::create('issue_note_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('issue_note_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('stock_no');
            $table->string('description');
            $table->string('unit_of_measure');
            $table->integer('order_qty');
            $table->integer('issued_qty');
            $table->integer('balance_qty');
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
        Schema::dropIfExists('issue_note_items');
    }
};
