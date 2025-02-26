<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layer extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'geometry', 'color', 'fill', 'fillColor', 'fillOpacity', 'layerType', 'opacity', 'style', 'width'
    ];
}
