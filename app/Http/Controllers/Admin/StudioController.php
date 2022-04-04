<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateStudioRequest;
use App\Models\BookingTime;
use App\Models\Type;
use App\Models\User;
use App\Repositories\Interfaces\StudioImageRepositoryInterface;
use App\Repositories\Interfaces\StudioLocationRepositoryInterface;
use App\Repositories\Interfaces\StudioPriceRepositoryInterface;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Interfaces\StudioTypeRepositoryInterface;
use DB;
use stdClass;
use Validator;
use App\Models\Studio;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserRepositoryInterface;

class StudioController extends Controller
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
        $this->middleware('auth:admin');
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->studioRepository = $studioRepository;
        $this->studioTypeRepository = $studioTypeRepository;
        $this->studioLocationRepository = $studioLocationRepository;
        $this->studioPriceRepository = $studioPriceRepository;
        $this->studioImageRepository = $studioImageRepository;
    }

    /**
     * get app verified user list
     */
    public function index()
    {
        return view('admin.studio.index');
    }

    public function create()
    {
        $customers = User::where([['role_id' ,'=',1],['is_active' ,'=',1]])->get();
        $types = Type::where('status',1)->get();
        $advanceBookingTimes = BookingTime::where('status',1)->get();
        $hoursStatus = Studio::HOURS_STATUS;
        return view('admin.studio.create',compact('customers','types','advanceBookingTimes','hoursStatus'));
    }

    public function store(CreateStudioRequest $request)
    {
        try{
            $user = $this->userRepository->find((int)$request->customer);

            $data = $request->all();

            $studioData = [
                'user_id' => $user->id,
                'name' => $data['studio_name'] ?? null,
                'detail' => $data['details'] ?? null,
                'minimum_booking_hr' => $data['minimum_booking_hr'] ?? null,
                'max_occupancy_people' => $data['max_occupancy_people'] ?? null,
                'hours_status' => $data['hours_status'] ?? null,
                'adv_booking_time_id' => $data['adv_booking_time_id'] ?? null,
                'past_client' => $data['past_client'] ?? null,
                'audio_sample' => $data['audio_samples'] ?? null,
                'amenities' => $data['amenities'] ?? null,
                'main_equipment' => $data['main_equipment'] ?? null,
                'rules' => $data['rules'] ?? null,
                'cancelation_policy' => $data['cancellation_policy'] ?? null,
            ];
            if ($data['hours_status'] == 3) {
                $studioData['hrs_to'] = $data['hrs_to'] ?? null;
                $studioData['hrs_from'] = $data['hrs_from'] ?? null;
            }
            $studio = $this->studioRepository->create($studioData);
            $studioId = $studio->id;
            $this->studioTypeRepository->addTypes($data['studio_types'], $studioId);
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
                'audio_eng_rate_hr' => ['audio_eng_rate_hr'] ?? null,
                'audio_eng_discount' => ['audio_eng_discount'] ? true : false,
                'other_fees' => ['other_fees'] ?? null,
                'mixing_services' => ['mixing_services'] ?? null,
                'studio_id' => $studioId
            ];
            $this->studioPriceRepository->create($studioPriceData);
            $studioImages = [];
            foreach ($request->images as $image)
            {
                $studioImages[] = uploadImageStudio(base64_encode($image), $studio->name);
            }
            if (count($studioImages) > 0) {
                $this->studioImageRepository->addImages($studioImages, $studioId);
            }
        } catch (\Exception $e)
        {

        }
        return back()->with('success','Studio created Successfully!');
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $studio = Studio::with(['getStudioTypes','getImages','getPrice','getLocation'])->where('id',$id)->firstOrFail();
        $customers = User::where([['role_id' ,'=',1],['is_active' ,'=',1]])->get();
        $types = Type::where('status',1)->get();
        $advanceBookingTimes = BookingTime::where('status',1)->get();
        $hoursStatus = Studio::HOURS_STATUS;
        return view('admin.studio.edit',compact('studio','customers','types','advanceBookingTimes','hoursStatus'));
    }

    public function update(Studio $studio, Request $request)
    {
        return back()->with('success','Studio updated Successfully!');
    }

    /**
     * yajra call after user list
     */
    public function studiosList(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Studio::where('status',1);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('status', static function ($record) {
                return view('admin.studio.status', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return view('admin.studio.action', compact('record'));
            })
            ->make(true);
    }

    public function toggleStatus(Studio $studio)
    {
        $studio->status= $studio->status ? false : true ;
        $studio->approved_at=  date('Y-m-d H:i:s') ;
        $studio->save();
        return redirect()->back()
        ->with('success', 'Status Changed Successfully!');
    }

      /**
     * get app verified user list
     */
    public function indexPending()
    {
        return view('admin.studio.pending');
    }

    /**
     * yajra call after user list
     */
    public function studiosListPending(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Studio::where('status',0);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('status', static function ($record) {
                return view('admin.studio.status', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return view('admin.studio.action', compact('record'));
            })
            ->make(true);
    }

}
