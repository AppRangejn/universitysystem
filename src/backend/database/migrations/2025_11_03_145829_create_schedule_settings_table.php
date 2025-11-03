<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_settings', function (Blueprint $table) {
            $table->id();
            $table->json('days')->default(json_encode([
                'Понеділок',
                'Вівторок',
                'Середа',
                'Четвер',
                'П’ятниця',
                'Субота',
                'Неділя'
            ]));
            $table->unsignedTinyInteger('pair_count')->default(6);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_settings');
    }
};
