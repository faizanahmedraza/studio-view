<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Classes\RestAPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PageResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\PagesRepositoryInterface;
use stdClass;

class UserController extends ApiBaseController
{

    private $userRepository;
    private $pageRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository,
                                PagesRepositoryInterface $pageRepository)
    {
        $this->userRepository = $userRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * Get User profile
    */
    public function getCustomer(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->find(auth()->user()->id);
            // $response = new UserResource($user,  jwt()->fromUser($user));
            $response = new UserResource($user);
            if ($user->is_unblock == 0) {
                return RestAPI::response($response, true, "Your account has been blocked by the admin, please contact ");
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, "Get Customer Profile Successfully");
    }

    /**
     * Update User profile
     */
    public function customerUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|max:255',
            'email' => 'required|email',
            // 'phone' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $user = $this->userRepository->find(auth()->user()->id);
            $data = $request->all();
            // $data = $request->all();
            // print_r($data);die;


            $updateData = [
                'first_name' => ucwords(trim($data['full_name'])),
                'email' => $data['email'],
				// 'phone' => $data['phone'],
            ];
            if(isset($data['phone']) && $user->phone != $data['phone']){
                $updateData['phone']=$data['phone'];
                $user->sms_verified=0;
                $user->save();
            }
            if($user->is_fb==1 || $user->is_google==1){
                unset($updateData['email']);
            }
            if ($request->profile_picture) {

                //move | upload file on server
                $slug = Str::slug(trim($data['full_name']), '-');

                $image = $request->profile_picture;  // your base64 encoded

                $image_parts = explode(";base64,", $image);

                $image_type_aux = explode("image/", $image_parts[0]);

                $image_type = $image_type_aux[1];

                $image_base64 = base64_decode($image_parts[1]);

                $file = backendUserFile() . $slug . '-' . time(). '-' . uniqid() . '.'.$image_type;

                file_put_contents($file, $image_base64);
                // $file->move(backendUserFile(), $filename);

                $updateData['profile_picture'] = url($file);

            }
            // dd($updateData);
            $this->userRepository->update(auth()->user()->id, $updateData);

            $user = $this->userRepository->find(auth()->user()->id);
            $response = new UserResource($user);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, "Update Profile Successfully");
    }

    /**
     * change user password
     */
    public function changePassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $result['is_unblock'] = 1;
            $userblock = User::where('id', auth()->user()->id)->first();
            if ($userblock->is_unblock == 0) {
                $result['is_unblock'] = 0;
                return RestAPI::response($result, true, "Your account has been blocked by the admin, please contact ");
            }
            if (!(Hash::check($request->get('current_password'), auth()->user()->password))) {
                return RestAPI::response('Your current password does not matches with the password you provided. Please try again.', false);
            }
            if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
                return RestAPI::response('New Password cannot be same as your current password. Please choose a different password.', false);
            }
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|string|min:6|confirmed',
            ]);
            if ($validator->fails()) {
                return RestAPI::response('New Password should match with confirm password & it\'s lenght must be minimum 6. Please try again.', false);
            }
            $user = auth()->user();
            $user->password = bcrypt($request->get('new_password'));
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response(new stdClass(), true, 'Your Password is changed successfully');
    }

    /**
     * Get Pages
     */
    public function getpage(Request $request)
    {
        $is_block = 1;
        $result = $this->pageRepository->getPage($request->get('slug'));
        $userblock = User::where('id', auth()->user()->id)->first();
        if ($userblock->is_unblock == 0) {
            $result['is_unblock'] = 0;
            $response = new PageResource($result);
            return RestAPI::response($response, true, "Your account has been blocked by the admin, please contact ");
        }
        $result['is_unblock'] = $is_block;
        $response = new PageResource($result);
        return RestAPI::response($response, true, 'Page record');
    }




}
