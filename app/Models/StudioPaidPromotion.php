<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudioPaidPromotion extends Model
{
    use SoftDeletes;

    protected $table = "studio_paid_promotions";

    protected $guarded = [];
}
