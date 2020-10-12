@extends('layouts.blank')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-xs-12">
                <div class="panel panel-login">
                    <div class="panel-heading">
                        <div class="logo">
                            <i class="fa fa-anchor"></i>
                        </div>
                        <h2>Login Administrator</h2>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ Admin::route('showlogin') }}">
                            {{ csrf_field() }}

                            @if($errors->has('active'))
                                <div class="alert alert-danger">
                                    {!! $errors->first('active') !!}
                                </div>
                            @endif

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 col-sm-4 col-xs-12 control-label">E-Mail Address</label>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 col-sm-4 col-xs-12 control-label">Password</label>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-md-offset-4 col-sm-offset-4 col-xs-6 text-right">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fa fa-btn fa-sign-in"></i> Login
                                    </button>
                                </div>

                                <div class="col-md-6 col-sm-6 col-md-offset-4 col-sm-offset-4 col-xs-6 text-right">
                                    <a class="btn btn-link none-pdmg-right" href="{{ route('admin.password.request') }}">Lost Password?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{URL::to('/')}}/themes/dashboard/css/style.css">
@endpush
