<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;

class Customer extends Authenticatable implements JWTSubject
{

    use Notifiable, CanResetPasswordTrait;


    protected $table = 'customer_master';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_id', 'customer_name', 'customer_mobile', 'customer_email', 'password','address_line_1','address_line_2','city','state','pincode', 'profile_image', 'login_count', 'magazine_count', 'free_article', 'article_count'
    ];

   protected $hidden = ['password'];


    public $timestamps = true;
    
    protected static $smsUrl = 'http://sms.profuseservices.com/sendsms.jsp';

    protected static $smsUser = 'sadhana';

    protected static $smsPassword = 'bd1a833560XX';

    protected static $smsSenderId = 'SPTRSS';

    protected static $smsTemplateId = '1777178945961395883';

    protected static $smsEntityId = '1701175817056971341';
    
    public static function sendSms($mobile, $message)
    {
        try {

            // Remove + from mobile number
            $mobile = str_replace('+', '', $mobile);

            $response = Http::timeout(30)->get(
                self::$smsUrl,
                [
                    'user'     => self::$smsUser,
                    'password' => self::$smsPassword,
                    'senderid' => self::$smsSenderId,
                    'mobiles'  => $mobile,
                    'sms'      => $message,
                ]
            );

            if ($response->successful()) {

                return [
                    'success' => true,
                    'response' => $response->body(),
                ];
            }

            return [
                'success' => false,
                'response' => $response->body(),
            ];

        } catch (\Throwable $e) {

            return [
                'success' => false,
                'response' => $e->getMessage(),
            ];
        }
    }


    
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'customer_id', 'customer_id')
                    ->where('isDelete', 0)
                    ->latestOfMany('subscription_id'); // gets the latest subscription if multiple
    }

    

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
     public function getEmailForPasswordReset()
    {
        return $this->customer_email; // ✅ change this to your column name
    }

   public function loginLogs()
    {
        return $this->hasMany(CustomerLoginLog::class, 'customer_id', 'customer_id');
    }

    public function lastLogin()
    {
        return $this->hasOne(CustomerLoginLog::class, 'customer_id', 'customer_id')
            ->latestOfMany('login_date_time');
    }

    public function magazineLogs()
    {
        return $this->hasMany(CustomerMagazineLog::class, 'customer_id', 'customer_id');
    }



}

    