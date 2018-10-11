<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Show the stats.
     *
     * @return Response
     */
    public function __invoke()
    {
        $fromDate = Carbon::now()->startOfWeek()->toDateTimeString();
        $tillDate = Carbon::now()->toDateTimeString();
        $stats = Ticket::resolved()->studentComputerLabs()->whereBetween('resolved_at', [$fromDate, $tillDate])
            ->groupBy('resolved_by')->select('resolved_by', DB::raw('count(*) as total'))->orderBy('total', 'desc')->get();

        return view('stats.index', [
            'stats' => $stats
        ]);
    }
}
