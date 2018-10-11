@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-3">
        <papercut-statuses :papercut-statuses-list="{{ $papercutStatuses }}"></papercut-statuses>
        <printers :printers-list="{{ $printers }}"></printers>
        <devices :devices-list="{{ $devices }}"></devices>
    </div>
    <div class="col">
        <tickets :tickets-list="{{ $tickets }}"></tickets>
    </div>
</div>
@endsection
