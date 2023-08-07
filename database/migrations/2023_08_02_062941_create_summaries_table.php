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
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ship_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('official_number')->nullable();
            $table->string('signal_code')->nullable();
            $table->string('engine_kw')->nullable();
            $table->string('pera_spec')->nullable();
            $table->string('me_model')->nullable();
            $table->string('me_sno')->nullable();
            $table->string('pera_sno')->nullable();
            $table->string('fire_equipt')->nullable();
            $table->float('full_length')->nullable();
            $table->float('lpp')->nullable();
            $table->float('breadth')->nullable();
            $table->float('beam_depth')->nullable();
            $table->float('mold_draft')->nullable();
            $table->string('draft_mark_F')->nullable();
            $table->string('draft_mark_A')->nullable();
            $table->string('fm_bl')->nullable();
            $table->string('draft_m')->nullable();
            $table->string('layer_2or3')->nullable();
            $table->text('winch_tension')->nullable();
            $table->text('stern_towboat')->nullable();
            $table->string('intake')->nullable();
            $table->string('exhaust')->nullable();
            $table->string('aux_engine')->nullable();
            $table->string('dk_machine_pp')->nullable();
            $table->string('fire_pump')->nullable();
            $table->string('harbor_gen')->nullable();
            $table->string('fire_extinguish')->nullable();
            $table->timestamps();
           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};
