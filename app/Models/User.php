<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IsActive;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRolesAndAbilities, IsActive;

    protected $table = 'users';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'phone_no',
        'country',
        'city',
        'parent_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'user_type',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_city()
    {
        return $this->hasOne(City::class,'city_id', 'city');
    }

    public function user_country()
    {
        return $this->hasOne(Country::class,'country_id','country');
    }

    public static function GetDataForYajra($user_type='', $column='', $value=''){
        return self::where('user_type', $user_type)
        ->orderBy($column, $value);
    }

    public function getAllUser($user_type='', $column='', $value='')
    {
        return user::where('user_type', $user_type)->where('is_active', 1)->where('is_delete', 0)
                ->orderBy($column, $value)->get()->toArray();
    }

    public function getAuthenticatedUser()
    {
        return auth()->user();
    }

    public function getCreatedUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    public function getupdatedUser()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    
    public function getCompany()
    {
        return $this->belongsTo(User::class, 'parent_id', 'id')->where('user_type',6);
    }
   
    public function getManager()
    {
        return $this->belongsTo(User::class, 'parent_id', 'id')->where('user_type',2);
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'parent_id', 'id')->where('user_type',6);
    }
   
    public function manager()
    {
        return $this->belongsTo(User::class, 'parent_id', 'id')->where('user_type',2);
    }
   
    public function managers()
    {
        return $this->hasMany(User::class, 'parent_id', 'id')->where('user_type',2)->active();
    }
}