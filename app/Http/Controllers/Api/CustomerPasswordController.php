<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Customer;

class CustomerPasswordController extends Controller
{
    private string $emailColumn = 'customer_email';

    // Temp password expiry (optional)
    private int $tempExpireMinutes = 60;
    
    // public function forgot(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => 'required',
    //     ]);

    //     $email = $request->email;
    
    //     $customer = Customer::where('customer_mobile', $request->mobile)->first();
    //     if (!$customer) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'mobile not found.',
    //         ], 404);
    //     }

    //     // Generate 6 digit OTP
    //     $otp = rand(100000, 999999);
    
    //     // Save OTP
    //     $customer->password_otp = $otp;
    //     $customer->password_otp_expires_at = now()->addMinutes(10);
    
    //     $customer->save();
    
    //     // Get customer mobile number
    //     $mobile = $customer->customer_mobile;
    
    //     if (!$mobile) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Mobile number not found.',
    //         ], 400);
    //     }

    //     // SMS message
    //     $sms = "Sadhna Weekly App User, OTP to change password for your Sadhna Weekly app is {$otp} do not share it with any other user. SADHANA PRAKASHAN TRUST";
    
    //     // Send SMS
    //     $smsResponse = Customer::sendSms($mobile, $sms);
    
    //     if (!$smsResponse['success']) {
    
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'SMS sending failed.',
    //             'error'   => $smsResponse['response'],
    //         ], 500);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'OTP sent successfully to your registered mobile number.',
    //     ]);
    // }
    
    public function verifyOtp(Request $request)
    {
    $request->validate([
        'mobile' => 'required',
        'otp'    => 'required|digits:6',
    ]);

    $customer = Customer::where('customer_mobile', $request->mobile)->first();

    if (!$customer) {
        return response()->json([
            'success' => false,
            'message' => 'Mobile number not found.',
        ], 404);
    }

    if (!$customer->password_otp) {
        return response()->json([
            'success' => false,
            'message' => 'OTP not found. Please request a new OTP.',
        ], 400);
    }

    // Check OTP
    if ((string) $customer->password_otp !== (string) $request->otp) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP.',
        ], 400);
    }

    // Check OTP expiry
    if (
        !$customer->password_otp_expires_at ||
        now()->greaterThan($customer->password_otp_expires_at)
    ) {
        return response()->json([
            'success' => false,
            'message' => 'OTP has expired. Please request a new OTP.',
        ], 400);
    }

    return response()->json([
        'success' => true,
        'message' => 'OTP verified successfully.',
        'mobile'  => $customer->customer_mobile,
    ]);
}


    public function resetPassword(Request $request)
    {
        $request->validate([
            'mobile'           => 'required',
            'otp'              => 'required|digits:6',
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);
    
        $customer = Customer::where('customer_mobile', $request->mobile)->first();
    
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number not found.',
            ], 404);
        }
    
        // OTP check again for security
        if (
            !$customer->password_otp ||
            (string) $customer->password_otp !== (string) $request->otp
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP.',
            ], 400);
        }
    
        // Expiry check
        if (
            !$customer->password_otp_expires_at ||
            now()->greaterThan($customer->password_otp_expires_at)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new OTP.',
            ], 400);
        }
    
        // Update password
        $customer->password = Hash::make($request->new_password);
    
        // OTP clear after successful reset
        $customer->password_otp = null;
        $customer->password_otp_expires_at = null;
    
        $customer->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        $customer = Customer::where($this->emailColumn, $email)->first();
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found.',
            ], 404);
        }

        // ✅ Generate a temporary password
        $tempPassword = $this->generateTempPassword();
        // $tempPassword = $this->generateTempPassword(10);

        // ✅ Update password in DB (hashed)
        $customer->password = Hash::make($tempPassword);

        // Optional fields (recommended)
        // Add these columns via migration (shown below) if you want:
        $customer->must_reset_password = 1;
        $customer->temp_password_set_at = now();

        $customer->save();

        // ✅ Email data
        $data = [
            'customer'     => $customer,
            'tempPassword' => $tempPassword,
            'minutes'      => $this->tempExpireMinutes,
        ];

        $msg = [
            'FromMail' => config('mail.from.address'),
            'Title'    => config('mail.from.name'),
            'ToEmail'  => $email,
            'Subject'  => 'Your Temporary Password',
        ];

        try {
            Mail::send('emails.customer_reset_password', $data, function ($message) use ($msg) {
                $message->from($msg['FromMail'], $msg['Title']);
                $message->to($msg['ToEmail'])->subject($msg['Subject']);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mail failed',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Temporary password sent successfully.',
        ]);
    }

    /**
     * ✅ Reset password using temp password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email'         => 'required|email',
            'temp_password' => 'required|string',
            'password'      => 'required|string|min:6|confirmed',
        ]);

        $email = $request->email;

        $customer = Customer::where($this->emailColumn, $email)->first();
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        // Optional expiry check (recommended)
        if (!empty($customer->temp_password_set_at)) {
            $created = Carbon::parse($customer->temp_password_set_at);
            if ($created->diffInMinutes(now()) > $this->tempExpireMinutes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Temporary password expired. Please request again.',
                ], 422);
            }
        }

        // ✅ Verify temp password
        if (!Hash::check($request->temp_password, $customer->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid temporary password.',
            ], 422);
        }

        // ✅ Set new password
        $customer->password = Hash::make($request->password);

        // Optional fields reset
        $customer->must_reset_password = 0;
        $customer->temp_password_set_at = null;

        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Strong-ish temp password generator
     */
 
    private function generateTempPassword(): string
    {
        return (string) random_int(100000, 999999);
    }


    public function changePassword(Request $request)
    {
        $customer = auth()->guard('api')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorised'
            ], 401);
        }

        $request->validate([
            'current_password' => 'required|string|min:6',
            'new_password'     => 'required|string|min:6|different:current_password|confirmed',
            // requires: new_password_confirmation
        ]);

        // ✅ Check current password
        if (!Hash::check($request->current_password, $customer->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // ✅ Update password
        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password changed successfully'
        ]);
    }

}
