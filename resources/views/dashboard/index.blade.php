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
  <div class="container-fluid">
    <div class="row">
      <div class="col">
      </div>
      <div class="col">
        <table class="table table-sm">
          <thead>
            <th>ID</th>
            <th>Title</th>
            <th>Lab</th>
            <th>Status</th>
            <th>Created</th>
          </thead>
          <tbody>
            @foreach($tickets as $ticket)
              <tr>
                <td>{{ $ticket->ticket_id }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->lab }}</td>
                <td>{{ $ticket->status }}</td>
                <td>{{ $ticket->ticket_created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    Echo.channel('tickets')
      .listen('.TicketCreated', (e) => {
          console.log(e);
      })
  </script>
</body>
</html>
