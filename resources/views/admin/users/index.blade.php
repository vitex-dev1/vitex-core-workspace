@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>@lang('menu.admin_manager')</h2>
                <ul class="nav navbar-right panel_toolbox">
                    @if(Helper::checkUserPermission('admin.users.create'))
                        <li>
                            <a href="{{ Admin::route('users.create') }}" class="btn-toolbox success">
                                <i class="fa fa-plus"></i> @lang('user.add_user')
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="clearfix"></div>
                @include('ContentManager::partials.errormessage')
            </div>

            <div class="x_content">
                <input type="hidden" id="data-token" value="{{ csrf_token()}}">

                @include('admin.users.partials.quick_search')
            </div>

            <div class="x_content">
                @include('admin.users.partials.table')

                <ul class="nav navbar-right panel_toolbox">
                    @if(Helper::checkUserPermission('admin.users.create'))
                        <li>
                            <a href="{{ Admin::route('users.create') }}" class="btn-toolbox success">
                                <i class="fa fa-plus"></i> @lang('user.add_user')
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('ContentManager::partials.scriptdelete')
@endpush