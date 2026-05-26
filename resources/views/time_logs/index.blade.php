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
                        <td class="border p-2">{{ $log->arrival_time }}</td>
                        <td class="border p-2">{{ $log->departure_time }}</td>
                        <td class="border p-2 text-center">{{ $log->status }}</td>
                        @if($log->status !== 'approved')
                            <td class="border p-2 text-center">
                                <div x-data="{ open: false, log: null, reasonError: false }">
                                    <button type="button" class="mt-3 bg-blue-600 text-white px-3 py-1 rounded"
                                        @click="open = true; log = { id: {{ $log->id }}, arrival: '{{ $log->arrival_time }}', departure: '{{ $log->departure_time }}' }">
                                        Edit
                                    </button>
                                    <div x-show="open" class="fixed inset-0 bg-black/40">
                                        <div class="bg-white p-4 max-w-md mx-auto mt-20">
                                            <form method="POST" :action="`/time-logs/{{$log->id}}`">
                                                @csrf
                                                @method('PATCH')
                                                <label class="block mt-2 mb-1">Arrival Time:</label>
                                                <input class="border rounded px-2 py-1" type="time" name="arrival_time"
                                                    value="{{ substr($log->arrival_time, 0, 5) }}">
                                                <label class="block mt-2 mb-1">Departure Time:</label>
                                                <input class="border rounded px-2 py-1" type="time" name="departure_time"
                                                    value="{{ substr($log->departure_time, 0, 5) }}">
                                                <label class="block mt-2 mb-1">Reason for change:</label>
                                                <textarea class="w-full border rounded p-2"
                                                    :class="reasonError ? 'border-red-500' : 'border-gray-300'" rows="3"
                                                    name="reason" @input="reasonError = false" x-ref="reason"></textarea>
                                                <p x-show="reasonError" class="text-red-500 text-sm mt-1">
                                                    Reason is required.
                                                </p>
                                                <div class="mt-3 flex gap-2">
                                                    <button type="button" class="px-3 py-1 bg-blue-600 text-white rounded"
                                                        @click="if ($refs.reason.value.trim() === '') { reasonError = true } else { $el.closest('form').submit() }">
                                                        Save
                                                    </button>
                                                    <button type="button" class="px-3 py-1 border rounded"
                                                        @click="open = false">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
      

    </div>
@endsection