@extends('layouts.app')

@section('content')
<div class="row">
    @isset($papercutStatuses)
        <div class="col col-lg-4">
            <papercut-statuses :papercut-statuses-list="{{ $papercutStatuses }}"></papercut-statuses>
            <printers :printers-list="{{ $printers }}"></printers>
            <devices :devices-list="{{ $devices }}"></devices>
        </div>
    @endisset
    <div class="col">
        <tickets :tickets-list="{{ $tickets }}"></tickets>
        @isset($resolutionsLastWeek)
            <div class="row">
                <div class="col">
                    <resolutions title='Last Week' :resolutions-list="{{ $resolutionsLastWeek }}"></resolutions>
                </div>
                <div class="col">
                    <resolutions title='This Week' :resolutions-list="{{ $resolutionsThisWeek }}"></resolutions>
                </div>
            </div>
        @endisset
    </div>
</div>
@endsection
