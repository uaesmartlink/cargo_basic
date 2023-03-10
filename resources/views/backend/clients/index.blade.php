@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Customers')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="{{ route('admin.clients.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New Customers')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Customers')}}</h5>
    </div>

</div>
{!! hookView('spot-cargo-shipment-client-addon',$currentView) !!}

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
