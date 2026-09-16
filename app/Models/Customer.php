<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Http;

class Customer extends Authenticatable implements JWTSubject
{

    use Notifiable, CanResetPasswordTrait;

    protected $table = 'customer_master';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_id', 'customer_name', 'customer_mobile', 'customer_email', 'password_otp','password_otp_expires_at','password','address_line_1','address_line_2','city','state','pincode', 'profile_image', 'login_count', 'magazine_count', 'free_article', 'article_count'
    ];

    protected $hidden = ['password'];


    public $timestamps = true;
    
    protected static $smsUrl = 'https://sms.profuseservices.com/sendsms.jsp';

    protected static $smsUser = 'sadhana';
    
    protected static $smsPassword = 'bd1a833560XX';
    
    protected static $smsSenderId = 'SPTRSS';
    
    protected static $smsTemplateId = '1777178945961395883';

    
    
   public static function sendSms($mobile, $message)
   {
    try {

        // Only digits
        $mobile = preg_replace('/\D/', '', $mobile);

        // If number already has 91, remove it first
        if (strlen($mobile) == 12 && substr($mobile, 0, 2) == '91') {
            $mobile = substr($mobile, 2);
        }

        // Must be 10 digit mobile
        if (strlen($mobile) != 10) {
            return [
                'success' => false,
                'response' => 'Invalid mobile number',
            ];
        }

        /*
         * IMPORTANT:
         * Build URL exactly like your working testmsg()
         */
        $url = 'https://sms.profuseservices.com/sendsms.jsp'
            . '?user=' . urlencode(self::$smsUser)
            . '&password=' . urlencode(self::$smsPassword)
            . '&senderid=' . urlencode(self::$smsSenderId)
            . '&mobiles=%2B91' . $mobile
            . '&sms=' . urlencode($message)
            . '&tempid=' . urlencode(self::$smsTemplateId);

        $response = Http::timeout(30)->get($url);

        $body = trim($response->body());

        \Log::info('SMS Gateway Response', [
            'http_status' => $response->status(),
            'mobile'      => $mobile,
            'body'        => $body,
        ]);

        // Gateway returns HTTP 200 even on SMS failure
        if (!$response->successful()) {
            return [
                'success' => false,
                'response' => $body,
            ];
        }

        // Parse gateway XML response
        $xml = @simplexml_load_string($body);

        if (!$xml) {
            return [
                'success' => false,
                'response' => $body,
            ];
        }

        $status = strtolower(trim((string) $xml->sms->status));
        $code   = trim((string) $xml->sms->code);
        $reason = trim((string) $xml->sms->reason);

        if ($status === 'error') {
            return [
                'success' => false,
                'response' => $reason,
                'code' => $code,
                'raw_response' => $body,
            ];
        }

        return [
            'success' => true,
            'response' => $body,
            'message_id' => (string) $xml->sms->messageid,
        ];

    } catch (\Throwable $e) {

        \Log::error('SMS Sending Error', [
            'message' => $e->getMessage(),
        ]);

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

    