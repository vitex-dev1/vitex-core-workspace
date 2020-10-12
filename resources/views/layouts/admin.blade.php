<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{!! config('app.name') !!}</title>

        <link href="{{ URL::to('/builds/css/vendor.admin.css') }}" rel="stylesheet">
        <!-- Custom Theme Style -->
        @stack('style-top')
        <link href="{{ URL::to('/builds/css/all.css') }}" rel="stylesheet">
        <link href="{{ URL::to('/builds/css/main.admin.css') }}" rel="stylesheet">
        {{-- Custom Style --}}
        @stack('style')

        <script>
            window.DOMAIN = '{{URL::to('/')}}/';
        </script>
    </head>

    <body class="nav-md footer_fixed">
        <div class="container body">
            @if(!empty($auth))
                <div class="main_container">
                    <div class="col-md-3 left_col">
                        <div class="left_col scroll-view">
                            <div class="navbar nav_title" style="border: 0;">
                                <a href="{{ route($guard.'.users.profile') }}" class="site_title">
                                    <i class="fa fa-paw"></i>
                                    <span>Administrator</span>
                                </a>
                            </div>

                            <div class="clearfix"></div>

                            <!-- menu profile quick info -->
                            <div class="profile" style="display: none;">
                                <div class="profile_pic">
                                    <img src="{{ $auth->photo }}" alt="..." class="img-circle profile_img">
                                </div>
                                <div class="profile_info">
                                    <span>Welcome,</span>
                                    <h2>{{ $auth->name }}</h2>
                                </div>
                            </div>
                            <!-- /menu profile quick info -->

                            <br />

                            <!-- sidebar menu -->
                            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                                <div class="menu_section">
                                    {{--<h3>General</h3>--}}
                                    <ul class="nav side-menu">
                                        @include('layouts.partials.generated-menu')
                                    </ul>
                                </div>
                            </div>
                            <!-- /sidebar menu -->

                            <!-- /menu footer buttons -->
                            <div class="sidebar-footer hidden-small">
                                <a href="#"
                                   data-toggle="tooltip" data-placement="top" title="Settings">
                                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                                    <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="Lock">
                                    <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                                </a>
                                <a href="{{ Admin::route('logout') }}"
                                   data-toggle="tooltip" data-placement="top" title="Logout">
                                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                                </a>
                            </div>
                            <!-- /menu footer buttons -->
                        </div>
                    </div>

                    <!-- top navigation -->
                    <div class="top_nav">
                        <div class="nav_menu">
                            <nav>
                                <div class="nav toggle">
                                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                                </div>

                                @include('layouts.partials.extra_menu')
                            </nav>
                        </div>

                    </div>
                    <!-- /top navigation -->

                    <!-- page content -->
                    <div class="right_col" role="main">
                        @if(Breadcrumbs::exists($routeName))
                            {!! Breadcrumbs::render($routeName, $request) !!}
                        @endif

                        @yield('content')
                    </div>
                    <!-- /page content -->

                    <!-- footer content -->
                    <br>
                    <footer>
                        <div class="pull-right">
                            Thank for using {!! config('app.name') !!}
                        </div>
                        <div class="clearfix"></div>
                    </footer>
                    <!-- /footer content -->
                </div>
            @endif
        </div>

        <script src="{{ URL::to('/builds/js/vendor.admin.js') }}"></script>
        <script src="{{ URL::to('/assets/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ URL::to('/builds/js/all.js') }}"></script>
        <script src="{{ URL::to('/builds/js/main.admin.js') }}"></script>

        <!-- CKEditor scripts -->
        @include('layouts.partials.ckeditor_scripts')

        @stack('scripts')
    </body>
</html>