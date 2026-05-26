@extends('layouts.app')

@php
    function decodeAuditValues($value): array {
        if (is_array($value)) return $value;
        if (is_null($value)) return [];
        $decoded = json_decode($value, true);
        if (is_string($decoded)) $decoded = json_decode($decoded, true);
        return is_array($decoded) ? $decoded : [];
    }
@endphp

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
                            @foreach (decodeAuditValues($audit->old_values) as $key => $value)
                                <div>
                                    <strong>{{ $key }}:</strong>
                                    {{ is_array($value) ? implode(', ', $value) : $value }}
                                </div>
                            @endforeach
                        </td>

                        <td class="border p-2">
                            @foreach (decodeAuditValues($audit->new_values) as $key => $value)
                                <div>
                                    <strong>{{ $key }}:</strong>
                                    {{ is_array($value) ? implode(', ', $value) : $value }}
                                </div>
                            @endforeach
                        </td>

                        <td class="border p-2">{{ $audit->reason }}</td>
                        <td class="border p-2">{{ $audit->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="border p-2" colspan="9">No audits found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $audits->links() }}
        </div>
    </div>
@endsection