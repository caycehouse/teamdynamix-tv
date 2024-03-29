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
        <table class="min-w-full divide-y divide-gray-200 mb-4">
            <tbody>
                @foreach($devices as $device)
                <tr class="text-red-700">
                    <td>{{ $device->name }}</td>
                    <td>{{ $device->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h5 class="text-yellow-300">Vans Checked Out ({{ $van_logs->count() }})</h5>
        <form class="w-full max-w-lg" wire:submit.prevent="submit">
            <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <select
                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                wire:model="van_id">
                <option value="">Select a Van</option>
                @foreach($vans as $van)
                    <option value="{{ $van->id }}">{{ $van->name }}</option>
                @endforeach
            </select>
            @error('van_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
              <div class="w-full md:w-1/2 px-3">
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" wire:model="employee_id" type="text" placeholder="Swipe your 1 Card">
                @error('employee_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>
            </div>
        </form>
        <table class="min-w-full divide-y divide-gray-200 mb-4">
            <tbody>
                @foreach($van_logs as $van_log)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $van_log->van->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $van_log->employee->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                        <button class="bg-transparent hover:bg-purple-900 text-yellow-300 font-semibold hover:text-white py-2 px-4 border border-purple-500 hover:border-transparent rounded" wire:click="vanCheckIn({{ $van_log->id }})">
                            Check In
                        </button>
                    </td>
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
            <div class="col-span-2 md:col-span-1">
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
