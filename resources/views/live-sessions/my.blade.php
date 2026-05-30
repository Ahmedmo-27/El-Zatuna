@extends('design_1.web.layouts.app')

@section('content')
    <section class="container mt-96 mb-104">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-16 mb-24">
            <div>
                <h1 class="font-display font-28 font-weight-bold mb-2">My Sessions</h1>
                <p class="text-gray-500 mb-0">Sessions you have paid for.</p>
            </div>
            <a href="{{ route('live_sessions.index') }}" class="btn btn-outline-primary">Browse sessions</a>
        </div>

        <div class="row">
            @forelse($bookings as $booking)
                <div class="col-12 col-lg-4 mb-24">
                    <div class="bg-white rounded-16 shadow-sm p-20 h-100 d-flex flex-column">
                        <span class="badge badge-success mb-12">{{ ucfirst($booking->status) }}</span>
                        <h2 class="font-20 font-weight-bold mb-8">{{ $booking->liveSession->title ?? 'Live Session' }}</h2>
                        <div class="font-14 mb-8"><strong>Start:</strong> {{ optional(optional($booking->liveSession)->start_at)->format('M d, Y H:i') }}</div>
                        <div class="font-14 mb-16"><strong>Booking ID:</strong> {{ $booking->id }}</div>
                        <div class="mt-auto">
                            <a href="{{ route('live_sessions.show', $booking->live_session_id) }}" class="btn btn-outline-primary btn-sm">Open</a>
                            <a href="{{ route('live_sessions.join', $booking->live_session_id) }}" class="btn btn-primary btn-sm">Join</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light">You have not booked any live sessions yet.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-24">
            {{ $bookings->links() }}
        </div>
    </section>
@endsection