<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Interfaces\StudioPriceInterface;

class StudioPrice extends Model implements StudioPriceInterface
{
    use SoftDeletes;

    public $table = 'studio_prices';
    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

}
