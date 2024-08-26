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
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('initiating_customer_id');
            $table->unsignedBigInteger('proxy_customer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->string('class'); // R, E -- Revenue, Expense
            $table->string('channel')->nullable();
            $table->unsignedBigInteger('search_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('batch')->nullable();
            $table->decimal('buying_price', 15, 4)->nullable();
            $table->decimal('selling_price', 15, 4)->nullable();
            $table->decimal('margin', 15, 4)->nullable();
            $table->timestamp('dated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
