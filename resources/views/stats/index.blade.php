@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <stats :stats-list="{{ $stats }}"></stats>
    </div>
</div>
@endsection
