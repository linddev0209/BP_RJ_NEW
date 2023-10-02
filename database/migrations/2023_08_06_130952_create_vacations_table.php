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
        Schema::create('vacations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            // custom
            $table->date('start_date')->format('d-m-y'); // Specify the date format here
            $table->date('end_date')->format('d-m-y');   // Specify the date format here
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('reason');
            $table->boolean('delflag')->default(false);
            $table->string('request_type')->nullable();
            $table->enum('status', ['Pending', 'Accepted', 'Rejected', 'Require Action'])->default('Pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->text('manager_comment')->nullable();

            // Foreign key constraint for user_id
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();


            $table->foreign('manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacations');
    }
};
