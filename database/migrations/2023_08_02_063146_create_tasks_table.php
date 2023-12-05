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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()
            ->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()
            ->constrained('tasks')->onDelete('cascade');
            $table->string('name');
            $table->string('color_id')->nullable();
            $table->boolean('ganttchart')->default(false);
            $table->date('start_date')->nullable();;
            $table->date('end_date')->nullable();;
            $table->date('deadline')->nullable();
            $table->date('completion')->nullable();
            $table->integer('priority')->default(5);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
