@extends('admin.layouts.app')

@section('css')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/select2/select2-metronic.css') }}"/>

    <link href="{{ asset('assets/admin/css/customPreview.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL STYLES -->
@stop
@section('content')
    <!-- BEGIN PAGE HEADER-->
    @include('admin.partials.errors')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }}
                <small></small>
            </h3>
            {{ Breadcrumbs::render('studio.booking_details',$studioBooking) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <!-- Action buttons Code Start -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Add New Button Code Moved Here -->
                    <div class="table-toolbar pull-right">
                        <div class="btn-group">


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
                        <i class="fa fa-list"></i> {{$pageTitle}}
                    </div>
                </div>

                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover yajrabox" id="sample_1">
                        <thead>
                        <tr>
                            {{-- <th>Id</th>
                            <th>Name</th>
                            <th>Action</th> --}}

                            {{-- <th>Status</th> --}}
                        </tr>
                        </thead>
                        <tbody>

                            <tr>
                            <td>Studio Owner</td>
                            <td>{{$studioBooking->studio->user->first_name}}</td>

                            <td>Customer Request</td>
                            <td>{{$studioBooking->user->first_name}}</td>

                            <td>Studio</td>
                            <td>{{$studioBooking->studio->name}}</td>
                            </tr>

                            <tr>
                            <td>For Date</td>
                            <td>{{$studioBooking->date}}</td>


                            <td>Start Time</td>
                            <td>{{$studioBooking->start_time}}</td>


                            <td>End Time</td>
                            <td>{{$studioBooking->end_time}}</td>
                            </tr>

                            <tr>

                            <td>Status</td>
                            <td>{{$studioBooking->status==1 ? "Approved" :"Pending/Rejected"}}</td>

                            <td>Hourly Rate</td>
                            <td>{{$studioBooking->hourly_rate}}</td>

                            <td>Audio Engineer Included</td>
                            <td>{{$studioBooking->audio_eng_included ? "included" : "not included"}}</td>
                            </tr>

                            <tr>
                            <td>Audio Engineer Rate Per Hour</td>
                            <td>{{$studioBooking->audio_eng_rate_hr}}</td>

                            <td>Audio Engineer Discount</td>
                            <td>{{$studioBooking->audio_eng_discount}}</td>

                            <td>Discount</td>
                            <td>{{$studioBooking->discount}}</td>
                            </tr>

                            <tr>
                            <td>Other Fees</td>
                            <td>{{$studioBooking->other_fees}}</td>

                            <td>Mixing Services Fees</td>
                            <td>{{$studioBooking->mixing_services}}</td>

                            <td>Total Hours Rate</td>
                            <td>{{$studioBooking->total_hours_price}}</td>
                           </tr>

                           <tr>
                            <td>Total Audio Engineer Hour Rate</td>
                            <td>{{$studioBooking->total_eng_hours_price}}</td>

                            <td>Grand Total</td>
                            <td>{{$studioBooking->grand_total}}</td>

                            <td>Requested At</td>
                            <td>{{$studioBooking->created_at}}</td>

                            </tr>






                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
@stop

@section('footer-js')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="{{ asset('assets/admin/plugins/select2/select2.min.js') }}"></script>
    {{--   <script type="text/javascript"--}}
    {{--            src="{!! URL::to('assets/admin/plugins/data-tables/jquery.dataTables.js') !!}"></script>--}}
    {{--    <script type="text/javascript" src="{!! URL::to('assets/admin/plugins/data-tables/DT_bootstrap.js') !!}"></script>--}}

    <script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
    <script src="{{ asset('assets/admin/scripts/custom/user-administrators.js') }}"></script>

    <script src="{{ asset('assets/admin/scripts/custom/customPreview.js') }}"></script>

    <script>

        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{{route('dashboard.index') }}";
            });
        });




    </script>
@stop
