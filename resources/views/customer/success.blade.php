@extends('customer.layout')
@section('title', 'Subscription Confirmed')
@section('content')
<div class="card" style="max-width:650px;margin:8vh auto;text-align:center">
    <div class="check">✓</div><div class="eyebrow">Payment successful</div><h1>Subscription confirmed</h1>
    <p class="muted">Your {{ $plan->plan_name }} subscription has been added successfully.</p>
    @if($subscription)
        <div style="text-align:left;margin:25px 0"><div class="summary-row"><span>Starts</span><strong>{{ \Carbon\Carbon::parse($subscription['start_date'])->format('d M Y') }}</strong></div><div class="summary-row"><span>Valid through</span><strong>{{ \Carbon\Carbon::parse($subscription['end_date'])->format('d M Y') }}</strong></div></div>
    @endif
    <a class="btn" href="{{ route('customer.registration.create') }}">Done</a>
</div>
@endsection
