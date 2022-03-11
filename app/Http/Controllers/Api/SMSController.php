<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Twilio\Rest\Client;
use App\Classes\RestAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\SmsVerification;

class SMSController extends ApiBaseController
{
    private $userRepository;
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendSms()
    {
        $this->user = $this->userRepository->find(auth()->user()->id);

        if($this->user->phone == null){
            return RestAPI::response('Please update your phone number to verify it.', false, 'error_exception');
        }
        if($this->user->sms_verified == 1){
            return RestAPI::response('Phone Number Already Verified ', false, 'error_exception');
        }
        DB::beginTransaction();
        try {
            $verifactionCode=substr(str_shuffle("0123456789"), 0, 5);
            $receiverNumber =  $this->user->phone;
            // $receiverNumber =  '+92number';

            $message = "Your verification code is $verifactionCode";
            SmsVerification::where('user_id',$this->user->id)->delete();
            SmsVerification::create(['user_id'=>$this->user->id,'code'=>$verifactionCode,'expires_at'=>time() + 2*60*60]);

            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_number = getenv("TWILIO_PHONE_NUMBER");

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response('Verification code sent Successfully', true, 'Verification code');
    }
    public function verifySmsCode(Request $request)
    {
        $status=true;
        $msg='Phone Number Verification';
        $this->validate($request,[
            'code'=>'required|numeric|digits:5',
        ]);
        DB::beginTransaction();
        try {
            $data=$request->all();
            $this->user = $this->userRepository->find(auth()->user()->id);
            if($this->user->sms_verified == 1){
                return RestAPI::response('Phone Number Already Verified', false, 'error_exception');
            }
            $checkCode=SmsVerification::where('code',$data['code'])->where('user_id',$this->user->id)->first();
            if($checkCode == null){
                $res='Invalid Code';
                $status=false;
                $msg='error_exception';
            }
            else{
                $res='Phone Number Verified Successfully';
                $this->userRepository->updateSmsVerification($this->user->email);
                SmsVerification::where('user_id',$this->user->id)->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($res, $status, $msg);
    }

}
