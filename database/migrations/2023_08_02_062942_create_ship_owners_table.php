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
        Schema::create('ship_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ship_id')->constrained()->onDelete('cascade');
            $table->string('owner_name');
            $table->float('ratio');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship_owners');
    }
};
