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
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->cascadeOnDelete();

            $table->string('bus_name')->nullable();
            $table->string('bus_number')->unique();
            $table->enum('bus_type', [
                'AC',
                'Non-AC',
                'Sleeper',
                'Seater'
            ]);

            $table->unsignedInteger('total_seats');

            $table->enum('status', [
                'active',
                'inactive',
                'maintenance'
            ])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
