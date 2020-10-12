<ul class="nav navbar-nav navbar-right">
    <li class="">
        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="{{ $auth->photo }}" alt="">{{ $auth->name }}
            <span class=" fa fa-angle-down"></span>
        </a>

        <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a href="{{ route($guard.'.users.profile') }}"><i class="fa fa-user pull-right"></i> @lang('strings.menu_profile')</a></li>
            @if(!empty($auth->isSuperAdmin()))
            <li><a href="{{ route($guard.'.contentManager.setting') }}"><i class="fa fa-gear pull-right"></i> @lang('strings.menu_setting')</a></li>
            @endif
            <li><a href="{{ route($guard.'.password.changePasswordForm') }}"><i class="fa fa-lock pull-right"></i> @lang('strings.menu_change_password')</a></li>
            <li><a href="{{ route($guard.'.logout') }}"><i class="fa fa-sign-out pull-right"></i> @lang('strings.menu_logout')</a></li>
        </ul>
    </li>

    <li class="dropdown">
        @include('layouts.partials.switch_workspace')
    </li>

    <li class="dropdown">
        @include('layouts.partials.switch_lang')
    </li>

    <li role="presentation" class="dropdown" style="display: none;">
        <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-envelope-o"></i>
            <span class="badge bg-green">6</span>
        </a>

        @include('layouts.partials.msg_list')
    </li>
</ul>