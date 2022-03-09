<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Interfaces\StudioLocationInterface;

class StudioLocation extends Model implements StudioLocationInterface
{
    use SoftDeletes;

    public $table = 'studio_locations';
    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

}
