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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Custom Fields
            $table->string('name');
            $table->string('barcode')->nullable();
            $table->string('comments')->nullable();
            $table->integer('amount');
            $table->integer('usedamount')->default(0);
            $table->timestamp('doi')->nullable();
            $table->timestamp('dom')->nullable();
            $table->string('mne');
            $table->string('mnm');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
