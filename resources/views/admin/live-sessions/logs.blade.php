@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Live Session Activity Logs</h1>
        </div>

        <div class="section-body">
            <form method="GET" class="bg-white p-3 rounded shadow-sm mb-3">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Session ID</label>
                        <input type="number" name="live_session_id" value="{{ request('live_session_id') }}" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>User</label>
                        <select name="user_id" class="form-control">
                            <option value="">All users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>{{ $user->full_name ?? $user->name ?? $user->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Action</label>
                        <input type="text" name="action" value="{{ request('action') }}" class="form-control" placeholder="session_published">
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive bg-white rounded shadow-sm p-3">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Session</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ optional($log->created_at)->format('Y-m-d H:i') }}</td>
                                <td>{{ $log->liveSession->title ?? $log->live_session_id }}</td>
                                <td>{{ $log->user->full_name ?? $log->user->name ?? $log->user_id ?? '-' }}</td>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No logs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $logs->appends(request()->query())->links() }}</div>
        </div>
    </section>
@endsection