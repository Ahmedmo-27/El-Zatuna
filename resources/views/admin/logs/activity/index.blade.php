@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a>{{ trans('logs.logs') }}</a></div>
                <div class="breadcrumb-item"><a href="#">{{ $pageTitle }}</a></div>
            </div>
        </div>
    </section>

    <div class="section-body">
        <section class="card">
            <div class="card-body">
                <form method="get" class="mb-0">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('logs.search') }}</label>
                                <input name="search" type="text" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ trans('logs.search_placeholder') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{ trans('logs.event') }}</label>
                                <select name="event" class="form-control">
                                    <option value="">{{ trans('logs.all_events') }}</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event }}" {{ (request()->get('event') == $event) ? 'selected' : '' }}>{{ trans('logs.event_' . $event) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{ trans('logs.log_name') }}</label>
                                <select name="log_name" class="form-control">
                                    <option value="">{{ trans('logs.all_logs') }}</option>
                                    @foreach($logNames as $logName)
                                        <option value="{{ $logName }}" {{ (request()->get('log_name') == $logName) ? 'selected' : '' }}>{{ ucfirst($logName) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{ trans('logs.from_date') }}</label>
                                <input type="date" class="text-center form-control" name="from" value="{{ request()->get('from') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{ trans('logs.to_date') }}</label>
                                <input type="date" class="text-center form-control" name="to" value="{{ request()->get('to') }}">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group mt-1">
                                <label class="input-label mb-4"> </label>
                                <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('logs.show_results') }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <div>
                <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('logs.activity_logs_subtitle') }}</p>
            </div>
            <div class="d-flex align-items-center gap-12">
                <a href="{{ url('admin/system-logs') }}" target="_blank" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                    <x-iconsax-lin-document-text class="icons text-gray-500" width="18px" height="18px"/>
                    <span class="ml-4 font-12">{{ trans('logs.system_logs') }}</span>
                </a>
                @can('admin_logs_activity_delete')
                    <a href="{{ getAdminPanelUrl().'/logs/activity/clear-old' }}" class="delete-action btn bg-white bg-hover-gray-100 border-gray-400 text-danger" data-title="{{ trans('logs.clear_old') }}" data-confirm="{{ trans('admin/main.yes') }}">
                        <x-iconsax-lin-trash class="icons text-danger" width="18px" height="18px"/>
                        <span class="ml-4 font-12">{{ trans('logs.clear_old') }}</span>
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table custom-table font-14">
                    <tr>
                        <th>{{ trans('logs.id') }}</th>
                        <th>{{ trans('logs.date_time') }}</th>
                        <th>{{ trans('logs.event') }}</th>
                        <th>{{ trans('logs.log_name') }}</th>
                        <th class="text-left">{{ trans('logs.description') }}</th>
                        <th class="text-left">{{ trans('logs.causer') }}</th>
                        <th>{{ trans('logs.subject') }}</th>
                        <th width="120">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @forelse($activities as $activity)
                        @php
                            $eventColors = ['created' => 'success', 'updated' => 'warning', 'deleted' => 'danger', 'restored' => 'info'];
                            $badge = $eventColors[$activity->event] ?? 'secondary';
                            $causer = $activity->causer;
                            $new = $activity->properties->get('attributes', []);
                            $old = $activity->properties->get('old', []);
                            $changeKeys = array_unique(array_merge(array_keys($new ?? []), array_keys($old ?? [])));
                        @endphp
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td class="font-12">{{ optional($activity->created_at)->format('j M Y H:i:s') }}</td>
                            <td>
                                @if($activity->event)
                                    <span class="badge badge-{{ $badge }}">{{ trans('logs.event_' . $activity->event) }}</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                            <td class="font-12">{{ ucfirst($activity->log_name ?? '-') }}</td>
                            <td class="text-left">{{ $activity->description }}</td>
                            <td class="text-left">
                                @if($causer)
                                    <div class="d-flex align-items-center">
                                        <figure class="avatar mr-2">
                                            <img src="{{ $causer->getAvatar() }}" alt="{{ $causer->full_name }}">
                                        </figure>
                                        <div class="media-body ml-1">
                                            <div class="mt-0 mb-1">{{ $causer->full_name }}</div>
                                            @if($causer->email)
                                                <div class="text-small font-12 text-gray-500">{{ $causer->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-500">{{ trans('logs.system') }}</span>
                                @endif
                            </td>
                            <td class="font-12">
                                @if($activity->subject_type)
                                    {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center" width="120">
                                <div class="btn-group dropdown table-actions position-relative">
                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <button type="button" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4" data-toggle="modal" data-target="#activity-details-{{ $activity->id }}">
                                            <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                            <span class="text-gray-500 font-14">{{ trans('logs.view_details') }}</span>
                                        </button>

                                        @can('admin_logs_activity_delete')
                                            <a href="{{ getAdminPanelUrl().'/logs/activity/'.$activity->id.'/delete' }}" class="delete-action dropdown-item d-flex align-items-center text-danger mb-0 py-3 px-0 font-14 gap-4" data-title="{{ trans('admin/main.delete_confirm_msg') }}" data-confirm="{{ trans('admin/main.delete') }}">
                                                <x-iconsax-lin-trash class="icons text-danger mr-2" width="18px" height="18px"/>
                                                <span class="text-danger font-14">{{ trans('logs.delete') }}</span>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- Details modal --}}
                        <div class="modal fade" id="activity-details-{{ $activity->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $activity->description }} — #{{ $activity->id }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body text-left">
                                        <ul class="list-unstyled font-13 mb-3">
                                            <li><strong>{{ trans('logs.date_time') }}:</strong> {{ optional($activity->created_at)->format('j M Y H:i:s') }}</li>
                                            <li><strong>{{ trans('logs.event') }}:</strong> {{ $activity->event ? trans('logs.event_' . $activity->event) : '-' }}</li>
                                            <li><strong>{{ trans('logs.log_name') }}:</strong> {{ ucfirst($activity->log_name ?? '-') }}</li>
                                            <li><strong>{{ trans('logs.causer') }}:</strong> {{ $causer ? $causer->full_name : trans('logs.system') }}</li>
                                            <li><strong>{{ trans('logs.subject') }}:</strong> {{ $activity->subject_type ? class_basename($activity->subject_type).' #'.$activity->subject_id : '-' }}</li>
                                        </ul>

                                        <h6 class="font-14">{{ trans('logs.changes') }}</h6>
                                        @if(count($changeKeys))
                                            <div class="table-responsive">
                                                <table class="table table-bordered font-12 mb-0">
                                                    <tr>
                                                        <th>{{ trans('logs.attribute') }}</th>
                                                        <th>{{ trans('logs.old_value') }}</th>
                                                        <th>{{ trans('logs.new_value') }}</th>
                                                    </tr>
                                                    @foreach($changeKeys as $key)
                                                        <tr>
                                                            <td>{{ $key }}</td>
                                                            <td>{{ is_array($old) && array_key_exists($key, $old) ? (is_scalar($old[$key]) ? $old[$key] : json_encode($old[$key])) : '-' }}</td>
                                                            <td>{{ is_array($new) && array_key_exists($key, $new) ? (is_scalar($new[$key]) ? $new[$key] : json_encode($new[$key])) : '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-gray-500 font-13 mb-0">{{ trans('logs.no_changes') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">{{ trans('logs.no_records') }}</td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>

        <div class="card-footer text-center">
            {{ $activities->appends(request()->input())->links() }}
        </div>
    </div>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{ trans('admin/main.hints') }}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{ trans('logs.hint_title_1') }}</div>
                        <div class="text-small font-600-bold">{{ trans('logs.hint_description_1') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{ trans('logs.hint_title_2') }}</div>
                        <div class="text-small font-600-bold">{{ trans('logs.hint_description_2') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{ trans('logs.hint_title_3') }}</div>
                        <div class="text-small font-600-bold">{{ trans('logs.hint_description_3') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
@endpush
