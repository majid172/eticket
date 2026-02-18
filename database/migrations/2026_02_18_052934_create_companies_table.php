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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            // Operator info
            $table->string('company_name');
            $table->string('trade_license')->nullable();
            $table->string('contact_phone', 20);
            $table->string('contact_email')->nullable();
            $table->text('address')->nullable();

            // Status control by admin
            $table->enum('status', ['pending', 'approved', 'blocked'])
                  ->default('pending');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
