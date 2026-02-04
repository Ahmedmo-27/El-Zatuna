@extends('design_1.web.layouts.app')


@push('styles_top')
    {{-- Prerequisite Styles --}}
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="/assets/design_1/landing_builder/front.min.css">
@endpush


@section('content')

    @if(!empty($landingItem))
        @foreach($landingItem->components as $component)
            @includeIf("landingBuilder.front.components.{$component->landingBuilderComponent->name}.index", ['landingComponent' => $component])
        @endforeach
    @else
        <section class="py-40">
            <div class="container">
                <div class="text-center">
                    <h1 class="font-32 font-weight-bold">Welcome to LMS</h1>
                    <p class="text-gray-500 mt-16">Your landing page is not configured yet. Add components in the admin panel to build the home page.</p>
                </div>
            </div>
        </section>
    @endif

@endsection

@push('scripts_bottom')
    {{-- Prerequisite Scripts --}}
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    {{-- Jquery --}}
    <script src="/assets/vendors/typed/typedjs.js"></script>

    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
    <script src="{{ getDesign1ScriptPath("video_player_helpers") }}"></script>
    <script src="/assets/design_1/landing_builder/js/front.min.js"></script>

@endpush
