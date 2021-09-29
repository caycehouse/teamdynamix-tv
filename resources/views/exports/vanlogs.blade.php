<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Employee</th>
        <th>Checked Out</th>
        <th>Checked In</th>
    </tr>
    </thead>
    <tbody>
    @foreach($van_logs as $van_log)
        <tr>
            <td>{{ $van_log->van->name }}</td>
            <td>{{ $van_log->employee->name }}</td>
            <td>{{ $van_log->created_at }}</td>
            <td>{{ $van_log->deleted_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
