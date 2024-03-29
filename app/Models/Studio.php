<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Interfaces\StudioInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Studio extends Model implements StudioInterface
{
    use SoftDeletes;
    public $table = 'studios';
    public const HOURS_STATUS = [
        1 => "Always available, 24/7",
        2 => "Message for availability",
        3 => "Daily from"
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
        return $this->belongsToMany(Type::class, 'studio_types')->orderBy('id');
    }

    public function getStudioTypes()
    {
        return $this->hasMany(StudioType::class, 'studio_id', 'id');
    }

    public function getImages()
    {
        return $this->hasMany(StudioImage::class, 'studio_id', 'id')->orderBy('id');
    }

    public function savedStudios()
    {
        return $this->hasMany(CustomerFavourite::class, 'studio_id', 'id')->orderBy('id');
    }

    public function deleteStudio()
    {
        $this->getLocation()->delete();
        $this->getPrice()->delete();
        $this->getStudioTypes()->delete();
        $this->getImages()->delete();
        $this->delete();
    }

    public function deleteTypes()
    {
        $this->getStudioTypes()->delete();
    }

    public function deleteImages()
    {
        $this->getImages()->delete();
    }

    public function isSaved()
    {
        return (bool)$this->savedStudios()->where('user_id', auth()->user()->id)->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function paidPromotion()
    {
        return $this->hasOne(StudioPaidPromotion::class, 'studio_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(StudioBooking::class,'studio_id','id');
    }

    public function avgRatings()
    {
        return $this->bookings()->where('ratings','!=',0)->avg('ratings');
    }
}
