<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeLogRequest;
use App\Http\Requests\UpdateTimeLogRequest;
use App\Models\TimeLog;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    public function index()
    {
         $logs = auth()->user()->timeLogs()->latest('log_date')->get();
        return view('time_logs.index', compact('logs'));
    }

      public function store(StoreTimeLogRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        TimeLog::create($data);

        return redirect()->back()->with('success', 'Logi salvestatud');
    }


   public function update(UpdateTimeLogRequest $request, TimeLog $timeLog)
{
    $this->authorize('update', $timeLog);

    $timeLog->update($request->validated());

    return redirect()->back()->with('success', 'Log updated');
}
}
