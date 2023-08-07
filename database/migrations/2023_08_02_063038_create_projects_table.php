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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ship_id')->nullable()->constrained();
            $table->string('name');
            $table->foreignId('pro_category_id')->constrained();
            $table->text('description')->nullable();
            $table->date('legal_start')->nullable();
            $table->date('legal_dline')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('completion')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
