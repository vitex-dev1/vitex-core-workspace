{!! Form::open(['url' => Admin::route('users.index'), 'method' => 'get']) !!}

<table class="table table-responsive table-borderless">
    <thead>
    <tr>
        <th>@lang('strings.search')</th>
        <th>@lang('strings.user.label_role')</th>
        <th>@lang('strings.status')</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{ Form::text('keyword', null, ['class' => 'form-control', 'placeholder' => trans('strings.placeholder_keyword')]) }}</td>
        <td>{{ Form::select('role_id', $roles, null, ['class' => 'form-control', 'placeholder' => trans('strings.user.placeholder_role')]) }}</td>
        <td>{{ Form::select('active', $active_statuses, null, ['class' => 'form-control', 'placeholder' => trans('strings.placeholder_status')]) }}</td>
        <td>
            {!! Form::submit(trans('strings.search'), ['class' => 'btn btn-primary clear-border-radius']) !!}
            {!! Html::link(route($guard.'.users.index'), trans('common.clear'), ['class' => 'btn btn-primary clear-border-radius']) !!}
        </td>
    </tr>
    </tbody>
</table>

{!! Form::close() !!}