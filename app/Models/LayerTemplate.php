<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayerTemplate extends Model
{
    use HasFactory;

    protected $table = 'layer_templates';
    protected $primaryKey = 'template_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    public function getCreatedUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    public function getupdatedUser()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    
}
