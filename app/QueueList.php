<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueList extends Model
{
	protected $guarded = [
		'id'
	];
    public function queues()
    {
        return $this->hasMany('\App\Queue');
    }
    public function histories()
    {
        return $this->hasMany('\App\History');
    }
    public function users()
    {
        return $this->belongsToMany('\App\User');
    }
}
