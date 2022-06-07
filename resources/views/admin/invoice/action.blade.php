@if(can_access_route(['invoice.index','invoice.index'],$userPermissoins) && isset($user))
    <a target="_blank" href="{{ url('customer/edit/'.base64_encode($user->id)) }}" class=""
       style="float: center;">{{$user->first_name}} </a>
@elseif(isset($studio))
<a target="_blank" href="{{ url('studios/edit/'.base64_encode($studio->id)) }}" class=""
    style="float: center;">{{$studio->name}} </a>
@else
<a class="btn btn-info btn-xs edit" target="_blank" href="{{ url('studio-booking/'.base64_encode($studioBooking->id)) }}" class=""
    style="float: center;">View Details</a>
@endif
