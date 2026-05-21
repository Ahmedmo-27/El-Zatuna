@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Live Sessions</h1>
        </div>

        <div class="section-body">
            <div class="table-responsive bg-white rounded shadow-sm p-3">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Teacher</th>
                            <th>Status</th>
                            <th>Bookings</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>{{ $session->title }}</td>
                                <td>{{ $session->teacher->full_name ?? $session->teacher->name ?? 'Teacher' }}</td>
                                <td>{{ ucfirst($session->status) }}</td>
                                <td>{{ $session->bookings->count() }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.live_sessions.show', $session->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No live sessions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $sessions->links() }}</div>
        </div>
    </section>
@endsection