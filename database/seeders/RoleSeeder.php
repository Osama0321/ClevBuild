<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Bouncer;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = config('settings.permissions');

        foreach($permissions as $per){
            foreach($per as $permission){
               Bouncer::allow('Admin')->to($permission);
            }
        }
    }
}
