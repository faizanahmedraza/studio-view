@extends('admin.layouts.app')


@section('header-css')
    <style>
        .detail-form-wrap-1, .detail-form-wrap-2
        {
            margin-left: 0px;
            margin-right: 0px;
        }
        .detail-form-wrap-h4 {
            font-size: 14px;
            background-color: #3d3d3d;
            padding: 5px 10px;
            color: #fff;
        }
    </style>
@stop

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }} <small></small></h3>
        {{ Breadcrumbs::render('role.create') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

        @include('admin.partials.success')
        <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-plus"></i> {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4>&nbsp;</h4>

                    <form method="POST"  action="{{ route('role.store') }}" class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')


                        <div class="form-group">
                            <label for="name" class="col-md-2 control-label">Name *</label>
                            <div class="col-md-4">
                                <input type="text" name="name" maxlength="150" value="{{ old('name') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="dashboard_card_permission" class="col-md-2 control-label"> Dashboard Cards Permissions </label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="dashboard_card_permission[]" multiple="multiple">
                                    @foreach($dashboard_card_permissions as $value => $name)
                                        <option value="{{$value}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('dashboard_card_permission[]'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('dashboard_card_permission[]') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <input type="submit" class="btn blue" id="save" value="Save">
                                <input type="button" class="btn black" name="cancel" id="cancel" value="Cancel">
                            </div>
                        </div>
                            <!--from-input-col-close-->

                        <!--from-input-wraper-close-->

                    </form>
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
@stop

@section('footer-js')
    <script type="text/javascript" src="{!! URL::to('assets/admin/plugins/ckeditor/ckeditor.js') !!}"></script>
    <script src="{!! URL::to('assets/admin/scripts/core/app.js') !!}"></script>
    <script>
        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{!! URL::route('role.index') !!}";
            });

        });
    </script>
@stop
