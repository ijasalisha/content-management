<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Privilege;
use App\Models\Menu;
use App\Models\Page;
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

        'menus.view',
        'menus.create',
        'menus.update',
        'menus.delete',
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

       //Moderator get page privileges only
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

        // Create demo menus
$homeMenu = Menu::create([
    'title' => 'Home',
    'sort_order' => 1,
    'is_active' => true,
]);

$servicesMenu = Menu::create([
    'title' => 'Services',
    'sort_order' => 2,
    'is_active' => true,
]);

$aboutMenu = Menu::create([
    'title' => 'About Us',
    'sort_order' => 3,
    'is_active' => true,
]);

// Create demo published pages
Page::create([
    'menu_id' => $homeMenu->id,
    'title' => 'Welcome to Our Website',
    'body' => '<h2>Welcome</h2><p>Welcome to our Content Management System.</p>',
    'status' => 'published',
    'publish_at' => null,
    'created_by' => $admin->id,
    'updated_by' => $admin->id,
]);

Page::create([
    'menu_id' => $servicesMenu->id,
    'title' => 'Our Services',
    'body' => '<h2>Our Services</h2><p>We provide professional technology and digital services.</p>',
    'status' => 'published',
    'publish_at' => null,
    'created_by' => $admin->id,
    'updated_by' => $admin->id,
]);

Page::create([
    'menu_id' => $aboutMenu->id,
    'title' => 'About Us',
    'body' => '<h2>About Us</h2><p>This is a sample CMS page created for the project.</p>',
    'status' => 'published',
    'publish_at' => null,
    'created_by' => $admin->id,
    'updated_by' => $admin->id,
]);
    }
}