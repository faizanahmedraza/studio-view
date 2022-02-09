@extends('admin.layouts.app')

@section('content')
<style>
    .form-horizontal .radio > span {
        margin-top: -2px;
    }
</style>
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }}
                <small></small>
            </h3>
        {{ Breadcrumbs::render('unverified-users.edit', $user) }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <link href="{{ asset('assets/admin/css/customPreview.css') }}" rel="stylesheet" type="text/css"/>
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

        @include('admin.partials.success')
        @include('admin.partials.errors')

        <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-edit"></i> {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4>&nbsp;</h4>

                    <form method="POST"  action="{{ route('unverified-users.update', $user->id) }}" class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="first_name" class="col-md-2 control-label">First Name *</label>
                            <div class="col-md-4">
                                <input required type="text" name="first_name" maxlength="150"
                                       value="{{ old('first_name', $user->first_name) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="col-md-2 control-label">Last Name *</label>
                            <div class="col-md-4">
                                <input required type="text" name="last_name" maxlength="150"
                                       value="{{ old('last_name', $user->last_name) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email *</label>
                            <div class="col-md-4">
                                <input required type="text" name="email" maxlength="32" value="{{ old('email', $user->email) }}"
                                       class="form-control" readonly/>
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-md-2 control-label">Phone *</label>
                            <div class="col-md-4">
                                <input required type="text" name="phone" maxlength="20" value="{{ old('phone', $user->phone) }}"
                                       class="form-control phone_us"/>
                            </div>
                            @if ($errors->has('phone'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                            @endif
                        </div>


                        <div class="form-group">
                            <label for="password" class="col-md-2 control-label">Password </label>
                            <div class="col-md-4">
                                <input type="password" name="password" maxlength="32" value=""
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif

                        </div>


                        <div class="form-group" >
                            <div>

                                {{ Form::label('upload_file', 'Upload Picture ', ['class'=>'col-md-2 control-label']) }}
                                <div class="col-md-4">
                                    {{ Form::file('upload_file', ['class' => 'form-control ','onchange'=> 'loadFile(event)','accept'=>"image/jpg, image/png, image/jpeg"]) }}
                                    <img id="image" style="max-width: 350px;height: 150px; margin-top: 16px" onClick="preview(this);"
                                         src="{{$user->profile_picture}}"/>

                                </div>
                                @if ( $errors->has('upload_file') )
                                    <p class="help-block">{{ $errors->first('upload_file') }}</p>
                                @endif


                            </div>
                        </div>


                        <br><br>
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

        var input = document.querySelector("input[name='home_address']"); // get the input element
        input.addEventListener('input', resizeInput); // bind the "resizeInput" callback on "input" event
        resizeInput.call(input); // immediately call the function

        var input = document.querySelector("input[name='office_address']"); // get the input element
        input.addEventListener('input', resizeInput); // bind the "resizeInput" callback on "input" event
        resizeInput.call(input); // immediately call the function

        function resizeInput() {
        this.style.width = this.value.length + "ch";
        }

        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                // window.location.href = "{{ route('new-request.index') }}";
            });
        });


        var loadFile = function(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('image');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };



    </script>

@stop
