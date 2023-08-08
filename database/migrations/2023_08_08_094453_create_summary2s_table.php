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
        Schema::create('summary2s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ship_id')
                        ->constrained()
                        ->onUpdate('cascade')
                        ->onDelete('cascade');
            $table->string('insurance_type')->nullable();
            $table->string('international_ton')->nullable();
            $table->string('passenger_capacity')->nullable();
            $table->float('speed50')->nullable();
            $table->float('speed100')->nullable();
            $table->float('rpm_peller50')->nullable();
            $table->float('rpm_peller100')->nullable();
            $table->float('slip_rate50')->nullable();
            $table->float('slip_rate100')->nullable();
            $table->float('tug_force50')->nullable();
            $table->float('tug_force100')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary2s');
    }
};
