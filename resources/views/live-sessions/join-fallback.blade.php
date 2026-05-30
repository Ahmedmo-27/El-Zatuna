@extends('design_1.web.layouts.app')

@section('content')
    <section class="container mt-96 mb-104">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="bg-white rounded-16 shadow-sm p-24 text-center">
                    <h1 class="font-display font-28 font-weight-bold mb-12">Join unavailable</h1>
                    <p class="text-gray-500 mb-24">You cannot join this session yet. Make sure your booking is confirmed and the session is within the join window.</p>
                    <a href="{{ route('live_sessions.show', $session->id) }}" class="btn btn-primary">Back to session</a>
                </div>
            </div>
        </div>
    </section>
@endsection