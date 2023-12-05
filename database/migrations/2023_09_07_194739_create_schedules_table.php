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
            $table->foreignId('ship_id')
                        ->constrained()
                        ->onUpdate('cascade')
                        ->onDelete('cascade');
            $table->date('interim_start1')->nullable();
            $table->date('interim_dline1')->nullable();
            $table->date('Periodic_start1')->nullable();
            $table->date('Periodic_dline1')->nullable();
            $table->date('interim_start2')->nullable();
            $table->date('interim_dline2')->nullable();
            $table->date('Periodic_start2')->nullable();
            $table->date('Periodic_dline2')->nullable();
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
