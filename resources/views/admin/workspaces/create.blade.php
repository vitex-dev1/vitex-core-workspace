@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>@lang('workspace.add_workspace')</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12">
                        @include('ContentManager::partials.errormessage')
                    </div>
                    {!! Form::open(['route' => 'admin.workspaces.store']) !!}

                    @include('admin.workspaces.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection