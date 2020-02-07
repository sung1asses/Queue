<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
	protected $guarded = [
		'id'
	];
    public function queueLists()
    {
        return $this->hasMany('\App\QueueList');
    }
}
