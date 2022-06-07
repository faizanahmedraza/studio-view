@extends('admin.layouts.app')

@section('css')
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
          type="text/css">
    <style>
        #geocoder {
            z-index: 1;
            margin: 20px;
        }

        .mapboxgl-ctrl-geocoder {
            min-width: 100%;
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
        {{ Breadcrumbs::render('studio.edit',$studio) }}
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
                          action="{{ route('studio.edit',$studio->id) }}"
                          class="form-horizontal"
                          role="form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="customer" class="col-md-3 control-label">Customer *</label>
                            <div class="col-md-7">
                                <select name="customer" class="form-control">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $key => $val)
                                        <option value="{{$val->id}}" {{old('customer',$studio->user_id) == $val->id ? 'selected' : ''}}>{{$val->getFullname()}}</option>
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
                                <input type="text" name="studio_name" maxlength="150"
                                       value="{{ old('studio_name',$studio->name) }}"
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
                                <input type="text" name="details" maxlength="150"
                                       value="{{ old('details',$studio->detail) }}"
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
                                @foreach($types as $key => $val)
                                    <label><input class="form-control" name="studio_types[]" type="checkbox"
                                                  data-parent="checkbox{{$key+1}}"
                                                  value="{{$val->id}}" {{  in_array($val->id, (array)old("studio_types",$studio->getStudioTypes->pluck('type_id')->toArray())) ? "checked":"" }}>
                                        {{$val->name}}</label>
                                    <br>
                                @endforeach
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
                                       value="{{ old('minimum_booking_hr',$studio->minimum_booking_hr) }}"
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
                                       value="{{ old('max_occupancy_people',$studio->max_occupancy_people) }}"
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
                                        <option value="{{$key}}" {{old('hours_status',$studio->hours_status) == $key ? 'selected' : ''}}>{{$val}}</option>
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
                                <input type="time" name="hrs_from" value="{{ old('hrs_from',$studio->hrs_from) }}"
                                       class="form-control"/>
                            </div>
                            <div class="col-md-1">
                                To
                            </div>
                            <div class="col-md-3">
                                <input type="time" name="hrs_to" value="{{ old('hrs_to',$studio->hrs_to) }}"
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
                                        <option value="{{$val->id}}" {{old('adv_booking_time',$studio->adv_booking_time_id) == $val->id ? 'selected' : ''}}>{{$val->name}}</option>
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
                                       value="{{ old('past_client',$studio->past_client) }}"
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
                                       value="{{ old('audio_samples',$studio->audio_sample) }}"
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
                                       value="{{ old('amenities',$studio->amenities) }}"
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
                                       value="{{ old('main_equipment',$studio->main_equipment) }}"
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
                                       value="{{ old('rules',$studio->rules) }}"
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
                                       value="{{ old('cancellation_policy',$studio->cancelation_policy) }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('cancellation_policy'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('cancellation_policy') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <h3 class="text-center">Where's your studio located?</h3>

                        <div id="geocoder"></div>

                        <div class="form-group">
                            <label for="cancellation_policy" class="col-md-3 control-label">Address
                                *</label>
                            <div class="col-md-7">
                                <input type="text" id="address" name="address"
                                       value="{{ old('address',$studio->getLocation->address ?? '') }}"
                                       class="form-control" readonly/>
                            </div>
                        </div>

                        <input type="hidden" id="street" name="street"
                               value="{{ old('street',$studio->getLocation->street ?? '') }}"
                               class="form-control"/>

                        <input type="hidden" id="country" name="country"
                               value="{{ old('country',$studio->getLocation->country ?? '') }}"
                               class="form-control"/>

                        <input type="hidden" id="city" name="city"
                               value="{{ old('city',$studio->getLocation->city ?? '') }}"
                               class="form-control"/>

                        <input type="hidden" id="state" name="state"
                               value="{{ old('state',$studio->getLocation->state ?? '') }}"
                               class="form-control"/>

                        <input type="hidden" id="zip_code" name="zip_code"
                               value="{{ old('zip_code',$studio->getLocation->zip_code ?? '') }}"
                               class="form-control"/>


                        <input type="hidden" id="lat" name="lat"
                               value="{{ old('lat',$studio->getLocation->lat ?? '') }}"
                               class="form-control"/>

                        <input type="hidden" id="lng" name="lng"
                               value="{{ old('lng',$studio->getLocation->lng ?? '') }}"
                               class="form-control"/>

                        <div class="form-group">
                            <label for="additional_details" class="col-md-3 control-label">Additional Location Details
                                *</label>
                            <div class="col-md-7">
                                <input type="text" name="additional_details"
                                       value="{{ old('additional_details',$studio->getLocation->additional_details ?? '') }}"
                                       class="form-control"/>
                            </div>
                            @if ($errors->has('additional_details'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('additional_details') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <h3 class="text-center">How much does it cost?</h3>

                        <div class="form-group">
                            <label for="hourly_rate" class="col-md-3 control-label">Price Per Hour *</label>
                            <div class="col-md-7">
                                <input type="number" name="hourly_rate"
                                       value="{{ old('hourly_rate',$studio->getPrice->hourly_rate ?? '') }}"
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
                                           value="1" {{ (old('audio_eng_included',$studio->getPrice->audio_eng_included ?? '') == '1') ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="audio_eng_included_yes">Yes</label>
                                    <input type="radio" id="audio_eng_included_no" name="audio_eng_included"
                                           class="form-check-input"
                                           value="0" {{ (old('audio_eng_included',$studio->getPrice->audio_eng_included ?? '') == '0') ? 'checked' : ''  }}/>
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
                                <input type="number" name="discount"
                                       value="{{ old('discount',$studio->getPrice->discount ?? '') }}"
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
                                <input type="number" name="audio_eng_rate_hr"
                                       value="{{ old('audio_eng_rate_hr',$studio->getPrice->audio_eng_rate_hr ?? '') }}"
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
                                           value="1" {{ (old('audio_eng_discount',$studio->getPrice->audio_eng_discount ?? '') == '1') ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="audio_eng_discount_yes">Yes</label>
                                    <input type="radio" id="audio_eng_discount_no" name="audio_eng_discount"
                                           class="form-check-input"
                                           value="0" {{ (old('audio_eng_discount',$studio->getPrice->audio_eng_discount ?? '') == '0') ? 'checked' : ''  }}/>
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
                                <input type="number" name="other_fees"
                                       value="{{ old('other_fees',$studio->getPrice->other_fees ?? '') }}"
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
                                <input type="number" name="mixing_services"
                                       value="{{ old('mixing_services',$studio->getPrice->mixing_services ?? '') }}"
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

                        <div class="col-12">
                            @forelse($studio->getImages as $key => $val)
                                <img src="{{$val->image_url}}" alt="{{$key}}" style="width: 150px; margin: 10px"/>
                            @empty
                            @endforelse
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Status *</label>
                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_yes" name="status" class="form-check-input"
                                           value="1" {{ (old('status',$studio->status) == '1') ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="status_yes">Approved</label>
                                    <input type="radio" id="status_no" name="status" class="form-check-input"
                                           value="0" {{ (old('status',$studio->status) == '0') ? 'checked' : ''  }}/>
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
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <script src="{{ asset('assets/admin/scripts/core/app.js') }}"></script>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiZmFpemFuYWhtZWRyYXphIiwiYSI6ImNsMWthdDJzazF3am8zZXJ0bDBoemd5NHoifQ.l-XO8G0aOsFypxjsXsAvVg';
        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            types: 'country,region,place'
        });

        geocoder.addTo('#geocoder');

        // Add geocoder result to container.
        geocoder.on('result', (e) => {
            $("#address").val(e.result.place_name);
            $("#street").val(e.result.text);
            $("#lat").val(e.result.geometry.coordinates[0]);
            $("#lng").val(e.result.geometry.coordinates[1]);
            if (e.result.id.split(".")[0] == "region") {
                $("#state").val(e.result.id.split(".")[1]);
            }

            if (e.result.id.split(".")[0] == "country") {
                $("#country").val(e.result.id.split(".")[1]);
            }

            if (e.result.id.split(".")[0] == "place") {
                $("#city").val(e.result.id.split(".")[1]);
            }
            e.result.context.forEach(filterContext);
        });

        function filterContext(item, index) {
            if (item.id.split(".")[0] == "region") {
                $("#state").val(item.text);
            }

            if (item.id.split(".")[0] == "country") {
                $("#country").val(item.text);
            }

            if (item.id.split(".")[0] == "place") {
                $("#city").val(item.text);
            }
            if (item.id.split(".")[0] == "postcode") {
                $("#zip_code").val(e.result.id.split(".")[1]);
            }
        }

        // Clear results container when search is cleared.
        geocoder.on('clear', () => {
            results.innerText = '';
        });

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
