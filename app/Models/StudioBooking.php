<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Interfaces\StudioTypeInterface;

class StudioType extends Model implements StudioTypeInterface
{
    use SoftDeletes;

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

