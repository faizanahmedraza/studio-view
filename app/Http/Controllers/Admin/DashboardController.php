<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    /**
     * Admin Dashboard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::where(['is_verified' => 1])->where('role_id', '=', 1)->get()->count();
        $newRequest = User::where(['is_verified' => 0])->get()->count();
        return view('admin.dashboard.index', compact('users', 'newRequest'));

    }
}
