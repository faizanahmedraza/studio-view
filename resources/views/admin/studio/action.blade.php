@if(can_access_route(['studio.edit','studio.update'],$userPermissoins))
    <a href="{{ url('studios/edit/'.base64_encode($record->id)) }}" class="btn btn-info btn-xs edit"
       style="float: center;"><i class="fa fa-pencil">
        </i> </a>
@endif
