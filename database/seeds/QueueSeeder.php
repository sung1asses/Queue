<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Carbon;
use App\QueueList;
use App\Queue;
class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$queue_list = QueueList::create([
        	'name' => 'Очередь в столовую',
        	'fromDate' => Carbon::now(),
        	'toDate' => Carbon::now(),
        	'status' => 1,
    	]);
    	$queue_list->users()->attach(2);

        
        $faker = Faker\Factory::create();

        $data = [];
        for ($i = 0; $i <= 30; $i++) {
            $data[$i] = [
              'name' => $faker->name,
              'email' => $faker->email,
              'key' => rand(1000,9999),
              'queue_list_id' => 1,
            ];
        };

        Queue::insert($data);
    }
}
