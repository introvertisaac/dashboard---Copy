<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('balance_alerts')->default(true);
            $table->integer('balance_threshold')->default(5000);
            $table->string('alert_frequency')->default('weekly');
            $table->time('preferred_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_notification_settings');
    }
};