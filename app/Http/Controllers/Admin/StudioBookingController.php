<?php

namespace App\Http\Controllers\Admin;


use DateTime;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\StudioBooking;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;



class StudioBookingController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();

    }

    /**
     * get app verified user list
     */
    public function getDetail($id)
    {
        $studioBooking=StudioBooking::find(base64_decode($id));
        return view('admin.studio.booking_details',compact('studioBooking'));
        // dd( $studioBooking);
    }





}
