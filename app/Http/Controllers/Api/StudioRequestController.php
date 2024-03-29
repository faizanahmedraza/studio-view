<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LatestStudioBookingResource;
use App\Models\StudioBooking;
use DB;
use DateTime;
use stdClass;
use Validator;
use Carbon\Carbon;
use App\Models\Studio;
use App\Classes\RestAPI;
use Illuminate\Http\Request;
use App\Classes\StripeWrapper;

use App\Services\NotificationService;

use App\Http\Resources\StudioBookingResource;
use App\Http\Resources\StudioRequestResource;
use App\Http\Requests\Api\RequestStudioRequest;
use App\Http\Requests\Api\RequestStudioStatusRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\Interfaces\StudioTypeRepositoryInterface;
use App\Repositories\Interfaces\StudioImageRepositoryInterface;
use App\Repositories\Interfaces\StudioPriceRepositoryInterface;
use App\Repositories\Interfaces\StudioBookingRepositoryInterface;
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
    private $invoiceRepository;


    public function __construct(UserRepositoryInterface $userRepository, StudioRepositoryInterface $studioRepository,
                                StudioTypeRepositoryInterface $studioTypeRepository, StudioLocationRepositoryInterface $studioLocationRepository,
                                StudioPriceRepositoryInterface $studioPriceRepository, StudioImageRepositoryInterface $studioImageRepository, StudioBookingRepositoryInterface $studioBookingRepository,
                                InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->userRepository = $userRepository;
        $this->studioRepository = $studioRepository;
        $this->studioTypeRepository = $studioTypeRepository;
        $this->studioLocationRepository = $studioLocationRepository;
        $this->studioPriceRepository = $studioPriceRepository;
        $this->studioImageRepository = $studioImageRepository;
        $this->studioBookingRepository = $studioBookingRepository;
        $this->invoiceRepository = $invoiceRepository;

    }

    public function request(RequestStudioRequest $request)
    {
        $user = $this->userRepository->find(auth()->user()->id);
        $studio = $this->studioRepository->find($request->studio_id);
        if ($studio->user_id == $user->id) {
            return RestAPI::response("You cann't book your own studio.", false);
        }
        if ($user->stripe_user_id == null || empty($user->card)) {
            return RestAPI::response('First add your card details to book a studio.', false, 'validation_error');
        }
        $requestStartTime = Carbon::parse($request->start_time);
        $requestEndTime = Carbon::parse($request->end_time);
        $differenceInHours = $requestEndTime->diffInHours($requestStartTime);
        $requestDate = Carbon::parse($request->date)->format('Y-m-d');

        if ($differenceInHours >= $studio->minimum_booking_hr) {
            $studioRequest = $this->studioBookingRepository->initiateQuery()
                ->where('studio_id', $studio->id)
                ->where('date', $requestDate)
                ->where('start_time', $requestStartTime->format('H:i'))
                ->where('end_time', $requestEndTime->format('H:i'));
            $studioRequestForCurrentTime1 = clone $studioRequest;
            $studioRequestForCurrentTime2 = clone $studioRequest;
            $studioRequestForCurrentTime3 = clone $studioRequest;

            if (!empty($studioRequestForCurrentTime1->where('status', 1)->first())) {
                $currentUser = $studioRequestForCurrentTime2->where('status', 1)->first();
                if ($currentUser->user_id == auth()->id()) {
                    return RestAPI::response('You have already booked this Studio.', false, 'validation_error');
                } else {
                    return RestAPI::response('The Studio is already booked for this time.', false, 'validation_error');
                }
            }

            if (!empty($studioRequestForCurrentTime3->where('user_id', $user->id)->where('status', 0)->first())) {
                return RestAPI::response('You have already requested for the studio for this time.', false, 'validation_error');
            }

            if ($studio->hours_status == 3) {
                if ($requestStartTime->format('H:i') >= $studio->hrs_from && $requestEndTime->format('H:i') <= $studio->hrs_to) {
                    $studioRequest = $studioRequest->first();
                    if (!empty($studioRequest)) {
                        $studioStartHours = Carbon::parse($studio->hrs_from);
                        $studioEndHours = Carbon::parse($studio->hrs_to);
                        $studioTotalHours = $studioEndHours->diffInHours($studioStartHours);

                        $remainingHours = $studioTotalHours - $differenceInHours;
                        if ($remainingHours < $studio->minimum_booking_hr) {
                            return RestAPI::response('The date ' . $requestDate . ' you selected studio, not have enough time to grant you. Please use another slot.', false, 'validation_error');
                        }
                    }
                } else {
                    return RestAPI::response('The time you selected is not the time where studio opens.', false, 'validation_error');
                }
            }
        } else {
            return RestAPI::response('Total hours should be greater or equal to studio minimum hours.', false, 'validation_error');
        }


        DB::beginTransaction();
        try {
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

            $notificationData['user_id'] = $studio->user_id;
            $notificationData['title'] = "Request For Studio";
            $notificationData['body'] = $user->getFullname() . ' has requested to rent your studio "' . $studio->name . '" at ' . $data['start_time'] . " to " . $data['end_time'] . " on " . $data['date'];
            $notificationData['image'] = $studio->getImages[0]->image_url;

            NotificationService::sendNotification($notificationData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studio Booking Created Successfully');

    }

    public function myStudioRequest()
    {
        try {
            $user = $this->userRepository->find(auth()->user()->id);
            $userStudios = $user->studios;
            $userStudiosIds = $userStudios->pluck('id');
            $studioRequests = $this->studioBookingRepository->getIn('studio_id', $userStudiosIds);

            $response = StudioRequestResource::collection($studioRequests);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studios Request List');

    }

    public function myBookingRequest()
    {
        try {
            $user = $this->userRepository->find(auth()->user()->id);

            $studioRequests = $this->studioBookingRepository->getIn('user_id', [$user->id]);

            $response = StudioRequestResource::collection($studioRequests);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studios Request List');
    }

    public function myStudioRequestStatus(RequestStudioStatusRequest $request, $studio_id)
    {

        DB::beginTransaction();
        try {
            $user = $this->userRepository->find(auth()->user()->id);
            if (empty($user)) {
                return RestAPI::response("User not found", false);
            }
            $studio = $this->studioRepository->find($studio_id);
            if (empty($studio)) {
                return RestAPI::response("Studio not found", false);
            }
            if ($request->status == 1 && $studio->user_id != $user->id) {
                return RestAPI::response("Unauthorized Access", false);
            }
            if ($request->status == 0) {
                return RestAPI::response("Can not change the status to pending", false);
            }
            $studioBooking = $this->studioBookingRepository->find($request->booking_id);
            if ($studioBooking->studio_id != $studio->id) {
                return RestAPI::response("Booking studio and requested studio doesn't match.", false);
            }
            if ($studioBooking->status != 0) {
                return RestAPI::response("Can not change the status because its already approved or rejected", false);
            }
            if ($request->status == 2) {
                $studioBooking->status = 2;
                $studioBooking->save();
                return RestAPI::response(new stdClass(), true, 'Status Changed Successfully');
            }
            if ($request->status == 1) {
                $studioBookingsByDate = $this->studioBookingRepository->where(['studio_id' => $studioBooking->studio_id, 'date' => $studioBooking->date, 'status' => 1]);
                if ($user->stripe_user_id == null || empty($user->card)) {
                    return RestAPI::response('First add your card details to accept a studio request.', false, 'validation_error');
                }
                $check = true;
                $filtered = $studioBookingsByDate->whereBetween('start_time', [$studioBooking->start_time, $studioBooking->end_time]);
                $filtered = $filtered->all();
                if (count($filtered) > 0) {
                    $check = false;
                }
                $filtered = $studioBookingsByDate->whereBetween('end_time', [$studioBooking->start_time, $studioBooking->end_time]);
                $filtered = $filtered->all();
                if (count($filtered) > 0) {
                    $check = false;
                }

                if (!$check) {
                    return RestAPI::response('You have other approved booking between this timing.', false, 'Status Change Failed');
                }
                // call stripe api
                $stripeWrapper = new StripeWrapper();
                // dd( $studioBooking->user->card->card_id, $studioBooking->grand_total, $studioBooking->user->stripe_user_id );
                $discount = $studioBooking->discount != null ? $studioBooking->discount : 0;
                $amount = $studioBooking->grand_total;// - $discount;
                $stripe = $stripeWrapper->charge($studioBooking->user->card->card_id, $amount, $studioBooking->user_id . ' user booking Studio of user ' . $studioBooking->studio->user_id, $studioBooking->user->stripe_user_id);

                if ($stripe->paid) {
                    // Invoice work
                    $this->invoiceRepository->create([
                        'requested_user_id' => $studioBooking->user_id,
                        'studio_owner_id' => $studioBooking->studio->user_id,
                        'amount' => $amount,
                        'status' => 1,
                        'studio_booking_id' => $studioBooking->id,
                    ]);
                    $studioBooking->status = 1;
                    $studioBooking->save();
                } else {
                    return RestAPI::response('Status changing failed because of payment failure.', false, 'Status Change Failed');
                }

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response(new \stdClass(), true, 'Status Changed Successfully');

    }

    public function cancelStudioRequest($bookingId)
    {
        $user = $this->userRepository->find(auth()->user()->id);
        $studioRequest = $this->studioBookingRepository->initiateQuery()
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->first();

        if (empty($studioRequest)) {
            return RestAPI::response("You don't have permission to cancel this booking request.", false, 'validation_error');
        }

        if ($studioRequest->status == StudioBooking::STATUS['cancel']) {
            return RestAPI::response("This booking is already cancelled.", false, 'validation_error');
        }

        $currentDate = Carbon::now()->format('Y-m-d H:i');
        $studioRequestDate = Carbon::parse($studioRequest->date . ' ' . $studioRequest->start_time);
        $diff = $studioRequestDate->diffInHours($currentDate);

        if ($diff < 24) {
            return RestAPI::response("You can only cancel your booking 24 hours prior to your booking date and time.", false, 'validation_error');
        }

        $studioRequest->status = StudioBooking::STATUS['cancel'];
        $studioRequest->save();
        return RestAPI::response(new stdClass(), true, 'Studio Request Cancelled Successfully!');
    }

    public function addRatings(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|numeric',
            'ratings' => 'required|numeric|max:5|min:1'
        ]);

        $user = $this->userRepository->find(auth()->user()->id);
        $studioRequest = $this->studioBookingRepository->initiateQuery()
            ->where('id', $request->booking_id)
            ->where('user_id', $user->id)
            ->where('ratings', 0)
            ->first();

        if (empty($studioRequest)) {
            return RestAPI::response("You don't have permission to add ratings.", false, 'validation_error');
        }

        $studioRequest->ratings = (int)$request->ratings;
        $studioRequest->save();

        return RestAPI::response(new stdClass(), true, 'Thanks for ratings!');
    }

    public function studioRequestDetail($bookingId)
    {
        try {
            $studioRequest = $this->studioBookingRepository->whereById($bookingId);
            if (empty($studioRequest)) {
                return RestAPI::response("Invalid Booking Id Or Booking Id doesn't exist.", false);
            }
            $response = new StudioRequestResource($studioRequest);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studios Request Detail');
    }

    public function unRatedBooking()
    {
        try {
            $user = $this->userRepository->find(auth()->user()->id);
            $studioBooking = $this->studioBookingRepository->initiateQuery()
                ->where('user_id', $user->id)
                ->where('ratings', 0)
                ->latest()
                ->first();

            if (empty($studioBooking)) {
                return RestAPI::response(new stdClass(), true, 'No unrated bookings!');
            }

            $response = new LatestStudioBookingResource($studioBooking);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }

        return RestAPI::response($response, true, 'Success');
    }
}
