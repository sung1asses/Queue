<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\QueueList;
use App\Queue;
use App\User;
use App\Role;
use App\History;
class OperatorController extends Controller
{
	public function listQueue()
	{
		$queue_list = Auth::user()->queueLists()->get();
		return view('admin.operator-level.queue.index', compact('queue_list'));
	}
	public function showQueue($id)
	{	
        $queue_name = QueueList::select('name')->find($id);
    	$queue = QueueList::find($id)->queues()->limit(10)->get();
    	return view('admin.operator-level.queue.show', compact('queue','id', 'queue_name'));
	}
	public function nextButton($id, Request $request)
    {
        if(!QueueList::find($id) || QueueList::find($id)->status != 1){
            return abort(404);
        }

        $queue = QueueList::find($id)->queues()->first();

        if($queue){
            $newHistory = QueueList::find($id)->histories()->create([
                'name' => $queue->name,
                'secondName' => $queue->secondName,
                'email' => $queue->email,
                'key' => $queue->key,
                'created_at' => Carbon::now('Asia/Almaty')->roundMinute(),
            ]);

            if($request->status == "succ"){
                $newHistory->status = 'Посетил';
                $newHistory->save();
            }
            elseif($request->status == "err"){
                $newHistory->status = 'Пропустил';
                $newHistory->save();
                
                // $job = new \App\Jobs\SendYouAreFired($old_history, QueueList::find($id)->name);
                // app(Dispatcher::class)->dispatch($job);
            }

            $queue->delete();

            $queue = QueueList::find($id)->queues()->limit(10)->get();
            broadcast(new \App\Events\QueueStatus($id ,$queue));

            // if(count($queue)>=2){
            //     $job = new \App\Jobs\SendRemainingOnePeople($queue[0], QueueList::find($id)->name);
            //     app(Dispatcher::class)->dispatch($job);
            // }

            // if(count($queue)>=4){
            //     $job = new \App\Jobs\SendRemainingTreePeople($queue[2], QueueList::find($id)->name);
            //     app(Dispatcher::class)->dispatch($job);
            // }

        }

        /*broadcast(new \App\Events\HistoryStatus($id));*/

        return 'success';
    }
}
