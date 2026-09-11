@extends('layouts.app')

@section('title', 'Active Customer Report')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h5>Active Customers by Magazine Publish Date</h5>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.activeCustomersByPublishDate') }}" class="row g-3 align-items-end mb-4">
                        <div class="col-md-4">
                            <label for="publish_date" class="form-label">Magazine Publish Date</label>
                            <input type="date" id="publish_date" name="publish_date"
                                   value="{{ old('publish_date', $publishDate) }}"
                                   class="form-control @error('publish_date') is-invalid @enderror" required>
                            @error('publish_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="q" class="form-label">Customer Search (optional)</label>
                            <input type="text" id="q" name="q" value="{{ old('q', $q) }}" class="form-control"
                                   placeholder="Name / Mobile / Email">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('admin.reports.activeCustomersByPublishDate') }}" class="btn btn-secondary mx-2">Reset</a>
                        </div>
                        @if($publishDate)
                                <a href="{{ route('admin.reports.activeCustomersByPublishDate.export', ['publish_date' => $publishDate, 'q' => $q]) }}"
                                   class="btn btn-success">
                                    <i class="fa fa-file-excel"></i> Export to Excel
                                </a>
                            @endif
                    </form>

                    @if(!$publishDate)
                        <div class="alert alert-info mb-0">
                            Select a magazine publish date to see customers whose subscription was active on that date.
                        </div>
                    @else
                        @if($magazines->isEmpty())
                            <div class="alert alert-warning">
                                No published magazine was found for {{ \Carbon\Carbon::parse($publishDate)->format('d-m-Y') }}.
                            </div>
                        @else
                            <div class="alert alert-light border">
                                <strong>Magazine{{ $magazines->count() > 1 ? 's' : '' }}:</strong>
                                {{ $magazines->pluck('title')->join(', ') }}
                                <span class="text-muted">({{ \Carbon\Carbon::parse($publishDate)->format('d-m-Y') }})</span>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Plan</th>
                                        <th>Subscription Start</th>
                                        <th>Subscription End</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->customer_id }}</td>
                                            <td>{{ $customer->customer_name }}</td>
                                            <td>{{ $customer->customer_mobile }}</td>
                                            <td>{{ $customer->customer_email }}</td>
                                            <td>{{ $customer->plan_name ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($customer->start_date)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($customer->end_date)->format('d-m-Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No active customers found for this publish date.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $customers->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
