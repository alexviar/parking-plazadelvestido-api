<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_configs', function (Blueprint $table) {
            $table->id();
            $table->string('last_scanned_code');
            $table->timestamps();
        });

        DB::table('ticket_configs')->insert([
            'last_scanned_code' => ''
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_configs');
    }
};
