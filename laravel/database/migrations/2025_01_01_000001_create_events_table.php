<?php
// database/migrations/2025_01_01_000001_create_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Gebruik unsignedBigInteger

            $table->string('title');
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('category')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('available_tickets')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_online')->default(false);
            $table->string('online_link')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Laat de foreign key WEG - voeg die later toe
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
