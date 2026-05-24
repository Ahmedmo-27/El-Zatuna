@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Refunds</h1>
        </div>

        <div class="section-body">
            <form method="GET" class="bg-white p-3 rounded shadow-sm mb-3">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Sale ID</label>
                        <input type="number" name="sale_id" value="{{ request('sale_id') }}" class="form-control">
                    </div>
                    <div class="form-group col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive bg-white rounded shadow-sm p-3">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sale</th>
                            <th>Status</th>
                            <th>Gateway</th>
                            <th>Error</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refunds as $refund)
                            <tr>
                                <td>{{ $refund->id }}</td>
                                <td>{{ $refund->sale_id }}</td>
                                <td>{{ $refund->status }}</td>
                                <td>{{ $refund->gateway_name }}</td>
                                <td>{{ $refund->last_error_message ?? '-' }}</td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('admin.refunds.retry', $refund->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Retry</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No refunds found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $refunds->appends(request()->query())->links() }}</div>
        </div>
    </section>
@endsection