<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Carbon;
use App\QueueList;
class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	QueueList::create([
        	'name' => 'Queue1',
        	'fromDate' => Carbon::now(),
        	'toDate' => Carbon::now(),
        	'status' => 1,
    	]);
        QueueList::find(1)->queues()->create([//Создание заявки
			'name' => 'Name1',
			'secondName' => 'SName1',
			'email' => 'email.1@mail.ru',
			'key' => '1234',
        ]);
        QueueList::find(1)->queues()->create([//Создание заявки
			'name' => 'Name2',
			'secondName' => 'SName2',
			'email' => 'email.2@mail.ru',
			'key' => '4567',
        ]);
        QueueList::find(1)->queues()->create([//Создание заявки
			'name' => 'Name3',
			'secondName' => 'SName3',
			'email' => 'email.3@mail.ru',
			'key' => '8778',
        ]);
        QueueList::find(1)->queues()->create([//Создание заявки
			'name' => 'Name4',
			'secondName' => 'SName4',
			'email' => 'email.4@mail.ru',
			'key' => '3564',
        ]);
        QueueList::find(1)->queues()->create([//Создание заявки
			'name' => 'Name5',
			'secondName' => 'SName5',
			'email' => 'email.5@mail.ru',
			'key' => '2145',
        ]);
        QueueList::find(1)->queues()->create([//Создание заявки
			'name' => 'Name6',
			'secondName' => 'SName6',
			'email' => 'email.6@mail.ru',
			'key' => '9574',
        ]);
        QueueList::find(1)->queues()->create([//Создание заявки
			'name' => 'Name7',
			'secondName' => 'SName7',
			'email' => 'email.7@mail.ru',
			'key' => '3569',
        ]);
    }
}
