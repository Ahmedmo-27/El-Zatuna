@extends('admin.layouts.app')

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
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('public.instructor') }}</th>
                                        <th>{{ trans('public.amount') }}</th>
                                        <th>{{ trans('public.status') }}</th>
                                        <th>{{ trans('public.date') }}</th>
                                        <th>{{ trans('public.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payouts as $payout)
                                        <tr>
                                            <td>{{ $payout->tutor->user->full_name ?? '-' }}</td>
                                            <td>{{ handlePrice($payout->amount) }}</td>
                                            <td>{{ $payout->status }}</td>
                                            <td>{{ dateTimeFormat($payout->created_at, 'j M Y H:i') }}</td>
                                            <td>
                                                @if($payout->status !== \App\Models\Payout::$paid)
                                                    <a href="{{ getAdminPanelUrl("/financial/tutor-payouts/{$payout->id}/paid") }}" class="btn btn-sm btn-success">{{ trans('update.mark_paid') }}</a>
                                                @else
                                                    <span class="text-success">{{ trans('update.paid') }}</span>
                                                @endif
                                            </td>
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
