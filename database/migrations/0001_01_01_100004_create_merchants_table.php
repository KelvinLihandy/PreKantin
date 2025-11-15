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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id('merchant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('image')->nullable();
            $table->time('open')->nullable();
            $table->time('close')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }

    protected function casts(): array
    {
        return [
            'open' => 'datetime:H:i',
            'close' => 'datetime:H:i',
        ];
    }
};
