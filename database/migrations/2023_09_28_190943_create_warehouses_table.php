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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            //Custom Fields
            $table->integer('PIVid');
            $table->integer('requesterid');
            $table->string('PIVname');
            $table->string('employee');
            $table->integer('PIVtype');
            $table->integer('quantity')->default(1);
            $table->integer('tempered')->default(0);;
            $table->integer('returned')->default(0);
            $table->boolean('delflag')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
