<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use SoftDeletes;
    public $table = 'std_types';

    public function getStudios()
    {
        return $this->belongsToMany(Studio::class,'studio_types');
    }
}
