<?php

use App\Enums\BookStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('reserved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->enum('status', array_column(BookStatus::cases(), 'value'))->default(BookStatus::AVAILABLE->value)->after('image');

            $table->foreignId('reserved_by')
                ->after('status')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }
};
