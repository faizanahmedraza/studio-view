@extends('admin.layouts.app')

@section('css')
    <style>
        #map_canvas {
            height: 400px;
            max-width: 100%;
            margin: 10px;
            padding: 0px
        }
    </style>
@endsection

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
                            <div class="col-md-7">
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
                            <div class="col-md-7">
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
                            <label for="details" class="col-md-3 control-label">Studio Details *</label>
                            <div class="col-md-7">
                                <input type="text" name="details" maxlength="150" value="{{ old('details') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('details'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('details') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="studio_types" class="col-md-3 control-label">Studio Types *</label>
                            <div class="col-md-7">
                                <div class="row">
                                    @foreach($types as $key => $val)
                                        <div class="col-md-3">
                                            <label><input class="form-control" name="studio_types[]" type="checkbox"
                                                          data-parent="checkbox{{$key+1}}"
                                                          value="{{$val->id}}" {{  (in_array($val->id, (array)old("studio_types")) ? "checked":"") }}>
                                                {{$val->name}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if ($errors->has('studio_types'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('studio_types') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="minimum_booking_hr" class="col-md-3 control-label">Minimum Booking Hour
                                *</label>
                            <div class="col-md-7">
                                <input type="number" id="minimum_booking_hr" name="minimum_booking_hr"
                                       value="{{ old('minimum_booking_hr') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('minimum_booking_hr'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('minimum_booking_hr') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="max_occupancy_people" class="col-md-3 control-label">Max Studio Occupancy
                                *</label>
                            <div class="col-md-7">
                                <input type="number" id="max_occupancy_people" name="max_occupancy_people"
                                       value="{{ old('max_occupancy_people') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('max_occupancy_people'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('max_occupancy_people') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="hours_status" class="col-md-3 control-label">Studio Hours *</label>
                            <div class="col-md-7">
                                <select name="hours_status" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($hoursStatus as $key => $val)
                                        <option value="{{$key}}" {{(int)old('hours_status') == $key ? 'selected' : ''}}>{{$val}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('hours_status'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('hours_status') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="hours_status" class="col-md-3 control-label">Daily From *</label>
                            <div class="col-md-3">
                                <input type="time" name="hrs_from" value="{{ old('hrs_from') }}"
                                       class="form-control"/>
                            </div>
                            <div class="col-md-1">
                                To
                            </div>
                            <div class="col-md-3">
                                <input type="time" name="hrs_to" value="{{ old('hrs_to') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('hrs_from'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('hrs_from') }}</strong>
                                    </span>
                            @endif
                            @if ($errors->has('hrs_to'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('hrs_to') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="adv_booking_time" class="col-md-3 control-label">Time in advance requirement for
                                booking requests
                                *</label>
                            <div class="col-md-7">
                                <select name="adv_booking_time" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($advanceBookingTimes as $key => $val)
                                        <option value="{{$val->id}}" {{old('adv_booking_time') == $val->id ? 'selected' : ''}}>{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('adv_booking_time'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('adv_booking_time') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="past_client" class="col-md-3 control-label">Past Client*</label>
                            <div class="col-md-7">
                                <input type="text" id="past_client" name="past_client"
                                       value="{{ old('past_client') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('past_client'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('past_client') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="audio_samples" class="col-md-3 control-label">Audio Samples *</label>
                            <div class="col-md-7">
                                <input type="text" id="audio_samples" name="audio_samples"
                                       value="{{ old('audio_samples') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('audio_samples'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('audio_samples') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <h3 class="text-center">What features does the studio have?</h3>

                        <div class="form-group">
                            <label for="amenities" class="col-md-3 control-label">Amenities *</label>
                            <div class="col-md-7">
                                <input type="text" id="amenities" name="amenities"
                                       value="{{ old('amenities') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('amenities'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('amenities') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="main_equipment" class="col-md-3 control-label">Main Equipment *</label>
                            <div class="col-md-7">
                                <input type="text" id="main_equipment" name="main_equipment"
                                       value="{{ old('main_equipment') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('main_equipment'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('main_equipment') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <h3 class="text-center">Add studio rules and policy for bookings.</h3>

                        <div class="form-group">
                            <label for="rules" class="col-md-3 control-label">Studio Rules *</label>
                            <div class="col-md-7">
                                <input type="text" id="rules" name="rules"
                                       value="{{ old('rules') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('rules'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('rules') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="cancellation_policy" class="col-md-3 control-label">Cancellation Policy
                                *</label>
                            <div class="col-md-7">
                                <input type="text" id="cancellation_policy" name="cancellation_policy"
                                       value="{{ old('cancellation_policy') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('cancellation_policy'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('cancellation_policy') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <h3 class="text-center">Where's your studio located?</h3>

                        <div class="form-group">
                            <label for="location" class="col-md-3 control-label">Location/City/Address*</label>
                            <div class="col-md-7">
                                <input type="text" name="location" id="location" class="form-control"
                                       placeholder="Choose Location/City/Address">
                            </div>
                        </div>

                        <div id="map_canvas"></div>

                        <input type="hidden" id="address" name="address" value="{{ old('address') }}"
                               class="form-control"/>

                        <input type="hidden" id="street" name="street" value="{{ old('street') }}"
                               class="form-control"/>

                        <input type="hidden" id="country" name="country" value="{{ old('country') }}"
                               class="form-control"/>

                        <input type="hidden" id="city" name="city" value="{{ old('city') }}"
                               class="form-control"/>

                        <input type="hidden" id="state" name="state" value="{{ old('state') }}"
                               class="form-control"/>

                        <input type="hidden" id="zip_code" name="zip_code" value="{{ old('zip_code') }}"
                               class="form-control"/>


                        <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}"
                               class="form-control"/>

                        <input type="hidden" id="lng" name="lng" value="{{ old('lng') }}"
                               class="form-control"/>

                        <div class="form-group">
                            <label for="additional_location_details" class="col-md-3 control-label">Additional Location
                                Details *</label>
                            <div class="col-md-7">
                                <input type="text" name="additional_location_details"
                                       value="{{ old('additional_location_details') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('additional_location_details'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('additional_location_details') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <h3 class="text-center">How much does it cost?</h3>

                        <div class="form-group">
                            <label for="hourly_rate" class="col-md-3 control-label">Price Per Hour *</label>
                            <div class="col-md-7">
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
                            <label class="col-md-3 control-label">Audio Engineer included in rate *</label>
                            <div class="col-md-7">
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
                            <label for="discount" class="col-md-3 control-label">% Discount on bookings longer than 8
                                hours *</label>
                            <div class="col-md-7">
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
                            <label for="audio_eng_rate_hr" class="col-md-3 control-label">Audio English Rate Per Hour
                                *</label>
                            <div class="col-md-7">
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
                            <div class="col-md-7">
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
                            <div class="col-md-7">
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
                            <div class="col-md-7">
                                <input type="number" name="mixing_services" value="{{ old('mixing_services') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('mixing_services'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('mixing_services') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <h3 class="text-center">Add a few photos</h3>

                        <div class="form-group">
                            <label for="images" class="col-md-3 control-label">Images *</label>
                            <div class="col-md-7">
                                <input type="file" name="images[]" value="{{ old('images') }}"
                                       class="form-control" multiple/>
                                <span>JPG,GIF,PNG. Max 10MB.</span>
                            </div>
                            @if ($errors->has('images'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('images') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Status *</label>
                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_yes" name="status" class="form-check-input"
                                           value="1" {{ (old('status') == '1') ? 'checked' : '' }}/>
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
    <script type="text/javascript"
            src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places"></script>

    <script>
        var lat = 37.09024;
        var lng = -95.712891;
        //get location if user browser is supported
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            console.log('Geolocation is not supported by this browser.')
        }

        window.addEventListener("load", initialize);

        function initialize() {
            var myLatlng = new google.maps.LatLng(lat, lng);
            var myOptions = {
                zoom: 12,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            //map view
            var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
            //add marker to current location
            var marker = new google.maps.Marker({
                position: myLatlng,
                map
            });

            var input = document.getElementById('location');
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();

                //set map marker to the search location
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                //set form fields that based on address or location
                $('#address').val(place.formatted_address);
                $('#lat').val(place.geometry['location'].lat());
                $('#lng').val(place.geometry['location'].lng());
                for (var i = 0; i < place.address_components.length; i++) {
                    for (var j = 0; j < place.address_components[i].types.length; j++) {
                        if (place.address_components[i].types[j] == "country") {
                            $('#country').val(place.address_components[i].long_name);
                        }
                        if (place.address_components[i].types[j] == "locality") {
                            $('#city').val(place.address_components[i].long_name);
                        }
                        if (place.address_components[i].types[j] == "administrative_area_level_1") {
                            $('#state').val(place.address_components[i].long_name);
                        }
                        if (place.address_components[i].types[j] == "postal_code") {
                            $('#zip_code').val(place.address_components[i].long_name);
                        }
                        if (place.address_components[i].types[j] == "street_address") {
                            $('#street').val(place.address_components[i].long_name);
                        }
                    }
                }
            });
        }

        function showPosition(position) {
            lat = position.coords.latitude;
            lng = position.coords.longitude;
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    console.log('User denied the request for Geolocation.')
                    break;
                case error.POSITION_UNAVAILABLE:
                    console.log('Location information is unavailable.')
                    break;
                case error.TIMEOUT:
                    console.log('The request to get user location timed out.')
                    break;
                case error.UNKNOWN_ERROR:
                    console.log('An unknown error occurred.')
                    break;
            }
        }

        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            Admin.init();
            $('#cancel').click(function () {
                window.location.href = "{{route('studio.index') }}";
            });
        });
    </script>
@endsection
