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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Custom Fields
            $table->string('name');
            $table->string('targa')->nullable();
            $table->string('comments')->nullable();
            $table->timestamp('doi')->nullable();
            $table->timestamp('dom')->nullable();
            $table->boolean('used')->default(false);
            $table->string('username');
            $table->string('mne');
            $table->string('mnm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
