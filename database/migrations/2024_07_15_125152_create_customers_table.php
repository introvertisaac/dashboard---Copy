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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('primary_email')->nullable();
            $table->jsonb('meta')->nullable();
            $table->jsonb('charges')->nullable();
            $table->string('status')->default('active');
            $table->string('uuid')->unique();
            $table->unsignedTinyInteger('api_count')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('parent_customer_id')->nullable();
            $table->boolean('is_reseller')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
