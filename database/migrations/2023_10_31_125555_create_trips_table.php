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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('unique_id');
            $table->string('event_name');
            $table->timestamp('pickup_date');
            $table->string('pickup_location')->nullable();
            $table->timestamp('delivery_date');
            $table->string('delivery_location')->nullable();
            $table->string('estimated_distance')->nullable();
            $table->string('estimated_time')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('event_id')->nullable();
            $table->string('description')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('drop_lat')->nullable();
            $table->string('drop_long')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
