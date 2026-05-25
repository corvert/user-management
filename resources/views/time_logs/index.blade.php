@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">My logs</h1>

        <form method="GET" action="/time-logs" class="mb-4 flex gap-2">
            <input type="date" name="from" value="{{ request('from') }}" class="border p-2">
            <input type="date" name="to" value="{{ request('to') }}" class="border p-2">
            <button class="bg-gray-700 text-white px-3 py-2 rounded">Filter</button>
            <a href="/time-logs/export?from={{ request('from') }}&to={{ request('to') }}"
                class="bg-blue-600 text-white px-3 py-2 rounded">Export CSV</a>
        </form>

        <form action="/time-logs" method="POST" class="mb-6 space-y-2">
            @csrf
            <x-inputs.text id="log_date" type="date" name="log_date" label="Date" required />
            <x-inputs.text id="arrival_time" type="time" name="arrival_time" label="Arrival Time" required />
            <x-inputs.text id="departure_time" type="time" name="departure_time" label="Departure Time" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Log</button>
        </form>

        <table class="w-full border">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Arrival Time</th>
                    <th class="border px-4 py-2">Departure Time</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td class="border p-2">{{ $log->log_date }}</td>

                        <td class="border p-2">
                            @if($log->status !== 'approved')
                                <input type="time" name="arrival_time" form="update-log-{{ $log->id }}"
                                    value="{{ substr($log->arrival_time, 0, 5) }}" class="border rounded px-2 py-1">
                            @else
                                {{ $log->arrival_time }}
                            @endif
                        </td>

                        <td class="border p-2">
                            @if($log->status !== 'approved')
                                <input type="time" name="departure_time" form="update-log-{{ $log->id }}"
                                    value="{{ substr($log->departure_time, 0, 5) }}" class="border rounded px-2 py-1">
                            @else
                                {{ $log->departure_time }}
                            @endif
                        </td>

                        <td class="border p-2 text-center">{{ $log->status }}</td>
                        @if($log->status !== 'approved')
                        <td class="border p-2 text-center">
                           
                                <form id="update-log-{{ $log->id }}" method="POST" action="/time-logs/{{ $log->id }}">
                                    <input type="hidden" name="reason" id="reason-{{ $log->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" class="mt-3 bg-green-600 text-white px-3 py-1 rounded"
                                        data-role-id="{{ $log->id }}">
                                        Save
                                    </button>
                                </form>
                            
                        </td>
                        @endif
                    </tr>
                @endforeach
                <x-inputs.pop-up />
            </tbody>
        </table>
    </div>
@endsection