<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organiser_id')->constrained('users');
            $table->string('title');
            $table->text('notes');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->string('timezone', 25);
            $table->geometry('location', subtype: 'point')->nullable();
            $table->unsignedInteger('confirmed_participant_count');
            $table->timestamps();
        });

        Schema::create('attendance', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('event_id')->constrained('events');
            $table->timestamp('confirmed_at')->nullable();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('event_id')->constrained('events');
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('attendance');
    }
};
