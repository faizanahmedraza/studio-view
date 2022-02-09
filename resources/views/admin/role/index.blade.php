@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ URL::to('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! URL::to('assets/admin/plugins/select2/select2-metronic.css') !!}"/>

    <!-- END PAGE LEVEL STYLES -->
@stop
@section('content')
    <!-- BEGIN PAGE HEADER-->
   {{-- @include('admin.partials.errors')--}}
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('role.index') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
        @include('admin.partials.success')
            <!-- Action buttons Code Start -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Add New Button Code Moved Here -->
                    <div class="table-toolbar pull-right">
                        <div class="btn-group">
                            <!--<a href="{!! URL::route('role.create') !!}" id="sample_editable_1_new"
                               class="btn orange">
                                Add <i class="fa fa-plus"></i>
                            </a>-->
                        </div>
                    </div>
                    <!-- Add New Button Code Moved Here -->
                </div>
            </div>
            <!-- Action buttons Code End -->



        <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">


                    <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover"  id="role_table">
                        <thead>
                        <tr>
                            <th style="width: 5%" class="text-center ">ID</th>
                            <th style="width: 30%" class="text-center ">Name</th>
                            <th style="width: 10%" class="text-center ">Total Users</th>
                            <th style="width: 10%" class="text-center ">Total Dashboard Cards </th>
                            <th style="width: 20%" class="text-center ">Created at</th>
                            <th style="width: 20%" class="text-center ">Set Privileges  </th>
                            <th style="width: 5%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($Roles as $record)
                            <tr>
                                <td class="text-center ">{{$record->id}}</td>
                                <td class="text-center ">{{$record->display_name}}</td>
                                <td class="text-center ">{{$record->User->count()}}</td>
                                <?php
                                    $dashboard_count = count(explode(',',$record->dashbaord_cards_rights));
                                        ?>
                                <td class="text-center ">{{$dashboard_count}}</td>
                                <td class="text-center ">{{$record->created_at}}</td>
                                <td class="text-center ">
                                    @if(can_access_route('role.set-permissions',$userPermissoins))
                                        <a href="{!! URL::route('role.set-permissions',$record->id) !!}" title="Edit"
                                           class="btn btn-xs btn-block btn-warning">
                                            <i class="fa fa-key"></i> Set Privileges
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center ">@include('admin.role.action',$record)</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
@stop

@section('footer-js')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="{!! URL::to('assets/admin/plugins/select2/select2.min.js') !!}"></script>

    <script src="{{ URL::to('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::to('assets/admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::to('assets/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{!! URL::to('assets/admin/scripts/core/app.js') !!}"></script>
    <script src="{!! URL::to('assets/admin/scripts/custom/user-administrators.js') !!}"></script>

    <script>

        jQuery(document).ready(function() {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function() {
                window.location.href = "{!! URL::route('dashboard.index') !!}";
            });
            //appConfig.set( 'dt.searching', true );

        });
        $(document).ready(function() {
            $('#role_table').DataTable( {
				 "lengthMenu": [[25, 50, 75, 100], [25, 50, 75, 100]],
                "order": [[  1,"asc" ]]
            } );
        } );



    </script>
@stop
