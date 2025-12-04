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
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('recorded_at');
            $table->decimal('weight', 5, 2);
            $table->decimal('height', 5, 2);
            $table->decimal('bmi', 5, 2)->nullable();
            $table->enum('nutrition_status', ['thin', 'normal', 'obese'])->nullable();
            $table->text('illness')->nullable();
            $table->text('health_constraints')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
