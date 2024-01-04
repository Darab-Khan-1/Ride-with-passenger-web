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
            $table->timestamp('pickup_date');
            $table->string('pickup_location');
            $table->timestamp('delivery_date');
            $table->string('delivery_location');
            $table->string('estimated_distance');
            $table->string('estimated_time');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('status')->default('available');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('lat');
            $table->string('long');
            $table->string('drop_lat');
            $table->string('drop_long');
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
