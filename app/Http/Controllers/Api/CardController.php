<?php

namespace App\Http\Controllers\Api;

use DB;
use stdClass;
use Validator;

use App\Classes\RestAPI;
use App\Models\BookingTime;
use Illuminate\Http\Request;
use App\Classes\StripeWrapper;
use App\Models\UserCardDetail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\CardStoreRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;

class CardController extends ApiBaseController
{
    private $userRepository;
    private $studioRepository;
    private $studioTypeRepository;
    private $studioLocationRepository;
    private $studioPriceRepository;
    private $studioImageRepository;
    private $studioBookingRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

    }
    public function store(CardStoreRequest $request)
    {

        $data = $request->all();
        DB::beginTransaction();
        try {

            $user = $this->userRepository->find(auth()->user()->id);
            if(empty($user)){
                return RestAPI::response("User not found", false);
            }
            $last4 =  substr($request->input('card_number'), -4);

            //  checkect card exsit
            $hasCard =  $user->card;

            if ($hasCard) {

                return RestAPI::response("Card Already Exist", false);
            }



            $stripe = StripeWrapper::createCard($request, $user);

            // verify stripe card
            $verification = StripeWrapper::setupIntent($stripe, $user);

            if ($verification->status != 'succeeded') {

                return RestAPI::response("Please enter valid card", false);
            }






            //  save card information
            $card = new UserCardDetail();
            $card->user_id = Auth::user()->id;
            $card->card_id = $stripe['id'];
            $card->last_digits = $stripe['last4'];
            $card->exp_month = $stripe['exp_month'];
            $card->exp_year = $stripe['exp_year'];
            $card->brand = $stripe['brand'];
            $card->country = $stripe['country'];
            $card->holder_name = $stripe['name'];
            $card->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($card, true, 'Card Saved Successfully');

    }

    public function destroy()
    {
        DB::beginTransaction();
        try {
            $card =  UserCardDetail::where('user_id' ,  Auth::user()->id)->first();

            if (!$card) {
                return RestAPI::response("Card Doesn't Exist", false);
            }

            $stripe = (new StripeWrapper())->deleteCard($card, \Auth::user());
            $card->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response("Card Deleted Successfully", true);

    }
}
