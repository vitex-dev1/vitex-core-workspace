process.env.DISABLE_NOTIFIER = true;
var elixir = require('laravel-elixir');
var gulp = require('gulp');
var concat = require('gulp-concat');
var spritesmith = require('gulp.spritesmith');

require('laravel-elixir-livereload');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    //===== combine general style =====
    mix.sass([
        './resources/assets/sass/'
    ], 'public/builds/css/all.css');

    //===== combine general scripts =====
    mix.scriptsIn('resources/assets/js', 'public/builds/js/all.js', 'resources/assets');

    //===== combine vendor admin scripts =====
    mix.scripts([
        '/assets/jquery/dist/jquery.min.js',
        '/assets/bootstrap/dist/js/bootstrap.min.js',
        '/assets/jasny-bootstrap/dist/js/jasny-bootstrap.min.js',
        '/assets/sweetalert/sweetalert.js',
        '/assets/moment/min/moment.min.js',
        '/assets/moment-timezone/moment-timezone-with-data.js',
        '/assets/daterangepicker/daterangepicker.js',
        '/assets/jquery-ui/jquery-ui.min.js',
        '/assets/masonry/masonry.pkgd.min.js',
        '/assets/treeview-cb/script.js',
        '/assets/switchery/dist/switchery.min.js',
        '/assets/iCheck/icheck.min.js',
        '/assets/bootbox.js/bootbox.js',
        '/assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        '/assets/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
        '/assets/select2/dist/js/select2.full.min.js',
        '/assets/taggle/src/taggle.js',
        '/assets/toastr/toastr.min.js',
        '/assets/jquery-loading/dist/jquery.loading.min.js',
        '/assets/dropzone/dist/min/dropzone.min.js',
        '/assets/bootstrap3-typeahead/bootstrap3-typeahead.min.js',
        '/assets/sweetalert/sweetalert.min.js',
        '/assets/jquery-sortable/source/js/jquery-sortable-min.js',
        '/themes/dashboard/js/main.js'
    ], 'public/builds/js/vendor.admin.js', 'public');

    //===== combine vendor admin style =====
    mix.styles([
        '/assets/bootstrap/dist/css/bootstrap.min.css',
        '/assets/fontawesome/css/font-awesome.min.css',
        '/assets/jasny-bootstrap/dist/css/jasny-bootstrap.min.css',
        '/assets/switchery/dist/switchery.min.css',
        '/assets/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
        '/assets/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
        '/assets/select2/dist/css/select2.min.css',
        '/assets/toastr/toastr.min.css',
        '/assets/jquery-loading/dist/jquery.loading.min.css',
        '/assets/dropzone/dist/min/dropzone.min.css',
        '/assets/sweetalert/sweetalert.css',
        '/assets/daterangepicker/daterangepicker.css',
        '/assets/jquery-ui/jquery-ui.min.css',
        '/assets/treeview-cb/style.css',
        '/themes/dashboard/css/style.css'
    ], 'public/builds/css/vendor.admin.css', 'public');

    //===== combine manual admin scripts =====
    mix.scriptsIn('./resources/assets/backend/js', 'public/builds/js/main.admin.js', 'resources/assets');

    //===== combine manual admin style =====
    mix.sass(['./resources/assets/backend/sass/'], 'public/builds/css/main.admin.css');

    //===== combine manual admin scripts =====
    mix.scriptsIn('./resources/assets/client/js', 'public/builds/js/main.client.js', 'resources/assets');

    //===== combine manual admin style =====
    mix.sass(['./resources/assets/client/sass/'], 'public/builds/css/main.client.css');

    //==== livereload ====
    mix.livereload([
        'resources/assets/**/*',
        'resources/assets/!**!/!**!/!*.scss',
    ]);
});

//---- fonts ----
gulp.task('fonts-fontawesome', function () {
    return gulp.src('./public/assets/fontawesome/fonts/*').pipe(gulp.dest('./public/builds/fonts/'));
});

gulp.task('fonts-bootstrap', function () {
    return gulp.src('./public/assets/bootstrap/dist/fonts/*').pipe(gulp.dest('./public/builds/fonts/'));
});

gulp.task('fonts', ['fonts-fontawesome', 'fonts-bootstrap']);
