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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand text-warning" href="#">Labtechs TV</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active" id="credits">
                    <a class="nav-link" href="#">Created & designed by <span class="text-warning">Cayce House<span></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid mt-3" id="app">
        <div class="row">
            <div class="col-3">
                <papercut-statuses :papercut-statuses-list="{{ $papercutStatuses }}"></papercut-statuses>
                <printers :printers-list="{{ $printers }}"></printers>
                <stats :stats-list="{{ $stats }}"></stats>
            </div>
            <div class="col">
                <tickets :tickets-list="{{ $tickets }}"></tickets>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
