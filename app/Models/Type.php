<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $table = 'std_types';

    public function getStudios()
    {
        return $this->belongsToMany(Studio::class,'studio_types');
    }
}
