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
        Schema::table('trips', function (Blueprint $table) {
            $table->integer('reminder_time')->default(15);
            $table->integer('old_user')->nullable();
            $table->timestamp('old_user_removed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('reminder_time');
            $table->dropColumn('old_user');
            $table->dropColumn('old_user_removed_at');
        });
    }
};
