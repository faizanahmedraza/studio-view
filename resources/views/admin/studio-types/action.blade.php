@if(can_access_route(['studio.type.edit','studio.type.update'],$userPermissoins))
    <a href="{{ url('studios/types/edit/'.base64_encode($record->id)) }}" class="btn btn-info btn-xs edit"
       style="float: center;"><i class="fa fa-pencil">
        </i> </a>
@endif
