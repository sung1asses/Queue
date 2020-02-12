<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Auth;

use App\QueueList;
use App\Queue;
use App\OperatorsStat;
use App\QueuesStat;

class AdminStatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function operatorStat(Request $request){
        if(!$request->date){
            $request->date = Carbon::now()->toDateString();
        }
        $arr = OperatorsStat::where([
            ['started_at','>',$request->date.' 00:00:00'],
            ['ended_at','<=',$request->date.' 23:59:59'],
            ['user_id',$request->user_id],
        ])->get();

        foreach ($arr as $key => $value) {
            $arr[$key]['queues'] = QueuesStat::where([
                ['created_at','>',$value->started_at],
                ['created_at','<=',$value->ended_at],
                ['user_id',$request->user_id],
                ['queue_list_id',$value->queue_list_id],
            ])->get();
            $arr[$key]['queue_name'] = QueueList::find($value->queue_list_id)->name;
        }
        return $arr;
    }

    public function getStatForADay(Request $request){
        if(!$request->date){
            $request->date = Carbon::now()->toDateString();
        }
    }

    public function historyStat(Request $request){
    	if(!$request->date){
    		$request->date = Carbon::now()->toDateString();
    	}
    	$data[] = (string) QueueList::find($request->queue_id)->histories()->where([['created_at','>',$request->date.' 00:00:00'],['created_at','<=',$request->date.' 08:00:00']])->count();
    	$data[] = (string) QueueList::find($request->queue_id)->histories()->where([['created_at','>',$request->date.' 08:00:00'],['created_at','<=',$request->date.' 10:00:00']])->count();
    	$data[] = (string) QueueList::find($request->queue_id)->histories()->where([['created_at','>',$request->date.' 10:00:00'],['created_at','<=',$request->date.' 12:00:00']])->count();
    	$data[] = (string) QueueList::find($request->queue_id)->histories()->where([['created_at','>',$request->date.' 12:00:00'],['created_at','<=',$request->date.' 14:00:00']])->count();
    	$data[] = (string) QueueList::find($request->queue_id)->histories()->where([['created_at','>',$request->date.' 14:00:00'],['created_at','<=',$request->date.' 16:00:00']])->count();
    	$data[] = (string) QueueList::find($request->queue_id)->histories()->where([['created_at','>',$request->date.' 16:00:00'],['created_at','<=',$request->date.' 18:00:00']])->count();
    	$data[] = (string) QueueList::find($request->queue_id)->histories()->where([['created_at','>',$request->date.' 18:00:00'],['created_at','<=',$request->date.' 23:59:59']])->count();
    	return [
			'labels' =>['8:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
			'datasets' => array([
			  'label' => 'Активность очереди',
			  'backgroundColor' => '#f87979',
			  'data' => $data,
			]), 
    	];
    }
}
