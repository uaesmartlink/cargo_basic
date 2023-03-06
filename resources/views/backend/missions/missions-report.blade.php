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
        <form method="POST" action="{{route('admin.missions.post.get-report')}}" >
            @csrf
            <div class="mb-7">
                <div class="row align-items-center">

                        <div class="col-lg-12 col-xl-12">
                            <div class="row align-items-center">
                                        @if($user_type == 'customer')
                                            @php
                                                $user  = App\Client::where('id',Auth::user()->userClient->client_id)->first();
                                            @endphp
                                            <input type="hidden" name="client_id" value="{{$user->id}}" />
                                        @else
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <label class="mb-0 mr-3 d-none d-md-block">{{translate('Customer')}}:</label>
                                                <select name="client_id" class="form-control client" id="kt_datatable_search_status">
                                                    <option value="">{{translate('All')}}</option>
                                                    @foreach(\App\Client::where('is_archived',0)->get() as $client)
                                                     <option @if(isset($_POST['client_id']) && $_POST['client_id'] == $client->id)  selected @endif value="{{$client->id}}">{{$client->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif



                            </div>
                            <div class="row align-items-center">
                                <input type="hidden" name="branch_id" value="1" >
                                {{-- Hide For Demo --}}
                                {{-- <div class="my-2 col-md-4 my-md-5">
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mr-3 d-none d-md-block">{{translate('Branch')}}:</label>
                                        <select name="branch_id" class="form-control branch" id="kt_datatable_search_type">
                                        <option value="">{{translate('All')}}</option>
                                            @foreach(\App\Branch::where('is_archived',0)->get() as $Branch)
                                            <option @if(isset($_POST['branch_id']) && $_POST['branch_id'] == $Branch->id)  selected @endif value="{{$Branch->id}}">{{$Branch->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mr-3 d-none d-md-block">{{translate('Driver')}}:</label>
                                        <select name="captain" class="form-control">
                                        <option value="">{{translate('All')}}</option>
                                        @foreach(\App\Captain::where('is_archived',0)->get() as $captain)
                                            <option @if(isset($_POST['captain_id']) && $_POST['captain_id'] == $captain->id)  selected @endif value="{{$captain->id}}">{{$captain->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mr-3 d-none d-md-block">{{translate('Status')}}:</label>
                                        <select name="status" class="form-control status">
                                        <option value="">{{translate('All')}}</option>
                                            @foreach(\App\Mission::getStatus() as $status_info)
                                            {{-- <option @if(isset($_POST['status']) && $_POST['status'] == $status_info['status'])  selected @endif value="{{$status_info['status']}}">{{$status_info['text']}}</option> --}}
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">

                                <div class="my-2 col-md-4 my-md-5">
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mr-3 d-none d-md-block">{{translate('From Date')}}:</label>
                                        <input type="text" name="from_date" value="<?php if(isset($_POST['from_date'])){echo $_POST['from_date'];}?>" class="form-control datepicker" placeholder="{{translate('Created From Date')}}" id="kt_datatable_search_query" />
                                    </div>
                                </div>
                                <div class="my-2 col-md-4 my-md-5">
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mr-3 d-none d-md-block">{{translate('From Date')}}:</label>
                                        <input type="text" name="to_date" value="<?php if(isset($_POST['to_date'])){echo $_POST['to_date'];}?>" class="form-control datepicker" placeholder="{{translate('Created To Date')}}" id="kt_datatable_search_query" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 col-lg-3 col-xl-4 mt-lg-0">
                            <button type="submit" class="px-6 btn btn-light-primary font-weight-bold">{{translate('Get Report')}}</button>
                            <input type="submit" class="px-6 btn btn-light-primary font-weight-bold" name="excel" value="{{translate('Export Excel Sheet')}}" />
                        </div>

            </div>
            </div>
            </form>

            <table class="table mb-0 aiz-table">
                <thead>
                    <tr>
                        <th width="3%"></th>
                        <th width="3%">#</th>
                        <th>{{translate('Mission Code')}}</th>
                        <th>{{translate('Shipment Code')}}</th>
                        <th>{{translate('Phone')}}</th>
                        <th>{{translate('Address')}}</th>
                        <th>{{translate('Status')}}</th>
                        <th>{{translate('Driver')}}</th>
                        <th>{{translate('Type')}}</th>

                        <th>{{translate('Amount')}}</th>
                        @if(isset($show_due_date)) <th>{{translate('Due Date') ?? translate('Due Date') }}</th> @endif

                        <th class="text-center">{{translate('Options')}}</th>
                        {{-- <th class="text-center">Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($missions as $key=>$mission)

                    <tr>
                        <td><label class="checkbox checkbox-success"><input class="ms-check" type="checkbox" name="checked_ids[]" value="{{$mission->id}}" /><span></span></label></td>
                        @if($user_type == 'admin' || in_array('1100', $staff_permission) || in_array('1008', $staff_permission) )
                            <td width="3%"><a href="{{route('admin.missions.show', $mission->id)}}">{{ ($key+1) + ($missions->currentPage() - 1)*$missions->perPage() }}</a></td>
                            <td width="5%"><a href="{{route('admin.missions.show', $mission->id)}}">{{$mission->code}}</a></td>
                            <td width="5%"><a href="{{route('admin.shipments.show', ['shipment'=>$mission->shipment_mission[0]->shipment->id])}}">{{$mission->shipment_mission[0]->shipment->code}}</a></td>
                        @else
                            <td width="3%">{{ ($key+1) + ($missions->currentPage() - 1)*$missions->perPage() }}</td>
                            <td width="5%"><a href="{{route('admin.missions.show', $mission->id)}}">{{$mission->code}}</a></td>
                            <td width="5%"><a href="{{route('admin.shipments.show', ['shipment'=>$mission->shipment_mission[0]->shipment->id])}}">{{$mission->shipment_mission[0]->shipment->code}}</a></td>
                        @endif
                        <td>{{ $mission->getOriginal('type') == 1 ? $mission->shipment_mission[0]->shipment->client_phone : $mission->shipment_mission[0]->shipment->reciver_phone }}</td>
                                            <td>{{ $mission->getOriginal('type') == 1 ? $mission->address : $mission->shipment_mission[0]->shipment->reciver_address }}

                        <td><span class="btn btn-sm btn-{{\App\Mission::getStatusColor($mission->status_id)}}">{{$mission->getStatus()}}</span></td>
                        @if ($mission->captain_id)
                            @if($user_type == 'admin' || in_array('1007', $staff_permission) )
                                <td><a href="{{route('admin.captains.show', $mission->captain->id)}}">{{$mission->captain->name}}</a></td>
                            @else
                                <td>{{$mission->captain->name}}</td>
                            @endif
                        @else
                            <td>{{translate('No Driver')}}</td>
                        @endif
                        <td>{{$mission->type}}</td>
                        @php
                            $helper = new \App\Http\Helpers\TransactionHelper();
                            $shipment_cost = $helper->calcMissionShipmentsAmount($mission->getOriginal('type'),$mission->id);
                        @endphp

                        <td>{{format_price($shipment_cost)}}</td>
                        @if(isset($show_due_date)) <td>{{$mission->due_date ?? "-"}}</td> @endif

                        <td class="text-center">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('admin.missions.show', $mission->id)}}" title="{{ translate('Show') }}">
                                <i class="las la-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
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
