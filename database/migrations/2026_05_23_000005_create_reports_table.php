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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('counselor_id')->nullable();
            $table->text('summary');
            $table->text('counseling_result');
            $table->text('recommendation');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('request_id')->references('request_id')->on('counseling_requests')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('counselor_id')->references('user_id')->on('users')->nullOnDelete()->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
