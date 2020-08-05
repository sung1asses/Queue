<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {   
        $adminRole = \HttpOz\Roles\Models\Role::create([
	        'name' => 'Admin',
	        'slug' => 'admin',
	        'description' => 'Custodians of the system.', // optional
	        'group' => 'admin' // optional, set as 'default' by default
	    ]);

        $admin = \App\User::create([
	        'name' => 'Admin',
	        'email' => 'admin',
	        'password' => Illuminate\Support\Facades\Hash::make('admin'),
	    ])->attachRole($adminRole);

	    $operatorRole = \HttpOz\Roles\Models\Role::create([
	        'name' => 'Operator',
	        'slug' => 'operator',
	        'description' => 'Operate queues.', // optional
	        'group' => 'operator' // optional, set as 'default' by default
	    ]);
	    
        $this->call([
        	OperatorSeeder::class,
	        QueueSeeder::class,
	    ]);
    }
}