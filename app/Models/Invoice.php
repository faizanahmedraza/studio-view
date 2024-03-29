<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Interfaces\InvoiceInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model implements InvoiceInterface
{
    use SoftDeletes;
    public $table = 'invoices';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    // public function getLocation()
    // {
    //     return $this->hasOne(StudioLocation::class, 'studio_id', 'id');
    // }
    // public function getPrice()
    // {
    //     return $this->hasOne(StudioPrice::class, 'studio_id', 'id');
    // }
    // public function getTypes()
    // {
    //     return $this->belongsToMany(Type::class,'studio_types')->orderBy('id');
    // }
    // public function getStudioTypes()
    // {
    //     return $this->hasMany(StudioType::class,'studio_id', 'id');
    // }
    // public function getImages()
    // {
    //     return $this->hasMany(StudioImage::class, 'studio_id', 'id')->orderBy('id');
    // }
    // public function savedStudios()
    // {
    //     return $this->hasMany(CustomerFavourite::class, 'studio_id', 'id')->orderBy('id');
    // }
    // public function deleteStudio()
    // {
    //     $this->getLocation()->delete();
    //     $this->getPrice()->delete();
    //     $this->getStudioTypes()->delete();
    //     $this->getImages()->delete();
    //     $this->delete();
    // }
    // public function deleteTypes()
    // {
    //     $this->getStudioTypes()->delete();
    // }
    // public function deleteImages()
    // {
    //     $this->getImages()->delete();
    // }
    // public function isSaved()
    // {
    //     return (bool)$this->savedStudios()->where('user_id',auth()->user()->id)->exists();
    // }
    public function studioOwner()
    {
        return $this->belongsTo(User::class,'studio_owner_id','id');
    }
    public function requestUser()
    {
        return $this->belongsTo(User::class,'requested_user_id','id');
    }
    public function studioBooking()
    {
        return $this->belongsTo(StudioBooking::class,'studio_booking_id','id');
    }
}
