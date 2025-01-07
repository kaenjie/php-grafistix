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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('full_name');
            $table->string('email');
            $table->string('address');
            $table->string('city');
            $table->string('payment_method')->default('ovo');
            $table->boolean('has_paid')->default(false); // Boolean field
            $table->date('order_date');
            $table->unsignedBigInteger('id_paket'); // Foreign key to packages table
            $table->timestamps();

            // Set foreign key
            $table->foreign('id_paket')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
