@extends('layouts.admin')

@section('content')
<div class="row">
  <div class="x_panel">
    <div class="x_title">
      <h2>Manage Page</h2>
      <ul class="nav navbar-right panel_toolbox">
        <li><a id="btn-sel-del" style="display:none;" href="#" class="btn-toolbox danger"><i class="fa fa-trash"></i> Delete Selected page</a></li>
        <li><a href="{{ Admin::route('contentManager.page.create') }}" class="btn-toolbox success"><i class="fa fa-plus"></i> Create Page</a></li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <table class="table table-striped jambo_table bulk_action"> 
        <thead>
          <tr> 
            <th><input id="checkAll" type="checkbox" class="flat"></th>
            <th>Post Title</th> 
            <th>Author</th> 
            <th>Date</th>
            <th></th>
          </tr> 
        </thead> 
        <tbody>
          @foreach ($model as $data)
          <tr id="tr-{{ $data->id }}"> 
            <td>
                <input type="checkbox" class="flat" name="checkbox" data-role="checkbox" value="{{$data->id}}" /> 
                <input type="hidden" id="idPost" value="{{ $data->id }}">
            </td>
            <td>
                <div class="btn-edit-delete">
                    {{$data->post_title}}
                    <div class="">
                        <a href="{{ Admin::route('contentManager.page.edit',['page'=>$data->id]) }}" > Edit </a> | 
                        <a href="#" data-role="delete-post" data-idpost="{{ $data->id }}" > Delete </a>
                    </div>
                </div>
            </td> 
            <td>{{$data->user->name}}</td> 
            <td>{{$data->updated_at}}</td>
            <td>@include('ContentManager::partials.copy_from', ['model' => $data, 'id' => $data->id])</td>
          </tr> 
          @endforeach  
        </tbody> 
      </table>
      {{ $model->links() }}
    </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$( document ).ready(function() {
    $("a[data-role='delete-post']").on( "click", function() {
        var idpost = $(this).data('idpost');
        swal({
          title: "Are you sure?",
          text: "Delete this page",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
          confirmButtonText: "Yes",
          confirmButtonClass: "btn-danger",
          cancelButtonText: "No"
        }, function () {
          $.ajax({
                type: 'DELETE',
                url: "{{ Admin::route('contentManager.page.index') }}/"+idpost,
                data: {"_token": "{{ csrf_token() }}"}
            })
          .done(function() {
            swal("Deleted!", "Delete Success", "success");
            $("#tr-"+idpost).remove();
          });
        });
        return false;
    });

    $("#checkAll").change(function () {
        $("input:checkbox[name=checkbox]").prop('checked', $(this).prop("checked"));
        if($("#btn-sel-del").css('display') == 'none'){
            $("#btn-sel-del").css("display","inline-block");
        }else{
            $("#btn-sel-del").css("display","none");
        }
    });

    $( "input[type=checkbox]" ).on( "change", function () {
        var n = $( "input:checked[name=checkbox]" ).length;
        if(n == 0){
            $("#btn-sel-del").css("display","none");
        }else{
            $("#btn-sel-del").css("display","inline-block");
        }
    });

    $("#btn-sel-del").on("click",function(){
        var array = new Array();
        $("input:checkbox[name=checkbox]:checked").each(function(){
            array.push($(this).val());
        });
        var id = array.join()
        swal({
          title: "Are you sure?",
          text: "Delete the selected page",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
          confirmButtonText: "Yes",
          confirmButtonClass: "btn-danger",
          cancelButtonText: "No"
        }, function () {
          $.ajax({
                type: 'DELETE',
                url: "{{ Admin::route('contentManager.page.index') }}/"+id,
                data: {"_token": "{{ csrf_token() }}"}
            })
          .done(function() {
            swal("Deleted!", "Delete Success", "success");
            location.reload();
          });
        });
        return false;
    });
});
</script>
@endpush
