@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="x_panel">
        <div class="x_title">
          <h2>Contact</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12">
                    @include('ContentManager::partials.errormessage')
                </div>
                {!! Form::model($contact, ['route' => ['admin.contacts.update', $contact->id], 'method' => 'patch']) !!}

                    @include('admin.contacts.fields')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection