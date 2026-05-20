@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">My logs</h1>

      
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

                        <td class="border p-2">{{ $log->status }}</td>

                        <td class="border p-2">
                            @if($log->status !== 'approved')
                                <form id="update-log-{{ $log->id }}" method="POST" action="/time-logs/{{ $log->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="bg-green-600 text-white px-3 py-1 rounded">
                                        Save
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection