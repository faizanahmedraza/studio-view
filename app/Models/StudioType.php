<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Interfaces\StudioTypeInterface;

class StudioType extends Model implements StudioTypeInterface
{
    use SoftDeletes;

    public $table = 'studio_types';
    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function getType()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    public function getStudio()
    {
        return $this->belongsTo(Studio::class, 'studio_id');
    }

}

