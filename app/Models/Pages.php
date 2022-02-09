<?php

namespace App\Models;

use App\Models\Interfaces\PagesInterface;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model implements PagesInterface
{

    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'page_title', 'slug', 'content', 'meta_title', 'meta_description',
        'is_active', 'created_at', 'updated_at'
    ];
}
