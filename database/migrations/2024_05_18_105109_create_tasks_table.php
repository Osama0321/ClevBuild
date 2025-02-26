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
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('task_id');
            $table->string('task_name')->index()->nullable();
            $table->string('layer_name')->nullable();
            $table->string('color_name')->nullable();
            $table->string('line_type')->nullable();
            $table->string('line_weight')->nullable();
            $table->string('scale_x')->nullable();
            $table->string('scale_y')->nullable();
            $table->string('rotation')->nullable();
            $table->json('insertion_point')->nullable();
            $table->json('attributes')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('project_id')->on('projects')->nullable();
            $table->integer('member_id')->nullable();
            $table->integer('priority_id')->default(0);
            $table->integer('project_status_id')->default(0);
            $table->text('description')->nullable();
            $table->text('plan')->nullable();
            $table->text('images')->nullable();
            $table->integer('is_delete')->default(0);
            $table->integer('is_active')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
