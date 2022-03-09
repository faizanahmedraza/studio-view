<div class="row">
    {{-- @if(can_view_cards('user_card_count',$dashbord_cards_rights)) --}}
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one green">
                <div class="visual" style="right:10px;">
                    <i class="fa fa-users" ></i>
                </div>
                <div class="details">
                    <div class="number">
                 {{$users}}
                    </div>
                    <div class="desc">
                        Users
                    </div>
                </div>
                <a class="more" href="{{route('users.index')}}">
                    See Users <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="dashboard-stat dashboard-cart-box-one green">
                <div class="visual" style="right:10px;">
                    <i class="fa fa-music" ></i>
                </div>
                <div class="details">
                    <div class="number">
                 {{$studios}}
                    </div>
                    <div class="desc">
                        Studios
                    </div>
                </div>
                <a class="more" href="{{route('studio.pending.index')}}">
                    See Pending Studios <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    {{-- @endif --}}
        {{-- @if(can_view_cards('new_request_card_count',$dashbord_cards_rights))
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="dashboard-stat dashboard-cart-box-one green">
                    <div class="visual" style="right:10px;">
                        <i class="fa fa-users" ></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            {{$newRequest}}
                        </div>
                        <div class="desc">
                            New Studio Request
                        </div>
                    </div>
                    <a class="more" href="#">
                        See New Studio Request <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
        @endif --}}
</div>
<div class="clearfix"></div>
<!-- Dashboard cards close -->
