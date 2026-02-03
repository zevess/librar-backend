<?php

use App\Enums\ReservationStatus;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->nullable()->constrained();
            $table->enum('status', array_column(ReservationStatus::cases(), 'value'));
            $table->foreignId('reserved_by')->nullable()->constrained('users');
            $table->dateTime('reserved_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('issued_at')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
