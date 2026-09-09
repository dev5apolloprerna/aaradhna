<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActiveCustomersExport implements FromQuery, WithHeadings, WithMapping
{
    private ?string $search;

    public function __construct(?string $search = null)
    {
        $this->search = $search;
    }

    public function query(): Builder
    {
        return Customer::query()
            ->where('isDelete', 0)
            ->where('iStatus', 1)
            ->when($this->search, function (Builder $query, string $search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_mobile', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('customer_id');
    }

    public function headings(): array
    {
        return [
            'Customer ID',
            'Name',
            'Mobile',
            'Email',
            'Address Line 1',
            'Address Line 2',
            'City',
            'State',
            'Pincode',
            'Created At',
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
            optional($customer->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
