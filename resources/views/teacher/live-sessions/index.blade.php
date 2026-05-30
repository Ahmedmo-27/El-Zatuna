@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>My Live Sessions</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('teacher.live_sessions.create') }}" class="btn btn-primary">Create Session</a>
            </div>
        </div>

        <div class="section-body">
            <div class="table-responsive bg-white rounded shadow-sm p-3">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>{{ $session->title }}</td>
                                <td>{{ ucfirst($session->status) }}</td>
                                <td>{{ optional($session->start_at)->format('M d, Y H:i') }}</td>
                                <td>{{ number_format((float) $session->price, 2) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('teacher.live_sessions.show', $session->id) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('teacher.live_sessions.edit', $session->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
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