<?php

namespace Tests\Unit;

use App\Services\SubscriptionRenewalService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class SubscriptionRenewalServiceTest extends TestCase
{
    /** @dataProvider subscriptionPeriods */
    public function testItCalculatesContinuousSubscriptionPeriods(
        ?string $existingEndDate,
        int $days,
        string $expectedStart,
        string $expectedEnd
    ): void {
        [$start, $end] = (new SubscriptionRenewalService())->calculatePeriod(
            $existingEndDate,
            $days,
            Carbon::parse('2026-10-02')
        );

        $this->assertSame($expectedStart, $start->toDateString());
        $this->assertSame($expectedEnd, $end->toDateString());
    }

    public function subscriptionPeriods(): array
    {
        return [
            'new or expired subscription starts today' => ['2026-10-01', 30, '2026-10-02', '2026-10-31'],
            'active subscription continues after expiry' => ['2026-10-20', 30, '2026-10-21', '2026-11-19'],
            'queued subscription continues after its final date' => ['2027-01-15', 365, '2027-01-16', '2028-01-15'],
        ];
    }
}
