@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    @lang('menu.workspaces')
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    @if(Helper::checkUserPermission('admin.workspaces.create'))
                        <li>
                            <a href="{!! route('admin.workspaces.create') !!}"
                               class="btn-toolbox success">
                                <i class="fa fa-plus"></i> @lang('workspace.add_workspace')
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="clearfix"></div>
                @include('ContentManager::partials.errormessage')
            </div>
            <div class="x_content">
                @include('admin.workspaces.table')

                <ul class="nav navbar-right panel_toolbox">
                    @if(Helper::checkUserPermission('admin.workspaces.create'))
                        <li>
                            <a href="{!! route('admin.workspaces.create') !!}"
                               class="btn-toolbox success">
                                <i class="fa fa-plus"></i> @lang('workspace.add_workspace')
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection