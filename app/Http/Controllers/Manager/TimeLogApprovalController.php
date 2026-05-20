<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use App\Models\TimeLogAudit;
use Illuminate\Support\Facades\Auth;

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
        if ($timeLog->user_id === Auth::id()) {
            abort(403, 'You cannot approve or reject your own log.');
        }
        $timeLog->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        TimeLogAudit::create([
            'time_log_id' => $timeLog->id,
            'user_id' => Auth::id(),
            'action' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Log approved');
    }

    public function reject(TimeLog $timeLog)
    {
        if ($timeLog->user_id === Auth::id()) {
            abort(403, 'You cannot approve or reject your own log.');
        }
        $timeLog->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        TimeLogAudit::create([
            'time_log_id' => $timeLog->id,
            'user_id' => Auth::id(),
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
