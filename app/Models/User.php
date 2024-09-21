<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use  HasRoles, HasPermissions, HasFactory, Notifiable, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public const SUSPENDED = 'suspended';
    public const ACTIVE = 'active';

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = (empty($value) || strlen($value) > 58) ? $value : bcrypt($value);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }



    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getRouteKey()
    {
        return $this->uuid;
    }

    public static function findByUuid($uuid)
    {
        return User::where('uuid', $uuid)->first();
    }


    function scopeUi($query)
    {
        $query->where('type', 'ui');
    }


    public function scopeMine($query)
    {
        $customer = customer();

        $child_customers = Customer::select('id')->where('parent_customer_id',$customer->id)->get()->pluck('id')->toArray();

        array_push($child_customers,$customer->id);

        $query->whereIn('customer_id', $child_customers);

    }


    public function getEmailForPasswordReset()
    {
        return $this->username;
    }
    public function routeNotificationForMail($notification)
    {
        return $this->username;
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this->username));
        //$url = 'https://example.com/reset-password?token='.$token;
       // $this->notify(new ResetPasswordNotification($url));
    }

}
