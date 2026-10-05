@extends('customer.layout')

@section('title', 'Customer Registration')

@push('head')
<style>
    .input-wrap { position:relative; }
    .input-spinner { display:none; position:absolute; right:14px; top:50%; width:20px; height:20px; margin-top:-10px; border:3px solid #ead9dd; border-top-color:var(--brand); border-radius:50%; animation:lookup-spin .7s linear infinite; }
    .input-wrap.loading .input-spinner { display:block; }
    .input-wrap.loading input { padding-right:46px; }
    .autofill-target { transition:opacity .2s; }
    form.is-looking-up .autofill-target { opacity:.45; pointer-events:none; }
    .lookup-status.lookup-loading { color:var(--brand); font-weight:700; }
    @keyframes lookup-spin { to { transform:rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) { .input-spinner { animation-duration:1.6s; } }
</style>
@endpush

@section('content')
<div class="intro">
    <div class="eyebrow">Customer subscription</div>
    <h1>Register & choose your plan</h1>
    <p class="muted">Enter your mobile number first. If you are already registered, we will automatically fill in your saved details so you can choose a plan and continue.</p>
</div>
<div class="card">
    @if($errors->any())
        <div class="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('customer.registration.store') }}" id="registration-form">
        @csrf
        <h2 class="section-title">Personal details</h2>
        <div class="grid">
            <div>
                <label for="customer_mobile">Mobile number *</label>
                <div class="input-wrap" id="mobile-wrap">
                    <input id="customer_mobile" name="customer_mobile" value="{{ old('customer_mobile') }}" required inputmode="numeric" maxlength="10" autocomplete="tel">
                    <span class="input-spinner" aria-hidden="true"></span>
                </div>
                <small class="lookup-status muted" id="lookup-status" role="status" aria-live="polite">Saved details will be filled automatically for existing customers.</small>
            </div>
            <div class="autofill-target"><label for="customer_name">Full name *</label><input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required autocomplete="name"></div>

            <div class="autofill-target"><label for="customer_email">Email address *</label><input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required autocomplete="email"></div>
            <div class="autofill-target" id="password-wrap">
                <label for="password">Login password *</label>
                <input type="text" id="password" name="password" value="{{ old('password', '123456') }}" required minlength="6" autocomplete="new-password">
                <small class="lookup-status muted">Default password is 123456. You can change it if you want.</small>
            </div>

            <div class="full autofill-target"><label for="address_line_1">Address *</label><input id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}" required autocomplete="address-line1"></div>
            <div class="full autofill-target"><label for="address_line_2">Address line 2</label><input id="address_line_2" name="address_line_2" value="{{ old('address_line_2') }}" autocomplete="address-line2"></div>
            <div class="autofill-target"><label for="state">State *</label><select id="state" name="state" required><option value="">Select state</option>@foreach($states as $state)<option value="{{ $state }}" @selected(old('state') === $state)>{{ $state }}</option>@endforeach</select></div>
            <div class="autofill-target"><label for="city">City *</label><input id="city" name="city" value="{{ old('city') }}" required autocomplete="address-level2"></div>
            <div class="autofill-target"><label for="pincode">Pincode *</label><input id="pincode" name="pincode" value="{{ old('pincode') }}" required inputmode="numeric" maxlength="10" autocomplete="postal-code"></div>
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
            <button class="btn btn-block" id="submit-btn" style="margin-top:28px" type="submit">Continue to secure payment →</button>
        @endif
    </form>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const form = document.getElementById('registration-form');
        const mobileInput = document.getElementById('customer_mobile');
        const mobileWrap = document.getElementById('mobile-wrap');
        const status = document.getElementById('lookup-status');
        const submitBtn = document.getElementById('submit-btn');
        const passwordWrap = document.getElementById('password-wrap');
        const passwordInput = document.getElementById('password');
        const DEFAULT_PASSWORD = '123456';
        const fields = ['customer_name', 'customer_email', 'address_line_1', 'address_line_2', 'city', 'state', 'pincode'];
        let lookupController = null;
        let autofilled = false;

        // Existing customer: hide and disable password (not submitted). New customer: show it.
        const setPasswordVisible = (visible) => {
            passwordWrap.hidden = !visible;
            passwordInput.disabled = !visible;
            if (visible && !passwordInput.value) {
                passwordInput.value = DEFAULT_PASSWORD;
            }
        };

        const setStatus = (type, text) => {
            status.className = 'lookup-status ' + type;
            status.textContent = text;
        };

        const setLoading = (loading) => {
            mobileWrap.classList.toggle('loading', loading);
            form.classList.toggle('is-looking-up', loading);
            mobileInput.setAttribute('aria-busy', loading ? 'true' : 'false');
            if (submitBtn) submitBtn.disabled = loading;
        };

        const clearAutofilled = () => {
            if (!autofilled) return;
            fields.forEach((field) => { document.getElementById(field).value = ''; });
            autofilled = false;
        };

        const findCustomer = async (mobile) => {
            lookupController?.abort();
            const controller = new AbortController();
            lookupController = controller;

            setLoading(true);
            setStatus('lookup-loading', 'Checking for your saved details…');

            try {
                const response = await fetch(@json(route('customer.registration.existing-customer')), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: JSON.stringify({ customer_mobile: mobile }),
                    signal: controller.signal,
                });

                let result = {};
                try { result = await response.json(); } catch (e) { /* non-JSON response */ }

                if (response.status === 404) {
                    clearAutofilled();
                    setPasswordVisible(true);
                    setStatus('muted', result.message || 'No existing customer found. Please fill in your details.');
                    return;
                }

                if (!response.ok) {
                    throw new Error(result.message || Object.values(result.errors || {})[0]?.[0] || 'We could not check your details. Please try again.');
                }

                fields.forEach((field) => {
                    document.getElementById(field).value = result.customer[field] || '';
                });
                autofilled = true;
                setPasswordVisible(false);
                setStatus('lookup-success', result.message);
            } catch (error) {
                if (error.name === 'AbortError') return;
                setStatus('lookup-error', error.message);
            } finally {
                if (lookupController === controller) {
                    setLoading(false);
                    lookupController = null;
                }
            }
        };

        mobileInput.addEventListener('input', () => {
            const mobile = mobileInput.value.replace(/\D/g, '').slice(0, 10);
            mobileInput.value = mobile;

            if (mobile.length === 10) {
                findCustomer(mobile);
            } else {
                lookupController?.abort();
                lookupController = null;
                setLoading(false);
                clearAutofilled();
                setPasswordVisible(true);
                setStatus('muted', 'Enter a 10-digit mobile number to check for saved details.');
            }
        });

        if (/^\d{10}$/.test(mobileInput.value)) {
            findCustomer(mobileInput.value);
        }
    })();
</script>
@endpush