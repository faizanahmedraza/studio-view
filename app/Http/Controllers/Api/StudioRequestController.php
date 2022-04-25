<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StudioBookingResource;
use App\Repositories\Interfaces\StudioBookingRepositoryInterface;
use Carbon\Carbon;
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
    private $studioBookingRepository;

    public function __construct(UserRepositoryInterface $userRepository, StudioRepositoryInterface $studioRepository,
                                StudioTypeRepositoryInterface $studioTypeRepository, StudioLocationRepositoryInterface $studioLocationRepository,
                                StudioPriceRepositoryInterface $studioPriceRepository, StudioImageRepositoryInterface $studioImageRepository, StudioBookingRepositoryInterface $studioBookingRepository)
    {
        $this->userRepository = $userRepository;
        $this->studioRepository = $studioRepository;
        $this->studioTypeRepository = $studioTypeRepository;
        $this->studioLocationRepository = $studioLocationRepository;
        $this->studioPriceRepository = $studioPriceRepository;
        $this->studioImageRepository = $studioImageRepository;
        $this->studioBookingRepository = $studioBookingRepository;
    }

    public function request(RequestStudioRequest $request)
    {
        $studio = $this->studioRepository->find($request->studio_id);
        $requestStartTime = Carbon::parse($request->start_time);
        $requestEndTime = Carbon::parse($request->end_time);
        $differenceInHours = $requestEndTime->diffInHours($requestStartTime);
        $requestDate = Carbon::parse($request->date)->format('Y-m-d');

        if ($differenceInHours >= $studio->minimum_booking_hr && $studio->hours_status == 3) {
            if ($studio->hrs_from >= $requestStartTime->format('H:i') && $studio->hrs_to <= $requestEndTime->format('H:i')) {
                $studioRequest = $this->studioBookingRepository->initiateQuery()
                    ->where('studio_id', $studio->id)
                    ->where('date', $requestDate)->first();

                if (!empty($studioRequest)) {
                    $studioRequestForCurrentTime = $this->studioBookingRepository->initiateQuery()
                        ->where('studio_id', $studio->id)
                        ->where('date', $requestDate)
                        ->where('start_time', $requestStartTime->format('H:i'))
                        ->where('end_time', $requestEndTime->format('H:i'))->first();

                    if (!empty($studioRequestForCurrentTime)) {
                        return RestAPI::response('The time you selected is already granted to some one.', false, 'validation_error');
                    }

                    $reqStartTime = Carbon::parse($studioRequest->start_time);
                    $reqEndTime = Carbon::parse($studioRequest->end);
                    $reqDiffTime = $reqEndTime->diffInHours($reqStartTime);

                    $studioStartHours = Carbon::parse($studio->hrs_from);
                    $studioEndHours = Carbon::parse($studio->hrs_to);
                    $studioTotalHours = $studioEndHours->diffInHours($studioStartHours);

                    $remainingHours = $studioTotalHours - $reqDiffTime;
                    if ($remainingHours < $studio->minimum_booking_hr) {
                        return RestAPI::response('The date ' . $requestDate . ' you selected studio, not have enough time to grant you. Please use another slot.', false, 'validation_error');
                    }
                }

            } else {
                return RestAPI::response('The time you selected is not the time where studio opens.', false, 'validation_error');
            }
        } else {
            return RestAPI::response('Total hours should be greater or equal to studio minimum hours.', false, 'validation_error');
        }


        DB::beginTransaction();
        try {
            $user = $this->userRepository->find(auth()->user()->id);
            $data = $request->all();

            $hourlyRate = $studio->getPrice->hourly_rate;
            $audioEngInc = $request->audio_engineer;
            $discount = $studio->getPrice->discount;
            $audioEngRateHr = 0;
            $audioEngDisc = 0;
            $otherFees = 0;
            $mixingServices = 0;

            $totalHrsPrice = $studio->getPrice->hourly_rate * $differenceInHours;
            $sellingPriceTotalHrs = $totalHrsPrice;
            if (!empty($studio->getPrice->discount)) {
                $sellingPriceTotalHrs = $totalHrsPrice - ($totalHrsPrice * ($studio->getPrice->discount / 100));
            }
            $sellingPriceAudioEngRateHrs = 0;
            if (!$studio->getPrice->audio_eng_included && $request->audio_engineer && !empty($studio->getPrice->audio_eng_rate_hr)) {
                $audioEngInc = $studio->getPrice->audio_eng_included;
                $audioEngRateHr = $studio->getPrice->audio_eng_rate_hr;
                $audioEngDisc = $studio->getPrice->discount;
                $engRateHr = $studio->getPrice->audio_eng_rate_hr * $differenceInHours;
                $sellingPriceAudioEngRateHrs = $engRateHr;
                if ($studio->getPrice->audio_eng_discount) {
                    $sellingPriceAudioEngRateHrs = $engRateHr - ($engRateHr * ($studio->getPrice->discount / 100));
                }
            }

            if ($request->other_fees && !empty($studio->getPrice->other_fees)) {
                $otherFees = $studio->getPrice->other_fees;
            }

            if ($request->mixing_services && !empty($studio->getPrice->mixing_services)) {
                $mixingServices = $studio->getPrice->mixing_services;
            }

            $grandPrice = $otherFees + $mixingServices + $sellingPriceAudioEngRateHrs + $sellingPriceTotalHrs;

            $bookingData = [
                'user_id' => $user->id,
                'studio_id' => $data['studio_id'],
                'date' => $data['date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'hourly_rate' => $hourlyRate,
                'audio_eng_included' => $audioEngInc,
                'discount' => $discount,
                'audio_eng_rate_hr' => $audioEngRateHr,
                'audio_eng_discount' => $audioEngDisc,
                'other_fees' => $otherFees,
                'mixing_services' => $mixingServices,
                'total_hours_price' => $sellingPriceTotalHrs,
                'total_eng_hours_price' => $sellingPriceAudioEngRateHrs,
                'grand_total' => $grandPrice,
                'on_arrival_to_bring_issued_id_agree' => $request->on_arrival_to_bring_issued_id_agree ? 1 : 0,
                'studio_cancellation_policy_agree' => $request->studio_cancellation_policy_agree ? 1 : 0,
                'status' => 0
            ];

            $booking = $this->studioBookingRepository->create($bookingData);
            $response = new StudioBookingResource($booking);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
         return RestAPI::response( $response, true, 'Studio Booking Created Successfully');

    }


}
