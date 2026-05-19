<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRolePermissionsRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(StoreRoleRequest $request){
        Role::create(['name' => $request->validated()['name']]);

        return redirect()->back()->with('success','Role created');
    }

    public function updatePermissions(UpdateRolePermissionsRequest $request, Role $role){
        $permissions = $request->validated()['permissions']?? [];
        $role->syncPermissions($permissions);

        return redirect()->back()->with('success','Permissions updated');
    }
}
