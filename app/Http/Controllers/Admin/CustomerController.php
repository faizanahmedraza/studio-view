<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;

class CustomerController extends Controller
{
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    /**
     * get app verified user list
     */
    public function index()
    {
        return view('admin.Customer.index');
    }

    /**
     * yajra call after user list
     */
    public function subAdminList(DataTables $datatables, Request $request): JsonResponse
    {
        $query = User::where('role_id', '=', 1);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('first_name', function ($record) {
                return $record->getFullname();
            })
            ->editColumn('status', static function ($record) {
                return view('admin.Customer.status', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return view('admin.Customer.action', compact('record'));
            })
            ->make(true);
    }

    /**
     * Show user edit form
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $user = User::find($id);
        return view('admin.Customer.edit', compact('user'));
    }
    /**
     * new Show unverified user edit form
     */

    /**
     * update user record
     */
    public function update(UpdateUserRequest $UserRequest, User $user)
    {
        $postData = $UserRequest->all();

        $updateRecord = [
            'first_name' => $postData['first_name'],
            'last_name' => $postData['last_name'],
            'phone' => $postData['phone'],
        ];
        if ($UserRequest->has('password') && $UserRequest->get('password', '') != '') {
            $updateRecord['password'] = \Hash::make($postData['password']);
        }
        if ($UserRequest->hasfile('upload_file')) {
            $file = $UserRequest->file('upload_file');
            $updateRecord['profile_picture'] = CloudinaryService::upload($file->getRealPath(),\auth()->id())->secureUrl;
        }
        $user->update($updateRecord);
        return redirect()->route('users.index')->with('success', 'User has been updated successfully!');
    }

    /**
     * active user record
     */
    public function active(User $record)
    {
        $record->active();
        return redirect()
            ->route('users.index')
            ->with('success', 'User has been Active successfully!');
    }

    /**
     * block user record
     */
    public function block(User $record)
    {
        $record->block();
        return redirect()
            ->route('users.index')
            ->with('success', 'User has been blocked successfully!');
    }
}
