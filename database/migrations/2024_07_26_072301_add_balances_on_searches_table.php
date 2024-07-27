<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table($this->table(), function (Blueprint $table) {
            $table->decimal('balance_after', 64, 0)->after('status')->nullable();

            $table->decimal('balance_before', 64, 0)->after('status')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table($this->table(), function (Blueprint $table) {
            $table->dropColumn(['balance_after', 'balance_before']);
        });
    }

    private function table(): string
    {
        return (new \App\Models\Search())->getTable();
    }

};
