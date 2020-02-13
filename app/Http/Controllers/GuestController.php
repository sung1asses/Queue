<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Contracts\Bus\Dispatcher;

use Illuminate\Support\Facades\Cookie;

use App\QueueList;
use App\Queue;
use App\User;
use App\Role;
use App\History;
class GuestController extends Controller
{
    public function listQueue()
    {
    	$queue_list = QueueList::where('status',1)->get(); //Находим все активные очереди
    	return view('queue.index', compact('queue_list'));
    }

    public function showQueue($id)
    {
        if(!QueueList::find($id) || QueueList::find($id)->status != 1){ // Если Очередь активна, и она существует
            return abort(404);
        }
        $cookie_queue = Cookie::get('queue_'.$id); //Берем информацию с куки
        if($cookie_queue && !Queue::find(json_decode($cookie_queue)->id)){ //Есть ли информация в куке, и не удалена ли она с очереди
            Cookie::queue(Cookie::forget('queue_'.$id)); // Если удалена, забываем куку
            $cookie_queue=0; //Это нужно для VUE компонента
        }
        elseif(!$cookie_queue){ // Если куки вообще нет
            $cookie_queue=0; //Это нужно для VUE компонента
        }

        $operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь
        $queue = QueueList::find($id)->queues()->limit(30)->get(); //Выбираем 10 первых заявок
    	return view('queue.show', compact('queue','operators','id','cookie_queue'));
    }

    public function standIn($id, Request $request){
        if(!QueueList::find($id) || QueueList::find($id)->status != 1){// Если Очередь активна, и она существует
            return abort(404);
        }
        
    	$validation = \Illuminate\Support\Facades\Validator::make($request->all(), [ //Валидация
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255']
        ]);

        if($validation->fails() || QueueList::find($id)->queues()->where('email', $request->email)->first()) {
            return ["validation" => $validation->errors()];
        }

        $key = rand(1000,9999);//Генерация уникального ключа
        while(QueueList::find($id)->queues()->where('key',$key)->first()){
            $key = rand(1000,9999);
        }
        $newQueue = QueueList::find($id)->queues()->create([//Создание заявки
			'name' => $request->name,
			'email' => $request->email,
			'key' => $key,
        ]);

        $active_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1],['queue_id', null]])->get();    //Выбираем всех активных и свободных операторов очереди

        if(count($active_operators)>0){

            $operator_id = rand(0,count($active_operators)-1); //случайно определяем оператора для следующей очереди
            
            $active_operators[$operator_id]->update([
                'queue_id' => $newQueue->id,
            ]); //Назначаем оператора

        }
        
        $all_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь

        Cookie::queue(Cookie::make('queue_'.$id, $newQueue, 60*60*24*30));// Создание куки и помещение её в очередь

        $job = new \App\Jobs\SendNewQueue($newQueue, QueueList::find($id));//Отправить сообщение о создании на почту 
        app(Dispatcher::class)->dispatch($job);

        // $email = new \App\Mail\NewQueue($newQueue, QueueList::find($id));
        // \Mail::to($newQueue->email)->send($email); // Прикрутить бы очередь, но пофиг

        $queue = QueueList::find($id)->queues()->limit(30)->get();
        broadcast(new \App\Events\QueueStatus($id ,$queue, $all_operators)); //Отсылаем всем обновленную очередь и операторов

    	return $newQueue;
    }

    public function standOut($id, $object){
        if(!QueueList::find($id) || QueueList::find($id)->status != 1){// Если Очередь активна, и она существует
            return abort(404);
        }
        $cookie_queue = json_decode(Cookie::get('queue_'.$id));//Считываем куку
        Cookie::queue(Cookie::forget('queue_'.$id));//Удаляем куку
        if($object != 'cookie' && $cookie_queue){//Основывая на асинхронный запрос удаляем заявку
            Queue::find($cookie_queue->id)->delete();//удаляем заявку

            $queue = QueueList::find($id)->queues()->limit(30)->get();//Выбираем 10 первых заявок

            $operator = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['queue_id', $cookie_queue->id]])->first();//Выбираем оператора, который обслуживал эту очередь
            if($operator){ // Есть ли такой оператор
                $operator->update([ //Если такой оператор существует, то обнуляем его
                    'queue_id' => null,
                ]);
            }
            $queue = QueueList::find($id)->queues()->limit(30)->get(); //выбираем 10 первых заявок
            $all_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь

            if(count($queue)>=count($all_operators)){ //Есть ли вообще очереди
                $active_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1],['queue_id', null]])->get();    //Выбираем всех активных и свободных операторов очереди

                if(count($active_operators)>0){
                    
                    $operator_id = rand(0,count($active_operators)-1); //случайно определяем оператора для следующей очереди

                    $active_operators[$operator_id]->update([
                        'queue_id' => $queue[count($all_operators)-count($active_operators)]->id, //Отнимаем от количества всех операторов количество всех незанятых, чтоб получить порядковое число необслуживаемой очереди.
                    ]); //Назначаем оператора
                    $all_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь
                }
            }

            broadcast(new \App\Events\QueueStatus($id ,$queue, $all_operators)); //Отсылаем всем обновленную очередь и операторов
        }

        return 'success';
    }

    public function resetCookie($id, $key){
        $queue = QueueList::find($id)->queues()->where('key', decrypt($key))->first();
        if($queue){//Проверяем, не просрочилась ли заявка
            Cookie::queue(Cookie::make('queue_'.$id, $queue, 60*60*24*30));//Пересоздаем куку

            return redirect()->route('queue.show', ['id' => $id]);
        }
        return redirect()->route('queue.show', ['id' => $id])
                        ->with('error', 'Ваша очередь прошла');
    }


}
