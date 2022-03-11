@extends( 'attendance.layouts.app' )

@section('title', 'Customers')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">

@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{ backend_asset('js/sweetalert2.all.min.js') }}"></script>
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

@section('inlineJS')
    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $('#datatable').dataTable({
                "lengthMenu": [ 250, 500, 750, 1000 ]
            });
            $(".group1").colorbox({height:"50%",width:"50%"});



            $(document).on('click', '.form-delete', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to delete sub admin ??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {
                                $form.submit();
                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });




        });


        $(document).ready(function() {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });

        $(function () {
            appConfig.set('yajrabox.ajax', '{{ route('attendance.users.data') }}');
            appConfig.set('dt.order', [0, 'asc']);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.datepicker = jQuery('[name=datepicker]').val();
            });

            appConfig.set('yajrabox.columns', [
                {data: 'id',   orderable: true,   searchable: true },
                  {data: 'first_name',   orderable: true,   searchable: true,className:'text-center'},
//                {data: 'profile_picture',   orderable: true,   searchable: true},
                {data: 'email',   orderable: true,   searchable: true,className:'text-center'},
                {data: 'phone',   orderable: true,   searchable: true ,className:'text-center'},
                {data: 'department',name:'department.title',   orderable: true,   searchable: true ,className:'text-center'},
                {data: 'status',   orderable: true,   searchable: false, className:'text-center'},
                {data: 'action',   orderable: false,   searchable: false, className:'text-center'}


            ]);
        })

    </script>
@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Customers List<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Customers  <small>Listing</small></h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            @include( 'attendance.layouts.notification_message' )

                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif


                            <table class="table table-striped table-bordered yajrabox" >
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th style="width: 20%">Action</th>

                                </tr>
                                </thead>


                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection
