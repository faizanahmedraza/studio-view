<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;

/**
 * Admin Login Controller
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*
         * Logged in user should not be able to visit login page, unless logged out
         *
         * It's redirection is managed from App > Http > Middleware > RedirectIfAuthenticated
         */
        $this->middleware('guest:admin')->except('logout');
        parent::__construct();
    }

    /**
     * Set Guard value
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Login function
     */
    public function login(Request $request)
    {
        $user_check = User::where('email',$request->get('email'))->first();
        if ($user_check){
            if ($user_check->role_id == 2)//for admin
            {
                try {
                    $user_check->validateUserActiveCriteria();
                }
                catch (\App\Exceptions\UserNotAllowedToLogin $e) {
                        return redirect()
                            ->route('login')
                            ->withErrors($e->getMessage());
                }
            }
        }
        else
        {
            return redirect()
                ->route('login')
                ->withErrors('Email not found');
        }

        $this->validateLogin($request);
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * check credentials
     */
    protected function credentials(Request $request)
    {

        return [
            'email' => $request->{$this->username()},
            'password' => $request->password,
            'role_id' => 2,
            'is_active' => 1
        ];
    }

    /**
     * function all on logout
     */
    public function logout(Request $request)
    {
        /*
         * Here, admin guard is being called by $this->guard()
         */
        $this->guard()->logout();
        return redirect()->route('login');
    }

    /**
     * Handle forgot password
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function forgotPassword(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('admin.auth.forgot-password');
    }
}
