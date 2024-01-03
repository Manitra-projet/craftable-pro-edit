<?php

namespace CustomPackages\CustomApp\Http\Controllers\Roles;

use CustomPackages\CustomApp\Http\Controllers\Controller;
use CustomPackages\CustomApp\Http\Requests\Roles\IndexRoleRequest;
use CustomPackages\CustomApp\Queries\Filters\FuzzyFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    public function index(IndexRoleRequest $request)
    {
        $roles = QueryBuilder::for(Role::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id',
                    'name',
                )),
            ])
            ->defaultSort('id')
            ->allowedSorts(['id', 'name'])
            ->with('users')
            ->select(['id', 'name'])
            ->paginate(request()->get('per_page'))->withQueryString();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function edit(Role $role)
    {
        $this->authorize('custom-app.role.edit');

        $allPermissions = Permission::all()->map->name;
        $assignedPermissions = $role->permissions->map->name;

        $permissionsTree = [];

        collect($allPermissions)->each(function ($permission) use (&$permissionsTree, $assignedPermissions) {
            $isAssigned = collect($assignedPermissions)->contains($permission);
            Arr::set($permissionsTree, $permission, $isAssigned);
        });

        return Inertia::render('Roles/Edit', [
            'role' => $role,
            'permissionsTree' => $permissionsTree,
        ]);
    }

    public function update(Role $role, Request $request)
    {
        $this->authorize('custom-app.role.edit');

        $role->syncPermissions(collect(Arr::dot($request->input('permissionsTree')))->filter()->keys());

        return redirect()->back()->with(['message' => ___('custom-app', 'Role has been successfully updated')]);
    }
}
