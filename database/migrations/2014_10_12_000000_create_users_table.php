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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_type')->default(0);
            $table->integer('warehouse_id');
            $table->string('customer_code');
            $table->string('customer_name');
            $table->string('customer_address')->nullable();
            $table->string('customer_phone_no');
            $table->string('customer_vat_no')->nullable();
            $table->string('customer_email');
            $table->string('password');
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
        Schema::dropIfExists('users');
    }
};
