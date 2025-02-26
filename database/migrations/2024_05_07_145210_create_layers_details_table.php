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
        Schema::create('layers_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('layer_id')->nullable();
            $table->json('geometry')->nullable();
            $table->json('properties')->nullable();
            $table->longText('text')->nullable();
            $table->integer('is_delete')->default(0);
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layers_details');
    }
};
