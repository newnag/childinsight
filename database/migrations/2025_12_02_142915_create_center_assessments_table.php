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
        Schema::create('center_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_id')->constrained('centers')->onDelete('cascade');
            $table->foreignId('assessor_id')->constrained('users')->onDelete('cascade');
            $table->date('assessment_date');
            $table->integer('total_score')->default(0);
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('center_assessments');
    }
};
