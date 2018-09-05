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
      <printers :printers-list="{{ $printers }}"></printers>
      </div>
      <div class="col">
        <tickets :tickets-list="{{ $tickets }}"></tickets>
      </div>
    </div>
    <p id="credits">Created by <span>Cayce House</span></p>
  </div>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
