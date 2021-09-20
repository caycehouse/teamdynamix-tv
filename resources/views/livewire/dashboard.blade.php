<div class="grid grid-cols-3 gap-4" wire:poll>
    <div class="col-span-3 md:col-span-1">
        <h5 class="text-yellow-300">Papercut Summary</h5>
        <table class="min-w-full divide-y divide-gray-200 mb-4">
            <tbody>
                @foreach($papercutStatuses as $status)
                <tr>
                    <td>{{ $status->status_name }}</td>
                    <td class="{{ $status->status_name }}">{{ $status->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h5 class="text-yellow-300">Printers in Error ({{ $printers->count() }})</h5>
        <table class="min-w-full divide-y divide-gray-200 mb-4">
            <tbody>
                @foreach($printers as $printer)
                <tr class="{{ $printer->status_color }}">
                    <td>{{ $printer->name }}</td>
                    <td>{{ $printer->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h5 class="text-yellow-300">Devices in Error ({{ $devices->count() }})</h5>
        <table class="min-w-full divide-y divide-gray-200">
            <tbody>
                @foreach($devices as $device)
                <tr class="text-red-700">
                    <td>{{ $device->name }}</td>
                    <td>{{ $device->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-span-3 md:col-span-2">
        <h5 class="text-yellow-300">Unresolved Tickets ({{ $tickets->count() }})</h5>
        <table class="min-w-full divide-y divide-gray-200 mb-4">
            <tbody>
                @foreach($tickets as $ticket)
                <tr onclick="window.open('https://ecu.teamdynamix.com/TDNext/Apps/217/Tickets/TicketDet.aspx?TicketID={{ $ticket->ticket_id }}');"
                    class="{{ $ticket->status_color }}">
                    <td>{{ $ticket->ticket_id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->age }}d</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2 md:co-span-1">
                <h5 class="text-yellow-300">Ticket Resolutions Last Week
                    ({{ $resolutionsLastWeek->sum('closes') }})</h5>
                <table class="min-w-full divide-y divide-gray-200 mb-4">
                    <tbody>
                        @foreach($resolutionsLastWeek as $resolution)
                        <tr>
                            <td>{{ $resolution->name }}</td>
                            <td>{{ $resolution->closes }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-span-2 md:col-span-1">
                <h5 class="text-yellow-300">Ticket Resolutions This Week
                    ({{ $resolutionsThisWeek->sum('closes') }})</h5>
                <table class="min-w-full divide-y divide-gray-200 mb-4">
                    <tbody>
                        @foreach($resolutionsThisWeek as $resolution)
                        <tr>
                            <td>{{ $resolution->name }}</td>
                            <td>{{ $resolution->closes }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-span-3 w-full text-right text-yellow-300">
        Last Checked {{ now()->format('h:i:s T') }}
    </div>
</div>
