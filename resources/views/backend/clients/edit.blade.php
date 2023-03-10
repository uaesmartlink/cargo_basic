@php
    $checked_google_map = \App\BusinessSetting::where('type', 'google_map')->first();
    $is_def_mile_or_fees = \App\ShipmentSetting::getVal('is_def_mile_or_fees');
    $countries = \App\Country::where('covered',1)->get();
    $user_type = Auth::user()->user_type;
@endphp

@extends('backend.layouts.app')

@section('content')

<div class="mx-auto col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Customer Information')}}</h5>
        </div>

        <form class="form-horizontal" action="{{ route('admin.clients.update',['client'=>$client->id]) }}" id="kt_form_1" method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PATCH') }}

        </form>

    </div>
</div>

@endsection

@section('script')
<script src="{{ static_asset('assets/dashboard/js/geocomplete/jquery.geocomplete.js') }}"></script>
<script src="//maps.googleapis.com/maps/api/js?libraries=places&key={{$checked_google_map->key}}"></script>

<script type="text/javascript">

    @foreach($client->addressess as $key => $address)
        $('.address-client-{{$address->id}}').each(function(){
            var address = $(this);

            var lat = '{{$address->client_lat}}';
            lat = parseFloat(lat);
            var lng = '{{$address->client_lng}}';
            lng = parseFloat(lng);

            address.geocomplete({
                map: ".map_canvas_{{$address->id}}.map-client_{{$address->id}}",
                mapOptions: {
                    zoom: 8,
                    center: { lat: lat, lng: lng },

                },
                markerOptions: {
                    draggable: true
                },
                details: ".location-client-{{$address->id}}",
                detailsAttribute: 'data-client',
                autoselect: true,
                restoreValueAfterBlur: true,
            });
            address.bind("geocode:dragged", function(event, latLng){
                $("input[data-client=lat]").val(latLng.lat());
                $("input[data-client=lng]").val(latLng.lng());
            });
        });
    @endforeach

    $('.address-client').each(function(){
        var address = $(this);
        address.geocomplete({
            map: ".map_canvas.map-client",
            mapOptions: {
                zoom: 8,
                center: { lat: -34.397, lng: 150.644 },
            },
            markerOptions: {
                draggable: true
            },
            details: ".location-client",
            detailsAttribute: 'data-client',
            autoselect: true,
            restoreValueAfterBlur: true,
        });
        address.bind("geocode:dragged", function(event, latLng){
            $("input[data-client=lat]").val(latLng.lat());
            $("input[data-client=lng]").val(latLng.lng());
        });
    });

    //Address Types Repeater
    $('#kt_repeater_1').repeater({
        initEmpty: false,

        show: function() {


            var repeater_item = $(this);

            var map_canvas  = repeater_item.find(".map_canvas.map-client");
            var map_data    = repeater_item.find(".location-client");
            repeater_item.find(".address").geocomplete({
                map: map_canvas,
                mapOptions: {
                    zoom: 18,
                    center: { lat: -34.397, lng: 150.644 },
                },
                markerOptions: {
                    draggable: true
                },
                details: map_data,
                detailsAttribute: "data-client",
                autoselect: true,
                restoreValueAfterBlur: true,
            });
            repeater_item.find(".address").bind("geocode:dragged", function(event, latLng){
                repeater_item.find("input[data-client=lat]").val(latLng.lat());
                repeater_item.find("input[data-client=lng]").val(latLng.lng());
            });

            $(this).slideDown();
            // changeCountry();
            initStetes();
            changeState();
            selectPlaceholder();
        },

        hide: function(deleteElement) {
            $(this).slideUp(deleteElement);
        },

        isFirstItemUndeletable: true,
        required: true,

    });

    function changeCountry()
    {
        $('.change-country-client-address').change(function() {
            // var id = $(this).parent().find( ".change-country-client-address" ).val();
            var id = 231;
            var row = $(this).closest(".row");
            var state_input = row.find(".change-state-client-address");
            var state_name  = state_input.attr("name");

            $.get("{{route('admin.shipments.get-states-ajax')}}?country_id=" + id, function(data) {
                $('select[name ="'+state_name+'"]').empty();

                $('select[name ="'+state_name+'"]').append('<option value=""></option>');
                for (let index = 0; index < data.length; index++) {
                    const element = data[index];
                    $('select[name ="'+state_name+'"]').append('<option value="' + element['id'] + '">' + element['name'] + '</option>');
                }


            });
        });
    }
    // changeCountry();

    initStetes();
    function initStetes(){
        // var id = $(this).val();
        console.log("Hello");
        var id = 231;
        $.get("{{route('admin.shipments.get-states-ajax')}}?country_id=" + id, function(data) {
            $('select[name ="address[0][state_id]"]').empty();
            $('select[name ="address[0][state_id]"]').append('<option value=""></option>');
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                console.log(element['name']);
                $('select[name ="address[0][state_id]"]').append('<option value="' + element['id'] + '">' + element['name'] + '</option>');
            }
        });
    }

    function changeState()
    {
        $('.change-state-client-address').change(function() {

            var id = $(this).parent().find( ".change-state-client-address" ).val();
            var row = $(this).closest(".row");
            var area_input = row.find(".change-area-client-address");
            var area_name  = area_input.attr("name");
            $.get("{{route('admin.shipments.get-areas-ajax')}}?state_id=" + id, function(data) {
                $('select[name ="'+area_name+'"]').empty();
                $('select[name ="'+area_name+'"]').append('<option value=""></option>');
                for (let index = 0; index < data.length; index++) {
                    const element = data[index];
                    $('select[name ="'+area_name+'"]').append('<option value="' + element['id'] + '">' + element['name'] + '</option>');
                }
            });
        });
    }
    changeState();


    function selectPlaceholder()
    {
        $('.branch').select2({
            placeholder: 'Select Branch',
            language: {
            noResults: function() {
                return `<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.branchs.create')}}?redirect=admin.captains.create"
                class="btn btn-primary" >Manage {{translate('Branchs')}}</a>
                </li>`;
            },
            },
            escapeMarkup: function(markup) {
            return markup;
            },
        });
        $('.select-area').select2({
            placeholder: "Select Area",
            language: {
            noResults: function() {
                @if($user_type == 'admin' || in_array('1105', $staff_permission) )
                    return `<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.areas.create')}}?redirect=admin.shipments.create"
                    class="btn btn-primary" >Manage {{translate('Areas')}}</a>
                    </li>`;
                @else
                    return ``;
                @endif
            },
            },
            escapeMarkup: function(markup) {
            return markup;
            },
        });
        $('.select-country').select2({
            placeholder: "Select country",
            language: {
            noResults: function() {
                @if($user_type == 'admin' || in_array('1105', $staff_permission) )
                    return `<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.shipments.covered_countries')}}?redirect=admin.shipments.create"
                    class="btn btn-primary" >Manage {{translate('Countries')}}</a>
                    </li>`;
                @else
                    return ``;
                @endif
            },
            },
            escapeMarkup: function(markup) {
            return markup;
            },
        });
        $('.select-state').select2({
            placeholder: "Select state",
            language: {
            noResults: function() {
                @if($user_type == 'admin' || in_array('1105', $staff_permission) )
                    return `<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.shipments.covered_countries')}}?redirect=admin.shipments.create"
                    class="btn btn-primary" >Manage {{translate('States')}}</a>
                    </li>`;
                @else
                    return ``;
                @endif
            },
            },
            escapeMarkup: function(markup) {
            return markup;
            },
        });
    }
    selectPlaceholder();



    $(document).ready(function() {
        FormValidation.formValidation(
            document.getElementById('kt_form_1'), {
                fields: {
                    "Client[name]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    // "Client[email]": {
                    //     validators: {
                    //         notEmpty: {
                    //             message: '{{translate("This is required!")}}'
                    //         },
                    //         emailAddress: {
                    //             message: '{{translate("This is should be valid email!")}}'
                    //         }
                    //     }
                    // },
                    "Client[responsible_name]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Client[responsible_mobile]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "Client[branch_id]": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("Client Branch is required!")}}'
                            }
                        }
                    },
                    "state_id":{
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "area_id":{
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    },
                    "address": {
                        validators: {
                            notEmpty: {
                                message: '{{translate("This is required!")}}'
                            }
                        }
                    }


                },


                plugins: {
                    autoFocus: new FormValidation.plugins.AutoFocus(),
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap(),
                    // Validate fields when clicking the Submit button
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    // Submit the form when all fields are valid
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    icon: new FormValidation.plugins.Icon({
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh',
                    }),
                }
            }
        );
    });
</script>
@endsection
