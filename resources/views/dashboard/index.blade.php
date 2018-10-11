@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <papercut-statuses :papercut-statuses-list="{{ $papercutStatuses }}"></papercut-statuses>
        <printers :printers-list="{{ $printers }}"></printers>
        <devices :devices-list="{{ $devices }}"></devices>
    </div>
    <div class="col-8">
        <tickets :tickets-list="{{ $tickets }}"></tickets>
    </div>
</div>
@endsection
