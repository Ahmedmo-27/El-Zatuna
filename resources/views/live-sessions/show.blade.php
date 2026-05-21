@extends('design_1.web.layouts.app')

@section('content')
    <section class="container mt-96 mb-104">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <div class="bg-white rounded-16 shadow-sm p-24">
                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-12 mb-16">
                        <div>
                            <h1 class="font-display font-28 font-weight-bold mb-2">{{ $session->title }}</h1>
                            <p class="text-gray-500 mb-0">Hosted by {{ $session->teacher->full_name ?? 'Teacher' }}</p>
                        </div>
                        <span class="badge badge-light">{{ ucfirst($session->status) }}</span>
                    </div>

                    <p class="mb-20">{{ $session->description }}</p>

                    <div class="row">
                        <div class="col-md-6 mb-12"><strong>Start:</strong> {{ optional($session->start_at)->format('M d, Y H:i') }}</div>
                        <div class="col-md-6 mb-12"><strong>End:</strong> {{ optional($session->end_at)->format('M d, Y H:i') }}</div>
                        <div class="col-md-6 mb-12"><strong>Price:</strong> {{ number_format((float) $session->price, 2) }}</div>
                        <div class="col-md-6 mb-12"><strong>Availability:</strong> {{ is_null($session->max_students) ? 'Unlimited' : max(($session->max_students ?? 0) - ($session->confirmed_bookings_count ?? 0), 0) . ' seats' }}</div>
                    </div>

                    @if($booking)
                        <div class="alert alert-success mt-20 mb-0">You have a confirmed booking for this session.</div>
                    @else
                        <div class="alert alert-warning mt-20 mb-0">You do not currently have a confirmed booking.</div>
                    @endif

                    <div class="mt-24 d-flex flex-wrap gap-12">
                        @if(auth()->check() && (auth()->user()->isAdmin() || auth()->id() === $session->creator_id || $booking))
                            <a href="{{ route('live_sessions.join', $session->id) }}" class="btn btn-primary">Join Session</a>
                        @endif

                        <a href="{{ route('live_sessions.index') }}" class="btn btn-outline-primary">Back to sessions</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection