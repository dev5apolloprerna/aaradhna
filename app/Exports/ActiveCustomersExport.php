<?php
 
 namespace App\Exports;
 
 use App\Models\Customer;
 use Illuminate\Database\Eloquent\Builder;
 use Illuminate\Support\Facades\DB;
 use Maatwebsite\Excel\Concerns\FromQuery;
 use Maatwebsite\Excel\Concerns\WithHeadings;
 use Maatwebsite\Excel\Concerns\WithMapping;
 
 class ActiveCustomersExport implements FromQuery, WithHeadings, WithMapping
 {
    private string $publishDate;

     private ?string $search;
 
    public function __construct(string $publishDate, ?string $search = null)
     {
        $this->publishDate = $publishDate;
         $this->search = $search;
     }
 
     public function query(): Builder
     {
        // Select only one qualifying subscription per customer. This prevents
        // renewals and overlapping subscriptions from creating duplicate rows.
        $qualifyingSubscriptions = DB::table('subscription_master')
            ->select('customer_id', DB::raw('MAX(subscription_id) as subscription_id'))
             ->where('isDelete', 0)
            ->whereDate('start_date', '<=', $this->publishDate)
            ->whereDate('end_date', '>=', $this->publishDate)
            ->groupBy('customer_id');

        return Customer::from('customer_master as cm')
            ->joinSub($qualifyingSubscriptions, 'qualifying_subscriptions', function ($join) {
                $join->on('qualifying_subscriptions.customer_id', '=', 'cm.customer_id');
            })
            ->join('subscription_master as sm', 'sm.subscription_id', '=', 'qualifying_subscriptions.subscription_id')
            ->leftJoin('plan_master as pm', 'pm.plan_id', '=', 'sm.plan_id')
            ->where('cm.isDelete', 0)
             ->when($this->search, function (Builder $query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('cm.customer_name', 'like', "%{$search}%")
                        ->orWhere('cm.customer_mobile', 'like', "%{$search}%")
                        ->orWhere('cm.customer_email', 'like', "%{$search}%");
                 });
             })
            ->select(
                'cm.customer_id',
                'cm.customer_name',
                'cm.customer_mobile',
                'cm.customer_email',
                'cm.address_line_1',
                'cm.address_line_2',
                'cm.city',
                'cm.state',
                'cm.pincode',
                'pm.plan_name',
                'sm.start_date',
                'sm.end_date'
            )
            ->orderBy('sm.end_date')
            ->orderBy('cm.customer_name');
     }
 
     public function headings(): array
     {
         return [
            'Customer ID', 'Name', 'Mobile', 'Email', 'Address Line 1',
            'Address Line 2', 'City', 'State', 'Pincode', 'Plan',
            'Subscription Start', 'Subscription End',
         ];
     }
 
     public function map($customer): array
     {
         return [
             $customer->customer_id,
             $customer->customer_name,
             $customer->customer_mobile,
             $customer->customer_email,
             $customer->address_line_1,
             $customer->address_line_2,
             $customer->city,
             $customer->state,
             $customer->pincode,
            $customer->plan_name,
            $customer->start_date,
            $customer->end_date,
         ];
     }
 }
