<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeLogRequest;
use App\Http\Requests\UpdateTimeLogRequest;
use App\Models\TimeLog;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
  public function index(Request $request)
{
    $query = auth()->user()->timeLogs()->orderByDesc('log_date');

    if ($request->filled('from')) {
        $query->whereDate('log_date', '>=', $request->from);
    }
    if ($request->filled('to')) {
        $query->whereDate('log_date', '<=', $request->to);
    }

    $logs = $query->get();

    return view('time_logs.index', compact('logs'));
}

      public function store(StoreTimeLogRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        TimeLog::create($data);

        return redirect()->back()->with('success', 'Log submitted');
    }


   public function update(UpdateTimeLogRequest $request, TimeLog $timeLog)
{
    $this->authorize('update', $timeLog);

     $timeLog->update(array_merge(
        $request->validated(),
        [
            'status' => 'pending',
            'approved_by' => null,
        ]
    ));

    return redirect()->back()->with('success', 'Log updated');
}

public function export(Request $request)
{
    $query = auth()->user()->timeLogs()->orderByDesc('log_date');

    if ($request->filled('from')) {
        $query->whereDate('log_date', '>=', $request->from);
    }
    if ($request->filled('to')) {
        $query->whereDate('log_date', '<=', $request->to);
    }

    $logs = $query->get();

    $filename = 'time_logs_' . now()->format('Y-m-d_H-i') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($logs) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Date', 'Arrival', 'Departure', 'Status']);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->log_date,
                $log->arrival_time,
                $log->departure_time,
                $log->status,
            ]);
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}
}
