@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-4">Role Management</h1>

    <form action="/admin/roles" method="POST" class="mb-6 space-y-2">
        @csrf
        <x-inputs.text id="name" name="name" label="Role Name" required />
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Create Role</button>

    </form>

   @foreach($roles as $role)
    <div class="border rounded p-4 mb-4">
        <h2 class="font-semibold mb-2">{{ $role->name }}</h2>

        <form method="POST" action="/admin/roles/{{ $role->id }}/permissions">
                <input type="hidden" name="reason" id="reason-{{ $role->id }}">
            @csrf
        
            <div class="grid grid-cols-2 gap-2">
                @foreach($permissions as $perm)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                            {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                        {{ $perm->name }}
                    </label>
                @endforeach
            </div>
            <button type="button"
        class="mt-3 bg-green-600 text-white px-3 py-1 rounded"
        data-role-id="{{ $role->id }}">
    Save Permissions
</button>
        </form>
    </div>
    @endforeach
    <x-inputs.pop-up />

</div>
@endsection