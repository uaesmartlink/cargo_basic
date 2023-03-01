@extends('backend.layouts.app')

@section('content')
<!--begin::Card-->
<div class="card card-custom gutter-b">
    <div class="card-header flex-wrap py-3">
        <div class="card-title">
            <h3 class="card-label">
                {{translate('Booking')}}
            </h3>
        </div>
       
    </div>

    <div class="card-body">
    <form action="" id="kt_form_1" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                
                    
                    <label>{{translate('Customer')}}:</label>
                    <select name="client_id" class="client_name" class="form-control">
                        @foreach($clients as $client)
                        <option value="{{$client->id}}">{{$client->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>{{translate('From:')}}:</label>
                <div class="form-group">
                    <input type="text" placeholder="{{translate('00000')}}" name="first_code" autocomplete="off" class="form-control" id="kt_datepicker_3" disabled/>
                </div>
            </div>
              <div class="col-md-4">
                <label>{{translate('To:')}}:</label>
                <div class="form-group">
                    <input type="text" placeholder="{{translate('00000')}}" name="last_code" autocomplete="off" class="form-control" id="kt_datepicker_3" disabled/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                  <button type="submit" class="btn btn-primary" style="display:block">{{translate('Generate')}}</button>
                </div>
            </div>
        </div>
    </form>
    </div>
</div>
@endsection
@section('modal')
@include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        $('.client_name').select2({
            width: '100%',
            placeholder: "Select client",
        });
        $('#kt_datepicker_3').datepicker({
            orientation: "bottom auto",
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayBtn: true,
            todayHighlight: true,
            startDate: new Date(),
        });

        $(document).ready(function() {
            FormValidation.formValidation(
                document.getElementById('kt_form_1'), {
                    fields: {
                        "first_code": {
                            validators: {
                                notEmpty: {
                                    message: '{{translate("This is required!")}}'
                                }
                            }
                        },
                        "last_code": {
                            validators: {
                                notEmpty: {
                                    message: '{{translate("This is required!")}}'
                                }
                            }
                        },
                        "client_name": {
                            validators: {
                                notEmpty: {
                                    message: '{{translate("This is required!")}}'
                                }
                            }
                        },
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