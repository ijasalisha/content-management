<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Privilege;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

    //Create privileges

       $privilegeNames = [
        'pages.view',
        'pages.create',
        'pages.update',
        'pages.delete',

        'users.view',
        'users.create',
        'users.update',
        'users.delete',

        'roles.view',
        'roles.create',
        'roles.update',
        'roles.delete',

        'privileges.view',
        'privileges.create',
        'privileges.update',
        'privileges.delete',
       ];

       $privileges = collect($privilegeNames)->mapWithKeys(function ($name) {
        $privilege = Privilege::create([
            'name' => $name,
            'description' => "Permission to {$name}",
        ]);

        return [$name => $privilege];
       });
        
       //Create roles
       $adminRole = Role::create([
        'name' => 'Admin',
        'description' => 'Administrator role with full access',
       ]);  

       $moderatorRole = Role::create([
        'name' => 'Moderator',
        'description' => 'Moderator role with limited access',
       ]);

       //admin get all privileges
       $adminRole->privileges()->attach($privileges->values());

       //Moderator get page
       $moderatorRole->privileges()->attach([
        $privileges['pages.view']->id,
        $privileges['pages.create']->id,
        $privileges['pages.update']->id,
       ]);

       //Create Admin User
         $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign the admin role to the admin user
        $admin->roles()->attach($adminRole);    

        //Create Moderator User
        $moderator = User::factory()->create([
            'name' => 'Moderator User',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password'),
        ]);

        // Assign the moderator role to the moderator user
        $moderator->roles()->attach($moderatorRole);
    }
}