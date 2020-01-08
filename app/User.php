<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable implements HasRoleContract
{
    use Notifiable, HasRole;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','queue_list_id','queue_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function queue()
    {
        return $this->belongsTo('\App\Queue');
    }
    public function queueLists()
    {
        return $this->belongsToMany('\App\QueueList');
    }
    public function roles()
    {
        return $this->belongsToMany('\App\Role');
    }
}
