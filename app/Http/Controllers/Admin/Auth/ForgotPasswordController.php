<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\Admin\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
        parent::__construct();
    }

    /**
     * Set Password broker
     */
    public function broker()
    {
        return Password::broker('admins');
    }

    /**
     * Show forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('admin.auth.passwords.email');
    }



    /**
     * Show custom reset password form
     */
    public function send_reset_link_email(Request $request)
    {
        $user = User::where('email', $request->email)->where('role_id',$request->role_id)->first();
        if (!$user) {
            return redirect()
                ->route('password.request')->with('status',  'Email not exist. try again!.');
        }
        $token_validate = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('role_id', $request->role_id)
            ->first();
        $token = hash('ripemd160',uniqid(rand(),true));
        if ($token_validate == null) {
            DB::table('password_resets')
                ->insert(['email'=> $request->email,'role_id' =>  $request->role_id,'token' => $token]);
        }
        else
        {
            DB::table('password_resets')
                ->update(['token' => $token]);
        }

        $email = base64_encode ($request->email);
        $user->sendPasswordResetEmail($email,$token,$request->role_id);
        return redirect()
            ->route('password.request')->with('status',  'We have e-mailed your password reset link!. Please also check Junk/Spam folder as well.!');
    }
}
