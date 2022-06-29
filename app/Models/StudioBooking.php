<?php

namespace App\Models;

use App\Models\Interfaces\StudioBookingInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudioBooking extends Model implements StudioBookingInterface
{
    use SoftDeletes;

    public const STATUS= ['pending' => 0 ,'approve' => 1  ,'reject' => 2,'cancel' => 3];

    public $table = 'studio_bookings';
    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studio_id');
    }

}

