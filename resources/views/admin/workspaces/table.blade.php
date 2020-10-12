<div class="list-responsive">
    <div class="list-header">
        <div class="row">
            <div class="col-item col-sm-2 col-xs-12">
                @lang('strings.id')
            </div>
            <div class="col-item col-sm-5 col-xs-12">
                @lang('strings.workspace.label_name')
            </div>
            <div class="col-item col-sm-2 col-xs-12">
                @lang('strings.workspace.label_created_at')
            </div>
            <div class="col-item col-sm-3 col-xs-12">
                @lang('common.options')
            </div>
        </div>
    </div>
    <div class="list-body">
        @foreach($workspaces as $workspace)
            <div id="tr-{{ $workspace->id }}" class="row">
                <div class="col-item col-sm-2 col-xs-12">
                    {!! $workspace->id !!}
                </div>
                <div class="col-item col-sm-5 col-xs-12">
                    {!! $workspace->name !!}
                </div>
                <div class="col-item col-sm-2 col-xs-12">
                    {!! Helper::getDatetimeFromFormat($workspace->created_at, null, $guard) !!}
                </div>
                <div class="col-item col-sm-3 col-xs-12">
                    @if(Helper::checkUserPermission($guard.'.workspaces.edit'))
                        <a href="{!! route($guard.'.workspaces.edit', [$workspace->id]) !!}" class="btn btn-default btn-xs">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </a>
                    @endif

                    @if(Helper::checkUserPermission($guard.'.workspaces.destroy'))
                        {!! Form::open(['route' => [$guard.'.workspaces.destroy', $workspace->id], 'method' => 'delete', 'class' => 'inline-block']) !!}
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>',
                            [
                            'type' => 'button',
                            'class' => 'btn btn-danger btn-xs show-confirm',
                            'data-title' => trans('workspace.are_you_sure_delete'),
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

@if(!empty($workspaces))
    {{ $workspaces->links() }}
@endif