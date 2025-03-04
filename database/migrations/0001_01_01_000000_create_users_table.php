<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->unique();
            $table->string('phone')->nullable();
            $table->string('type')->default('api');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            $table->jsonb('meta')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->rememberToken();
            $table->string('api_secret')->nullable();
            $table->timestamps();
            $table->timestamp('last_active_at')->nullable();
            $table->string('status')->default('active');
            $table->string('login_otp_token')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('uuid')->nullable();
            $table->boolean('is_super_admin')->default(false);
            $table->softDeletes();

        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
