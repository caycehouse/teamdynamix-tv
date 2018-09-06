<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div class="container-fluid mt-3" id="app">
    <div class="row">
      <div class="col-3">
        <h2>Printers in Error</h2>
        <printers :printers-list="{{ $printers }}"></printers>
        <h2>Resolved Ticket Stats <span class="text-light h6">by week</span></h2>
        <stats :stats-list="{{ $stats }}"></stats>
      </div>
      <div class="col">
        <h2>Unresolved Tickets</h2>
        <tickets :tickets-list="{{ $tickets }}"></tickets>
      </div>
    </div>
    <p id="credits">Created by <span>Cayce House</span></p>
  </div>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
