@extends('customer.layout')

@section('title', 'Customer Registration')
@section('content')
<div class="intro">
    <div class="eyebrow">Customer subscription</div>
    <h1>Register & choose your plan</h1>
    <p class="muted">Create your customer account and continue securely to payment. Already registered? Use the same email or mobile and your password to extend your subscription.</p>
</div>
<div class="card">
    @if($errors->any())
        <div class="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('customer.registration.store') }}">
        @csrf
        <h2 class="section-title">Personal details</h2>
        <div class="grid">
            <div><label for="customer_name">Full name *</label><input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required autocomplete="name"></div>
            <div><label for="customer_mobile">Mobile number *</label><input id="customer_mobile" name="customer_mobile" value="{{ old('customer_mobile') }}" required inputmode="numeric" maxlength="10" autocomplete="tel"></div>
            <div><label for="customer_email">Email address *</label><input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required autocomplete="email"></div>
            <div><label for="password">Password *</label><input type="password" id="password" name="password" required minlength="6" autocomplete="current-password"><small class="muted">Existing customers must enter their current password.</small></div>
            <div class="full"><label for="address_line_1">Address *</label><input id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}" required autocomplete="address-line1"></div>
            <div class="full"><label for="address_line_2">Address line 2</label><input id="address_line_2" name="address_line_2" value="{{ old('address_line_2') }}" autocomplete="address-line2"></div>
            <div><label for="state">State *</label><select id="state" name="state" required><option value="">Select state</option>@foreach($states as $state)<option value="{{ $state }}" @selected(old('state') === $state)>{{ $state }}</option>@endforeach</select></div>
            <div><label for="city">City *</label><input id="city" name="city" value="{{ old('city') }}" required autocomplete="address-level2"></div>
            <div><label for="pincode">Pincode *</label><input id="pincode" name="pincode" value="{{ old('pincode') }}" required inputmode="numeric" maxlength="10" autocomplete="postal-code"></div>
        </div>

        <h2 class="section-title" style="margin-top:34px">Choose a plan</h2>
        @if($plans->isEmpty())
            <div class="alert">No subscription plans are currently available.</div>
        @else
            <div class="plans">
                @foreach($plans as $plan)
                    <label class="plan">
                        <input type="radio" name="plan_id" value="{{ $plan->plan_id }}" @checked((string) old('plan_id', $loop->first ? $plan->plan_id : '') === (string) $plan->plan_id) required>
                        <span class="plan-box"><strong>{{ $plan->plan_name }}</strong><span class="price">₹{{ number_format($plan->plan_amount, 2) }}</span><span class="muted">{{ $plan->days }} days access</span></span>
                    </label>
                @endforeach
            </div>
            <button class="btn btn-block" style="margin-top:28px" type="submit">Continue to secure payment →</button>
        @endif
    </form>
</div>
@endsection
