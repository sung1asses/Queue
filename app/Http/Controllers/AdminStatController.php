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

    public function operatorStat(/*$id*/){
        // $operators_stat = QueuesStat::get();
        // dd($operators_stat);
        $arr = [
            ["key" => 1
            ],
            ["key" => 2
            ],
            ["key" => 3
            ]
        ];
        $arr[0]['key2'] = [
            ["key" => 1
            ],
            ["key" => 2
            ],
            ["key" => 3
            ]
        ];
        $date = '2020-02-10';
        $arr = OperatorsStat::where([['started_at','>',$date.' 00:00:00'],['ended_at','<=',$date.' 23:59:59']])->get();
        dd($arr);
        return false;
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
