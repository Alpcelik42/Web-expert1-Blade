<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Controleer of kolommen bestaan voordat ze worden toegevoegd
            if (!Schema::hasColumn('events', 'short_description')) {
                $table->text('short_description')->nullable()->after('description');
            }

            if (!Schema::hasColumn('events', 'start_time')) {
                $table->time('start_time')->nullable()->after('start_date');
            }

            if (!Schema::hasColumn('events', 'end_time')) {
                $table->time('end_time')->nullable()->after('end_date');
            }

            if (!Schema::hasColumn('events', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('location');
            }

            if (!Schema::hasColumn('events', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }

            if (!Schema::hasColumn('events', 'category')) {
                $table->string('category')->nullable()->after('longitude');
            }

            if (!Schema::hasColumn('events', 'available_tickets')) {
                $table->integer('available_tickets')->nullable()->after('capacity');
            }

            if (!Schema::hasColumn('events', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }

            if (!Schema::hasColumn('events', 'is_online')) {
                $table->boolean('is_online')->default(false)->after('is_featured');
            }

            if (!Schema::hasColumn('events', 'online_link')) {
                $table->string('online_link')->nullable()->after('is_online');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'short_description',
                'start_time',
                'end_time',
                'latitude',
                'longitude',
                'category',
                'available_tickets',
                'is_featured',
                'is_online',
                'online_link'
            ]);
        });
    }
};
