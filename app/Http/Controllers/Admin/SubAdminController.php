<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreSubAdminRequest;
use App\Http\Requests\Admin\UpdateSubAdminRequest;
use App\Models\Departments;
use App\Models\Roles;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class SubAdminController extends Controller
{

    use SendsPasswordResetEmails;
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
     * Get subadmin list
     */
    public function index()
    {
        return backend_view('subAdmin.index');
    }

    /**
     * yajra call after subadmin list
     */
    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = User::where(['role_id' => User::ROLE_ID])->where('email', '!=', Auth::user()->email)->where('email','!=','admin@studio.com');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('first_name', function ($record) {
                return $record->getFullname();
            })
            ->editColumn('phone', static function ($record) {
                return $record->phone;
            })
            ->editColumn('status', static function ($record) {
                return backend_view('subAdmin.status', compact('record'));
            })
            ->addColumn('department', function ($record) {
                return $record->department->title;
            })
            ->addColumn('image', static function ($record) {
                return backend_view('subAdmin.image', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return backend_view('subAdmin.action', compact('record'));
            })
            ->make(true);
    }

    /**
     * subadmin active
     */
    public function active(User $record)
    {
        $record->activate();
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub Admin has been Active successfully!');
    }

    /**
     * subadmin inactive
     */
    public function inactive(User $record)
    {
        $record->deactivate();
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub Admin has been Inactive successfully!');
    }

    /**
     * Subadmin create form open.
     *
     */
    public function create()
    {
        $role_list = Roles::NotAdminRole()->get();
        $department = Departments::where('is_active', 1)->get();
        return view('admin.subAdmin.create', compact('role_list', 'department'));
    }

    /**
     * store subadmin record
     */
    public function store(StoreSubAdminRequest $request)
    {
        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );
        $data = $request->all();
        $createRecord = [
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'] ?? '',
            'password' => \Hash::make($data['password']),
            'role_type' => $data['role'],
            'department_id' => $data['department'],
            'is_active' => 1,
            'role_id' => User::ROLE_ID,
        ];

        if ($request->hasfile('upload_file')) {
            $file = $request->file('upload_file');
            $fileName = $file->getClientOriginalName();
            $file->move(backendUserFile(), $file->getClientOriginalName());
            $data['upload_file'] = $fileName;
            $createRecord['profile_picture'] = url(backendUserFile() . $file->getClientOriginalName());
        } else {
            $imageName = "default.png";
            $createRecord['profile_picture'] = url(backendUserFile() . $imageName);
        }

        $user = $this->userRepository->create($createRecord);

       $token = hash('ripemd160',uniqid(rand(),true));
        DB::table('password_resets')
            ->insert(['email'=> $data['email'],'role_id' =>  User::ROLE_ID,'token' => $token]);

        $email = base64_encode ($data['email']);
        $user->sendPasswordResetEmail($email,$token,User::ROLE_ID);

        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub Admin added successfully.');
    }

    /**
     * Display the subadmin record.
     *
     */
    public function show($subadmin)
    {
        $id = base64_decode($subadmin);
        $sub_admin = User::find($id);
        return view('admin.subAdmin.show', compact('sub_admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($subadmin)
    {
        $id = base64_decode($subadmin);
        $sub_admin = User::find($id);
        $role_list = Roles::NotAdminRole()->get();
        $department = Departments::where('is_active', 1)->get();
        return view('admin.subAdmin.edit', compact('sub_admin', 'role_list', 'department'));
    }

    /**
     * update subadmin record
     */
    public function update(UpdateSubAdminRequest $request, User $sub_admin)
    {
        $exceptFields = [
            '_token',
            '_method',
        ];
        $data = $request->all();
        $updateRecord = [
            'first_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => phoneFormat($data['phone']),
            'address' => $data['address'] ?? '',
            'department_id' => $data['department'],
            'role_type' => $data['role'],
        ];
        if ($request->hasfile('upload_file')) {
            $file = $request->file('upload_file');
            $fileName = $file->getClientOriginalName();
            $file->move(backendUserFile(), $file->getClientOriginalName());
            $updateRecord['profile_picture'] = url(backendUserFile() . $fileName);
        }
        if ($request->has('password') && $request->get('password', '') != '') {
            $updateRecord['password'] = \Hash::make($data['password']);
        }
        $this->userRepository->update($sub_admin->id, $updateRecord);
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub Admin updated successfully.');
    }

    /**
     * Removes the subadmin from database.
     */
    public function destroy(User $sub_admin)
    {
        $this->userRepository->delete($sub_admin->id);
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub Admin  was removed successfully!');
    }

}
