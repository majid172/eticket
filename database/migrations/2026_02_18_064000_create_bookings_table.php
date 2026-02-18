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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();

            // Relations
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('schedule_bus_id')
                  ->constrained('schedule_buses')
                  ->cascadeOnDelete();

            // Amounts
            $table->decimal('total_amount', 10, 2);

            // Status
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled'])
                  ->default('pending');

            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])
                  ->default('unpaid');

            // Extra info
            $table->timestamp('booked_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
