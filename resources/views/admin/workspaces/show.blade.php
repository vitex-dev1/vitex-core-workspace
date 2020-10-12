@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>
            Workspace
        </h1>
    </section>
    <div class="content">
        <div class="box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.workspaces.show_fields')
                    <a href="{!! route('admin.workspaces.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection