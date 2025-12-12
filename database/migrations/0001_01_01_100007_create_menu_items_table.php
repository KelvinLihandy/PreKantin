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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id('menu_item_id');
            $table->unsignedBigInteger('merchant_id');
            $table->string('image');
            $table->string('name');
            $table->integer('price');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->foreign('merchant_id')
                ->references('merchant_id')
                ->on('merchants')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function(Blueprint $table){
            $table->dropForeign(['merchant_id']);
        });
        Schema::dropIfExists('menu_items');
    }
};
