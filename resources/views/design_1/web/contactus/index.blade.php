@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("contactus") }}">
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
@endpush


@section('content')
    <div class="w-full max-w-[1700px] mx-auto px-12 md:px-24 lg:px-32 xl:px-44 mt-48 pb-28">
        <div class="bg-[#072923] p-10 md:p-16 lg:p-20 rounded-[48px] shadow-2xl">
            <div class="row">
                <div class="col-12 col-md-3">
                    @include('design_1.web.contactus.our_info')
                </div>

                <div class="col-12 col-md-5 mt-20 mt-md-0">
                    @include('design_1.web.contactus.form')
                </div>

                <div class="col-12 col-md-4 mt-20 mt-md-0">
                    @include('design_1.web.contactus.map')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var leafletApiPath = '{{ getLeafletApiPath() }}';
    </script>

    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>
    <script src="{{ getDesign1ScriptPath("leaflet_map") }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gsap !== 'undefined') {
            // Contact container animation
            gsap.from('.bg-\\[\\#072923\\].rounded-\\[32px\\]', {
                y: 60,
                opacity: 0,
                duration: 1,
                ease: 'power3.out'
            });
            
            // Info panel animation
            gsap.from('.col-12.col-md-3', {
                x: -50,
                opacity: 0,
                duration: 0.8,
                delay: 0.3,
                ease: 'power3.out'
            });
            
            // Form animation
            gsap.from('.col-12.col-md-5', {
                y: 40,
                opacity: 0,
                duration: 0.8,
                delay: 0.5,
                ease: 'power3.out'
            });
            
            // Map animation
            gsap.from('.col-12.col-md-4', {
                x: 50,
                opacity: 0,
                duration: 0.8,
                delay: 0.7,
                ease: 'power3.out'
            });
            
            // Form inputs stagger
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach((input, i) => {
                gsap.from(input, {
                    y: 20,
                    opacity: 0,
                    duration: 0.5,
                    delay: 0.8 + (i * 0.1),
                    ease: 'power3.out'
                });
            });
            
            // Button animation
            gsap.from('button[type="submit"]', {
                scale: 0.9,
                opacity: 0,
                duration: 0.6,
                delay: 1.2,
                ease: 'power3.out'
            });
        }
    });
    </script>
@endpush
