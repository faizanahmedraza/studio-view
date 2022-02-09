<?php

namespace App\Models;

use App\Models\Interfaces\UserInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements UserInterface, JWTSubject
{
    public $table = 'users';
    use SoftDeletes, Notifiable;

    // for admin
    public const ROLE_ID = '2';
    public const ROLE_TYPE = '1';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'role_id', 'role_type', 'first_name', 'last_name', 'email',
        'password', 'phone', 'email_verified', 'sms_verified', 'is_verified', 'department_id',
        'is_unblock', 'is_notification', 'is_active', 'address',
        'is_admin', 'device_type', 'device_token',
        'remember_token', 'profile_picture', 'email_verified_at','is_home','is_office'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAdmin($query)
    {
        $admin_role_id = config('app.super_admin_role_id');
        return $query->where('role_id', '!=', $admin_role_id);
    }

    /**
     * @User related methods
     */
    public function validateUserActiveCriteria(): bool
    {
        if ((int)$this->attributes['is_active'] === 0) {

            if ((int)$this->attributes['is_unblock'] === 0) {
                //throw new \Mockery\Exception('Your account has been blocked by the admin, please contact '. constants('global.site.name').' admin');
                throw new \App\Exceptions\UserNotAllowedToLogin('Your account has been blocked by the admin, please contact ', 'account_block');
            }
            // if ((int)$this->attributes['is_verified'] === 0) {
            //     //throw new \Mockery\Exception('Your account has not been verify by the admin, please contact '. constants('global.site.name').' admin');
            //     throw new \App\Exceptions\UserNotAllowedToLogin('Your account has not been verify by the admin, please contact ', 'account_verify');
            // }

            // if ((int)$this->attributes['email_verified'] !== 1) {
            //     //throw new \Mockery\Exception('Your email is not verified please verify your email first.');
            //     throw new \App\Exceptions\UserNotAllowedToLogin('Your email is not verified please verify your email first.', 'account_email_verify');
            // }

            // if ((int)$this->attributes['sms_verified'] !== 1) {
            //     //throw new \Mockery\Exception('Please verify your mobile number first, it\'s not verified.');
            //     throw new \App\Exceptions\UserNotAllowedToLogin('Please verify your mobile number first, it\'s not verified.', 'account_sms_verify');
            // }

            //throw new \Mockery\Exception('Your account is inactive, please contact '. constants('global.site.name').' admin');
            throw new \App\Exceptions\UserNotAllowedToLogin('Your account has been In-active by the admin, please contact.', 'account_active');
        }

        return true;

    }

    /**
     * Status Html
     */
    public function getStatusTextFormattedAttribute(): string
    {
        return (int)$this->attributes['status'] === 1 ?
            '<a href="' . route('sub-admin.inactive', $this->attributes['id']) . '"><span class="label label-success">Active</span></a>' :
            '<a href="' . route('sub-admin.active', $this->attributes['id']) . '"><span class="label label-warning">Inactive</span></a>';
    }

/*    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\Backend\AdminResetPasswordNotification($token));
    }*/
	    public function sendPasswordResetEmail($email,$token,$role_id)
    {
        $this->notify(new \App\Notifications\Backend\AdminResetPasswordNotification($email,$token,$role_id));
    }

    /**
     * Get current permissions user.
     *
     * @return array
     */
    public function getPermissions()
    {
        return $this->Permissions->pluck('route_name')->toArray();
    }

    /**
     * Get dashboard cards rights user.
     *
     * @return array
     */
    public function getDashboardCardsPermissionsArray()
    {
        $data = $this->Role->pluck('dashbaord_cards_rights');

    }

    /**
     * Get the role of user.
     *
     * @return array
     */
    public function Role()
    {
        return $this->belongsTo(Roles::class, 'role_type', 'id');
    }

    /**
     * Get the role of user.
     *
     * @return array
     */
    public function DashboardCardRightsArray()
    {
        $rights = false;
        $data = $this->Role()->pluck('dashbaord_cards_rights')->first();

        if ($this->role_type == Permissions::GetSuperAdminRole()) {
            return true;
        }
        if ($data != null && $data != '') {
            $rights = explode(',', $data);
        }
        return $rights;
    }

    /**
     * Get the role permissions .
     *
     * @return array
     */
    public function Permissions()
    {
        return $this->hasMany(Permissions::class, 'role_id', 'role_type');
    }

    /**
     * Get permissions data extracted .
     *
     * @return array
     */
    public function PermissionsExtract()
    {
        return $this->hasMany(Permissions::class, 'role_id', 'role_type')->pluck('route_name')->toArray();
    }

    public function sendWelcomeEmail()
    {
        $email = $this->attributes['email'];
        $name = $this->attributes['first_name'];
        $token = uniqid(Str::random(64), true);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        $resetUrl = url('/password/reset/' . $token );
        return $this->notify(new \App\Notifications\Backend\ResetPasswordEmailSend($resetUrl, $name));
    }

    public function isAdmin()
    {
        return (bool)(intval($this->attributes['role_id']) === self::ROLE_ATTENDANCE);
    }

    public function getFullname()
    {

        return $this->first_name . ' ' . $this->last_name;
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }


    public function deactivate()
    {
        $this->is_active = 0;
        $this->save();
    }

    public function activate()
    {
        $this->is_active = 1;
        $this->save();
    }

    public function unverified()
    {
        $this->is_verified = 0;
        $this->is_active = 0;
        $this->save();
    }

    public function verified()
    {
        $this->is_verified = 1;
        $this->is_active = 1;
        $this->save();
    }

    public function active()
    {
        $this->is_unblock = 1;
        $this->is_active = 1;
        $this->save();
    }

    public function block()
    {
        $this->is_unblock = 0;
        $this->is_active = 0;
        $this->save();
    }

    public function addDevice($deviceToken, $deviceType, $authToken)
    {
        UserDevice::whereDeviceToken($deviceToken)->delete();
        return $this->devices()->create([
            'device_token' => $deviceToken,
            'device_type' => $deviceType,
            'auth_token' => $authToken,
        ]);
    }

    public function updateDevice($authToken, $deviceToken, $deviceType)
    {
        $record = $this->devices()->whereAuthToken($authToken)->limit(1)->first();
        if ($record) {
            $record->device_token = $deviceToken;
            $record->device_type = $deviceType;
            $record->save();
        }
    }

    public function removeDevice($authToken)
    {
        $record = $this->devices()->whereAuthToken($authToken)->limit(1)->first();
        if ($record) {
            $record->delete();
        }
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }
    public function userWorkingDay()
    {
        return $this->hasOne(UserWorkingDay::class, 'user_id', 'id')->whereNull('deleted_at');
    }
    public function userApplyLeave()
    {
        return $this->hasMany(ApplyLeave::class, 'user_id', 'id')->whereNull('deleted_at');
    }

    public function userLocation()
    {
        return $this->hasMany(UserLocation::class, 'user_id', 'id')->whereNull('deleted_at');
    }
    public function userLocationHome()
    {
        return $this->hasOne(UserLocation::class, 'user_id', 'id')->whereNull('deleted_at')->where('type','home');
    }
    public function userLocationOffice()
    {
        return $this->hasOne(UserLocation::class, 'user_id', 'id')->whereNull('deleted_at')->where('type','office');
    }


}
