<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateAdministratorRequest;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    private $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->adminRepository = $adminRepository;
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        return view('admin.users.changePassword');
    }

    /**
     * update user password
     */
    public function processChangePassword(UpdatePasswordRequest $request)
    {
        $id = Auth::user()->id;
        if (Hash::check($request->get('oldPassword'), Auth::user()->password)) {
            $data['password'] = bcrypt($request->get('password'));
            $this->adminRepository->update($id, $data);
            return redirect()
                ->route('users.change-password')
                ->with('success', 'Password has been changed successfully..');
        } else {
            return redirect()
                ->route('users.change-password')
                ->with('success', 'Please enter the old password correctly');
        }
    }

    /**
     * show admin edit form
     */
    public function editProfile()
    {
        $data = $this->adminRepository->find(auth()->user()->id);
        return view('admin.users.profile', compact('data'));
    }

    /**
     * update admin record
     */
    public function updateEditProfile(UpdateAdministratorRequest $request)
    {
        $userRecord = auth()->user();
        $exceptFields = [
            '_token',
            '_method',
            'email',
        ];
        // 1 = super admin user id, and is_active status cannot be set for it
        if ($userRecord->id == 1) {
            $exceptFields[] = 'is_active';
        }
        $data = $request->except($exceptFields);
        $updateRecord = [
            'first_name' => $data['full_name'],
            'phone' => $data['phone'],
        ];
        //check logo if exists
        if ($request->hasfile('profile_picture')) {
            //move | upload file on server
            $file = $request->file('profile_picture');
            $updateRecord['profile_picture'] = CloudinaryService::upload($file->getRealPath(),\auth()->id())->secureUrl;
            $oldImage = $userRecord->profile_picture;
        }
        if (isset($data['password'])) {
            $updateRecord['password'] = bcrypt($data['password']);
        }
        $this->adminRepository->update($userRecord->id, $updateRecord);
        if (isset($oldImage)) {
            $this->safeRemoveImage($oldImage, backendUserFile());
        }
        return redirect()
            ->route('users.edit-profile')
            ->with('success', 'Profile updated successfully.');
    }
}
