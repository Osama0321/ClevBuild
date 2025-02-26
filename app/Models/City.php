<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\IsActive;

class City extends Model
{
    use HasFactory, IsActive;
  

    protected $table = 'cities';
    protected $primaryKey = 'city_id';

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    
}