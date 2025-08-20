<?php

use App\Models\User;
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
        Schema::create('cash_closings', function (Blueprint $table) {
            $table->id();
            $table->datetime('period_start');
            $table->datetime('period_end')->nullable();
            $table->unsignedInteger('total_tickets');
            $table->decimal('total_amount', 10, 2);
            $table->json('gaps');
            $table->string('first');
            $table->string('last');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_closings');
    }
};
