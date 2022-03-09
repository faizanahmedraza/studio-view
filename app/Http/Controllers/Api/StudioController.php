<?php

namespace App\Http\Controllers\Api;

use DB;
use stdClass;
use Validator;
use App\Models\Studio;

use App\Classes\RestAPI;
use Illuminate\Http\Request;
use App\Http\Resources\StudioResource;
use App\Http\Requests\Api\CreateStudioRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Interfaces\StudioTypeRepositoryInterface;
use App\Repositories\Interfaces\StudioImageRepositoryInterface;
use App\Repositories\Interfaces\StudioPriceRepositoryInterface;
use App\Repositories\Interfaces\StudioLocationRepositoryInterface;



class StudioController extends ApiBaseController
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

    public function store(CreateStudioRequest $request)
    {
        if (json_last_error() != JSON_ERROR_NONE) {
            return RestAPI::response("Json request is not valid / ".json_last_error_msg(), false, json_last_error());
        }

        $data=$request->all();
        DB::beginTransaction();
        try {
            $user = $this->userRepository->find(auth()->user()->id);

            $studioData=[
                'user_id'=>$user->id,
                'name'=>$data['name'] ?? null,
                'detail'=>$data['detail'] ?? null,
                'minimum_booking_hr'=>$data['minimum_booking_hr'] ?? null,
                'max_occupancy_people'=>$data['max_occupancy_people'] ?? null,
                'hours_status'=>$data['hours_status'] ?? null,
                'adv_booking_time_id'=>$data['adv_booking_time_id'] ?? null,
                'past_client'=>$data['past_client'] ?? null,
                'audio_sample'=>$data['audio_sample'] ?? null,
                'amenities'=>$data['amenities'] ?? null,
                'main_equipment'=>$data['main_equipment'] ?? null,
                'rules'=>$data['rules'] ?? null,
                'cancelation_policy'=>$data['cancelation_policy'] ?? null,
            ];
            if($data['hours_status'] == 3){
                $studioData['hrs_to']=$data['hrs_to'] ?? null;
                $studioData['hrs_from']=$data['hrs_from'] ?? null;
            }
            $studio=$this->studioRepository->create($studioData);
            $studioId=$studio->id;
            $this->studioTypeRepository->addTypes($data['types'],$studioId);
            $studioLocationData=[
                'address'=>$data['address'] ?? null,
                'lat'=>$data['lat'] ?? null,
                'lng'=>$data['lng'] ?? null,
                'additional_details'=>$data['additional_location_details'] ?? null,
                'studio_id'=>$studioId
            ];
            $this->studioLocationRepository->create($studioLocationData);
            $studioPriceData=[
                'hourly_rate'=>$data['hourly_rate'] ?? null,
                'audio_eng_included'=>$data['audio_eng_included'] ? true : false,
                'discount'=>$data['discount'] ?? null,
                'audio_eng_rate_hr'=>$data['additional_services']['audio_eng_rate_hr'] ?? null,
                'audio_eng_discount'=>$data['additional_services']['audio_eng_discount'] ? true : false,
                'other_fees'=>$data['additional_services']['other_fees'] ?? null,
                'mixing_services'=>$data['additional_services']['mixing_services'] ?? null,
                'studio_id'=>$studioId
            ];
            $this->studioPriceRepository->create($studioPriceData);
            $studioImages=[];
            foreach ($data['photos'] as $base64Image) {
                $studioImages[]=uploadImageStudio($base64Image,$studio->name);
            }
            if(count($studioImages) > 0){
                $this->studioImageRepository->addImages($studioImages,$studioId);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response(new stdClass(), true, 'Studio Created Successfully');

    }

    public function show(Studio $studio)
    {
        $user = $this->userRepository->find(auth()->user()->id);
        if($studio == null){
            return RestAPI::response('No Studio Found', false, 'error_exception');
        }
        if($user->id != $studio->user_id){
            return RestAPI::response('Unathorized Access', false, 'error_exception');
        }
        $response = new StudioResource($studio);
        return RestAPI::response($response, true, 'Studio');
    }




}
