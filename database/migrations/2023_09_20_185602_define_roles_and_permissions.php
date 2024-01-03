<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $defaultPermissions = collect([
            // view admin as a whole
            'custom-app',

            // manage translations
            'custom-app.translation.index',
            'custom-app.translation.edit',
            'custom-app.translation.rescan',
            'custom-app.translation.publish',
            'custom-app.translation.export',
            'custom-app.translation.import',

            // manage users (access)
            'custom-app.custom-app-user.index',
            'custom-app.custom-app-user.create',
            'custom-app.custom-app-user.show',
            'custom-app.custom-app-user.edit',
            'custom-app.custom-app-user.destroy',
            'custom-app.custom-app-user.impersonal-login',

            // media
            'custom-app.media.index',
            'custom-app.media.upload',
            'custom-app.media.destroy',

            // permissions
            'custom-app.role.index',
            'custom-app.role.edit',

            // manage tags (access)
            'custom-app.tag.index',
            'custom-app.tag.store',

            // settings
            'custom-app.settings.edit',

            // permissions
            'custom-app.permission.index',
            'custom-app.permission.edit'
        ]);

        $adminRoleId = DB::table('roles')->insertGetId([
            'name' => 'Administrator',
            'guard_name' => 'custom-app',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $defaultPermissions->each(function ($permission) use ($adminRoleId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name' => $permission,
                'guard_name' => 'custom-app',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionId,
                'role_id' => $adminRoleId,
            ]);
        });

        // let's create a default Guest role in case self-registration is enabled
        $guestRoleId = DB::table('roles')->insertGetId([
            'name' => 'Guest',
            'guard_name' => 'custom-app',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('role_has_permissions')->insert([
            'permission_id' => DB::table('permissions')
                ->where('name', '=', 'custom-app')
                ->where('guard_name', '=', 'custom-app')
                ->value('id'),
            'role_id' => $guestRoleId,
        ]);

        app()['cache']->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $guestRole = DB::table('roles')->where('name', 'Guest')->where('guard_name', 'custom-app')->first();
        DB::table('role_has_permissions')
            ->where('role_id', $guestRole->id)
            ->delete();
        DB::table('roles')->where('id', $guestRole->id)->delete();

        $adminRole = DB::table('roles')->where('name', 'Administrator')->where('guard_name', 'custom-app')->first();
        DB::table('role_has_permissions')
            ->where('role_id', $adminRole->id)
            ->delete();
        DB::table('roles')->where('id', $adminRole->id)->delete();

        $this->defaultPermissions->each(function ($permission){
            $permissionItem = DB::table('permissions')->where([
                'name' => $permission,
                'guard_name' => 'custom-app'
            ])->first();

            if ($permissionItem !== null) {
                DB::table('permissions')->where('id', $permissionItem->id)->delete();
                DB::table('model_has_permissions')->where('permission_id', $permissionItem->id)->delete();
            }
        });
        app()['cache']->forget(config('permission.cache.key'));
    }
};
