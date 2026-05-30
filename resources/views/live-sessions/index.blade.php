@extends('design_1.web.layouts.app')

@section('content')
    <section class="container mt-96 mb-104">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-16 mb-24">
            <div>
                <h1 class="font-display font-28 font-weight-bold mb-2">Live Sessions</h1>
                <p class="text-gray-500 mb-0">Browse upcoming booked sessions.</p>
            </div>
            <a href="{{ route('live_sessions.me') }}" class="btn btn-primary">My Sessions</a>
        </div>

        <form method="GET" action="{{ route('live_sessions.index') }}" class="bg-white rounded-16 shadow-sm p-16 mb-24 d-flex flex-wrap gap-12 align-items-end">
            <div>
                <label class="d-block font-14 mb-2">University ID</label>
                <input type="text" name="university_id" value="{{ request('university_id') }}" class="form-control" placeholder="Filter by university">
            </div>
            <div>
                <label class="d-block font-14 mb-2">Faculty ID</label>
                <input type="text" name="faculty_id" value="{{ request('faculty_id') }}" class="form-control" placeholder="Filter by faculty">
            </div>
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="upcoming" value="1" id="upcomingOnly" {{ request('upcoming') ? 'checked' : '' }}>
                <label class="form-check-label" for="upcomingOnly">Upcoming only</label>
            </div>
            <button type="submit" class="btn btn-outline-primary">Filter</button>
        </form>

        <div class="row">
            @forelse($sessions as $session)
                <div class="col-12 col-lg-4 mb-24">
                    <div class="bg-white rounded-16 shadow-sm p-20 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-12">
                            <span class="badge badge-light">{{ ucfirst($session->status) }}</span>
                            <span class="font-14 text-gray-500">{{ $session->teacher->full_name ?? 'Teacher' }}</span>
                        </div>

                        <h2 class="font-20 font-weight-bold mb-12">{{ $session->title }}</h2>
                        <p class="text-gray-500 font-14 mb-12">{{ \Illuminate\Support\Str::limit($session->description, 120) }}</p>

                        <div class="font-14 mb-8"><strong>Start:</strong> {{ optional($session->start_at)->format('M d, Y H:i') }}</div>
                        <div class="font-14 mb-8"><strong>End:</strong> {{ optional($session->end_at)->format('M d, Y H:i') }}</div>
                        <div class="font-14 mb-8"><strong>Price:</strong> {{ number_format((float) $session->price, 2) }}</div>
                        <div class="font-14 mb-16">
                            <strong>Seats:</strong>
                            @if(is_null($session->max_students))
                                Unlimited
                            @else
                                {{ max(($session->max_students ?? 0) - ($session->confirmed_bookings_count ?? 0), 0) }} available
                            @endif
                        </div>

                        <div class="mt-auto">
                            <a href="{{ route('live_sessions.show', $session->id) }}" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light">No live sessions found.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-24">
            {{ $sessions->withQueryString()->links() }}
        </div>
    </section>
@endsection
