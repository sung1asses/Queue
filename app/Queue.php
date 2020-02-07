<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
	protected $guarded = [
		'id'
	];
    public function queueLists()
    {
        return $this->hasMany('\App\QueueList');
    }
    public function user()
    {
        return $this->belongsTo('\App\User');
    }
}
