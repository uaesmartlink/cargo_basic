
@php
    $user_type = Auth::user()->user_type;
    $staff_permission = json_decode(Auth::user()->staff->role->permissions ?? "[]");
@endphp



@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
