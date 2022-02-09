@extends('admin.layouts.app')

@section('content')
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">{{ $pageTitle ?? '' }} <small></small></h3>
    {{ Breadcrumbs::render('attendancePage.edit', $Attendancepage) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<link href="{{ asset('assets/admin/css/customPreview.css') }}" rel="stylesheet" type="text/css"/>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">

        @include('admin.partials.success')

        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-edit"></i> {{ $pageTitle }}
                </div>
            </div>

            <div class="portlet-body">

                <h4>&nbsp;</h4>

                <form method="POST" action="{{ route('attendancePage.update', $Attendancepage[0]->id) }}" class="form-horizontal" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group" hidden>
                        <label for="id" class="col-md-2 control-label" >Id *</label>
                        <div class="col-md-4">
                            <input type="text" name="id" maxlength="150" value="{{$Attendancepage[0]->id}}"
                                   class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="col-md-2 control-label" >Page Title *</label>
                        <div class="col-md-4">
                            <input type="text" name="page_title" maxlength="150" value="{{$Attendancepage[0]->page_title}}"
                                   class="form-control" readonly/>
                        </div>

                    </div>


                    <div class="form-group">
                        <label for="content" class="col-md-2 control-label">Page Content *</label>
                        <div class="col-md-4">
                            <textarea type="text" name="content"
                                   class="form-control "  rows="10">{{$Attendancepage[0]->content}}
                                </textarea>

                        </div>
                     @if ($errors->has('content'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('content') }}</strong>
                                        </span>
                            @endif
                    </div>


                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="submit" class="btn blue" id="save" value="Save">
                            <input type="button" class="btn black" name="cancel" id="cancel" value="Cancel">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('footer-js')
<script type="text/javascript" src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
<script src="{{ asset('assets/admin/scripts/custom/customPreview.js') }}"></script>

<script>

    jQuery(document).ready(function() {
   // initiate layout and plugins
   App.init();
   Admin.init();
   $('#cancel').click(function() {
        window.location.href = "{{ route('dashboard.index') }}";
   });
});

</script>
@stop
