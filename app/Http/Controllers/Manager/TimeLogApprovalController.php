<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;

class TimeLogApprovalController extends Controller
{
    public function index()
    {
        $logs = TimeLog::where('status', 'pending')
            ->with('user')
            ->orderBy('log_date')
            ->get();

        return view('manager.time_logs.index', compact('logs'));
    }

    public function approve(TimeLog $timeLog)
    {
        $timeLog->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Logi approveitud');
    }

    public function reject(TimeLog $timeLog)
    {
        $timeLog->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Logi tagasi lükatud');
    }
}