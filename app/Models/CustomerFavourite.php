<?php

namespace App\Models;

use App\Models\Interfaces\CustomerFavouriteInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFavourite extends Model implements CustomerFavouriteInterface
{
    use SoftDeletes;

    protected $table = "customer_favourites";

    protected $guarded = [];

    public function user()
    {
        $this->belongsTo(User::class,'user_id','id');
    }

    public function studio()
    {
        $this->belongsTo(Studio::class,'studio_id','id');
    }
}
