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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('status_id');
            $table->timestamp('order_time');
            $table->string('invoice_number')->unique();
            $table->integer('gross_amount');
            $table->string('midtrans_status');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete();
            $table->foreign('merchant_id')
                ->references('merchant_id')
                ->on('merchants')
                ->restrictOnDelete();
            $table->foreign('status_id')
                ->references('status_id')
                ->on('statuses')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['merchant_id']);
            $table->dropForeign(['status_id']);
        });
        Schema::dropIfExists('orders');
    }
};
