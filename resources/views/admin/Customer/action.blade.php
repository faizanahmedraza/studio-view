@if(can_access_route(['users.edit','users.update'],$userPermissoins))
    <a href="{{ url('customer/edit/'.base64_encode($record->id)) }}" class="btn btn-info btn-xs edit"
       style="float: center;"><i class="fa fa-pencil">
        </i> </a>
@endif
