<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use App\Models\TimeLogAudit;

class TimeLogApprovalController extends Controller
{
    public function index()
    {

        $pendingLogs = TimeLog::where('status', 'pending')->with('user')->get();
        $historyLogs = TimeLog::whereIn('status', ['approved', 'rejected'])->with('user')->latest()->get();

        return view('manager.time_logs.index', compact('pendingLogs', 'historyLogs'));
    }

    public function approve(TimeLog $timeLog)
    {
        if ($timeLog->user_id === auth()->id()) {
            abort(403, 'You cannot approve or reject your own log.');
        }
        $timeLog->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        TimeLogAudit::create([
            'time_log_id' => $timeLog->id,
            'user_id' => auth()->id(),
            'action' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Log approved');
    }

    public function reject(TimeLog $timeLog)
    {
        if ($timeLog->user_id === auth()->id()) {
            abort(403, 'You cannot approve or reject your own log.');
        }
        $timeLog->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        TimeLogAudit::create([
            'time_log_id' => $timeLog->id,
            'user_id' => auth()->id(),
            'action' => 'rejected',
        ]);

        return redirect()->back()->with('success', 'log rejected');
    }

    public function history(TimeLog $timeLog)
    {
        $audits = $timeLog->audits()->with('user')->latest()->get();

        return view('manager.time_logs.history', compact('timeLog', 'audits'));
    }
}
