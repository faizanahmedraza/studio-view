<?php

namespace App\Http\Controllers\Api;

use DB;
use stdClass;
use Validator;

use App\Classes\RestAPI;
use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends ApiBaseController
{
    public function index()
    {
        $response=Type::select('id', 'name')->where('status',1)->orderBy('id')->get();
        return RestAPI::response( $response, true, 'Types List');
    }
}
