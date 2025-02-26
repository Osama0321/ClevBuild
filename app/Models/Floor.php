<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IsActive;

class Floor extends Model
{
    use HasFactory, SoftDeletes, IsActive;

    protected $primaryKey = 'floor_id';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function project(){
        return $this->belongsTo(Projects::class,'project_id','project_id');
    }
    
    public function category(){
        return $this->belongsTo(Category::class,'category_id','category_id');
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function status(){
        return $this->hasOne(Statuses::class,'status_id','floor_status_id')->select('status_id','status_name','status_type','color');
    }

    public function tasks(){
        return $this->hasMany(Task::class,'floor_id','floor_id');
    }
	
    public function member(){
        return $this->hasOne(User::class, 'id', 'member_id');
    }
	
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}