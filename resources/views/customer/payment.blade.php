@extends('customer.layout')
@section('title', 'Complete Payment')
@section('content')
<div class="intro"><div class="eyebrow">Almost complete</div><h1>Complete your payment</h1><p class="muted">Review your subscription and pay securely through Razorpay.</p></div>
<div class="card" style="max-width:680px;margin:auto">
    @if($errors->any())<div class="alert">{{ $errors->first() }}</div>@endif
    <h2 class="section-title">Order summary</h2>
    <div class="summary-row"><span>Customer</span><strong>{{ $customer->customer_name }}</strong></div>
    <div class="summary-row"><span>Plan</span><strong>{{ $plan->plan_name }}</strong></div>
    <div class="summary-row"><span>Access period</span><strong>{{ $plan->days }} days</strong></div>
    <div class="summary-row"><span>Total</span><strong class="price">₹{{ number_format($order->amount, 2) }}</strong></div>
    <p class="muted">If you already have remaining access, this plan will begin after your latest subscription expires, so no paid days are lost.</p>
    <button id="pay-button" class="btn btn-block" type="button">Pay ₹{{ number_format($order->amount, 2) }}</button>
    <form id="verification-form" method="POST" action="{{ route('customer.registration.verify') }}" hidden>@csrf<input name="razorpay_payment_id"><input name="razorpay_order_id"><input name="razorpay_signature"></form>
</div>
@endsection
@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
(function () {
    var button = document.getElementById('pay-button');
    button.addEventListener('click', function () {
        var checkout = new Razorpay({
            key: @json($razorpayKey), amount: {{ (int) round($order->amount * 100) }}, currency: 'INR',
            name: @json(config('app.name')), description: @json($plan->plan_name), order_id: @json($order->order_id),
            prefill: { name: @json($customer->customer_name), email: @json($customer->customer_email), contact: @json($customer->customer_mobile) },
            theme: { color: '#7b1f35' },
            handler: function (response) {
                var form = document.getElementById('verification-form');
                form.elements.razorpay_payment_id.value = response.razorpay_payment_id;
                form.elements.razorpay_order_id.value = response.razorpay_order_id;
                form.elements.razorpay_signature.value = response.razorpay_signature;
                button.disabled = true; button.textContent = 'Verifying payment…'; form.submit();
            }
        });
        checkout.open();
    });
}());
</script>
@endpush
