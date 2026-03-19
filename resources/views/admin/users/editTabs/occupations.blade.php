<div class="tab-pane mt-3 fade" id="occupations" role="tabpanel" aria-labelledby="occupations-tab">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-1">{{ trans('site.occupations') }}</h5>
                    <p class="text-muted small mb-4">{{ trans('admin/main.occupations_tab_hint') }}</p>

                    <form action="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/occupationsUpdate" method="post" class="js-occupations-form">
                        @csrf

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

                        @php $occupationsDescription = trans('admin/main.occupations_input_placeholder_hint'); @endphp
                        @include('design_1.web.includes.occupations_input')

                        <div class="form-group mt-4 mb-0">
                            <button type="submit" class="btn btn-primary">
                                {{ trans('admin/main.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath('become_instructor_wizard') }}"></script>
@endpush
