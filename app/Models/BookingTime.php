<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingTime extends Model
{
    use SoftDeletes;
    public $table = 'adv_booking_times';
}
