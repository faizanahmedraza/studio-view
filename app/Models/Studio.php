<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Interfaces\StudioInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Studio extends Model  implements StudioInterface
{
    use SoftDeletes;
    public $table = 'studios';
    public const HOURS_STATUS=[
        1=>"Always available, 24/7",
        2=>"Message for availability",
        3=>"Daily from"
    ];
    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function getLocation()
    {
        return $this->hasOne(StudioLocation::class, 'studio_id', 'id');
    }
    public function getPrice()
    {
        return $this->hasOne(StudioPrice::class, 'studio_id', 'id');
    }
    public function getTypes()
    {
        return $this->belongsToMany(StudioType::class,'studio_types', 'studio_id', 'id');
    }
    public function getImages()
    {
        return $this->hasMany(StudioImage::class, 'studio_id', 'id');
    }
}
