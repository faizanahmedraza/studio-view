<?php

namespace App\Http\Controllers\Api;

use DB;
use stdClass;
use Validator;

use App\Classes\RestAPI;
use Illuminate\Http\Request;
use App\Models\BookingTime;

class BookingTimeController extends ApiBaseController
{
    public function index()
    {
        $response=BookingTime::select('id', 'name')->where('status',1)->orderBy('id')->get();
        return RestAPI::response( $response, true, 'Advnace Booking Time List');

    }
}
