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
        Schema::table('titles', function (Blueprint $table) {
            $table->string('poto_1')->nullable()->after('name'); 
            $table->string('poto_2')->nullable()->after('poto_1'); 
            $table->text('deskripsi')->nullable()->after('poto_2'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titles', function (Blueprint $table) {
            $table->dropColumn(['poto_1', 'poto_2', 'deskripsi']);
        });
    }
};
