<?php

namespace App\Services;

use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionRenewalService
{
    /**
     * Add a paid plan after the customer's current (or already queued) entitlement.
     */
    public function renew(int $customerId, Plan $plan): object
    {
        return DB::transaction(function () use ($customerId, $plan) {
            $today = Carbon::today();

            // Lock all entitlements while calculating the next available date. Looking at
            // the furthest end date also makes repeated advance renewals line up correctly.
            $latestSubscription = DB::table('subscription_master')
                ->where('customer_id', $customerId)
                ->where('isDelete', 0)
                ->lockForUpdate()
                ->orderByDesc('end_date')
                ->orderByDesc('subscription_id')
                ->first();

            $days = max(1, (int) $plan->days);
            [$startDate, $endDate] = $this->calculatePeriod(
                $latestSubscription->end_date ?? null,
                $days,
                $today
            );
            $startsToday = $startDate->isSameDay($today);

            if ($startsToday) {
                DB::table('subscription_master')
                    ->where('customer_id', $customerId)
                    ->where('isActive', 1)
                    ->update(['isActive' => 0]);
            }

            $subscriptionId = DB::table('subscription_master')->insertGetId([
                'customer_id' => $customerId,
                'plan_id' => $plan->plan_id,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'days' => $days,
                'amount' => $plan->plan_amount,
                'isActive' => $startsToday ? 1 : 0,
                'iStatus' => 1,
                'isDelete' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return (object) [
                'subscription_id' => $subscriptionId,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ];
        });
    }

    /** @return array{0: Carbon, 1: Carbon} */
    public function calculatePeriod(?string $latestEndDate, int $days, ?Carbon $today = null): array
    {
        $today = ($today ?: Carbon::today())->copy()->startOfDay();
        $startDate = $today->copy();

        if ($latestEndDate) {
            $currentEndDate = Carbon::parse($latestEndDate)->startOfDay();
            if ($currentEndDate->gte($today)) {
                $startDate = $currentEndDate->addDay();
            }
        }

        return [$startDate, $startDate->copy()->addDays(max(1, $days) - 1)];
    }
}
