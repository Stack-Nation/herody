<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employer extends Authenticatable
{
    use Notifiable;
    protected $guard = 'employers';
    protected $table = 'employers';
    public function projects()
    {
        return $this->hasMany('App\Project', 'user');
    }
    public function gigs()
    {
        return $this->hasMany('App\Gig', 'user_id');
    }
}
