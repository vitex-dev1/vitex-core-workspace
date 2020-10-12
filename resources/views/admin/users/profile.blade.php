@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('user.profile_title')</h2>
                    <div class="clearfix"></div>
                    @include('ContentManager::partials.errormessage')
                </div>
                <div class="x_content">
                    <div class="row">
                        <form method="POST" action="#" class="form-view">
                            <input type="hidden" id="data-token" value="{{ csrf_token()}}">

                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <!-- Current avatar -->
                                <img class="img-responsive avatar-view" src="{{ $model->photo }}" alt="Avatar" title="Change the avatar">
                            </div>

                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <!-- Name Field -->
                                <div class="row form-group">
                                    <div class="col-sm-2 col-xs-12">
                                        {!! Html::decode(Form::label('first_name', trans('user.first_name'))) !!}
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="form-control pdt-0">{{ $model->first_name }}</div>
                                    </div>
                                </div>

                                <!-- Name Field -->
                                <div class="row form-group">
                                    <div class="col-sm-2 col-xs-12">
                                        {!! Html::decode(Form::label('last_name', trans('user.last_name'))) !!}
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="form-control pdt-0">{{ $model->last_name }}</div>
                                    </div>
                                </div>

                                <!-- Email Field -->
                                <div class="row form-group">
                                    <div class="col-sm-2 col-xs-12">
                                        {!! Html::decode(Form::label('email', trans('strings.user.label_email'))) !!}
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="form-control pdt-0">{{ $model->email }}</div>
                                    </div>
                                </div>

                                <!-- Birthday Field -->
                                <div class="row form-group">
                                    <div class="col-sm-2 col-xs-12">
                                        {!! Form::label('birthday', trans('strings.user.label_birthday')) !!}
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="form-control pdt-0">{{ Helper::getDateFromFormat($model->birthday) }}</div>
                                    </div>
                                </div>

                                <!-- Gender Field -->
                                <div class="row form-group">
                                    <div class="col-sm-2 col-xs-12">
                                        {!! Form::label('gender', trans('strings.user.label_gender')) !!}
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="form-control pdt-0">{{ (array_key_exists($model->gender, App\Models\User::genders())) ? App\Models\User::genders($model->gender) : '' }}</div>
                                    </div>
                                </div>

                                <!-- Address Field -->
                                <div class="row form-group">
                                    <div class="col-sm-2 col-xs-12">
                                        {!! Form::label('address', trans('strings.user.label_address')) !!}
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="form-control pdt-0">{{ nl2br($model->address) }}</div>
                                    </div>
                                </div>

                                <!-- Phone Field -->
                                <div class="row form-group">
                                    <div class="col-sm-2 col-xs-12">
                                        {!! Form::label('phone', trans('strings.user.label_phone')) !!}
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="form-control pdt-0">{{ $model->phone }}</div>
                                    </div>
                                </div>

                                <!-- Description Field -->
                                <div class="row form-group">
                                    <div class="col-sm-2 col-xs-12">
                                        {!! Form::label('description', trans('strings.user.label_description')) !!}
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="form-control pdt-0">{{ nl2br($model->description) }}</div>
                                    </div>
                                </div>

                                <!-- Timezone Field -->
                                <div class="row form-group">
                                    <div class="col-sm-2 col-xs-12">
                                        {!! Form::label('timezone', trans('strings.user.label_timezone')) !!}
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="form-control pdt-0">{{ (array_key_exists($model->timezone, App\Models\User::timezones())) ? App\Models\User::timezones($model->timezone) : '' }}</div>
                                    </div>
                                </div>

                                @include($guard.'.users.partials.show_workspace')

                                <!-- Submit Field -->
                                <div class="row form-group">
                                    <hr/>
                                    {!! Html::link(Admin::route('users.editProfile'), trans('strings.edit'), ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection