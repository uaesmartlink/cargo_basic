@extends('backend.layouts.app')

@php
    $user_type = Auth::user()->user_type;
    $staff_permission = json_decode(Auth::user()->staff->role->permissions ?? "[]");
@endphp

@section('content')

<div class="mt-2 mb-3 text-left aiz-titlebar">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Shipments Report')}}</h1>
        </div>
    </div>
</div>

<!--begin::Card-->
<div class="card card-custom gutter-b">
    <div class="flex-wrap py-3 card-header">
        <div class="card-title">
            <h3 class="card-label">
                {{$page_name}}
            </h3>
        </div>

    </div>

    <div class="card-body">
        <!--begin::Search Form-->





        </div>
        <!--end::Search Form-->

    </div>
</div>
{!! hookView('shipment_addon',$currentView) !!}

@endsection

@section('modal')
@include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
$('.datepicker').datepicker({
            orientation: "bottom auto",
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayBtn: true,
            todayHighlight: true,

        });
    function openCaptainModel(element, e) {
        var selected = [];
        $('.sh-check:checked').each(function() {
            selected.push($(this).data('clientid'));
        });
        if(selected.length == 1)
        {
            $('#tableForm').attr('action', $(element).data('url'));
            $('#tableForm').attr('method', $(element).data('method'));
            $('#assign-to-captain-modal').modal('toggle');
        }else if(selected.length == 0)
        {
            Swal.fire("{{translate('Please Select Shipments')}}", "", "error");
        }else if(selected.length > 1)
        {

            Swal.fire("{{translate('Select shipments of the same client to Assign')}}", "", "error");
        }
    }

    function openAssignShipmentCaptainModel(element, e) {
        var selected = [];
        $('.sh-check:checked').each(function() {
            selected.push($(this).data('clientid'));
        });
        if(selected.length == 1)
        {
            $('#tableForm').attr('action', $(element).data('url'));
            $('#tableForm').attr('method', $(element).data('method'));
            $('#assign-to-captain-modal').modal('toggle');
        }else if(selected.length == 0)
        {
            Swal.fire("{{translate('Please Select Shipments')}}", "", "error");
        }else if(selected.length > 1)
        {

            Swal.fire("{{translate('Select shipments of the same client to Assign')}}", "", "error");
        }
    }

    $('.type').select2({
        placeholder: "Type",
    });

    $('.status').select2({
        placeholder: "Status",
    });

    $('.branch').select2({
        placeholder: 'Branch',
        language: {
          noResults: function() {
            return `<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.branchs.create')}}"
              class="btn btn-primary" >Manage {{translate('Branchs')}}</a>
              </li>`;
          },
        },
        escapeMarkup: function(markup) {
          return markup;
        },
    });

    $('.client').select2({
        placeholder: 'Client',
        language: {
          noResults: function() {
            return `<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{route('admin.clients.create')}}"
              class="btn btn-primary" >Manage {{translate('Customers')}}</a>
              </li>`;
          },
        },
        escapeMarkup: function(markup) {
          return markup;
        },
    });

    $(document).ready(function() {
        $('.action-caller').on('click', function(e) {
            e.preventDefault();
            $('#tableForm').attr('action', $(this).data('url'));
            $('#tableForm').attr('method', $(this).data('method'));
            $('#tableForm').submit();
        });

    });
</script>

@endsection
