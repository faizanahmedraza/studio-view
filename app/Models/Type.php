<?php

namespace App\Models;

use App\Models\Interfaces\TypeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model implements TypeInterface
{
    use SoftDeletes;
    public $table = 'std_types';

    protected $guarded = [];

    public function getStudios()
    {
        return $this->belongsToMany(Studio::class,'studio_types');
    }
}
