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

                            <form action="/panel/financial/tutor-payouts/request" method="post" class="mb-4">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="form-group-label">{{ trans('update.request_payout') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="amount" min="1" class="form-control" placeholder="{{ trans('update.enter_amount') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">{{ trans('update.submit_request') }}</button>
                                        </div>
                                    </div>
                                    <div class="text-gray-500 mt-1">{{ trans('update.minimum_payout_amount') }}: {{ handlePrice($minimumPayout) }}</div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('public.amount') }}</th>
                                        <th>{{ trans('public.status') }}</th>
                                        <th>{{ trans('public.date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payouts as $payout)
                                        <tr>
                                            <td>{{ handlePrice($payout->amount) }}</td>
                                            <td>{{ $payout->status }}</td>
                                            <td>{{ dateTimeFormat($payout->created_at, 'j M Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $payouts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
