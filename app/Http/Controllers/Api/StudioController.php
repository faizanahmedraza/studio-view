<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StudioCityListResource;
use DB;
use stdClass;
use Validator;
use App\Models\Studio;

use App\Classes\RestAPI;
use Illuminate\Http\Request;
use App\Http\Resources\StudioResource;
use App\Http\Resources\StudioListResource;
use App\Http\Requests\Api\CreateStudioRequest;
use App\Http\Requests\Api\UpdateStudioRequest;
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

    public function __construct(UserRepositoryInterface $userRepository, StudioRepositoryInterface $studioRepository,
                                StudioTypeRepositoryInterface $studioTypeRepository, StudioLocationRepositoryInterface $studioLocationRepository,
                                StudioPriceRepositoryInterface $studioPriceRepository, StudioImageRepositoryInterface $studioImageRepository)
    {
        $this->userRepository = $userRepository;
        $this->studioRepository = $studioRepository;
        $this->studioTypeRepository = $studioTypeRepository;
        $this->studioLocationRepository = $studioLocationRepository;
        $this->studioPriceRepository = $studioPriceRepository;
        $this->studioImageRepository = $studioImageRepository;


    }

    public function index()
    {
        try {
            $allStudios = $this->studioRepository->initiateQuery()->where('user_id',auth()->id())->get();
            $response = StudioListResource::collection($allStudios);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studios List');
    }

    public function store(CreateStudioRequest $request)
    {
        // if (json_last_error() != JSON_ERROR_NONE) {
        //     return RestAPI::response("Json request is not valid / ".json_last_error_msg(), false, json_last_error());
        // }

        $data = $request->all();
        DB::beginTransaction();
        try {
            $user = $this->userRepository->find(auth()->user()->id);

            $studioData = [
                'user_id' => $user->id,
                'name' => $data['name'] ?? null,
                'detail' => $data['detail'] ?? null,
                'minimum_booking_hr' => $data['minimum_booking_hr'] ?? null,
                'max_occupancy_people' => $data['max_occupancy_people'] ?? null,
                'hours_status' => $data['hours_status'] ?? null,
                'adv_booking_time_id' => $data['adv_booking_time_id'] ?? null,
                'past_client' => $data['past_client'] ?? null,
                'audio_sample' => $data['audio_sample'] ?? null,
                'amenities' => $data['amenities'] ?? null,
                'main_equipment' => $data['main_equipment'] ?? null,
                'rules' => $data['rules'] ?? null,
                'cancelation_policy' => $data['cancelation_policy'] ?? null,
            ];
            if ($data['hours_status'] == 3) {
                $studioData['hrs_to'] = $data['hrs_to'] ?? null;
                $studioData['hrs_from'] = $data['hrs_from'] ?? null;
            }
            $studio = $this->studioRepository->create($studioData);
            $studioId = $studio->id;
            $this->studioTypeRepository->addTypes($data['types'], $studioId);
            $studioLocationData = [
                'address' => $data['address'] ?? null,
                'street' => $data['street'] ?? null,
                'country' => $data['country'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'zip_code' => $data['zip_code'] ?? null,
                'lat' => $data['lat'] ?? null,
                'lng' => $data['lng'] ?? null,
                'additional_details' => $data['additional_location_details'] ?? null,
                'studio_id' => $studioId
            ];
            $this->studioLocationRepository->create($studioLocationData);
            $studioPriceData = [
                'hourly_rate' => $data['hourly_rate'] ?? null,
                'audio_eng_included' => $data['audio_eng_included'] ? true : false,
                'discount' => $data['discount'] ?? null,
                'audio_eng_rate_hr' => $data['additional_services']['audio_eng_rate_hr'] ?? null,
                'audio_eng_discount' => $data['additional_services']['audio_eng_discount'] ? true : false,
                'other_fees' => $data['additional_services']['other_fees'] ?? null,
                'mixing_services' => $data['additional_services']['mixing_services'] ?? null,
                'studio_id' => $studioId
            ];
            $this->studioPriceRepository->create($studioPriceData);
            $studioImages = [];
            foreach ($data['photos'] as $base64Image) {
                $studioImages[] = uploadImageStudio($base64Image, $studio->name);
            }
            if (count($studioImages) > 0) {
                $this->studioImageRepository->addImages($studioImages, $studioId);
            }
            $response = new StudioResource($studio);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studio Created Successfully');

    }

    public function update(UpdateStudioRequest $request, Studio $studio)
    {
        $user = $this->userRepository->find(auth()->user()->id);
        if ($user->id != $studio->user_id) {
            return RestAPI::response('Unathorized Access', false, 'error_exception');
        }

        // if (json_last_error() != JSON_ERROR_NONE) {
        //     return RestAPI::response("Json request is not valid / ".json_last_error_msg(), false, json_last_error());
        // }

        $data = $request->all();

        DB::beginTransaction();
        try {
            $studioData = [
                'name' => $data['name'] ?? null,
                'detail' => $data['detail'] ?? null,
                'minimum_booking_hr' => $data['minimum_booking_hr'] ?? null,
                'max_occupancy_people' => $data['max_occupancy_people'] ?? null,
                'hours_status' => $data['hours_status'] ?? null,
                'adv_booking_time_id' => $data['adv_booking_time_id'] ?? null,
                'past_client' => $data['past_client'] ?? null,
                'audio_sample' => $data['audio_sample'] ?? null,
                'amenities' => $data['amenities'] ?? null,
                'main_equipment' => $data['main_equipment'] ?? null,
                'rules' => $data['rules'] ?? null,
                'cancelation_policy' => $data['cancelation_policy'] ?? null,
            ];
            if ($data['hours_status'] == 3) {
                $studioData['hrs_to'] = $data['hrs_to'] ?? null;
                $studioData['hrs_from'] = $data['hrs_from'] ?? null;
            } else {
                $studioData['hrs_to'] = null;
                $studioData['hrs_from'] = null;
            }
            $this->studioRepository->update($studio->id, $studioData);
            $studio->deleteTypes();
            $this->studioTypeRepository->addTypes($data['types'], $studio->id);
            $studioLocationData = [
                'address' => $data['address'] ?? null,
                'street' => $data['street'] ?? null,
                'country' => $data['country'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'zip_code' => $data['zip_code'] ?? null,
                'lat' => $data['lat'] ?? null,
                'lng' => $data['lng'] ?? null,
                'additional_details' => $data['additional_location_details'] ?? null,
                'studio_id' => $studio->id
            ];
            $this->studioLocationRepository->updateByStudioId($studio->id, $studioLocationData);
            $studioPriceData = [
                'hourly_rate' => $data['hourly_rate'] ?? null,
                'audio_eng_included' => $data['audio_eng_included'] ? true : false,
                'discount' => $data['discount'] ?? null,
                'audio_eng_rate_hr' => $data['additional_services']['audio_eng_rate_hr'] ?? null,
                'audio_eng_discount' => $data['additional_services']['audio_eng_discount'] ? true : false,
                'other_fees' => $data['additional_services']['other_fees'] ?? null,
                'mixing_services' => $data['additional_services']['mixing_services'] ?? null,
                'studio_id' => $studio->id
            ];
            $this->studioPriceRepository->updateByStudioId($studio->id, $studioPriceData);
            $studioImages = [];
            foreach ($data['photos']['old'] as $url) {
                $studioImages[] = $url;
            }
            if (isset($data['photos']['new']) && count($data['photos']['new']) > 0) {
                foreach ($data['photos']['new'] as $base64Image) {
                    $studioImages[] = uploadImageStudio($base64Image, $studioData['name']);
                }
            }
            $studio->deleteImages();
            $this->studioImageRepository->addImages($studioImages, $studio->id);

            $response = new StudioResource($studio);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studio Updated Successfully');
    }

    public function show(Studio $studio)
    {
        try {
            $user = $this->userRepository->find(auth()->user()->id);
            if ($user->id != $studio->user_id) {
                return RestAPI::response('Unathorized Access', false, 'error_exception');
            }
            $response = new StudioResource($studio);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studio Details');
    }

    public function destroy(Studio $studio)
    {
        $user = $this->userRepository->find(auth()->user()->id);
        if ($user->id != $studio->user_id) {
            return RestAPI::response('Unathorized Access', false, 'error_exception');
        }
        DB::beginTransaction();
        try {
            $studio->deleteStudio();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response(new stdClass(), true, 'Studio Deleted Successfully');
    }

    public function studiosList(Request $request)
    {
        try {
            $allStudios = $this->studioRepository->initiateQuery()->with(['getLocation', 'getPrice', 'getStudioTypes']);

            $allStudios->whereHas('getLocation', function ($q) use ($request) {
                if ($request->query("country")) {
                    $q->whereRaw("TRIM(LOWER(country)) = ? ", cleanQueryValue($request->country));
                }
                if ($request->query("state")) {
                    $q->whereRaw("TRIM(LOWER(state)) = ? ", cleanQueryValue($request->state));
                }
                if ($request->query("city")) {
                    $q->whereRaw("TRIM(LOWER(city)) = ? ", cleanQueryValue($request->city));
                }
            });

            $allStudios->whereHas('getPrice', function ($q) use ($request) {
                if ($request->query("from_price")) {
                    $q->whereRaw("TRIM(FLOOR(hourly_rate)) >= ? ", (int)($request->from_price));
                }
                if ($request->query("to_price")) {
                    $q->whereRaw("TRIM(FLOOR(hourly_rate)) <= ? ", (int)($request->to_price));
                }
            });

            $allStudios->whereHas('getStudioTypes', function ($q) use ($request) {
                if ($request->query("category_id")) {
                    $q->whereIn('type_id', getIds($request->category_id));
                }
            });

            if ($request->query('status')) {
                $allStudios->where('status', (int)$request->status);
            } else {
                $allStudios->where('status', 1);
            }

            if ($request->query('order_by')) {
                $allStudios->orderBy('id', $request->get('order_by'));
            } else {
                $allStudios->orderBy('id', 'desc');
            }

            if ($request->query('page_limit')) {
                $allStudios = $allStudios->paginate((int)$request->page_limit);
            } else {
                $allStudios = $allStudios->paginate(20);
            }

            $response = StudioListResource::collection($allStudios);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studios List');
    }

    public function citiesList(Request $request)
    {
        try {
            $limit = 20;
            if (!empty($request->page_no)) {
                $page_no = (int)$request->page_no;
                $offset = $page_no * $limit;
            } else {
                $offset = 0;
            }
            $cities = $this->studioLocationRepository->initiateQuery()->select('city')->distinct()->offset($offset)->limit($limit)->get();
            $response = StudioCityListResource::collection($cities);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studios Cities List');
    }
}
