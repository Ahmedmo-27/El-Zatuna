@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $session->title }}</h1>
            <div class="section-header-breadcrumb">
                <form method="POST" action="{{ route('admin.live_sessions.cancel', $session->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                </form>
            </div>
        </div>

        <div class="section-body">
            <div class="bg-white p-4 rounded shadow-sm mb-4">
                <p>{{ $session->description }}</p>
                <ul class="list-unstyled mb-0">
                    <li><strong>Teacher:</strong> {{ $session->teacher->full_name ?? $session->teacher->name ?? 'Teacher' }}</li>
                    <li><strong>Status:</strong> {{ ucfirst($session->status) }}</li>
                    <li><strong>Start:</strong> {{ optional($session->start_at)->format('M d, Y H:i') }}</li>
                    <li><strong>End:</strong> {{ optional($session->end_at)->format('M d, Y H:i') }}</li>
                    <li><strong>Bookings:</strong> {{ $session->bookings->count() }}</li>
                </ul>
            </div>

            <div class="bg-white p-4 rounded shadow-sm">
                <h3 class="h5 mb-3">Bookings</h3>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Status</th>
                                <th>Booked At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($session->bookings as $booking)
                                <tr>
                                    <td>{{ $booking->student->full_name ?? $booking->student->name ?? 'Student' }}</td>
                                    <td>{{ ucfirst($booking->status) }}</td>
                                    <td>{{ optional($booking->created_at)->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No bookings yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection