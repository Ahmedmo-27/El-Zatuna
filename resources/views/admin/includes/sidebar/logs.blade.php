@if($authUser->can('admin_logs_activity') or $authUser->can('admin_logs_system'))
    <li class="menu-header">{{ trans('logs.logs') }}</li>

    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/logs*', false)) or request()->is('admin/system-logs*')) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-document-text class="icons" width="24px" height="24px"/>
            <span>{{ trans('logs.logs_dashboard') }}</span>
        </a>
        <ul class="dropdown-menu">
            @can('admin_logs_activity')
                <li class="{{ (request()->is(getAdminPanelUrl('/logs/activity*', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/logs/activity">{{ trans('logs.activity_logs') }}</a>
                </li>
            @endcan

            @can('admin_logs_system')
                <li class="{{ request()->is('admin/system-logs*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/system-logs') }}" target="_blank">{{ trans('logs.system_logs') }}</a>
                </li>
            @endcan
        </ul>
    </li>
@endif
