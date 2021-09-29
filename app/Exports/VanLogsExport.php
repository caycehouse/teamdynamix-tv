<?php

namespace App\Exports;

use App\Models\VanLog;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VanLogsExport implements FromView
{
    public function view(): View
    {
        return view('exports.vanlogs', [
            'van_logs' => VanLog::withTrashed()->with('employee')->get()
        ]);
    }
}
