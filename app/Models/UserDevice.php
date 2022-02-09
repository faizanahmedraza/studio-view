<?php

namespace App\Models;

use App\Models\Interfaces\UserDeviceInterface;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model implements UserDeviceInterface
{
    public $table = 'user_devices';

    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'device_token',
        'device_type',
        'auth_token',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be append to toArray.
     *
     * @var array
     */
    protected $appends = [];


    public function userDetail()
    {
        return $this->belongsTo(\App\Models\User::class);
    }


}
