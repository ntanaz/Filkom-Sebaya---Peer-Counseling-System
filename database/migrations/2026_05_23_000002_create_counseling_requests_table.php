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
        Schema::create('counseling_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedBigInteger('konseli_id')->nullable();
            $table->unsignedBigInteger('konselor_id')->nullable();
            $table->string('topic');
            $table->text('description');
            $table->enum('status', ['pending', 'accepted', 'rescheduled', 'cancelled', 'completed'])->default('pending');
            $table->enum('category', ['Akademik', 'Pribadi', 'Sosial', 'Keluarga', 'Kesehatan Mental', 'Karier', 'Lainnya']);
            $table->enum('case_level', ['Ringan', 'Sedang', 'Mendesak'])->nullable();
            $table->text('problem_description')->nullable();
            
            $table->timestamps();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Foreign Key Constraints
            $table->foreign('konseli_id')->references('user_id')->on('users')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('konselor_id')->references('user_id')->on('users')->nullOnDelete()->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counseling_requests');
    }
};
