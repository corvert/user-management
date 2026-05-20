@extends('layouts.app')
@section('content')
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">User Management</h1>

        <form action="/admin/users" method="POST">
            @csrf
            <x-inputs.text id="name" name="name" label="Name" required />
            <x-inputs.text id="email" name="email" label="Email" type="email" required />
            <x-inputs.text id="password" name="password" label="Password" type="password" required />
            <x-inputs.text id="password_confirmation" name="password_confirmation" label="Password Confirmation"
                type="password" required />
            <x-inputs.select id="role" name="role" label="Role" :options="['User' => 'User', 'Manager' => 'Manager']"
                required />
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create User</button>

        </form>

        <table class="w-full border">
            <thead>
                <tr>
                    <th class="border p-2">Name</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Role</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="border p-2">{{ $user->name }}</td>
                        <td class="border p-2">{{ $user->email }}</td>
                        <td class="border p-2">{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td class="border p-2">{{ $user->status ? 'Active' : 'Inactive' }}</td>

                        <td class="border p-2">
                            @if($user->status)
                                <form method="POST" action="/admin/users/{{ $user->id }}/deactivate">
                                    @csrf
                                    <button class="bg-red-600 text-white px-3 py-1 rounded">Deactivate</button>
                                </form>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection