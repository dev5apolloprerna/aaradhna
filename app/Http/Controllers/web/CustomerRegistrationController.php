<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FreeArticle;
use App\Models\Plan;
use App\Models\RazorpayOrder;
use App\Services\SubscriptionRenewalService;
use App\Support\IndianStates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class CustomerRegistrationController extends Controller
{
    private const DEFAULT_PASSWORD = '123456';

    public function create()
    {
        return view('customer.registration', [
            'plans' => Plan::where('iStatus', 1)->orderBy('plan_amount')->get(),
            'states' => IndianStates::all(),
        ]);
    }

    public function existingCustomer(Request $request)
    {
        $data = $request->validate([
            'customer_mobile' => ['required', 'digits:10'],
        ]);

        $customer = Customer::where('customer_mobile', $data['customer_mobile'])
            ->where('isDelete', 0)
            ->first();

        if (!$customer) {
            return response()->json([
                'message' => 'No existing customer was found. Please complete the form to create an account.',
            ], 404);
        }

        if (!(int) $customer->iStatus) {
            return response()->json([
                'message' => 'This customer account is inactive. Please contact support.',
            ], 422);
        }

        return response()->json([
            'message' => 'Welcome back! Your details have been filled in. Choose a plan to continue.',
            'customer' => $customer->only([
                'customer_name',
                'customer_mobile',
                'customer_email',
                'address_line_1',
                'address_line_2',
                'city',
                'state',
                'pincode',
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_mobile' => ['required', 'digits:10'],
            'customer_email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', Rule::in(IndianStates::all())],
            'pincode' => ['required', 'digits_between:5,10'],
            'plan_id' => ['required', Rule::exists('plan_master', 'plan_id')->where('iStatus', 1)],
        ]);

        $emailCustomer = Customer::where('customer_email', $data['customer_email'])->where('isDelete', 0)->first();
        $mobileCustomer = Customer::where('customer_mobile', $data['customer_mobile'])->where('isDelete', 0)->first();

        if ($emailCustomer && $mobileCustomer && !$emailCustomer->is($mobileCustomer)) {
            return back()->withInput()->withErrors(['customer_email' => 'This email and mobile belong to different customer accounts.']);
        }

        $customer = $emailCustomer ?: $mobileCustomer;

        if (!$customer) {
            $freeArticle = FreeArticle::first();
            $customer = Customer::create([
                'customer_name' => $data['customer_name'],
                'customer_mobile' => $data['customer_mobile'],
                'customer_email' => $data['customer_email'],
                // New customers: use the entered password, or the default 123456 if left blank.
                'password' => Hash::make(($data['password'] ?? null) ?: self::DEFAULT_PASSWORD),
                'address_line_1' => $data['address_line_1'],
                'address_line_2' => $data['address_line_2'] ?? '',
                'city' => $data['city'],
                'state' => $data['state'],
                'pincode' => $data['pincode'],
                'free_article' => $freeArticle->free_article ?? 0,
                'iStatus' => 1,
                'isDelete' => 0,
            ]);
        }

        if (!(int) $customer->iStatus) {
            return back()->withInput()->withErrors(['customer_email' => 'This customer account is inactive. Please contact support.']);
        }

        $plan = Plan::where('iStatus', 1)->findOrFail($data['plan_id']);

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $receipt = 'web_' . $customer->customer_id . '_' . time();
            $razorpayOrder = $api->order->create([
                'receipt' => $receipt,
                'amount' => (int) round((float) $plan->plan_amount * 100),
                'currency' => 'INR',
            ]);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->withErrors(['payment' => 'Payment could not be started. Please try again shortly.']);
        }

        $order = RazorpayOrder::create([
            'customer_id' => $customer->customer_id,
            'plan_id' => $plan->plan_id,
            'order_id' => $razorpayOrder['id'],
            'receipt' => $receipt,
            'amount' => $plan->plan_amount,
            'currency' => 'INR',
            'status' => 'created',
        ]);

        $request->session()->put('customer_checkout_order', $order->id);

        return redirect()->route('customer.registration.payment');
    }

    public function payment(Request $request)
    {
        $order = $this->checkoutOrder($request);
        if (!$order || $order->status !== 'created') {
            return redirect()->route('customer.registration.create')->withErrors(['payment' => 'Your checkout session has expired. Please select a plan again.']);
        }

        return view('customer.payment', [
            'order' => $order,
            'customer' => Customer::findOrFail($order->customer_id),
            'plan' => Plan::findOrFail($order->plan_id),
            'razorpayKey' => config('services.razorpay.key'),
        ]);
    }

    public function verify(Request $request, SubscriptionRenewalService $renewalService)
    {
        $data = $request->validate([
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $order = $this->checkoutOrder($request);
        if (!$order || !hash_equals((string) $order->order_id, $data['razorpay_order_id'])) {
            return redirect()->route('customer.registration.create')->withErrors(['payment' => 'Invalid or expired payment session.']);
        }

        if ($order->status === 'paid') {
            return redirect()->route('customer.registration.success');
        }

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->utility->verifyPaymentSignature($data);
            $payment = $api->payment->fetch($data['razorpay_payment_id']);
            if (($payment['status'] ?? null) !== 'captured') {
                return redirect()->route('customer.registration.payment')->withErrors(['payment' => 'Payment has not been captured. Please try again.']);
            }
        } catch (SignatureVerificationError $exception) {
            $order->update(['status' => 'failed']);
            return redirect()->route('customer.registration.payment')->withErrors(['payment' => 'Payment verification failed. No subscription was added.']);
        } catch (\Throwable $exception) {
            report($exception);
            return redirect()->route('customer.registration.payment')->withErrors(['payment' => 'Unable to confirm payment right now. Please contact support if money was deducted.']);
        }

        $plan = Plan::findOrFail($order->plan_id);
        $subscription = DB::transaction(function () use ($order, $data, $plan, $renewalService) {
            $lockedOrder = RazorpayOrder::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($lockedOrder->status === 'paid') {
                return null;
            }

            $subscription = $renewalService->renew((int) $lockedOrder->customer_id, $plan);
            $lockedOrder->update([
                'payment_id' => $data['razorpay_payment_id'],
                'signature' => $data['razorpay_signature'],
                'status' => 'paid',
            ]);

            return $subscription;
        });

        if ($subscription) {
            $request->session()->put('customer_subscription_result', (array) $subscription);
        }

        return redirect()->route('customer.registration.success');
    }

    public function success(Request $request)
    {
        $order = $this->checkoutOrder($request);
        if (!$order || $order->status !== 'paid') {
            return redirect()->route('customer.registration.create');
        }

        return view('customer.success', [
            'plan' => Plan::findOrFail($order->plan_id),
            'subscription' => $request->session()->get('customer_subscription_result'),
        ]);
    }

    private function checkoutOrder(Request $request): ?RazorpayOrder
    {
        $orderId = $request->session()->get('customer_checkout_order');

        return $orderId ? RazorpayOrder::find($orderId) : null;
    }
}