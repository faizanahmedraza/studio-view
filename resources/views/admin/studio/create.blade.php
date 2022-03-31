@extends('admin.layouts.app')

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">{{ $pageTitle }}
                <small></small>
            </h3>
        {{ Breadcrumbs::render('studio.create') }}
        <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

        @include('admin.partials.errors')

        <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-plus"></i> {{ $pageTitle }}
                    </div>
                </div>

                <div class="portlet-body">

                    <h4>&nbsp;</h4>

                    <form method="POST"
                          action="{{ route('studio.store') }}"
                          class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="customer" class="col-md-3 control-label">Customer *</label>
                            <div class="col-md-4">
                                <select name="customer" class="form-control">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $key => $val)
                                        <option value="{{$val->id}}" {{old('customer') == $val->id ? 'selected' : ''}}>{{$val->getFullname()}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('customer'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('customer') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="studio_name" class="col-md-3 control-label">Studio Name *</label>
                            <div class="col-md-4">
                                <input type="text" name="studio_name" maxlength="150" value="{{ old('studio_name') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('studio_name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('studio_name') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="hourly_rate" class="col-md-3 control-label">Hourly Rate *</label>
                            <div class="col-md-4">
                                <input type="number" name="hourly_rate" value="{{ old('hourly_rate') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('hourly_rate'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('hourly_rate') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Audio English Included *</label>
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="audio_eng_included_yes" name="audio_eng_included"
                                           class="form-check-input"
                                           value="1" {{ (old('audio_eng_included',1) == '1') ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="audio_eng_included_yes">Yes</label>
                                    <input type="radio" id="audio_eng_included_no" name="audio_eng_included"
                                           class="form-check-input"
                                           value="0" {{ (old('audio_eng_included') == '0') ? 'checked' : ''  }}/>
                                    <label class="form-check-label" for="audio_eng_included_no">No</label>
                                </div>
                            </div>
                            @if ($errors->has('audio_eng_included'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('audio_eng_included') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="discount" class="col-md-3 control-label">Discount *</label>
                            <div class="col-md-4">
                                <input type="number" name="discount" value="{{ old('discount') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('discount'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('discount') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="audio_eng_rate_hr" class="col-md-3 control-label">Audio English Rate Hour
                                *</label>
                            <div class="col-md-4">
                                <input type="number" name="audio_eng_rate_hr" value="{{ old('audio_eng_rate_hr') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('audio_eng_rate_hr'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('audio_eng_rate_hr') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Audio English Discount *</label>
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="audio_eng_discount_yes" name="audio_eng_discount"
                                           class="form-check-input"
                                           value="1" {{ (old('audio_eng_discount',1) == '1') ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="audio_eng_discount_yes">Yes</label>
                                    <input type="radio" id="audio_eng_discount_no" name="audio_eng_discount"
                                           class="form-check-input"
                                           value="0" {{ (old('audio_eng_discount') == '0') ? 'checked' : ''  }}/>
                                    <label class="form-check-label" for="audio_eng_discount_no">No</label>
                                </div>
                            </div>
                            @if ($errors->has('audio_eng_discount'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('audio_eng_discount') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="other_fees" class="col-md-3 control-label">Other Fees *</label>
                            <div class="col-md-4">
                                <input type="number" name="other_fees" value="{{ old('other_fees') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('other_fees'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('other_fees') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="mixing_services" class="col-md-3 control-label">Mixing Services *</label>
                            <div class="col-md-4">
                                <input type="number" name="mixing_services" value="{{ old('mixing_services') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('mixing_services'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('mixing_services') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="studio_types" class="col-md-3 control-label">Studio Types *</label>
                            <div class="col-md-4">
                                <select name="studio_types[]" class="form-control" multiple>
                                    @foreach($types as $key => $val)
                                        <option value="{{$val->id}}" {{  (in_array($val->id, (array)old("studio_types")) ? "selected":"") }}>{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('studio_types'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('studio_types') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="images" class="col-md-3 control-label">Images *</label>
                            <div class="col-md-4">
                                <input type="file" name="images[]" value="{{ old('images') }}"
                                       class="form-control" multiple/>
                            </div>
                            @if ($errors->has('images'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('images') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="address" class="col-md-3 control-label">Address *</label>
                            <div class="col-md-4">
                                <input type="text" name="address" value="{{ old('address') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('address'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="street" class="col-md-3 control-label">Street *</label>
                            <div class="col-md-4">
                                <input type="text" name="street" value="{{ old('street') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('street'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('street') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="country" class="col-md-3 control-label">Country *</label>
                            <div class="col-md-4">
                                <input type="text" name="country" value="{{ old('country') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('country'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="city" class="col-md-3 control-label">City *</label>
                            <div class="col-md-4">
                                <input type="text" name="city" value="{{ old('city') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('city'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="state" class="col-md-3 control-label">State/Province *</label>
                            <div class="col-md-4">
                                <input type="text" name="state" value="{{ old('state') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('state'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="zip_code" class="col-md-3 control-label">Zip Code *</label>
                            <div class="col-md-4">
                                <input type="text" name="zip_code" value="{{ old('zip_code') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('zip_code'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('zip_code') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="lat" class="col-md-3 control-label">Latitude *</label>
                            <div class="col-md-4">
                                <input type="text" name="lat" value="{{ old('lat') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('lat'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('lat') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="lng" class="col-md-3 control-label">Longitude *</label>
                            <div class="col-md-4">
                                <input type="text" name="lng" value="{{ old('lng') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('lng'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('lng') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="additional_details" class="col-md-3 control-label">Additional Details *</label>
                            <div class="col-md-4">
                                <input type="text" name="additional_details" value="{{ old('additional_details') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('additional_details'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('additional_details') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Status *</label>
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_yes" name="status" class="form-check-input"
                                           value="1" {{ (old('status') == '1' ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="status_yes">Approved</label>
                                    <input type="radio" id="status_no" name="status" class="form-check-input"
                                           value="0" {{ (old('status') == '0') ? 'checked' : ''  }}/>
                                    <label class="form-check-label" for="status_no">Not Approved</label>
                                </div>
                            </div>
                            @if ($errors->has('status'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
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
    <script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
    <script>
        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{{route('studio.index') }}";
            });
        });
    </script>
@stop
