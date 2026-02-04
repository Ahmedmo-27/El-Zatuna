@extends('design_1.panel.layouts.panel')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="font-14 text-gray-600">{{ trans('update.amount_ready_to_payout', ['amount' => handlePrice($payoutBalance)]) }}</div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('public.course') }}</th>
                                        <th>{{ trans('update.sales_count') }}</th>
                                        <th>{{ trans('update.gross_revenue') }}</th>
                                        <th>{{ trans('update.platform_fee') }}</th>
                                        <th>{{ trans('update.tutor_earnings') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($revenues as $revenue)
                                        <tr>
                                            <td>{{ $revenue->course->title ?? '-' }}</td>
                                            <td>{{ $revenue->sales_count }}</td>
                                            <td>{{ handlePrice($revenue->gross_amount) }}</td>
                                            <td>{{ handlePrice($revenue->platform_fee) }}</td>
                                            <td>{{ handlePrice($revenue->tutor_earnings) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $revenues->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
