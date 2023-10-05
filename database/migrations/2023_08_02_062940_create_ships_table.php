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
        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ex_name')->nullable();
            $table->string('former_name')->nullable();
            $table->string('yard')->nullable();
            $table->string('ship_no')->nullable();
            $table->date('delivered')->nullable();
            $table->date('issueInspCert')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('dock_term')->nullable();
            $table->integer('gross_tonn')->nullable();
            $table->foreignId('operat_section_id')->nullable()->constrained();
            $table->foreignId('navigation_area_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ships');
    }
};
