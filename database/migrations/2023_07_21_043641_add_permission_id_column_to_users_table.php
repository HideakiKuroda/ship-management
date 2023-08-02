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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned()->comment('権限ID')->after('address3');
            $table->foreignId('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            // $table->foreignId('purchase_id')->constrained()->onUpdate('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_permission_id_foreign');
        });
    }
};
