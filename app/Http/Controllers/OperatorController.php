<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Carbon;

use Illuminate\Contracts\Bus\Dispatcher;

use App\OperatorsStat;
use App\QueueList;
use App\Queue;
use App\User;
use App\Role;
use App\QueuesStat;
class OperatorController extends Controller
{
	public function listQueue()
	{
		$queue_list = Auth::user()->queueLists()->where('status',1)->get(); //Список всех очередей
		return view('admin.operator-level.queue.index', compact('queue_list'));
	}
	public function showQueue($id)
	{
        $queue_name = QueueList::select('name')->find($id); //Название очереди
    	$queue = QueueList::find($id)->queues()->limit(30)->get(); //30 первых заявок
        $operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь
        $status = Auth::user()->status;
    	return view('admin.operator-level.queue.show', compact('queue','operators','id', 'queue_name','status'));
	}
	public function nextButton($id, Request $request)
    {
        if(!QueueList::find($id) || QueueList::find($id)->status != 1){ // Если Очередь активна, и она существует
            return abort(404);
        }

        $queue = QueueList::find($id)->queues()->find(Auth::user()->queue_id); //заявка оператора

        if($queue){ //Проверяем есть ли вообще заявка

            $QueuesStat = QueuesStat::create([ // записываем в историю очередь
                'name' => $queue->name,
                'secondName' => $queue->secondName,
                'email' => $queue->email,
                'key' => $queue->key,
                'user_id' => Auth::id(),
                'queue_list_id' => $id,
                'created_at' => Carbon::now('Asia/Almaty'),
            ]);
            
            if($request->status == "err"){ //Если заявка была пропущена
                $QueuesStat->status = 'Пропустил';
                $QueuesStat->save();
                
                // $job = new \App\Jobs\SendYouAreFired($queue, QueueList::find($id)->name);
                // app(Dispatcher::class)->dispatch($job);

                $email = new \App\Mail\YouAreFired(QueueList::find($id)->name); //Отправляем сообщение о пропуске заявки
                \Mail::to($queue->email)->send($email);//КОСТЫЛЬ!
            }

            User::find(Auth::id())->update([
                'queue_id' => null,
            ]);//обновляем оператора, который обрабатывал заявку(мы)

            $queue->delete(); //удаляем заявку

            $queue = QueueList::find($id)->queues()->limit(30)->get(); //выбираем 10 первых заявок
            $all_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь

            if(count($queue)>=count($all_operators)){ //Есть ли вообще очереди
                $active_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1],['queue_id', null]])->get();    //Выбираем всех активных и свободных операторов очереди

                User::find(Auth::id())->update([
                    'queue_id' => $queue[count($all_operators)-count($active_operators)]->id,
                ]);//обновляем оператора, который обрабатывал заявку(мы)

                $stat = OperatorsStat::where([['user_id',Auth::id()],['queue_list_id',$id],['ended_at',null]])->first();
                $stat->queues_count = $stat->queues_count+1;
                $stat->save();
                
                $all_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь

            }

            broadcast(new \App\Events\QueueStatus($id ,$queue, $all_operators)); //Отсылаем всем обновленную очередь и операторов

            // if(count($queue)>=2){
            //     $job = new \App\Jobs\SendRemainingOnePeople($queue[1], QueueList::find($id)->name); 
            //     app(Dispatcher::class)->dispatch($job);
            // }

            // if(count($queue)>=4){
            //     $job = new \App\Jobs\SendRemainingTreePeople($queue[2], QueueList::find($id)->name);
            //     app(Dispatcher::class)->dispatch($job);
            // }

        }

        return 'success';
    }

    public function operatorStatus($id, Request $request){
        if($request->status == 1){
            $queue = QueueList::find($id)->queues()->limit(30)->get(); //выбираем 10 первых заявок
            $all_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь

            if(count($queue)>count($all_operators)){ //Есть ли вообще очереди                
                User::find(Auth::id())->update([
                    'queue_list_id' => $id,
                    'status' => 1,
                    'queue_id' => $queue[count($all_operators)]->id, //Отнимаем от количества всех операторов количество всех незанятых, чтоб получить порядковое число необслуживаемой очереди.
                ]);
                OperatorsStat::create([
                    'user_id' => Auth::id(),
                    'queue_list_id' => $id,
                    'queues_count' => 1,
                    'started_at' => Carbon::now('Asia/Almaty'),
                ]);
            }
            else{
                User::find(Auth::id())->update([
                    'queue_list_id' => $id,
                    'status' => 1,
                    'queue_id' => null, //В противном случае просто меняем статус
                ]);
                OperatorsStat::create([
                    'user_id' => Auth::id(),
                    'queue_list_id' => $id,
                    'queues_count' => 0,
                    'started_at' => Carbon::now('Asia/Almaty'),
                ]);
            }

        }
        else{
            $operator_queue = QueueList::find($id)->queues()->find(Auth::user()->queue_id);
            if($operator_queue){
                $operator_queue->delete(); //удаляем заявку;
            }

            User::find(Auth::id())->update([
                'queue_list_id' => $id,
                'status' => 0,
                'queue_id' => null,
            ]);
            OperatorsStat::where([['user_id',Auth::id()],['queue_list_id',$id],['ended_at',null]])->update(['ended_at' => Carbon::now('Asia/Almaty')]);
        }

        $queue = QueueList::find($id)->queues()->limit(30)->get(); //выбираем 10 первых заявок
        $all_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь
        broadcast(new \App\Events\QueueStatus($id ,$queue, $all_operators)); //Отсылаем всем обновленную очередь и операторов
        return 'success';
    }
}
