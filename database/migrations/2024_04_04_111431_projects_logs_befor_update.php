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
        DB::unprepared("
        CREATE TRIGGER `projects_logs_before_update` BEFORE UPDATE ON `projects` 
        FOR EACH ROW BEGIN
        INSERT INTO projects_logs (project_id, project_name, category_id, address, country_id, city_id, member_id, follower_id, is_delete, is_active, created_by, updated_by, deleted_at, created_at, updated_at, action_type, action_date)
        VALUES (OLD.project_id, OLD.project_name, OLD.category_id, OLD.address, OLD.country_id, OLD.city_id, OLD.member_id, OLD.follower_id, OLD.is_delete, OLD.is_active, OLD.created_by, OLD.updated_by, OLD.deleted_at, OLD.created_at, OLD.updated_at, 'UPDATE', NOW());
        END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS projects_logs_before_update');
    }
};
