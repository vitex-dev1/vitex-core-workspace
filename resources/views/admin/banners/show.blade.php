@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>
            Banner
        </h1>
    </section>
    <div class="content">
        <div class="box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.banners.show_fields')
                    <a href="{!! route('admin.banners.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection