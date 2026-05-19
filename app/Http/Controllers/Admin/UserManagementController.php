<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(){
        $users = User::with('roles')->orderBy('name')->get();
        $roles = Role::whereIn('name', ['Manager', 'User'])->get();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(StoreUserRequest $request){
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => true,
        ]);
        $user->assignRole($data['role']);
        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function deactivate(User $user){
        $this->authorize('deactivate users');
        
        $user->update(['status' => false]);
        return redirect()->back()->with('success', 'User deactivated successfully.');
    }
}
