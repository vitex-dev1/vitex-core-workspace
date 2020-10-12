<div class="list-responsive">
    <div class="list-header">
        <div class="row">
            <div class="col-item col-sm-3 col-xs-12">
                @lang('strings.user.label_name')
            </div>
            <div class="col-item col-sm-3 col-xs-12">
                @lang('strings.user.label_email')
            </div>
            <div class="col-item col-sm-2 col-xs-12">
                @lang('strings.user.label_role')
            </div>
            <div class="col-item col-sm-2 col-xs-12">
                @lang('strings.user.label_status')
            </div>
            <div class="col-item col-sm-2 col-xs-12">
                @lang('common.options')
            </div>
        </div>
    </div>
    <div class="list-body">
        @foreach ($model as $data)
            <div id="tr-{{ $data->id }}" class="row">
                <div class="col-item col-sm-3 col-xs-12">
                    {{$data->name}}
                </div>
                <div class="col-item col-sm-3 col-xs-12">
                    <a href="mailto:{{$data->email}}">{{$data->email}}</a>
                </div>
                <div class="col-item col-sm-2 col-xs-12">
                    @if (!empty($data->role))
                        {{ $data->role->name }}
                    @endif
                </div>
                <div class="col-item col-sm-2 col-xs-12">
                    {{-- Active status --}}
                    <div class="@if ($data->active) text-success @else text-danger @endif">
                        <span class="glyphicon @if ($data->active) glyphicon-record @else glyphicon-ban-circle @endif" aria-hidden="true"></span>
                        &nbsp;{!! \App\Models\User::active_statuses((int)$data->active) !!}
                    </div>
                </div>
                <div class="col-item col-sm-2 col-xs-12">
                    @if(Helper::checkUserPermission('admin.users.show'))
                        <a href="{{ Admin::route('users.show', ['user'=>$data->id]) }}" class="btn btn-default btn-xs">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                    @endif
                    @if(Helper::checkUserPermission('admin.users.edit'))
                        <a href="{{ Admin::route('users.edit', ['user'=>$data->id]) }}" class="btn btn-default btn-xs">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </a>
                    @endif
                    @if(Helper::checkUserPermission('admin.users.status'))
                        @php
                            $statusVal = ($data->active == \App\Models\User::IS_YES) ? 0 : 1;
                            $statusText = ($data->active == \App\Models\User::IS_YES) ? 'ban' : 'unban';
                            $statusIcon = ($data->active == \App\Models\User::IS_YES) ? 'fa-ban' : 'fa-play-circle';
                        @endphp
                        {!! Form::open(['route' => [$guard.'.users.status', $data->id], 'method' => 'put', 'class' => 'inline-block']) !!}
                        {!! Form::button('<i class="fa '.$statusIcon.'" aria-hidden="true"></i>',
                            [
                                'type' => 'button',
                                'class' => 'btn btn-default btn-xs show-confirm',
                                'data-title' => trans('user.are_you_sure_'.$statusText),
                                'data-yes_label' => trans('common.yes'),
                                'data-no_label' => trans('common.no')
                            ]) !!}
                        {!! Form::close() !!}
                    @endif
                    @if(Helper::checkUserPermission('admin.users.destroy'))
                        {!! Form::open(['route' => [$guard.'.users.destroy', $data->id], 'method' => 'delete', 'class' => 'inline-block']) !!}
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>',
                            [
                                'type' => 'button',
                                'class' => 'btn btn-danger btn-xs show-confirm',
                                'data-title' => trans('user.are_you_sure_delete'),
                                'data-yes_label' => trans('common.yes'),
                                'data-no_label' => trans('common.no')
                            ]) !!}
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@if(!empty($model))
    {{ $model->links() }}
@endif