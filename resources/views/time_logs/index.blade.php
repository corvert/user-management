@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">My logs</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <form action="/time-logs" method="POST" class="mb-6 space-y-2">
            @csrf
            <x-inputs.text type="date" name="log_date" label="Date" required />
            <x-inputs.text type="time" name="arrival_time" label="Arrival Time" required />
            <x-inputs.text type="time" name="departure_time" label="Departure Time" />
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
                        <td class="border p-2">{{ $log->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection