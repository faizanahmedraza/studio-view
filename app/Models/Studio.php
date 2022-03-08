<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Interfaces\StudioInterface;

class Studio extends Model  implements StudioInterface
{
    public $table = 'studios';
    public const HOURS_STATUS=[
        1=>"Always available, 24/7",
        2=>"Message for availability",
        3=>"Daily from"
    ];
}
