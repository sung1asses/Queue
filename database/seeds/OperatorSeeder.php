<?php

use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$operator_role = \HttpOz\Roles\Models\Role::where("name","Operator")->first();

    	\App\User::create([
	        'name' => 'Suvorov Roman',
	        'email' => 'suvorov_roman@queue.operator',
	        'password' => Illuminate\Support\Facades\Hash::make('operator'),
	    ])->attachRole($operator_role);

        $faker = Faker\Factory::create("ru_RU");

        for ($i = 0; $i <= 15; $i++) {
        	\App\User::create([
				'name' => $faker->name,
				'email' => $faker->email,
				'password' => $faker->password,
		    ])->attachRole($operator_role);
        };
    }
}
