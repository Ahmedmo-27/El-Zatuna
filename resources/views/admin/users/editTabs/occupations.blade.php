<div class="tab-pane mt-3 fade" id="occupations" role="tabpanel" aria-labelledby="occupations-tab">
    <div class="row">
        <div class="col-12 col-md-8">
            <form action="{{ getAdminPanelUrl() }}/users/{{ $user->id .'/occupationsUpdate' }}" method="Post">
                {{ csrf_field() }}

                @php
                    $occupationsInitial = [];
                    if (!empty($occupations) && !empty($categories)) {
                        foreach ($categories as $cat) {
                            if (!empty($cat->subCategories) && count($cat->subCategories)) {
                                foreach ($cat->subCategories as $sub) {
                                    if (in_array($sub->id, $occupations)) {
                                        $occupationsInitial[] = ['id' => $sub->id, 'text' => $sub->title];
                                    }
                                }
                            } else {
                                if (in_array($cat->id, $occupations)) {
                                    $occupationsInitial[] = ['id' => $cat->id, 'text' => $cat->title];
                                }
                            }
                        }
                    }
                @endphp

                @include('design_1.web.includes.occupations_input')

                <div class="mt-4">
                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath('become_instructor_wizard') }}"></script>
@endpush
