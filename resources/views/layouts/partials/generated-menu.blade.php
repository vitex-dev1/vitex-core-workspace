@php($basePath = app()->getLocale().'/'.$guard)

@if(Helper::checkUserPermission($guard.'.dashboard.index'))
    <li class="{{ $request->is($basePath.'/dashboard*') ? 'active' : '' }}">
        <a href="{{ route($guard.'.dashboard.index', ['workspace' => $activeWorkspace]) }}">
            <i class="fa fa-home"></i>
            @lang('menu.dashboard')
        </a>
    </li>
@endif

@if(Helper::checkUserPermission($guard.'.users.index') || Helper::checkUserPermission($guard.'.roles.index') || Helper::checkUserPermission($guard.'.workspaces.index'))
    <li class="{{ $request->is($basePath.'/users*') || $request->is($basePath.'/roles*') || $request->is($basePath.'/workspaces*') ? 'active' : '' }}">
        <a>
            <i class="fa fa-gear"></i>
            @lang('menu.platform')
            <span class="fa fa-chevron-down"></span>
        </a>
        <ul class="nav child_menu" style="{{ $request->is($basePath.'/users*') || $request->is($basePath.'/roles*') || $request->is($basePath.'/workspaces*') ? 'display:block;' : '' }}">
            @if(Helper::checkUserPermission($guard.'.users.index'))
                <li class="{{ $request->is($basePath.'/users*') ? 'active current-page' : '' }}">
                    <a href="{{ route($guard.'.users.index') }}">
                        <i class="fa fa-users"></i>
                        @lang('menu.admin_manager')
                    </a>
                </li>
            @endif

            @if(Helper::checkUserPermission($guard.'.roles.index'))
                <li class="{{ $request->is($basePath.'/roles*') ? 'active current-page' : '' }}">
                    <a href="{!! route($guard.'.roles.index') !!}">
                        <i class="fa fa-user-secret"></i>
                        <span>@lang('menu.role_backoffice')</span>
                    </a>
                </li>
            @endif

            @if(Helper::checkUserPermission($guard.'.workspaces.index'))
                <li class="{{ $request->is($basePath.'/workspaces*') ? 'active current-page' : '' }}">
                    <a href="{!! route($guard.'.workspaces.index') !!}">
                        <i class="fa fa-gear"></i>
                        <span>@lang('menu.workspaces')</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

@if(Helper::checkUserPermission($guard . '.langs.index'))
    <li class="{{ $request->is($basePath . '/langs*') ? 'active' : '' }}">
        <a href="{{ Admin::route('langs.index') }}">
            <i class="fa fa-globe"></i>
            @lang('menu.lang_manager')
        </a>
    </li>
@endif