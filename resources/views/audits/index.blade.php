@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-4">Audits</h1>
        <table class="w-full border">
            <thead>
                <tr>
                    <th class="border px-2 py-1">ID</th>
                    <th class="border px-2 py-1">Event</th>
                    <th class="border px-2 py-1">Table</th>
                    <th class="border px-2 py-1">User</th>
                    <th class="border px-2 py-1">Auditable</th>
                    <th class="border px-2 py-1">Old Values</th>
                    <th class="border px-2 py-1">New Values</th>
                    <th class="border px-2 py-1">Reason</th>
                    <th class="border px-2 py-1">Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($audits as $audit)
                    <tr>
                        <td class="border p-2">{{ $audit->id }}</td>
                        <td class="border p-2">{{ $audit->event }}</td>
                        <td class="border p-2">{{ $audit->user_type }}</td>
                        <td class="border p-2">{{ $audit->user?->name }}</td>
                        <td class="border p-2">{{ $audit->auditable_type }} with ID - {{ $audit->auditable_id }}</td>
                        <td class="border p-2">
                            @if (is_array($audit->old_values))
                                @foreach ($audit->old_values as $key => $value)
                                    <div><strong>{{ $key }}:</strong> {{ $value }}</div>
                                @endforeach
                            @else
                                {{ $audit->old_values }}
                            @endif
                        </td>

                        <td class="border p-2">
                            @if (is_array($audit->new_values))
                                @foreach ($audit->new_values as $key => $value)
                                    <div><strong>{{ $key }}:</strong> {{ $value }}</div>
                                @endforeach
                            @else
                                {{ $audit->new_values }}
                            @endif
                        </td>
                        <td class="border p-2">{{ $audit->reason }}</td>
                        <td class="border p-2">{{ $audit->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="border p-2" colspan="8">No audits found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $audits->links() }}
        </div>


    </div>
@endsection