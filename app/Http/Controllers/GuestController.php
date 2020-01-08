<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Contracts\Bus\Dispatcher;

use Illuminate\Support\Facades\Cookie;

use App\QueueList;
use App\Queue;
use App\History;
class GuestController extends Controller
{
    public function listQueue()
    {
    	$queue_list = QueueList::where('status',1)->get();
    	return view('queue.index', compact('queue_list'));
    }

    public function showQueue($id)
    {
        if(!QueueList::find($id) || QueueList::find($id)->status != 1){
            return abort(404);
        }
        $cookie_queue = Cookie::get('queue_'.$id);
        if($cookie_queue && !Queue::find(json_decode($cookie_queue)->id)){
            Cookie::queue(Cookie::forget('queue_'.$id));
            $cookie_queue=0;
        }
        elseif(!$cookie_queue){
            $cookie_queue=0;
        }
        $queue = QueueList::find($id)->queues()->limit(10)->get();
    	return view('queue.show', compact('queue','id','cookie_queue'));
    }

    public function standIn($id, Request $request){
        if(!QueueList::find($id) || QueueList::find($id)->status != 1){
            return abort(404);
        }
        
    	$validation = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'secondName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255']
        ]);

        if($validation->fails() || QueueList::find($id)->queues()->where('email', $request->email)->first()) {
            return abort(500);
        }

        $key = rand(1000,9999);
        while(QueueList::find($id)->queues()->where('key',$key)->first()){
            $key = rand(1000,9999);
        }
        $newQueue = QueueList::find($id)->queues()->create([
			'name' => $request->name,
			'secondName' => $request->secondName,
			'email' => $request->email,
			'key' => $key,
        ]);

        Cookie::queue(Cookie::make('queue_'.$id, $newQueue, 60*60*24*30));

        // $job = new \App\Jobs\SendNewQueue($newQueue, QueueList::find($id)->name);
        // app(Dispatcher::class)->dispatch($job);

        $queue = QueueList::find($id)->queues()->limit(10)->get();
        broadcast(new \App\Events\QueueStatus($id ,$queue));

    	return $newQueue;
    }

    public function standOut($id, Request $request){
        if(!QueueList::find($id) || QueueList::find($id)->status != 1){
            return abort(404);
        }

        Cookie::queue(Cookie::forget('queue_'.$id));

        $cookie_queue = json_decode(Cookie::get('queue_'.$id));
        if($request->object != 'cookie' && $cookie_queue){
            Queue::find($cookie_queue->id)->delete();

            $queue = QueueList::find($id)->queues()->limit(10)->get();
            broadcast(new \App\Events\QueueStatus($id ,$queue));
        }

        return 'success';
    }
}
