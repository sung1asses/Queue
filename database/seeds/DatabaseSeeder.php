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
	        'email' => 'admin@admin.admin',
	        'password' => Illuminate\Support\Facades\Hash::make('password'),
	    ])->attachRole($adminRole);

	    $operatorRole = \HttpOz\Roles\Models\Role::create([
	        'name' => 'Operator',
	        'slug' => 'operator',
	        'description' => 'Operate queues.', // optional
	        'group' => 'operator' // optional, set as 'default' by default
	    ]);

        \App\User::create([
	        'name' => 'Suvorov Roman',
	        'email' => 'suvorov_roman@queue.operator',
	        'password' => Illuminate\Support\Facades\Hash::make('operator'),
	    ])->attachRole($operatorRole);

        \App\User::create([
	        'name' => 'Semenov Pavel',
	        'email' => 'semenov_pavel@queue.operator',
	        'password' => Illuminate\Support\Facades\Hash::make('operator'),
	    ])->attachRole($operatorRole);
	    
        \App\User::create([
	        'name' => 'Saptova Tomiris',
	        'email' => 'saptova_tomiris@queue.operator',
	        'password' => Illuminate\Support\Facades\Hash::make('operator'),
	    ])->attachRole($operatorRole);
	    
        $this->call([
	        QueueSeeder::class,
	    ]);
    }
}