<?php

namespace App\Http\Controllers\Api;

use stdClass;
use App\Models\User;
use App\Classes\RestAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\Api\NewUserCreateEvent;
use App\Http\Resources\NewUserResource;
use App\Http\Requests\Api\StoreUserRequest;
use App\Http\Requests\Api\UserLoginRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class AuthApiController extends ApiBaseController
{

    private $userRepository;


    use SendsPasswordResetEmails;

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
     * Signup new user
     */
    public function userRegister(StoreUserRequest $request)
    {
        $data = $request->all();
        // print_r($data);die;
        DB::beginTransaction();
        try {
            $userRecord = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? '',
                'password' => bcrypt($data['password']),
                'email' => $data['email'],
                'phone' => $data['phone'] ?? '',
                'email_verified' => 1,
                'is_active' => 1,
                'is_verified' => 1,
                'role_id' => 1,
            ];
            if ($request->hasfile('profile_picture')) {
                //move | upload file on server
                $slug = Str::slug($data['first_name'].' '. $data['last_name'], '-');
                $file = $request->file('profile_picture');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename = $slug . '-' . time() . '.' . $extension;
                $file->move(backendUserFile(), $filename);
                $userRecord['profile_picture'] = url(backendUserFile() . $filename);
            } else {
                $imageName = "default.png";
                $userRecord['profile_picture'] = url(backendUserFile() . $imageName);
            }


            $user = $this->userRepository->create($userRecord);
            $user = $this->userRepository->find($user->id);
            $token = jwt()->fromUser($user);
            $user->addDevice($data['device_token'], $data['device_type'], $token);
            event(new NewUserCreateEvent($user));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        $response = new NewUserResource($user, $token);
        return RestAPI::response($response, true, 'You are successfully signup');
    }

    /**
     * login user
     */
    public function userLogin(UserLoginRequest $request)
    {
        DB::beginTransaction();
        try {
            $check = User::where('email', $request->get('email'))->where('role_id', 1)->first();
            if (!$check) {
                return RestAPI::response('Email not exist. try again!.', false);
            }
            $credentials = $request->only(['email', 'password']);
            $credentials['role_id'] = 1;
            $token = jwt()->attempt($credentials);
            if (!$token) {
                return RestAPI::response('Invalid credentials, please try again.', false);
            }
            $user = jwt()->user();
            $user->validateUserActiveCriteria();
            $user->addDevice($request->get('device_token'), $request->get('device_type'), $token);
            $user['_token'] = $token;
            DB::commit();
        } catch (\App\Exceptions\UserNotAllowedToLogin $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, $e->getResolvedErrorCode());
        }
        $response = new NewUserResource($user,$token);
        return RestAPI::response($response, true, "Logged-In Successfully");
    }

    /**
     * forogt user by email
     */
    public function ForgotPassword(Request $request)
    {
        $user = User::where('email', $request->get('email'))->where('role_id',1)->first();
        if (!$user) {
            return RestAPI::response('Email not exist. try again!.', false);
        }
        else
        {
            $token_validate = DB::table('password_resets')
                ->where('email', $user->email)
                ->where('role_id', $user->role_id)
                ->first();
            $token = hash('ripemd160',uniqid(rand(),true));
            if ($token_validate == null) {
                DB::table('password_resets')
                    ->insert(['email'=> $user->email,'role_id' =>  $user->role_id,'token' => $token]);
            }
            else
            {
                DB::table('password_resets')
                    ->update(['token' => $token]);
            }
            $email = base64_encode ($user->email);
            $user->sendPasswordResetEmail($email,$token,$user->role_id);
            return RestAPI::response(new stdClass(), true, 'We have e-mailed your password reset link!. Please also check Junk/Spam folder as well.!');
        }

    }
    /**
     * logout user
     */
    public function logout(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $header = substr($request->header('Authorization'), 7);
            $user->removeDevice($header);
            auth()->guard('api')->logout();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response(new \stdClass(), true, "You have been logged out successfully.");
    }
}
