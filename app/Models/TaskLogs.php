<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\IsActive;

class TaskLogs extends Model
{
    use HasFactory, IsActive;

    protected $table = 'task_logs';
    protected $primaryKey = 'task_log_id';
}