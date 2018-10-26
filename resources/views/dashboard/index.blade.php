@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col col-lg-4">
        <papercut-statuses :papercut-statuses-list="{{ $papercutStatuses }}"></papercut-statuses>
        <printers :printers-list="{{ $printers }}"></printers>
        <devices :devices-list="{{ $devices }}"></devices>
    </div>
    <div class="col">
        <tickets :tickets-list="{{ $tickets }}"></tickets>
        <stats :stats-list="{{ $stats }}"></stats>
    </div>
</div>
@endsection
