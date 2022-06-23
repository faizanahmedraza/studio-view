<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StudioCityListResource;
use App\Models\StudioPrice;
use App\Services\CloudinaryService;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
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
            $allStudios = $this->studioRepository->initiateQuery()->where('user_id', auth()->id())->get();
            $response = StudioResource::collection($allStudios);
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
                'is_owner' => $user->id,
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
                $studioImages[] = CloudinaryService::upload($base64Image, $studioId)->secureUrl;
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
        if ($user->id != $studio->is_owner) {
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
                    $studioImages[] = CloudinaryService::upload($base64Image, $studio->id)->secureUrl;
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
            $response = new StudioResource($studio);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studio Details');
    }

    public function destroy(Studio $studio)
    {
        $user = $this->userRepository->find(auth()->user()->id);
        if ($user->id != $studio->is_owner) {
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

    public function search(Request $request)
    {
        $this->validate($request, [
            'address' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $studioLocations = $this->studioLocationRepository->findBy('address', $data['address']);
            $studioLocations = $studioLocations->pluck('studio_id');
            $studioLocations = $studioLocations->toArray();
            $allStudios = $this->studioRepository->findByIn('id', $studioLocations);
            $allStudios = $allStudios->where('status', 1);
            $allStudios->all();
            $response = StudioListResource::collection($allStudios);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studio List');
    }

    public function studiosList(Request $request)
    {
        $request->validate([
            'location' => 'required|string|regex:/^[0-9\.]+(,[0-9\.]+)$/'
        ]);
        $lat = trim(explode(',', $request->location)[0]);
        $lng = trim(explode(',', $request->location)[1]);
        try {
            $allStudios = $this->studioRepository->initiateQuery()->with(['getLocation', 'getPrice', 'getStudioTypes', 'paidPromotion']);

            if ($request->query('premium')) {
                if ($request->premium == "yes") {
                    $allStudios->has('paidPromotion');
                } elseif ($request->premium == "no") {
                    $allStudios->doesntHave('paidPromotion');
                }
            }

            if ($request->query('location')) {
                $allStudios->whereHas('getLocation', function ($q) use ($request, $lat, $lng) {
                    $q->selectRaw('SQRT(
                         POW(69.1 * (lat - ?), 2) +
                         POW(69.1 * (? - lng) * COS(lat / 57.3), 2)) as distance', [$lat, $lng])
                        ->having('distance', '<=', 5);
                });
            } else {
                $allStudios->whereHas('getLocation', function ($q) use ($request) {
                    if ($request->query("country")) {
                        $q->whereRaw("TRIM(LOWER(country)) = ? ", cleanQueryValue($request->country));
                    }
                    if ($request->query("state")) {
                        $q->orWhereRaw("TRIM(LOWER(state)) = ? ", cleanQueryValue($request->state));
                    }
                    if ($request->query("city")) {
                        $q->orWhereRaw("TRIM(LOWER(city)) = ? ", cleanQueryValue($request->city));
                    }
                });
            }

            if ($request->query("from_price")) {
                $allStudios->whereHas('getPrice', function ($q) use ($request) {
                    $q->whereRaw("TRIM(FLOOR(hourly_rate)) >= ? ", (int)($request->from_price));
                });
            }

            if ($request->query("to_price")) {
                $allStudios->orWhereHas('getPrice', function ($q) use ($request) {
                    $q->orWhereRaw("TRIM(FLOOR(hourly_rate)) <= ? ", (int)($request->to_price));
                });
            }

            if ($request->query("order_by_price")) {
                $allStudios->orderBy(StudioPrice::select('hourly_rate')
                    ->whereColumn('studio_prices.studio_id', 'studios.id')
                    , trim($request->order_by_price));
            }

            if ($request->query("category_id")) {
                $allStudios->whereHas('getStudioTypes', function ($q) use ($request) {
                    $q->whereIn('type_id', getIds($request->category_id));
                });
            }

            if ($request->query('status')) {
                $allStudios->where('status', (int)$request->status);
            } else {
                $allStudios->where('status', 1);
            }

            if ($request->query('order_by')) {
                $allStudios->orderBy('id', $request->get('order_by'));
            } elseif (empty($request->query("order_by_price"))) {
                $allStudios->orderBy('id', 'asc');
            }

            if ($request->query('page_limit')) {
                $allStudios = $allStudios->paginate((int)$request->page_limit);
            } else {
                $allStudios = $allStudios->paginate(20);
            }

            $response = StudioResource::collection($allStudios);
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
            $cities = $this->studioLocationRepository->initiateQuery()
                ->selectRaw("COUNT(city) as no_of_studios, city")
                ->groupBy("city")
                ->orderBy("no_of_studios", "desc")
                ->offset($offset)
                ->limit($limit)
                ->get();
            $response = StudioCityListResource::collection($cities);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studios Cities List');
    }
}
