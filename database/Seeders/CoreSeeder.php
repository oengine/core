<?php

namespace OEngine\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use OEngine\Core\Facades\Core;
use OEngine\Core\Http\Action\LoadPermission;
use OEngine\Core\Models\Role;
use OEngine\Core\Models\User;

class CoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Load All Perrmission
        LoadPermission::UpdatePermission();
        //
        $roleAdmin = new Role();
        $roleAdmin->name = Core::RoleAdmin();
        $roleAdmin->slug = Core::RoleAdmin();
        $roleAdmin->save();
        $userAdmin = new User();
        $userAdmin->name = "nguyen van hau";
        $userAdmin->email = env('OENGINE_CORE_EMAIL', "admin@oengine.local");
        $userAdmin->password = env('OENGINE_CORE_PASSWORD', "AdMin@123");
        $userAdmin->status = 1;
        $userAdmin->save();
        $userAdmin->roles()->sync([$roleAdmin->id]);
    }
}
