<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Interfaces\StudioImageInterface;

class StudioImage extends Model implements StudioImageInterface
{
    use SoftDeletes;

    public $table = 'studio_images';
    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

}
