<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toilet_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('nickname');
            $table->char('toilet', 1); // A, B, C
            $table->dateTime('slot_at'); // začátek 15min slotu
            $table->timestamps();

            $table->unique(['toilet', 'slot_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toilet_reservations');
    }
};
