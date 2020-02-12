<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Contracts\Bus\Dispatcher;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use App\QueueList;
use App\Queue;
use App\User;
use App\Role;
class AdminController extends Controller
{
	public function home()
    {
        $queue_list_active = QueueList::where('status',1)->get();
        $queue_list_ended = QueueList::where('status',2)->get();
    	return view('admin.home', compact('queue_list_active','queue_list_ended'));
    }
    // QUEUES
    public function listQueue()
    {
        $queue_list_active = QueueList::where('status',1)->get();
        $queue_list_ended = QueueList::where('status',2)->get();
        $queue_list_potencially = QueueList::where('status',0)->get();
    	return view('admin.queue.index', compact('queue_list_active','queue_list_ended','queue_list_potencially'));
    }

    public function createQueue(Request $request)
    {
        $validation = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required',
            'fromDate' => 'required',
            'toDate' => 'required',
        ]);

        if($validation->fails()) {
            return redirect()->route('admin.queue.list')
		                ->withInput()
		                ->with('error', 'Вы ввели не все поля...');
        };

        $queue = QueueList::create([
        	'name' => $request['name'],
        	'fromDate' => $request['fromDate'],
        	'toDate' => $request['toDate'],
        ]);

        $job = (new \App\Jobs\UpdateQueueStatus($queue->id, 1))->delay(Carbon::createFromFormat('Y-m-d', $queue->fromDate,'Asia/Almaty'));
        app(Dispatcher::class)->dispatch($job);
        
        $job = (new \App\Jobs\UpdateQueueStatus($queue->id, 2))->delay(Carbon::createFromFormat('Y-m-d', $queue->toDate,'Asia/Almaty'));
        app(Dispatcher::class)->dispatch($job);

        $job = (new \App\Jobs\SendOperatorNotification($queue->id))->delay(Carbon::createFromFormat('Y-m-d', $queue->fromDate,'Asia/Almaty')->subDays(2));

        app(Dispatcher::class)->dispatch($job);

    	return redirect()->route('admin.queue.list')
		    	->with('succ', 'Очередь успешно создана...');
    }
    public function deleteQueue($id)
    {
        QueueList::find($id)->users()->detach();
        QueueList::find($id)->queues()->delete();
        QueueList::find($id)->delete();
        return redirect()->route('admin.queue.list');
    }
    
    public function showQueue($id)
    {
        if(!QueueList::find($id) || QueueList::find($id)->status == 2){
            return abort(404);
        }

    	$queue = QueueList::find($id)->queues()->limit(30)->get();
        $operators = Role::where('group', 'operator')->first()->users()->get();
        $active_operators = QueueList::find($id)->users()->get();
        $queue_name = QueueList::select('name')->find($id)->name;
        $queue_operators = Role::where('group', 'operator')->first()->users()->where([['queue_list_id', $id],['status', 1]])->get();//Выбираем всех операторов, обслуживаюищих очередь
    	return view('admin.queue.show', compact('queue','id', 'operators', 'active_operators', 'queue_name','queue_operators'));
    }

    public function setOperator($id, Request $request)
    {
        if($request['checked'] == false){
            QueueList::find($id)->users()->attach($request['operator_id']);
        }
        elseif($request['checked'] == true){
            QueueList::find($id)->users()->detach($request['operator_id']);
        }
        $operators = QueueList::find($id)->users()->get();
        return $operators;
    }

    // operator

    
    public function listOperator()
    {
        $operators = Role::where('group', 'operator')->first()->users()->get();
        return view('admin.operator.index', compact('operators'));
    }
    public function createOperator(Request $request)
    {
        $validation = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'sname' => ['required', 'string', 'max:255'],
            'fname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validation->fails()) {
            return redirect()->route('admin.operator.list')
                        ->withInput()
                        ->with('error', 'Ошибка ввода');
        };

        $name = ucfirst($request['sname'])." ".ucfirst($request['fname']);
        $email = strtolower($request['email']);
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($request['password']),
        ])->attachRole(Role::where('group', 'operator')->first());

        $operators = Role::where('group', 'operator')->first()->users()->get();
        return view('admin.operator.index', compact('operators'));
    }
    public function deleteOperator($id)
    {
        User::find($id)->queueLists()->detach();
        User::find($id)->delete();
        return redirect()->route('admin.operator.list');
    }
    public function showOperator($id)
    {
        $operator = User::find($id);
        $queue_list_active = User::find($id)->queueLists()->where("status", 1)->get();
        $queue_list_passive = User::find($id)->queueLists()->where("status", 0)->get();
        return view('admin.operator.show', compact('operator','queue_list_active','queue_list_passive'));
    }
}