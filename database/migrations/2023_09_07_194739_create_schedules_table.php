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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ship_id')->constrained();
            $table->foreignId('pro_category_id')->nullable()->constrained();
            $table->integer('category_no')->nullable(); //カテゴリーに対して何回目か
            $table->string('yard')->nullable();
            $table->text('memo')->nullable();
            $table->foreignId('project_id')->nullable()->constrained();
            $table->date('legal_start')->nullable();
            $table->date('legal_dline')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
