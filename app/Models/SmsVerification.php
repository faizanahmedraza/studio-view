<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsVerification extends Model
{
    use SoftDeletes;

    public $table = 'sms_verifications';
    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

}
