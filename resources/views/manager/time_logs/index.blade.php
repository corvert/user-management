@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">Pending logs</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
 

        <table class="w-full border">
            <thead>
                <tr>
                     <th class="border p-2">User</th>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Arrival Time</th>
                    <th class="border px-4 py-2">Departure Time</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td class="border p-2">{{ $log->user->name }}</td>
                        <td class="border p-2">{{ $log->log_date }}</td>
                        <td class="border p-2">{{ $log->arrival_time }}</td>
                        <td class="border p-2">{{ $log->departure_time }}</td>
                        <td class="border p-2">
                            <form action="manager/time-logs/{{ $log->id }}/approve" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Approve</button>
                            </form>
                            <form action="manager/time-logs/{{ $log->id }}/reject" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection