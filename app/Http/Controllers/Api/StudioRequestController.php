<?php

namespace App\Http\Controllers\Api;

use DB;
use DateTime;
use Validator;

use App\Classes\RestAPI;
use Illuminate\Http\Request;
use App\Http\Requests\Api\RequestStudioRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Interfaces\StudioTypeRepositoryInterface;
use App\Repositories\Interfaces\StudioImageRepositoryInterface;
use App\Repositories\Interfaces\StudioPriceRepositoryInterface;
use App\Repositories\Interfaces\StudioLocationRepositoryInterface;



class StudioRequestController extends ApiBaseController
{
    private $userRepository;
    private $studioRepository;
    private $studioTypeRepository;
    private $studioLocationRepository;
    private $studioPriceRepository;
    private $studioImageRepository;

    public function __construct(UserRepositoryInterface $userRepository,StudioRepositoryInterface $studioRepository,
    StudioTypeRepositoryInterface $studioTypeRepository,StudioLocationRepositoryInterface $studioLocationRepository,
    StudioPriceRepositoryInterface $studioPriceRepository,StudioImageRepositoryInterface $studioImageRepository)
    {
        $this->userRepository = $userRepository;
        $this->studioRepository = $studioRepository;
        $this->studioTypeRepository = $studioTypeRepository;
        $this->studioLocationRepository = $studioLocationRepository;
        $this->studioPriceRepository = $studioPriceRepository;
        $this->studioImageRepository = $studioImageRepository;


    }
    public function request(RequestStudioRequest $request)
    {
        $time1 = new DateTime($request->date.' '.$request->start_time);
        $time2 = new DateTime($request->date.' '.$request->end_time);
        $timediff = $time1->diff($time2);
        // echo $timediff->format('%y year %m month %d days %h hour %i minute %s second');
        $hours= $timediff->format('%h');
        $studio=$this->studioRepository->find($request->studio_id);
        if($studio->minimum_booking_hr > $hours){
            return RestAPI::response('Total hours should be greater or equal to studio minimum hours.', false, 'validation_error');
        }
        DB::beginTransaction();
        try {
            $data=$request->all();
            print_r($data);

            // $response='';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        // return RestAPI::response( $response, true, 'Studio Created Successfully');

    }



}
