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
        Schema::create('projects_logs', function (Blueprint $table) {
            $table->bigInteger('project_id');
            $table->string('project_name', 255)->index()->nullable();
            $table->integer('category_id')->nullable();
            $table->string('address', 255)->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('member_id')->nullable();
            $table->integer('follower_id')->nullable();
            $table->integer('is_delete')->default(0);
            $table->integer('is_active')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('action_type', 255)->nullable();
            $table->dateTime('action_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_logs');
    }
};
