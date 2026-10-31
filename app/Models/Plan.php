<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Plan extends Model
{
    protected $table = 'plan_master';
    protected $primaryKey = 'plan_id';

    protected $fillable = [
        'plan_name',
        'plan_amount',
        'days',
        'iStatus'
    ];

    public $timestamps = true;

    /** આજીવન = 15 વર્ષ */
    public const LIFELONG_YEARS = 15;

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id', 'plan_id');
    }

    /**
     * સંજીવની (14) / શુભેચ્છક (15) = આજીવન = 15 વર્ષ
     */
    public function isLifelong(): bool
    {
        return in_array((int) $this->plan_id, [14, 15], true);
    }

    /**
     * આજીવન end date = start થી 15 વર્ષ
     */
    public static function lifelongEndDate(?Carbon $from = null): string
    {
        $from = ($from ?: Carbon::today())->copy()->startOfDay();
        return $from->copy()->addYears(self::LIFELONG_YEARS)->subDay()->toDateString();
    }

    /**
     * આજીવન days count (approx 15 years)
     */
    public static function lifelongDays(?Carbon $from = null): int
    {
        $from = ($from ?: Carbon::today())->copy()->startOfDay();
        $end  = $from->copy()->addYears(self::LIFELONG_YEARS)->subDay();
        return $from->diffInDays($end) + 1;
    }
}
