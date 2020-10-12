@extends('layouts.blank')

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-xs-12">
            <div class="panel panel-login">
                <div class="panel-heading">
                    <div class="logo">
                        <i class="fa fa-anchor"></i>
                    </div>
                    <h2>Reset Password</h2>
                </div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1 text-center color-black">
                                <p>Please enter your e-mail address below. A link to change your password will be sent to you immediately after you have requested it.</p>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ url('/admin') }}" class="btn btn-default">Go back</a>

                                <button type="submit" class="btn btn-primary btn-gray">
                                    <i class="fa fa-btn fa-envelope"></i> Submit
                                </button>
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
