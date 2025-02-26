<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskImages extends Model
{
    use HasFactory;
    protected $table = 'tasks_images';
    public function tasks()
    {
        return $this->belongsTo(Task::class);
    }
}
